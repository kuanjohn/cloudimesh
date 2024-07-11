<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tier;
use App\Rules\TierExistsForTeam;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use Laravel\Jetstream\Jetstream;

class TierManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $addingTier = false;
    public $confirmingTierRemoval = false;
    public $managingTier = false;
    public $confirmingSelectedTierRemoval = false;

    public $addTierForm = [
        'name' => '',
        'published' => true,
    ];
    public $tierId;
    public $updateTierForm = [
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

    public function getTiersProperty()
    {
        return Tier::where('team_id', $this->team->id)
            ->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $tiers = $this->tiers;
        return view('manage.tier.tier-manager', ['team' => $this->team, 'tiers' => $tiers]);
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
        $row = $this->tiers->pluck('id')->map(function ($id) {
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
        Tier::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedTierRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Tier deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function updatedselectedPageRow($value)
    {
        if ($value) {
            $this->selectedRows = $this->tiers->pluck('id')->map(function ($id) {
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

    public function confirmTierAddition()
    {
        $this->resetErrorBag();
        $this->addingTier = true;
        $this->addTierForm['name'] = '';
        $this->addTierForm['published'] = true;
    }

    public function addTier()
    {
        $this->validate(
            [
                'addTierForm.name' => ['required', 'min:3', 'max:255', new TierExistsForTeam($this->team->id)],
            ],
            [
                'addTierForm.name.required' => 'The tier name is required.',
                'addTierForm.name.min' => 'The tier name must be at least 3 characters.',
                'addTierForm.name.max' => 'The tier name may not be greater than 255 characters.',
                'addTierForm.code.max' => 'The tier code may not be greater than 100 characters.',
            ],
        );

        $tier = new Tier();
        $tier->team_id = $this->team->id;
        $tier->name = trim($this->addTierForm['name']);
        $tier->published = $this->addTierForm['published'];
        $tier->created_by = Auth()->id();
        $tier->updated_by = Auth()->id();
        $tier->save();

        // Get all locations belonging to the team and associate the new Tier with them
        $teamId = $this->team->id;

        // Retrieve all location_environment records belonging to the current team
        $locationEnvironments = $this->team->locationEnvironments;
        // dd($locationEnvironments);

        // Retrieve the newly created tier
        $tier = Tier::find($tier->id);

        // Insert relationships for each location_environment record
        foreach ($locationEnvironments as $locationEnvironment) {
            // Attach the relationship to the environment_tiers table
            $locationEnvironment->tiers()->attach($tier->id, [
                'team_id' => $teamId,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
            ]);
        }

        $data = ['style' => 'success', 'message' => 'Tier created successfully.'];
        $this->dispatch('showBanner', $data);

        $this->addTierForm['name'] = '';
        $this->addTierForm['published'] = '';
        $this->addingTier = false;
        $this->resetErrorBag();
    }

    public function confirmTierRemoval($id)
    {
        $this->confirmingTierRemoval = true;
        $this->tierId = $id;
    }

    public function deleteTier()
    {
        $tier = Tier::find($this->tierId);
        if ($tier) {
            // If the tier record exists, delete it
            $tier->delete();
            $this->confirmingTierRemoval = false;
            $data = ['style' => 'success', 'message' => 'Tier deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmSelectedTierRemoval()
    {
        $this->confirmingSelectedTierRemoval = true;
    }

    public function toggleActive($id)
    {
        $tier = Tier::findOrFail($id);
        $tier->published = !$tier->published;
        $tier->save();
        $this->render();
    }

    public function confirmManageTier($id)
    {
        $this->resetPage();
        $this->resetErrorBag();
        $this->tierId = $id;
        $tier = Tier::find($id);
        $this->managingTier = true;

        if ($tier) {
            // If the department record exists, retrieve information
            // $this->updateTierForm['id'] = $id;
            $this->updateTierForm['name'] = $tier->name;
            $this->updateTierForm['published'] = $tier->published == 1 ? true : false;
            // $this->creator = $tier->creator->name;
            // $this->updater = $tier->updater->name;
            $this->updateTierForm['created_at'] = $tier->created_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
            $this->updateTierForm['updated_at'] = $tier->updated_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
        } else {
            return;
        }
    }

    public function updateTier()
    {
        $tier = Tier::find($this->tierId);

        if ($tier->name === $this->updateTierForm['name']) {
        } else {
            $this->validate(
                [
                    'updateTierForm.name' => ['required', 'min:3', 'max:255', new TierExistsForTeam($this->team->id)],
                ],
                [
                    'updateTierForm.name.required' => 'The tier name is required.',
                    'updateTierForm.name.min' => 'The tier name must be at least 3 characters.',
                    'updateTierForm.name.max' => 'The tier name may not be greater than 255 characters.',
                    'updateTierForm.code.max' => 'The tier code may not be greater than 100 characters.',
                ],
            );
        }

        $tier->name = trim($this->updateTierForm['name']);
        $tier->published = $this->updateTierForm['published'];
        $tier->created_by = Auth()->id();
        $tier->save();

        $data = ['style' => 'success', 'message' => 'Tier updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingTier = false;

        // $this->render();
    }
}
