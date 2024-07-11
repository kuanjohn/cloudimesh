<?php

namespace App\Livewire\Project;

use App\Models\VirtualMachine;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectManager extends Component
{
    use WithPagination;
    public $project;
    // public $vms;

    public $search;
    public $perPage = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRow = false;
    public $selectedRows = [];

    public function mount($project)
    {
        $this->project = $project;
        // $this->vms = VirtualMachine::where('project_id',$this->project->id)
        // ->orderBy($this->sortField, $this->sortDirection)->paginate(5);
        // $this->vms = $this->project->vms->orderBy($this->sortField, $this->sortDirection)->paginate(5);
        // dd($this->vms);
    }

    public function getVmsProperty()
    {
        return VirtualMachine::where('project_id',$this->project->id)
            ->where('name', 'like', '%' . strtolower($this->search) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        $vms = $this->vms;
        return view('project.project-manager', ['project' => $this->project, 'vms' => $vms]);
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
}
