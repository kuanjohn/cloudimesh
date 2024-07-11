<?php

namespace App\Livewire;

use App\Models\Environment;
use App\Models\EnvironmentTier;
use Livewire\Component;
use App\Models\Location;
use App\Models\LocationEnvironment;
use App\Models\LocationStorage;
use App\Models\storage;
use App\Models\Tier;
use App\Models\vmspec;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;

class InfraPolicyManager extends Component
{
    public $team;

    public $confirmingTierRemoval;
    public $environmentTierId;
    public $confirmingEnvironmentRemoval;
    public $locationEnvironmentId;
    public $confirmingEnvironmentAddition;
    public $locationId;
    public $environmentsWithoutLocation;
    public $environmentId;
    public $confirmingTierAddition;
    public $tiersWithoutEnvironment;
    public $tierId;
    public $managingVmspecforEnv;
    public $confirmingStoragePolicyAddition = false;
    public $availableStorages;
    public $storageId;
    public $confirmingStoragePolicyRemoval;
    public $locationStorageId;

    public $managingVmspec = false;
    public $vmspecId;
    public $VMPolicyForm = [
        'name' => '',
        'min_vcpu' => '',
        'max_vcpu' => '',
        'inc_vcpu' => [],
        'min_vmem' => '',
        'max_vmem' => '',
        'inc_vmem' => [],
        'cost_vcpu' => '',
        'cost_vmem' => '',
    ];
    public $vmspecs;

