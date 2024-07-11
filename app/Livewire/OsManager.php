<?php

namespace App\Livewire;

use App\Models\OperatingSystem;
use App\Rules\OsExistsForTeam;
use Livewire\Component;
use Livewire\WithPagination;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Gate;

class OsManager extends Component
{
    use WithPagination;

    public $team;
    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public $managingImport = false;
    public $commonOses = [];
    public $confirmingSelectedOsRemoval = false;
    public $confirmingOsRemoval = false;
    public $OsId;
    public $OsForm = [
        'name' => '',
        'type' => '',
        'version' => '',
        'cost' => '',
        'cost_type' => '',
        'min_disk' => '',
        'published' => true,
    ];
    public $managingOs = false;
    public $addingOs = false;

    public function mount($id)
    {
        $this->team = Jetstream::newTeamModel()->findOrFail($id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }
    }

    public function getOperatingSystemsProperty()
    {
        return OperatingSystem::where('team_id', $this->team->id)
        ->where(function($query){

            $query->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('type', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('version', 'like', '%' . strtolower($this->search) . '%')
            ->orWhere('cost_type', 'like', '%' . strtolower($this->search) . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $operatingSystems = $this->operatingSystems;
        return view('manage.os.os-manager', ['team' => $this->team, 'operatingSystems' => $operatingSystems]);
    }

    public function confirmImport()
    {
        // dd('here');
        $this->managingImport = true;
        // $this->availableOses = config('departments.name');
        $this->commonOses = config('operatingsystem');
        // $this->selectedDepartments = $this->commonDepartments;
    }

    public function importOs()
    {
        $i = 0;
        foreach ($this->commonOses as $osName => $osConfig) {
            foreach ($osConfig as $osVer => $os) {
                $OsExists = OperatingSystem::where('name', trim($osVer))
                    ->where('team_id', $this->team->id)
                    ->exists();

                if (!$OsExists) {
                    $operatingSystem = new OperatingSystem();
                    $operatingSystem->team_id = $this->team->id;
                    $operatingSystem->name = trim($osVer);
                    $operatingSystem->type = $osName;
                    $operatingSystem->version = trim($osVer);
                    $operatingSystem->published = true;
                    $operatingSystem->cost = $os['cost'];
                    $operatingSystem->cost_type = $os['cost_type'];
                    $operatingSystem->min_disk = $os['min_disk'];
                    $operatingSystem->created_by = Auth()->id();
                    $operatingSystem->updated_by = Auth()->id();
                    $operatingSystem->save();
                    $i++;
                }
            }
        }
        if ($i === 0) {
            $data = ['style' => 'info', 'message' => 'No Operating System imported.'];
            $this->dispatch('showBanner', $data);
        } else {
            $data = ['style' => 'success', 'message' => $i . ' Operating Systems imported successfully.'];
            $this->dispatch('showBanner', $data);
        }
        
        $this->managingImport = false;
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
        $row = $this->operatingSystems->pluck('id')->map(function ($id) {
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
        OperatingSystem::whereIn('id', $this->selectedRows)->delete();

        $this->confirmingSelectedOsRemoval = false;

        $this->reset(['selectedPageRow', 'selectedRows']);

        $data = ['style' => 'success', 'message' => 'Selected Operating System deleted successfully.'];
        $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function updatedselectedPageRow($value)
    {
        if ($value) {
            $this->selectedRows = $this->operatingSystems->pluck('id')->map(function ($id) {
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

    public function confirmSelectedOsRemoval()
    {
        $this->confirmingSelectedOsRemoval = true;
    }

    public function confirmOsRemoval($id)
    {
        $this->confirmingOsRemoval = true;
        $this->OsId = $id;
    }

    public function deleteOs()
    {
        $Os = OperatingSystem::find($this->OsId);
        if ($Os) {
            // If the Os record exists, delete it
            $Os->delete();
            $this->confirmingOsRemoval = false;
            $data = ['style' => 'success', 'message' => 'Operating System deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function confirmManageOs($id)
    {
        $this->resetPage();
        $this->resetErrorBag();
        $this->OsId = $id;
        $os = OperatingSystem::find($id);
        $this->managingOs = true;

        if ($os) {
            // If the department record exists, retrieve information
            $this->OsForm['id'] = $id;
            $this->OsForm['name'] = $os->name;
            $this->OsForm['type'] = $os->type;
            $this->OsForm['version'] = $os->version;
            $this->OsForm['cost'] = $os->cost;
            $this->OsForm['cost_type'] = $os->cost_type;
            $this->OsForm['min_disk'] = $os->min_disk;
            $this->OsForm['published'] = $os->published == 1 ? true : false;
        } else {
            return;
        }
    }

    public function updateOs()
    {
        $os = OperatingSystem::find($this->OsId);

        if ($os->name !== $this->OsForm['name']) {
            $this->validate(
                [
                    'OsForm.name' => ['required', 'min:3', 'max:255', new OsExistsForTeam($this->team->id)],
                ],
                [
                    'OsForm.name.required' => 'The operating system name is required.',
                    'OsForm.name.min' => 'The operating system name must be at least 3 characters.',
                    'OsForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                ],
            );
        }

        $this->validate(
            [
                'OsForm.version' => ['required', 'min:3', 'max:255'],
                'OsForm.cost' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                'OsForm.min_disk' => ['required', 'integer', 'gt:0'],
            ],
            [
                'OsForm.name.required' => 'The operating system name is required.',
                'OsForm.name.min' => 'The operating system name must be at least 3 characters.',
                'OsForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                'OsForm.version.required' => 'The operating system version is required.',
                'OsForm.version.min' => 'The operating system version must be at least 3 characters.',
                'OsForm.version.max' => 'The operating system version may not be greater than 255 characters.',
                'OsForm.cost.required' => 'The operating system cost is required.',
                'OsForm.cost.numeric' => 'The operating system cost must be numeric.',
                'OsForm.cost.gt' => 'The operating system cost must be greater than 0.',
                'OsForm.cost.regex' => 'The operating system cost can have maximum 4 decimals.',
                'OsForm.min_disk.required' => 'The operating system disk size is required.',
                'OsForm.min_disk.numeric' => 'The operating system disk size must be integer.',
                'OsForm.min_disk.gt' => 'The operating system disk size must be greater than 0.',
            ],
        );

        $os->name = trim($this->OsForm['name']);
        $os->type = $this->OsForm['type'];
        $os->version = trim($this->OsForm['version']);
        $os->cost = $this->OsForm['cost'];
        $os->cost_type = $this->OsForm['cost_type'];
        $os->min_disk = $this->OsForm['min_disk'];
        $os->published = $this->OsForm['published'];
        $os->team_id = $this->team->id;
        $os->updated_by = Auth()->id();
        $os->save();

        $data = ['style' => 'success', 'message' => 'Operating System updated successfully.'];
        $this->dispatch('showBanner', $data);
        $this->managingOs = false;

        // $this->render();
    }

    public function confirmOsAddition()
    {
        $this->resetPage();
        $this->resetErrorBag();
        // $this->OsId = $id;
        // $os = OperatingSystem::find($id);

        $this->OsForm['id'] = '';
        $this->OsForm['name'] = '';
        $this->OsForm['type'] = 'Arch Linux';
        $this->OsForm['version'] = '';
        $this->OsForm['cost'] = '';
        $this->OsForm['cost_type'] = 'Core';
        $this->OsForm['min_disk'] = '';
        $this->OsForm['published'] = true;

        $this->addingOs = true;
    }

    public function addOs()
    {
        $this->validate(
            [
                'OsForm.name' => ['required', 'min:3', 'max:255', new OsExistsForTeam($this->team->id)],
                'OsForm.version' => ['required', 'min:3', 'max:255'],
                'OsForm.cost' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,6}(\.\d{1,4})?$/'],
                'OsForm.min_disk' => ['required', 'integer', 'gt:0'],
            ],
            [
                'OsForm.name.required' => 'The operating system name is required.',
                'OsForm.name.min' => 'The operating system name must be at least 3 characters.',
                'OsForm.name.max' => 'The operating system name may not be greater than 255 characters.',

                'OsForm.name.required' => 'The operating system name is required.',
                'OsForm.name.min' => 'The operating system name must be at least 3 characters.',
                'OsForm.name.max' => 'The operating system name may not be greater than 255 characters.',
                'OsForm.version.required' => 'The operating system version is required.',
                'OsForm.version.min' => 'The operating system version must be at least 3 characters.',
                'OsForm.version.max' => 'The operating system version may not be greater than 255 characters.',
                'OsForm.cost.required' => 'The operating system cost is required.',
                'OsForm.cost.numeric' => 'The operating system cost must be numeric.',
                'OsForm.cost.gt' => 'The operating system cost must be greater than 0.',
                'OsForm.cost.regex' => 'The operating system cost can have maximum 4 decimals.',
                'OsForm.min_disk.required' => 'The operating system disk size is required.',
                'OsForm.min_disk.numeric' => 'The operating system disk size must be integer.',
                'OsForm.min_disk.gt' => 'The operating system disk size must be greater than 0.',
            ],
        );

        $os = new OperatingSystem();
        $os->name = trim($this->OsForm['name']);
        $os->type = $this->OsForm['type'];
        $os->version = trim($this->OsForm['version']);
        $os->cost = $this->OsForm['cost'];
        $os->cost_type = $this->OsForm['cost_type'];
        $os->min_disk = $this->OsForm['min_disk'];
        $os->published = $this->OsForm['published'];
        $os->team_id = $this->team->id;
        $os->created_by = Auth()->id();
        $os->updated_by = Auth()->id();
        $os->save();

        $data = ['style' => 'success', 'message' => 'Os added successfully.'];
        $this->dispatch('showBanner', $data);
        $this->addingOs = false;

        // $this->render();
    }
}
