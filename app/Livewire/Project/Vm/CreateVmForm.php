<?php

namespace App\Livewire\Project\Vm;

use App\Models\Location;
use App\Models\LocationEnvironment;
use App\Models\VirtualMachine;
use App\Rules\VmExistsForTeam;
use Livewire\Component;

class CreatevmForm extends Component
{
    public $project;
    public $user;
    public $team;
    public $location;
    public $locations;
    public $environment;
    public $environments;
    public $tiers;
    public $operating_systems;
    public $storages;
    public $vmPolicy;

    public $vmForm = [
        'name' => '',
        'location_id' => '',
        'environment_id' => [],
        'tier_id' => [],
        'vcpu' => '',
        'min_vcpu' => '',
        'max_vcpu' => '',
        'cost_vcpu' => '',
        'vmem' => '',
        'min_vmem' => '',
        'max_vmem' => '',
        'cost_vmem' => '',
        'storage_id' => '',
        'storage_cost' => '',
        'add_storage' => 0,
        'operating_system_id' => '',
        'operating_system_cost' => '',
        'operating_system_cost_type' => '',
        'min_disk' => 0,
        'project_cost' => '',
    ];

    public function mount($project)
    {
        $this->project = $project;
        $this->user = Auth()->User();
        $this->team = Auth()->User()->currentTeam;
        $this->locations = $this->team->locations->where('published', true)->sortBy('name');
        $this->location = $this->locations->first();

        $this->operating_systems = $this->team->operating_systems->sortBy('name');
        $this->setOS($this->operating_systems->first());

        if ($this->locations->isNotEmpty()) {
            $this->vmForm['location_id'] = $this->locations->first()->id;
            $this->environments = $this->locations->first()->environments->where('published', true)->sortBy('name');
            $this->getStoragePolicy();

            if ($this->environments->isNotEmpty()) {
                $this->vmForm['environment_id'] = $this->environments->first()->id;
                $location_environment_id = $this->environments->first()->pivot->id;
                // dd($this->environments->first()->pivot->id);
                $this->tiers = LocationEnvironment::find($location_environment_id)->tiers->where('published', true)->sortBy('name');
                $this->getVmPolicy($location_environment_id);

                if ($this->tiers->isNotEmpty()) {
                    $this->vmForm['tier_id'] = $this->tiers->first()->id;
                } else {
                    $this->tiers = [];
                }
            } else {
                // dd('here');
                $this->tiers = [];
            }
        } else {
            $this->environments = [];
            $this->tiers = [];
        }
        // dd($this->team->locations->where('published',true));
        // return view('project.vm.create-vm-form');
        $this->getProjectCost();
    }

    public function render()
    {
        return view('project.vm.create-vm-form', ['project' => $this->project, 'locations' => $this->locations]);
    }

    public function getStoragePolicy()
    {
        $this->storages = $this->locations->find($this->vmForm['location_id'])->storages->where('published', true);

        if ($this->storages->isEmpty()) {
            $this->vmForm['storage_cost'] = config('storagepolicy.ssd.cost');
            // dd($this->vmForm['storage_cost']);
        } else {
            $this->vmForm['storage_id'] = $this->storages->first()->id;
            $this->vmForm['storage_cost'] = $this->storages->first()->cost;
        }
    }

    public function updatedvmFormLocation($value)
    {
        $this->location = $this->locations->find($value);
        $this->environments = $this->location->environments->where('published', true);
        $this->vmForm['environment_id'] = $this->environments->first()->id;
        $this->getStoragePolicy();
        // dd($this->environments->first()->pivot->id);
        $this->getVmPolicy($this->environments->first()->pivot->id);
        $this->getProjectCost();

    }

    public function updatedvmFormEnvironment($value)
    {
        // dd($this->location->environments->find($value)->pivot->id);
        $location_environment_id = $this->location->environments->find($value)->pivot->id;
        // dd($location_environment_id);
        $this->getVmPolicy($location_environment_id);
        $this->getProjectCost();

    }

    public function updatedvmFormStorage($value)
    {
        // $this->environments = $this->locations->find($this->vmForm['location_id'])->environments->where('published', true);
        // $this->vmForm['storage']_id = $this->storages->first()->id;
        $this->vmForm['storage_cost'] = $this->storages->find($value)->cost;
        $this->getProjectCost();

    }

    public function updatedvmFormOperatingSystem($value)
    {
        $os = $this->operating_systems->find($value);
        $this->setOs($os);
        $this->getProjectCost();

    }

