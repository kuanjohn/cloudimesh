<?php

namespace App\Livewire;

use App\Models\Environment;
use App\Models\EnvironmentTier;
use App\Models\Location;
use App\Models\LocationEnvironment;
use App\Models\Tier;
use App\Rules\EnvironmentExistsForTeam;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;

class EnvironmentManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $addingEnvironment = false;
    public $confirmingEnvironmentRemoval = false;
    public $managingEnvironment = false;
    public $confirmingSelectedEnvironmentRemoval = false;

    public $addEnvironmentForm = [
        'name' => '',
        'published' => true,
    ];
    public $environmentId;
    public $updateEnvironmentForm = [
        'id' => '',
        'name' => '',
        'published' => true,
        'created_at' => '',
        'updated_at' => '',
    ];

    public function mount($id)
    {
        $this->team = Jetstream::newTeamModel()->findOrFail($id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }
    }

    public function getEnvironmentsProperty()
    {
        return Environment::where('team_id', $this->team->id)
            ->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $environments = $this->environments;
        return view('manage.environment.environment-manager', ['team' => $this->team, 'environments' => $environments]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
            return;
        }
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    public function updatedSelectedRows()
    {
        $row = $this->environments->pluck('id')->map(function ($id) {
            return (string) $id;
        });
        if (count($this->selectedRows) === count($row)) {
            $this->selectedPageRow = true;
        } else {
            $this->reset(['selectedPageRow']);
        }
    }

    public function deleteSelectedRows()
    {
        Environment::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedEnvironmentRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Environment deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function updatedselectedPageRow($value)
    {
        if ($value) {
            $this->selectedRows = $this->environments->pluck('id')->map(function ($id) {
                return (string) $id;
            });
        } else {
            $this->reset(['selectedPageRow', 'selectedRows']);
        }
    }

    // Reset properties or perform actions when moving to a new page
    public function updatingPage($page)
    {
        // Reset any properties or perform actions here
        // For example, you can reset a property like this:
        $this->reset(['selectedPageRow', 'selectedRows']);
    }

    public function confirmEnvironmentAddition()
    {
        $this->resetErrorBag();
        $this->addingEnvironment = true;
        $this->addEnvironmentForm['name'] = '';
        $this->addEnvironmentForm['published'] = true;
    }

    public function addEnvironment()
    {
        $this->validate(
            [
                'addEnvironmentForm.name' => ['required', 'min:3', 'max:255', new EnvironmentExistsForTeam($this->team->id)],
            ],
            [
                'addEnvironmentForm.name.required' => 'The environment name is required.',
                'addEnvironmentForm.name.min' => 'The environment name must be at least 3 characters.',
                'addEnvironmentForm.name.max' => 'The environment name may not be greater than 255 characters.',
                'addEnvironmentForm.code.max' => 'The environment code may not be greater than 100 characters.',
            ],
        );

        $environment = new Environment();
        $environment->team_id = $this->team->id;
        $environment->name = trim($this->addEnvironmentForm['name']);
        $environment->published = $this->addEnvironmentForm['published'];
        $environment->created_by = Auth()->id();
        $environment->updated_by = Auth()->id();
        $environment->save();

        // Get all locations belonging to the team and associate the new environment with them
        $teamId = $this->team->id;
        $this->team->locations()->each(function ($location) use ($environment, $teamId) {
            $location->environments()->attach($environment->id, ['team_id' => $teamId, 'created_by' => $environment->created_by, 'updated_by' => $environment->updated_by, 'created_at' => now(), 'updated_at' => now()]);
        });

        $tiers = Tier::where('team_id', $teamId)->get();
        $locations = Location::where('team_id', $this->team->id)->get();

        foreach ($locations as $location) {
            $locationEnvironmentIds = LocationEnvironment::where('location_id', $location->id)
                ->where('environment_id', $environment->id)
                ->pluck('id');
            // Step 3: Add new records into the environment_tiers table
            foreach ($locationEnvironmentIds as $locationEnvironmentId) {
                foreach ($tiers as $tier) {
                    $environmentTier = new EnvironmentTier();
                    $environmentTier->location_environment_id = $locationEnvironmentId;
                    $environmentTier->team_id = $teamId;
                    $environmentTier->tier_id = $tier->id;
                    $environmentTier->created_by = Auth()->id();
                    $environmentTier->updated_by = Auth()->id();
                    $environmentTier->save();
                    // $locationEnvironment = new LocationEnvironment();
                    // $locationEnvironment->location_id = $location->id;
                    // $locationEnvironment->environment_id = $environment->id;
                    // $locationEnvironment->team_id = $this->team->id;
                    // $locationEnvironment->created_by = Auth()->id();
                    // $locationEnvironment->updated_by = Auth()->id();
                    // $locationEnvironment->tier_id = $tier->id;
                    // $locationEnvironment->save();
                }
            }
        }

        $data = ['style' => 'success', 'message' => 'Environment created successfully.'];
        $this->dispatch('showBanner', $data);

        $this->addEnvironmentForm['name'] = '';
        $this->addEnvironmentForm['published'] = '';
        $this->addingEnvironment = false;
        $this->resetErrorBag();
    }

    public function confirmEnvironmentRemoval($id)
    {
        $this->confirmingEnvironmentRemoval = true;
        $this->environmentId = $id;
    }

    public function deleteEnvironment()
    {
        $environment = Environment::find($this->environmentId);
        if ($environment) {
            // If the environment record exists, delete it
            $environment->delete();
            $this->confirmingEnvironmentRemoval = false;
            $data = ['style' => 'success', 'message' => 'Environment deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmSelectedEnvironmentRemoval()
    {
        $this->confirmingSelectedEnvironmentRemoval = true;
    }

    public function toggleActive($id)
    {
        $environment = Environment::findOrFail($id);
        $environment->published = !$environment->published;
        $environment->save();
        $this->render();
    }

    public function confirmManageEnvironment($id)
    {
        $this->resetPage();
        $this->resetErrorBag();
        $this->environmentId = $id;
        $environment = Environment::find($id);
        $this->managingEnvironment = true;

        if ($environment) {
            // If the department record exists, retrieve information
            $this->updateEnvironmentForm['id'] = $id;
            $this->updateEnvironmentForm['name'] = $environment->name;
            $this->updateEnvironmentForm['published'] = $environment->published == 1 ? true : false;
            // $this->creator = $environment->creator->name;
            // $this->updater = $environment->updater->name;
            $this->updateEnvironmentForm['created_at'] = $environment->created_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
            $this->updateEnvironmentForm['updated_at'] = $environment->updated_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
        } else {
            return;
        }
    }

    public function updateEnvironment()
    {
        $environment = Environment::find($this->environmentId);

        if ($environment->name === $this->updateEnvironmentForm['name']) {
        } else {
            $this->validate(
                [
                    'updateEnvironmentForm.name' => ['required', 'min:3', 'max:255', new EnvironmentExistsForTeam($this->team->id)],
                ],
                [
                    'updateEnvironmentForm.name.required' => 'The environment name is required.',
                    'updateEnvironmentForm.name.min' => 'The environment name must be at least 3 characters.',
                    'updateEnvironmentForm.name.max' => 'The environment name may not be greater than 255 characters.',
                    'updateEnvironmentForm.code.max' => 'The environment code may not be greater than 100 characters.',
                ],
            );
        }

        $environment->name = trim($this->updateEnvironmentForm['name']);
        $environment->published = $this->updateEnvironmentForm['published'];
        $environment->created_by = Auth()->id();
        $environment->save();

        $data = ['style' => 'success', 'message' => 'Environment updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingEnvironment = false;

        // $this->render();
    }
}
