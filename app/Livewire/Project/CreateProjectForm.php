<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Rules\ProjectExistsForTeam;
use Illuminate\Http\Request;
use Livewire\Component;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Gate;

class CreateProjectForm extends Component
{
    public $projectForm = [
        'name' => '',
        'description' => '',
        'charge_code' => '',
        'timeline' => '',
        'budget' => '',
    ];
    public $team;

    public function mount()
    {
        $this->team = Jetstream::newTeamModel()->findOrFail(Auth()->User()->currentTeam->id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }
    }

    public function render(Request $request)
    {
        return view('project.create-project-form', [
            'user' => $request->user(),
        ]);
    }

    public function createProject()
    {
        $this->validate(
            [
                'projectForm.name' => ['required', 'min:3', 'max:255', new ProjectExistsForTeam($this->team->id)],
                'projectForm.budget' => ['required', 'numeric', 'gt:0', 'regex:/^\d{1,12}(\.\d{1,4})?$/'],
            ],
            [
                'projectForm.name.required' => 'The project name is required.',
                'projectForm.name.min' => 'The project name must be at least 3 characters.',
                'projectForm.name.max' => 'The project name may not be greater than 255 characters.',
                'projectForm.budget.required' => 'The project budget is required.',
                'projectForm.budget.numeric' => 'The project budget must be numeric.',
                'projectForm.budget.gt' => 'The project budget must be greater than 0.',
            ],
        );

        $project = new Project();
        $project->name = trim($this->projectForm['name']);
        $project->description = trim($this->projectForm['description']);
        $project->charge_code = trim($this->projectForm['charge_code']);
        $project->budget = $this->projectForm['budget'];
        $project->timeline = $this->projectForm['timeline'];
        $project->cost = 0;
        $project->team_id = $this->team->id;
        $project->owner = Auth()->id();
        $project->created_by = Auth()->id();
        $project->updated_by = Auth()->id();
        $project->save();

        $data = ['style' => 'success', 'message' => 'Project created successfully.'];
        $this->dispatch('showBanner', $data);

        $this->projectForm['name'] = '';
        $this->projectForm['description'] = '';
        $this->projectForm['charge_code'] = '';
        $this->projectForm['budget'] = '';
        $this->resetErrorBag();

        return redirect('project');
    }
}