    public function setvmForm($vmPolicy)
    {
        $this->vmForm['vcpu'] = 1;
        $this->vmForm['min_vcpu'] = $vmPolicy->min_vcpu;
        $this->vmForm['max_vcpu'] = $vmPolicy->max_vcpu;
        $this->vmForm['cost_vcpu'] = $vmPolicy->cost_vcpu;
        $this->vmForm['vmem'] = 1;
        $this->vmForm['min_vmem'] = $vmPolicy->min_vmem;
        $this->vmForm['max_vmem'] = $vmPolicy->max_vmem;
        $this->vmForm['cost_vmem'] = $vmPolicy->cost_vmem;
        // dd($this->vmForm);
    }

    public function setOs($os) {
        $this->vmForm['operating_system_id'] = $os->id;
        $this->vmForm['operating_system_cost'] = $os->cost;
        $this->vmForm['operating_system_cost_type'] = $os->cost_type;
        $this->vmForm['min_disk'] = $os->min_disk;
    }

    public function getVmPolicy($location_environment_id)
    {
        $this->vmPolicy = LocationEnvironment::find($location_environment_id)->vmspec;
        if (is_null($this->vmPolicy)) {
            $this->vmPolicy = Location::find($this->vmForm['location_id'])->vmspec;
            if (is_null($this->vmPolicy)) {
                $this->vmForm['vcpu'] = 1;
                $this->vmForm['min_vcpu'] = config('vmspecs.min_vcpu');
                $this->vmForm['max_vcpu'] = config('vmspecs.max_vcpu');
                $this->vmForm['cost_vcpu'] = config('vmspecs.cost_vcpu');
                $this->vmForm['vmem'] = 1;
                $this->vmForm['min_vmem'] = config('vmspecs.min_vmem');
                $this->vmForm['max_vmem'] = config('vmspecs.max_vmem');
                $this->vmForm['cost_vmem'] = config('vmspecs.cost_vmem');
            } else {
                $this->setvmForm($this->vmPolicy);
            }
            // dd($this->vmPolicy);
        } else {
            $this->setvmForm($this->vmPolicy);
        }
    }

    public function createVM()
    {
        $this->validate(
            [
                'vmForm.name' => ['required', 'min:3', 'max:255', new VmExistsForTeam($this->team->id)],
            ],
            [
                'vmForm.name.required' => 'The VM name is required.',
                'vmForm.name.min' => 'The VM name must be at least 3 characters.',
                'vmForm.name.max' => 'The VM name may not be greater than 255 characters.',
             
            ],
        );

        $vm = new VirtualMachine();
        $vm->name = trim($this->vmForm['name']);
        $vm->vcpu = $this->vmForm['vcpu'];
        $vm->vmem = $this->vmForm['vmem'];
        $vm->add_storage = $this->vmForm['add_storage'];
        $vm->location_id = $this->vmForm['location_id'];
        $vm->environment_id = $this->vmForm['environment_id'];
        $vm->tier_id = $this->vmForm['tier_id'];
        $vm->operating_system_id = $this->vmForm['operating_system_id'];
        $vm->storage_id = $this->vmForm['storage_id'];
        $vm->team_id = $this->team->id;
        $vm->project_id = $this->project->id;
        // $vm->owner = Auth()->id();
        $vm->created_by = Auth()->id();
        $vm->updated_by = Auth()->id();
        $vm->save();

        $data = ['style' => 'success', 'message' => 'VM created successfully.'];
        $this->dispatch('showBanner', $data);

        $this->vmForm['name'] = '';
        $this->vmForm['description'] = '';
        $this->vmForm['charge_code'] = '';
        $this->vmForm['budget'] = '';
        $this->resetErrorBag();
        // sleep(5);
        // return redirect()->route('project/{id}/manage', ['id' => $this->project->id]);
    }

    public function getProjectCost() {
        $cpuCost = $this->vmForm['cost_vcpu'] * $this->vmForm['vcpu']; 
        $memCost = $this->vmForm['cost_vmem'] * $this->vmForm['vmem']; 
        $storageCost = $this->vmForm['storage_cost'] * ($this->vmForm['add_storage'] + $this->vmForm['min_disk']); 
        
        if($this->vmForm['operating_system_cost_type'] === 'Core') {
            $osCost = $this->vmForm['operating_system_cost'] * $this->vmForm['vcpu'];
        } else {
            $osCost = $this->vmForm['operating_system_cost'];
        }

         $this->vmForm['project_cost'] = $cpuCost + $memCost + $storageCost + $osCost;
         $this->vmForm['project_cost'] = $this->vmForm['project_cost'] * 365 / 12;
    }
}
