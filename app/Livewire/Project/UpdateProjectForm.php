<?php

namespace App\Livewire\Project;

use App\Rules\ProjectExistsForTeam;
use Livewire\Component;

class UpdateProjectForm extends Component
{
    public $project;
    public $projectForm = [
        'name' => '',
        'description' => '',
        'charge_code' => '',
        'timeline' => '',
        'budget' => '',
        'updated_by' => '',
    ];

    public function mount($project)
    {
        $this->project = $project;

        $this->projectForm['name'] = $project->name; 
        $this->projectForm['description'] = $project->description; 
        $this->projectForm['charge_code'] = $project->charge_code; 
        $this->projectForm['budget'] = $project->budget; 
        $this->projectForm['timeline'] = $project->timeline;
        $this->projectForm['cost'] = $project->cost;
        $this->projectForm['updated_by'] = $project->updated_by;

        // dd($project->timeline);
        
    }

    public function render()
    {
        return view('project.update-project-form', ['project' => $this->project]);
    }

    public function updateProject()
    {
        if (trim($this->projectForm['name']) === trim($this->project->name)) {
            
        } else {
            $this->validate(
                [
                    'projectForm.name' => ['required', 'min:3', 'max:255', new ProjectExistsForTeam(Auth()->User()->currentTeam->id)],
                ],
                [
                    'projectForm.name.required' => 'The project name is required.',
                    'projectForm.name.min' => 'The project name must be at least 3 characters.',
                    'projectForm.name.max' => 'The project name may not be greater than 255 characters.',
                ],
            );
        }
        $this->validate(
            [
                'projectForm.budget' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,12}(\.\d{1,4})?$/'],
            ],
            [
                'projectForm.budget.required' => 'The project budget is required.',
                'projectForm.budget.numeric' => 'The project budget must be numeric.',
                'projectForm.budget.gt' => 'The project budget must be greater than 0.',
            ],
        );

        // $project = new Project();
        $this->project->name = trim($this->projectForm['name']);
        $this->project->description = trim($this->projectForm['description']);
        $this->project->charge_code = trim($this->projectForm['charge_code']);
        $this->project->budget = $this->projectForm['budget'];
        $this->project->timeline = $this->projectForm['timeline'];
        // $this->project->cost = 0;
        // $this->project->team_id = $this->team->id;
        // $this->project->owner = Auth()->id();
        $this->project->updated_by = Auth()->id();
        $this->project->save();

        // $data = ['style' => 'success', 'message' => 'Project created successfully.'];
        // $this->dispatch('showBanner', $data);

        // $this->projectForm['name'] = '';
        // $this->projectForm['description'] = '';
        // $this->projectForm['charge_code'] = '';
        // $this->projectForm['budget'] = '';
        $this->resetErrorBag();
        $this->dispatch('saved');

    }

    public function goToProject($id)
    {

        // Navigate to the project/{id} route
        return redirect()->route('project/{id}/addprojectmember', ['id' => $id]);
    }
}
