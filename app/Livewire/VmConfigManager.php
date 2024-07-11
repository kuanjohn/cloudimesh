<?php

namespace App\Livewire;

use App\Models\vmspec;
use App\Rules\MinMaxRule;
use App\Rules\NumIncRule;
use App\Rules\NumIncvCpuRule;
use App\Rules\NumIncvMemRule;
use App\Rules\VMPoliyExistsForTeam;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;
use Livewire\WithPagination;

class VmConfigManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $addingVMPolicy = false;
    public $addVMPolicyForm = [
        'name' => '',
        'min_vcpu' => 1,
        'max_vcpu' => 128,
        'inc_vcpu' => '',
        'min_vmem' => 1,
        'max_vmem' => 12800,
        'inc_vmem' => '',
        'cost_vcpu' => 0.1346,
        'cost_vmem' => 0.247,
    ];
    public $confirmingVmspecRemoval = false;
    public $vmspecId;
    public $confirmingSelectedVmspecRemoval = false;
    public $managingVmspec = false;

    public function mount($id)
    {
        $this->team = Jetstream::newTeamModel()->findOrFail($id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }

        $this->getVMPolicyForm();
    }

    public function getVMPolicyForm()
    {
        // dd(config('vmspecs'));
        $vmspec = config('vmspecs');
        $this->addVMPolicyForm = [
            'name' => '',
            'min_vcpu' => $vmspec['min_vcpu'],
            'max_vcpu' => $vmspec['max_vcpu'],
            'inc_vcpu' => $vmspec['inc_vcpu'],
            'min_vmem' => $vmspec['min_vmem'],
            'max_vmem' => $vmspec['max_vmem'],
            'inc_vmem' => $vmspec['inc_vmem'],
            'cost_vcpu' => $vmspec['cost_vcpu'],
            'cost_vmem' => $vmspec['cost_vmem'],
        ];
    }

    public function getVmspecsProperty()
    {
        return vmspec::where('team_id', $this->team->id)
            ->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $vmspecs = $this->vmspecs;
        return view('manage.vm-config.vm-config-manager', ['team' => $this->team, 'vmspecs' => $vmspecs]);
    }

    public function updatedSelectedRows()
    {
        $row = $this->vmspecs->pluck('id')->map(function ($id) {
            return (string) $id;
        });
        if (count($this->selectedRows) === count($row)) {
            $this->selectedPageRow = true;
        } else {
            $this->reset(['selectedPageRow']);
        }
    }

    public function updatedselectedPageRow($value)
    {
        if ($value) {
            $this->selectedRows = $this->vmspecs->pluck('id')->map(function ($id) {
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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
            return;
        }
        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }
    
    public function confirmVMPolicyAddition()
    {
        $this->resetErrorBag();
        $this->getVMPolicyForm();
        $this->addingVMPolicy = true;
    }

    public function addVMPolicy()
    {
        if (!is_array($this->addVMPolicyForm['inc_vcpu'])){
            $this->addVMPolicyForm['inc_vcpu'] = explode(',', $this->addVMPolicyForm['inc_vcpu']);
            $this->addVMPolicyForm['inc_vcpu'] = array_map('intval', $this->addVMPolicyForm['inc_vcpu']);
        };
        if (!is_array($this->addVMPolicyForm['inc_vmem'])){
            $this->addVMPolicyForm['inc_vmem'] = explode(',', $this->addVMPolicyForm['inc_vmem']);
            $this->addVMPolicyForm['inc_vmem'] = array_map('intval', $this->addVMPolicyForm['inc_vmem']);
        }

        $this->validate(
            [
                'addVMPolicyForm.name' => ['required', 'min:3', 'max:255', new VMPoliyExistsForTeam($this->team->id)],
                'addVMPolicyForm.min_vcpu' => ['required', 'integer', 'gt:0', new MinMaxRule($this->addVMPolicyForm['max_vcpu'])],
                'addVMPolicyForm.max_vcpu' => ['required', 'integer', 'gt:0'],
                'addVMPolicyForm.inc_vcpu' => ['required', new NumIncvCpuRule($this->addVMPolicyForm['min_vcpu'])],
                'addVMPolicyForm.min_vmem' => ['required', 'integer', 'gt:0', new MinMaxRule($this->addVMPolicyForm['max_vmem'])],
                'addVMPolicyForm.max_vmem' => ['required', 'integer', 'gt:0'],
                'addVMPolicyForm.inc_vmem' => ['required', new NumIncvMemRule()],
                'addVMPolicyForm.cost_vcpu' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                'addVMPolicyForm.cost_vmem' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
            ],
            [
                'addVMPolicyForm.name.required' => 'The VM policy name is required.',
                'addVMPolicyForm.name.min' => 'The VM policy name must be at least 3 characters.',
                'addVMPolicyForm.name.max' => 'The VM policy name may not be greater than 255 characters.',
                'addVMPolicyForm.min_vcpu.required' => 'The Min vCPU is required.',
                'addVMPolicyForm.min_vcpu.integer' => 'The Min vCPU must be integer.',
                'addVMPolicyForm.min_vcpu.gt' => 'The Min vCPU must be greater than 0.',
                'addVMPolicyForm.max_vcpu.required' => 'The Max vCPU is required.',
                'addVMPolicyForm.max_vcpu.integer' => 'The Max vCPU must be integer.',
                'addVMPolicyForm.max_vcpu.gt' => 'The Max vCPU must be greater than 0.',
            ],
        );

        $vmspec = new vmspec();
        $vmspec->team_id = $this->team->id;
        $vmspec->name = $this->addVMPolicyForm['name'];
        $vmspec->min_vcpu = $this->addVMPolicyForm['min_vcpu'];
        $vmspec->max_vcpu = $this->addVMPolicyForm['max_vcpu'];
        $vmspec->inc_vcpu = json_encode($this->addVMPolicyForm['inc_vcpu']);
        $vmspec->min_vmem = $this->addVMPolicyForm['min_vmem'];
        $vmspec->max_vmem = $this->addVMPolicyForm['max_vmem'];
        $vmspec->inc_vmem = json_encode($this->addVMPolicyForm['inc_vmem']);
        $vmspec->cost_vcpu = $this->addVMPolicyForm['cost_vcpu'];
        $vmspec->cost_vmem = $this->addVMPolicyForm['cost_vmem'];
        $vmspec->created_by = Auth()->id();
        $vmspec->updated_by = Auth()->id();
        $vmspec->save();

        $data = ['style' => 'success', 'message' => 'VM Policy created successfully.'];
        $this->dispatch('showBanner', $data);
        $this->getVMPolicyForm();

        $this->resetErrorBag();
        $this->addingVMPolicy = false;
    }

    public function confirmVmspecRemoval($id)
    {
        $this->confirmingVmspecRemoval = true;
        $this->vmspecId = $id;
    }

    public function deleteVmspec()
    {
        $vmspec = Vmspec::find($this->vmspecId);
        if ($vmspec) {
            // If the vmspec record exists, delete it
            $vmspec->delete();
            $this->confirmingVmspecRemoval = false;
            $data = ['style' => 'success', 'message' => 'VM Policy deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmSelectedVmspecRemoval()
    {
        $this->confirmingSelectedVmspecRemoval = true;
    }

    public function deleteSelectedRows()
    {
        vmspec::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedVmspecRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Policy deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }


    public function removeBracket($value) {

        $value = str_replace('[', '', $value);
        $value = str_replace(']', '', $value);
        return $value;
    }

    public function confirmManageVmspec($id)
    {
        // $this->resetPage();
        $this->resetErrorBag();
        $this->vmspecId = $id;
        $vmspec = vmspec::find($id);
        $this->managingVmspec = true;

        if ($vmspec) {
            // If the record exists, retrieve information
            $this->addVMPolicyForm = [
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

        } else {
            return;
        }
    }

    public function updateVMPolicy()
    {
        $vmspec = vmspec::find($this->vmspecId);

        if (!is_array($this->addVMPolicyForm['inc_vcpu'])){
            $this->addVMPolicyForm['inc_vcpu'] = explode(',', $this->addVMPolicyForm['inc_vcpu']);
            $this->addVMPolicyForm['inc_vcpu'] = array_map('intval', $this->addVMPolicyForm['inc_vcpu']);
        };
        if (!is_array($this->addVMPolicyForm['inc_vmem'])){
            $this->addVMPolicyForm['inc_vmem'] = explode(',', $this->addVMPolicyForm['inc_vmem']);
            $this->addVMPolicyForm['inc_vmem'] = array_map('intval', $this->addVMPolicyForm['inc_vmem']);
        }

        if ($vmspec->name === $this->addVMPolicyForm['name']) {
            $this->validate(
                [
                    // 'addVMPolicyForm.name' => ['required', 'min:3', 'max:255', new VMPoliyExistsForTeam($this->team->id)],
                    'addVMPolicyForm.min_vcpu' => ['required', 'integer', 'gt:0', new MinMaxRule($this->addVMPolicyForm['max_vcpu'])],
                    'addVMPolicyForm.max_vcpu' => ['required', 'integer', 'gt:0'],
                    'addVMPolicyForm.inc_vcpu' => ['required', new NumIncvCpuRule($this->addVMPolicyForm['min_vcpu'])],
                    'addVMPolicyForm.min_vmem' => ['required', 'integer', 'gt:0', new MinMaxRule($this->addVMPolicyForm['max_vmem'])],
                    'addVMPolicyForm.max_vmem' => ['required', 'integer', 'gt:0'],
                    'addVMPolicyForm.inc_vmem' => ['required', new NumIncvMemRule()],
                    'addVMPolicyForm.cost_vcpu' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                    'addVMPolicyForm.cost_vmem' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                ],
                [
                    'addVMPolicyForm.name.required' => 'The VM policy name is required.',
                    'addVMPolicyForm.name.min' => 'The VM policy name must be at least 3 characters.',
                    'addVMPolicyForm.name.max' => 'The VM policy name may not be greater than 255 characters.',
                    'addVMPolicyForm.min_vcpu.required' => 'The Min vCPU is required.',
                    'addVMPolicyForm.min_vcpu.integer' => 'The Min vCPU must be integer.',
                    'addVMPolicyForm.min_vcpu.gt' => 'The Min vCPU must be greater than 0.',
                    'addVMPolicyForm.max_vcpu.required' => 'The Max vCPU is required.',
                    'addVMPolicyForm.max_vcpu.integer' => 'The Max vCPU must be integer.',
                    'addVMPolicyForm.max_vcpu.gt' => 'The Max vCPU must be greater than 0.',
                ],
            );
        } else {
            $this->validate(
                [
                    'addVMPolicyForm.name' => ['required', 'min:3', 'max:255', new VMPoliyExistsForTeam($this->team->id)],
                    'addVMPolicyForm.min_vcpu' => ['required', 'integer', 'gt:0', new MinMaxRule($this->addVMPolicyForm['max_vcpu'])],
                    'addVMPolicyForm.max_vcpu' => ['required', 'integer', 'gt:0'],
                    'addVMPolicyForm.inc_vcpu' => ['required', new NumIncvCpuRule($this->addVMPolicyForm['min_vcpu'])],
                    'addVMPolicyForm.min_vmem' => ['required', 'integer', 'gt:0', new MinMaxRule($this->addVMPolicyForm['max_vmem'])],
                    'addVMPolicyForm.max_vmem' => ['required', 'integer', 'gt:0'],
                    'addVMPolicyForm.inc_vmem' => ['required', new NumIncvMemRule()],
                    'addVMPolicyForm.cost_vcpu' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                    'addVMPolicyForm.cost_vmem' => ['required', 'numeric', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                ],
                [
                    'addVMPolicyForm.name.required' => 'The VM policy name is required.',
                    'addVMPolicyForm.name.min' => 'The VM policy name must be at least 3 characters.',
                    'addVMPolicyForm.name.max' => 'The VM policy name may not be greater than 255 characters.',
                    'addVMPolicyForm.min_vcpu.required' => 'The Min vCPU is required.',
                    'addVMPolicyForm.min_vcpu.integer' => 'The Min vCPU must be integer.',
                    'addVMPolicyForm.min_vcpu.gt' => 'The Min vCPU must be greater than 0.',
                    'addVMPolicyForm.max_vcpu.required' => 'The Max vCPU is required.',
                    'addVMPolicyForm.max_vcpu.integer' => 'The Max vCPU must be integer.',
                    'addVMPolicyForm.max_vcpu.gt' => 'The Max vCPU must be greater than 0.',
                ],
            );
        }

        $vmspec->name = $this->addVMPolicyForm['name'];
        $vmspec->min_vcpu = $this->addVMPolicyForm['min_vcpu'];
        $vmspec->max_vcpu = $this->addVMPolicyForm['max_vcpu'];
        $vmspec->inc_vcpu = json_encode($this->addVMPolicyForm['inc_vcpu']);
        $vmspec->min_vmem = $this->addVMPolicyForm['min_vmem'];
        $vmspec->max_vmem = $this->addVMPolicyForm['max_vmem'];
        $vmspec->inc_vmem = json_encode($this->addVMPolicyForm['inc_vmem']);
        $vmspec->cost_vcpu = $this->addVMPolicyForm['cost_vcpu'];
        $vmspec->cost_vmem = $this->addVMPolicyForm['cost_vmem'];
        $vmspec->updated_by = Auth()->id();
        $vmspec->save();

        $data = ['style' => 'success', 'message' => 'VM Policy updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingVmspec = false;

        // $this->render();
    }
}