    public $cpu_range = 1;
    public $cpu_range_step;
    public $mem_range = 1;
    public $mem_range_step;

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
            ->orderby('name', 'asc')
            ->get();
    }

    // public function getLocationEnvironmentsProperty()
    // {
    //     return LocationEnvironment::where('team_id', $this->team->id)->get();
    // }

    public function confirmTierRemoval($id)
    {
        $this->confirmingTierRemoval = true;
        $this->environmentTierId = $id;
    }

    public function deleteEnvironmentTier()
    {
        $environmentTier = EnvironmentTier::find($this->environmentTierId);
        if ($environmentTier) {
            // If the location record exists, delete it
            $environmentTier->delete();
            $this->confirmingTierRemoval = false;
            $data = ['style' => 'success', 'message' => 'Tier removed from Environment successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmEnvironmentRemoval($id)
    {
        $this->confirmingEnvironmentRemoval = true;
        $this->locationEnvironmentId = $id;
    }

    public function deleteLocationEnvironment()
    {
        $locationEnvironment = LocationEnvironment::find($this->locationEnvironmentId);
        if ($locationEnvironment) {
            // If the location record exists, delete it
            $locationEnvironment->delete();
            $this->confirmingEnvironmentRemoval = false;
            $data = ['style' => 'success', 'message' => 'Environment removed from Location successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmEnvironmentAddition($id)
    {
        $this->confirmingEnvironmentAddition = true;
        $this->environmentsWithoutLocation = '';
        $this->environmentId = '';
        $this->locationId = $id;

        $this->environmentsWithoutLocation = Environment::where('team_id', $this->team->id)
            ->whereNotIn('id', function ($query) {
                $query
                    ->select('environment_id')
                    ->from('location_environments')
                    ->where('location_id', $this->locationId);
            })
            ->orderby('name', 'asc')
            ->get();
        // dd($environmentsWithoutLocation);
    }

    public function addLocationEnvironment()
    {
        $this->validate(
            [
                'environmentId' => ['required'],
            ],
            [
                'environmentId' => 'You must select one environment.',
            ],
        );

        // Get all locations belonging to the team and associate the new environment with them
        $teamId = $this->team->id;

        $this->team
            ->locations()
            ->find($this->locationId)
            ->environments()
            ->attach($this->environmentId, ['team_id' => $teamId, 'created_by' => Auth()->id(), 'updated_by' => Auth()->id(), 'created_at' => now(), 'updated_at' => now()]);

        $locationEnvironmentIds = LocationEnvironment::where('location_id', $this->locationId)
            ->where('environment_id', $this->environmentId)
            ->pluck('id');

        $tiers = Tier::where('team_id', $this->team->id)->get();

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
            }
        }

        $this->confirmingEnvironmentAddition = false;
        $this->environmentsWithoutLocation = '';
        $this->environmentId = '';

        $data = ['style' => 'success', 'message' => 'Environment successfully assosciated to the location and tier.'];
        $this->dispatch('showBanner', $data);
    }

    public function confirmTierAddition($id)
    {
        $this->confirmingTierAddition = true;
        $this->locationEnvironmentId = $id;
        $this->tierId = '';

        $this->tiersWithoutEnvironment = Tier::where('team_id', $this->team->id)
            ->whereNotIn('id', function ($query) {
                $query
                    ->select('tier_id')
                    ->from('environment_tiers')
                    ->where('location_environment_id', $this->locationEnvironmentId);
            })
            ->orderby('name', 'asc')
            ->get();
    }

    public function addEnvironmentTier()
    {
        $this->validate(
            [
                'tierId' => ['required'],
            ],
            [
                'tierId' => 'You must select one tier.',
            ],
        );

        // Get all locations belonging to the team and associate the new Tier with them
        $teamId = $this->team->id;

        // Find the locationEnvironment
        $locationEnvironment = $this->team->locationEnvironments->find($this->locationEnvironmentId);
        // dd($locationEnvironments);

        // Retrieve the newly created tier
        $tier = Tier::find($this->tierId);

        // Attach the relationship to the environment_tiers table
        $locationEnvironment->tiers()->attach($tier->id, [
            'team_id' => $teamId,
            'created_by' => Auth()->id(),
            'updated_by' => Auth()->id(),
        ]);

        $this->confirmingTierAddition = false;
        $this->locationEnvironmentId = '';
        $this->tierId = '';

        $data = ['style' => 'success', 'message' => 'Tier successfully assosciated to the environment.'];
        $this->dispatch('showBanner', $data);
    }

    public function render()
    {
        $locationEnvironments = LocationEnvironment::where('team_id', $this->team->id)->get();
        // dd($locations);
        $locations = $this->locations;
        // $locationEnvironments = $this->locationEnvironments;
        return view('manage.infra-policy.infra-policy-manager', ['team' => $this->team, 'locations' => $locations, 'locationEnvironments' => $locationEnvironments]);
        // return view('manage.infra-policy.infra-policy-manager', ['team' => $this->team, 'locations' => $locations, 'locationEnvironments' => $locationEnvironments]);
    }

    public function confirmManageVmspec($id, $locationId)
    {
        // $this->resetPage();
        $this->resetErrorBag();
        $this->vmspecId = $id;
        $this->locationId = $locationId;
        $vmspec = vmspec::find($id);
        $this->vmspecs = vmspec::where('team_id', $this->team->id)->get();
        $this->managingVmspec = true;

        $this->cpu_range = 1;
        $this->mem_range = 1;

        if ($vmspec) {
            // If the record exists, retrieve information
            $this->VMPolicyForm = [
                'name' => $vmspec['name'],
                'min_vcpu' => $vmspec['min_vcpu'],
                'max_vcpu' => $vmspec['max_vcpu'],
                'inc_vcpu' => $this->removeBracket($vmspec['inc_vcpu']),
                'min_vmem' => $vmspec['min_vmem'],
                'max_vmem' => $vmspec['max_vmem'],
                'inc_vmem' => $this->removeBracket($vmspec['inc_vmem']),
                'cost_vcpu' => $vmspec['cost_vcpu'],
                'cost_vmem' => $vmspec['cost_vmem'],
            ];

            $this->cpu_range = $vmspec['min_vcpu'];
        } elseif ($vmspec === null) {
            $this->VMPolicyForm = [
                'name' => config('vmspecs.name'),
                'min_vcpu' => config('vmspecs.min_vcpu'),
                'max_vcpu' => config('vmspecs.max_vcpu'),
                'inc_vcpu' => $this->removeBracket(config('vmspecs.inc_vcpu')),
                'min_vmem' => config('vmspecs.min_vmem'),
                'max_vmem' => config('vmspecs.max_vmem'),
                'inc_vmem' => $this->removeBracket(config('vmspecs.inc_vmem')),
                'cost_vcpu' => config('vmspecs.cost_vcpu'),
                'cost_vmem' => config('vmspecs.cost_vmem'),
            ];
        }
    }

    public function confirmManageVmspecforEnv($id, $locationEnvironmentId)
    {
        // $this->resetPage();
        $this->resetErrorBag();
        $this->vmspecId = $id;
        $this->locationEnvironmentId = $locationEnvironmentId;
        $vmspec = vmspec::find($id);
        $this->vmspecs = vmspec::where('team_id', $this->team->id)->get();
        $this->managingVmspecforEnv = true;

        $this->cpu_range = 1;
        $this->mem_range = 1;

        if ($vmspec) {
            // If the record exists, retrieve information
            $this->VMPolicyForm = [
                'name' => $vmspec['name'],
                'min_vcpu' => $vmspec['min_vcpu'],
                'max_vcpu' => $vmspec['max_vcpu'],
                'inc_vcpu' => $this->removeBracket($vmspec['inc_vcpu']),
                'min_vmem' => $vmspec['min_vmem'],
                'max_vmem' => $vmspec['max_vmem'],
                'inc_vmem' => $this->removeBracket($vmspec['inc_vmem']),
                'cost_vcpu' => $vmspec['cost_vcpu'],
                'cost_vmem' => $vmspec['cost_vmem'],
            ];

            $this->cpu_range = $vmspec['min_vcpu'];

        } elseif ($vmspec === null) {
            $this->VMPolicyForm = [
                'name' => config('vmspecs.name'),
                'min_vcpu' => config('vmspecs.min_vcpu'),
                'max_vcpu' => config('vmspecs.max_vcpu'),
                'inc_vcpu' => $this->removeBracket(config('vmspecs.inc_vcpu')),
                'min_vmem' => config('vmspecs.min_vmem'),
                'max_vmem' => config('vmspecs.max_vmem'),
                'inc_vmem' => $this->removeBracket(config('vmspecs.inc_vmem')),
                'cost_vcpu' => config('vmspecs.cost_vcpu'),
                'cost_vmem' => config('vmspecs.cost_vmem'),
            ];
        }
    }

    public function updateLocationVMPolicy()
    {
        $location = location::find($this->locationId);
        $location->vmspec_id = $this->vmspecId;
        $location->updated_by = Auth()->id();
        $location->save();
        $this->managingVmspec = false;
        $data = ['style' => 'success', 'message' => 'VM Policy is updated successfully.'];
        $this->dispatch('showBanner', $data);
    }

    public function detachLocationVMPolicy()
    {
        $location = location::find($this->locationId);
        $location->vmspec_id = null;
        $location->updated_by = Auth()->id();
        $location->save();
        $this->managingVmspec = false;
        $data = ['style' => 'success', 'message' => 'VM Policy is detached successfully.'];
        $this->dispatch('showBanner', $data);
    }

    public function detachLocationEnvironmentVMPolicy()
    {
        $locationEnvironment = LocationEnvironment::find($this->locationEnvironmentId);
        $locationEnvironment->vmspec_id = null;
        $locationEnvironment->updated_by = Auth()->id();
        $locationEnvironment->save();
        $this->managingVmspecforEnv = false;
        $data = ['style' => 'success', 'message' => 'VM Policy is detached successfully.'];
        $this->dispatch('showBanner', $data);
    }

    public function updateLocationEnvironmentVMPolicy()
    {
        $locationEnvironment = LocationEnvironment::find($this->locationEnvironmentId);
        $locationEnvironment->vmspec_id = $this->vmspecId;
        $locationEnvironment->updated_by = Auth()->id();
        $locationEnvironment->save();
        $this->managingVmspecforEnv = false;
        $data = ['style' => 'success', 'message' => 'VM Policy is updated successfully.'];
        $this->dispatch('showBanner', $data);
    }

    public function removeBracket($value)
    {
        $value = str_replace('[', '', $value);
        $value = str_replace(']', '', $value);
        return $value;
    }

    // protected $listeners = ['cpurangeUpdated'];

    public function updatedCpuRange($value)
    {
        if (!is_array($this->VMPolicyForm['inc_vcpu'])) {
            $steparray = explode(',', $this->VMPolicyForm['inc_vcpu']);
        } else {
            $steparray = $this->VMPolicyForm['inc_vcpu'];
        }
        if ($value >= end($steparray)) {
            $this->cpu_range_step = end($steparray);
            // $this->cpu_range = $this->cpu_range/end($steparray) <= 1 ? end($steparray) : $this->cpu_range_step + end($steparray) * (intval($this->cpu_range/end($steparray)));
        } else {
            if (in_array($value, $steparray)) {
                $this->cpu_range_step = $value;
            } else {
                foreach ($steparray as $step) {
                    if ($value < $step) {
                        $this->cpu_range = $step;
                        $this->cpu_range_step = $step;
                        break;
                    }
                }
            }
        }

        // dd($this->VMPolicyForm['inc_vcpu']);
    }

    public function updatedMemRange($value)
    {
        if (!is_array($this->VMPolicyForm['inc_vmem'])) {
            $steparray = explode(',', $this->VMPolicyForm['inc_vmem']);
        } else {
            $steparray = $this->VMPolicyForm['inc_vmem'];
        }
        if ($value >= end($steparray)) {
            $this->mem_range_step = end($steparray);
            // $this->cpu_range = $this->cpu_range/end($steparray) <= 1 ? end($steparray) : $this->mem_range_step + end($steparray) * (intval($this->cpu_range/end($steparray)));
        } else {
            if (in_array($value, $steparray)) {
                $this->mem_range_step = $value;
            } else {
                foreach ($steparray as $step) {
                    if ($value < $step) {
                        $this->mem_range = $step;
                        $this->mem_range_step = $step;
                        break;
                    }
                }
            }
        }

        // dd($this->VMPolicyForm['inc_vmem']);
    }

    public function confirmingAddStoragePolicy($locationId) {
        $this->confirmingStoragePolicyAddition = true;

        $this->resetErrorBag();
        $this->locationId = $locationId;
        $this->storageId = '';
        // $attachedStorage = Location::find($this->locationId);

        $this->availableStorages = storage::where('team_id', $this->team->id)
        ->whereNotIn('id', function($query){
            $query
                ->select('storage_id')
                ->from('location_storages')
                ->where('location_id', $this->locationId);
        })
        ->orderby('name','asc')
        ->get();

    }

    public function attachLocationStoragePolicy()
    {
       
        // Get all locations belonging to the team and associate the new environment with them
        $teamId = $this->team->id;

        $this->team
            ->locations()
            ->find($this->locationId)
            ->storages()
            ->attach($this->storageId, ['team_id' => $teamId, 'created_by' => Auth()->id(), 'updated_by' => Auth()->id(), 'created_at' => now(), 'updated_at' => now()]);

        $this->confirmingStoragePolicyAddition = false;

        $data = ['style' => 'success', 'message' => 'Storage Policy successfully assosciated to the location.'];
        $this->dispatch('showBanner', $data);
    }

    public function confirmStoragePolicyRemoval($locationStorageId)
    {
        $this->confirmingStoragePolicyRemoval = true;
        $this->locationStorageId = $locationStorageId;
        // $this->storageId = $storageId;
    }

    public function deleteStoragePolicyforLocation()
    {
        $locationStorage = LocationStorage::find($this->locationStorageId);
        if ($locationStorage) {
            // If the location record exists, delete it
            $locationStorage->delete();
            $this->confirmingStoragePolicyRemoval = false;
            $data = ['style' => 'success', 'message' => 'Storage Policy removed from Environment successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }
}
