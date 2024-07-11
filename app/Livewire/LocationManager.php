<?php

namespace App\Livewire;

use App\Models\Environment;
use App\Models\EnvironmentTier;
use App\Models\Location;
use App\Models\LocationEnvironment;
use App\Models\Tier;
use App\Rules\LocationExistsForTeam;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use Livewire\WithPagination;
use Laravel\Jetstream\Jetstream;

class LocationManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $addingLocation = false;
    public $confirmingLocationRemoval = false;
    public $managingLocation = false;
    public $confirmingSelectedLocationRemoval = false;

    public $addLocationForm = [
        'name' => '',
        'published' => true,
    ];
    public $locationId;
    public $updateLocationForm = [
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

    public function getLocationsProperty()
    {
        return Location::where('team_id', $this->team->id)
            ->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $locations = $this->locations;
        return view('manage.location.location-manager', ['team' => $this->team, 'locations' => $locations]);
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
        $row = $this->locations->pluck('id')->map(function ($id) {
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
        Location::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedLocationRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Location deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function updatedselectedPageRow($value)
    {
        if ($value) {
            $this->selectedRows = $this->locations->pluck('id')->map(function ($id) {
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

    public function confirmLocationAddition()
    {
        $this->resetErrorBag();
        $this->addingLocation = true;
        $this->addLocationForm['name'] = '';
        $this->addLocationForm['published'] = true;
    }

    public function addLocation()
    {
        $this->validate(
            [
                'addLocationForm.name' => ['required', 'min:3', 'max:255', new LocationExistsForTeam($this->team->id)],
            ],
            [
                'addLocationForm.name.required' => 'The location name is required.',
                'addLocationForm.name.min' => 'The location name must be at least 3 characters.',
                'addLocationForm.name.max' => 'The location name may not be greater than 255 characters.',
                'addLocationForm.code.max' => 'The location code may not be greater than 100 characters.',
            ],
        );

        $location = new Location();
        $location->team_id = $this->team->id;
        $location->name = trim($this->addLocationForm['name']);
        $location->published = $this->addLocationForm['published'];
        $location->created_by = Auth()->id();
        $location->updated_by = Auth()->id();
        $location->save();

        // $teamId = $this->team->id;
        // Get all environments belonging to the same team
        $environments = Environment::where('team_id', $location->team_id)->get();

        // Associate the new location with all environments
        foreach ($environments as $environment) {
            $environment->locations()->syncWithoutDetaching([$location->id => ['team_id' => $location->team_id, 'created_by' => $environment->created_by, 'updated_by' => $environment->updated_by, 'created_at' => now(), 'updated_at' => now()]]);
        }
        // Step 2: Find all tiers belonging to the specified team_id

        $tiers = Tier::where('team_id', $this->team->id)->get();

        foreach ($environments as $environment) {
            $locationEnvironmentIds = LocationEnvironment::where('location_id', $location->id)
                ->where('environment_id', $environment->id)
                ->pluck('id');
            // Step 3: Add new records into the environment_tiers table
            foreach ($locationEnvironmentIds as $locationEnvironmentId) {
                foreach ($tiers as $tier) {
                    $environmentTier = new EnvironmentTier();
                    $environmentTier->location_environment_id = $locationEnvironmentId;
                    $environmentTier->team_id = $this->team->id;
                    $environmentTier->tier_id = $tier->id;
                    $environmentTier->created_by = Auth()->id();
                    $environmentTier->updated_by = Auth()->id();
                    $environmentTier->save();
                }
            }
            // foreach ($tiers as $tier) {
            //     $locationEnvironment = new LocationEnvironment();
            //     // $locationEnvironment->location_environment_id = $locationEnvironmentId;
            //     $locationEnvironment->location_id = $location->id;
            //     $locationEnvironment->environment_id = $environment->id;
            //     $locationEnvironment->team_id = $this->team->id;
            //     $locationEnvironment->created_by = Auth()->id();
            //     $locationEnvironment->updated_by = Auth()->id();
            //     $locationEnvironment->tier_id = $tier->id;
            //     $locationEnvironment->save();
            // }

            
        }

        $data = ['style' => 'success', 'message' => 'Location created successfully.'];
        $this->dispatch('showBanner', $data);

        $this->addLocationForm['name'] = '';
        $this->addLocationForm['published'] = '';
        $this->addingLocation = false;
        $this->resetErrorBag();
    }

    public function confirmLocationRemoval($id)
    {
        $this->confirmingLocationRemoval = true;
        $this->locationId = $id;
    }

    public function deleteLocation()
    {
        $location = Location::find($this->locationId);
        if ($location) {
            // If the location record exists, delete it
            $location->delete();
            $this->confirmingLocationRemoval = false;
            $data = ['style' => 'success', 'message' => 'Location deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmSelectedLocationRemoval()
    {
        $this->confirmingSelectedLocationRemoval = true;
    }

    public function toggleActive($id)
    {
        $location = Location::findOrFail($id);
        $location->published = !$location->published;
        $location->save();
        $this->render();
    }

    public function confirmManageLocation($id)
    {
        $this->resetPage();
        $this->resetErrorBag();
        $this->locationId = $id;
        $location = Location::find($id);
        $this->managingLocation = true;

        if ($location) {
            // If the department record exists, retrieve information
            $this->updateLocationForm['id'] = $id;
            $this->updateLocationForm['name'] = $location->name;
            $this->updateLocationForm['published'] = $location->published == 1 ? true : false;
            // $this->creator = $location->creator->name;
            // $this->updater = $location->updater->name;
            $this->updateLocationForm['created_at'] = $location->created_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
            $this->updateLocationForm['updated_at'] = $location->updated_at->timezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s');
        } else {
            return;
        }
    }

    public function updateLocation()
    {
        $location = Location::find($this->locationId);

        if ($location->name === $this->updateLocationForm['name']) {
        } else {
            $this->validate(
                [
                    'updateLocationForm.name' => ['required', 'min:3', 'max:255', new LocationExistsForTeam($this->team->id)],
                ],
                [
                    'updateLocationForm.name.required' => 'The location name is required.',
                    'updateLocationForm.name.min' => 'The location name must be at least 3 characters.',
                    'updateLocationForm.name.max' => 'The location name may not be greater than 255 characters.',
                    'updateLocationForm.code.max' => 'The location code may not be greater than 100 characters.',
                ],
            );
        }

        $location->name = trim($this->updateLocationForm['name']);
        $location->published = $this->updateLocationForm['published'];
        $location->created_by = Auth()->id();
        $location->save();

        $data = ['style' => 'success', 'message' => 'Location updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingLocation = false;

        // $this->render();
    }
}
