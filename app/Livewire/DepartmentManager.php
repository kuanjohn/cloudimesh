<?php

namespace App\Livewire;

use App\Models\Department;
use App\Rules\DepartmentExistsForTeam;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class DepartmentManager extends Component
{
    use WithPagination;
    public $team;
    public $users;

    public $search;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 5;
    public $selectedRowsforDepartment = [];
    public $selectedPageRowsforDepartment = false;
    public $confirmingSelectedDepartmentRemoval = false;
    public $teamDepartmentIdBeingRemoved;

    public $name;
    public $hod;
    public $code;
    public $published;
    public $addDepartmentForm = [
        'id' => '',
        'name' => '',
        'code' => '',
        'hod' => null,
        'published' => true,
        'created_by' => '',
        'updated_by' => '',
    ];

    public $confirmingDepartmentRemoval = false;
    public $department_id;
    public $managingDepartment = false;
    public $managingImport = false;

    public $commonDepartments = [];
    public $availableDepartments = [];
    public $selectedDepartments = [];
    public $selectedOptions = [];
    public $excludedDepartments = [];

    public function mount($team)
    {
        $this->team = $team;
        $this->users = $this->team->allUsers()->sortBy($this->sortField);
    }

    public function addDepartment()
    {
        $this->validate(
            [
                'name' => ['required', 'min:3', 'max:255', new DepartmentExistsForTeam($this->team->id)],
                'code' => ['max:100'],
            ],
            [
                'name.required' => 'The department name is required.',
                'name.min' => 'The department name must be at least 3 characters.',
                'name.max' => 'The department name may not be greater than 255 characters.',
                // 'name.exists_for_team' => 'The department name already exists for this team.',
                'code.max' => 'The department code may not be greater than 100 characters.',
            ],
        );

        $department = new Department();
        $department->team_id = $this->team->id;
        $department->name = trim($this->name);
        $department->hod = $this->hod;
        $department->code = trim($this->code);
        $department->created_by = Auth()->id();
        $department->updated_by = Auth()->id();
        $department->save();

        $this->dispatch('saved');

        $this->name = '';
        $this->hod = null;
        $this->code = '';
    }

    public function toggleActive($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        $department->published = !$department->published;
        $department->updated_by = Auth()->id();
        $department->save();

        $this->dispatch('toggled');
    }

    public function updatedSelectedPageRowsforDepartment($value)
    {
        if ($value) {
            $this->selectedRowsforDepartment = $this->departments->pluck('id')->map(function ($id) {
                return (string) $id;
            });
            // dd($value);
        } else {
            $this->reset(['selectedPageRowsforDepartment', 'selectedRowsforDepartment']);
        }
    }

    public function updatedSelectedRowsforDepartment()
    {
        // dd(count($this->selectedPageRowsforDepartment));
        $row = $this->departments->pluck('id')->map(function ($id) {
            return (string) $id;
        });
        if (count($this->selectedRowsforDepartment) === count($row)) {
            $this->selectedPageRowsforDepartment = true;
        } else {
            $this->reset(['selectedPageRowsforDepartment']);
        }
    }

    public function confirmSelectedDepartmentRemoval()
    {
        // $this->previousPage();
        $this->confirmingSelectedDepartmentRemoval = true;
        // dd($this->selectedRows );
    }

    public function deleteSelectedRowsforDepartment()
    {
        // dd(Tier::whereIn('id', $this->selectedRows)->get());

        // dd($this->selectedRowsforDepartment);

        foreach ($this->selectedRowsforDepartment as $selectedRowforDepartment) {
            $this->teamDepartmentIdBeingRemoved = $selectedRowforDepartment;

            // dd($this->teamDepartmentIdBeingRemoved);
            // $this->removeTeamMember();
            // $remover->remove($this->user, $this->team, $user = Jetstream::findUserByIdOrFail($this->teamMemberIdBeingRemoved));

            // Find the department record you want to delete by its ID
            $department = Department::find($this->teamDepartmentIdBeingRemoved);
            // dd($department);
            if ($department) {
                // If the department record exists, delete it
                $department->delete();
                // $this->confirmingDepartmentRemoval = false;
            } else {
                return;
            }
            $this->teamDepartmentIdBeingRemoved = null;

            $data = ['style' => 'success', 'message' => 'Selected Departments are deleted successfully.'];
            $this->dispatch('showBanner', $data);

            // $this->team = $this->team->fresh();
        }

        $this->confirmingSelectedDepartmentRemoval = false;

        $this->reset(['selectedPageRowsforDepartment', 'selectedRowsforDepartment']);

        // $data = ['style' => 'success', 'message' => 'Selected Location deleted successfully.'];
        // $this->dispatch('showBanner', $data);

        $this->resetPage();
    }

    public function confirmDepartmentRemoval($department_id)
    {
        $this->confirmingDepartmentRemoval = true;
        $this->department_id = $department_id;
    }

    public function removeDepartment()
    {
        // Find the department record you want to delete by its ID
        $department = Department::find($this->department_id);
        // dd($department);
        if ($department) {
            // If the department record exists, delete it
            $department->delete();
            $this->confirmingDepartmentRemoval = false;

            $data = ['style' => 'success', 'message' => 'Department deleted successfully.'];
            $this->dispatch('showBanner', $data);
        } else {
            return;
        }
    }

    public function manageDepartment($department_id)
    {
        // dd($departId);
        $department = Department::find($department_id);

        // $this->departmentId = $department_id;

        // dd($this->department);

        if ($department) {
            $this->managingDepartment = true;

            $this->addDepartmentForm['id'] = $department->id;
            $this->addDepartmentForm['name'] = $department->name;
            $this->addDepartmentForm['hod'] = $department->hod;
            $this->addDepartmentForm['code'] = $department->code;
            $this->addDepartmentForm['published'] = $department->published == 1 ? true : false;
            // dd($this->addDepartmentForm['published']);
            $this->addDepartmentForm['updated_by'] = $department->updated_by;

            // dd($this->addDepartmentForm);
            // $this->cr = $this->department->creator->name;
        } else {
            return;
        }
    }

    public function updateDepartment()
    {
        // dd($this->team->id, $this->name, Auth::id(), $this->hod, $this->code);
        $department = Department::find($this->addDepartmentForm['id']);

        // dd($department);

        if ($department->name === $this->addDepartmentForm['name']) {
        } else {
            $this->validate(
                [
                    'addDepartmentForm.name' => ['required', 'min:3', 'max:255', new DepartmentExistsForTeam($this->team->id)],
                    'addDepartmentForm.code' => ['max:100'],
                ],
                [
                    'addDepartmentForm.name.required' => 'The department name is required.',
                    'addDepartmentForm.name.min' => 'The department name must be at least 3 characters.',
                    'addDepartmentForm.name.max' => 'The department name may not be greater than 255 characters.',
                    // 'addDepartmentForm.name.exists_for_team' => 'The department name already exists for this team.',
                    'addDepartmentForm.code.max' => 'The department code may not be greater than 100 characters.',
                ],
            );
        }

        $department->name = trim($this->addDepartmentForm['name']);
        $department->hod = $this->addDepartmentForm['hod'] === '0' ? null : $this->addDepartmentForm['hod'];
        $department->code = trim($this->addDepartmentForm['code']);
        $department->updated_by = Auth()->id();
        $department->published = $this->addDepartmentForm['published'];
        $department->save();

        $this->managingDepartment = false;

        $data = ['style' => 'success', 'message' => 'Department updated successfully.'];
        $this->dispatch('showBanner', $data);
        // $this->render();
    }

    public function confirmImport()
    {
        // dd('here');
        $this->managingImport = true;
        // $this->availableDepartments = config('departments.name');
        $this->commonDepartments = config('departments.name');
        $this->availableDepartments = [];
        $this->excludedDepartments = [];
        // $this->selectedDepartments = $this->commonDepartments;
    }

    public function importDepartment()
    {
        $i = 0;

        foreach ($this->commonDepartments as $commonDepartment) {
            $departmentExists = Department::where('name', trim($commonDepartment))
                ->where('team_id', $this->team->id)
                ->exists();

            if (!$departmentExists) {
                $department = new Department();
                $department->team_id = $this->team->id;
                $department->name = trim($commonDepartment);
                // $department->hod = $this->hod;
                // $department->code = trim($this->code);
                $department->created_by = Auth()->id();
                $department->updated_by = Auth()->id();
                $department->save();
                $i++;
            }

            $this->managingImport = false;

            $data = ['style' => 'success', 'message' => $i . Str::plural(' department', $i) . ' imported successfully.'];
            $this->dispatch('showBanner', $data);
        }
    }

    public function removeSelected()
    {
        $selectedDepartment = $this->selectedDepartments;
        $this->commonDepartments = array_diff($this->commonDepartments, $selectedDepartment);
        $this->excludedDepartments = array_merge($this->excludedDepartments, $selectedDepartment);
        $this->selectedDepartments = [];
    }

    public function addSelected()
    {
        $availableDepartment = $this->availableDepartments;
        $this->excludedDepartments = array_diff($this->excludedDepartments, $availableDepartment);
        $this->commonDepartments = array_merge($this->commonDepartments, $availableDepartment);
        $this->availableDepartments = [];
    }

    public function getDepartmentsProperty()
    {
        return $this->team
            ->departments()
            ->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        // $departments = Department::where('name', 'like', '%' . strtolower($this->search) . '%')
        //     ->where('team_id', $this->team->id)
        //     // ->with('head')
        //     ->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate($this->perPage);

        $departments = $this->departments;
        // $departments = $this->team
        //     ->departments()
        //     ->where('name', 'like', '%' . strtolower($this->search) . '%')
        //     ->orderBy($this->sortField, $this->sortDirection)
        //     ->paginate($this->perPage);

        return view('livewire.department-manager', ['departments' => $departments]);
    }
}
