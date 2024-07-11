<?php

namespace App\Livewire\Project;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Gate;


use Livewire\Component;

class AddProjectMemberForm extends Component
{
    public $project;
    public $team;
    public $search;
    public $perPageforTeamUser = 5;
    public $projectUserId;


    public function mount($project)
    {
        $this->project = $project;
        // dd($this->project->owner);
        $this->team = Jetstream::newTeamModel()->findOrFail(Auth()->User()->currentTeam->id);
        if (Gate::denies('addProjectMember', $project)) {
            abort(403);
        }
    }

    public function getteamUsersProperty()
    {
        return $this->team->users()
        ->whereNotIn('user_id', [$this->project->owner])
        // ->whereNotIn('user_id', [auth()->id()])
        ->whereNotIn('user_id', function ($query) {
            $query
                ->select('user_id')
                ->from('project_users')
                ->where('project_id', $this->project->id);
                // ->orWhere('owner', auth()->user()->id);
        })
        ->where(function ($query) {
            $query->where('name', 'like', '%' . strtolower($this->search) . '%')
                ->orWhere('email', 'like', '%' . strtolower($this->search) . '%');
        })
        ->orderBy('name', 'asc')
        // ->get();
        ->paginate($this->perPageforTeamUser);
    }

    public function render()
    {
        // $teamOwner = $this->team->owner;
        $team_users = $this->teamUsers;
        // $team_users = $team_users->merge([$teamOwner]);

        // dd($team_users);

        return view('project.add-project-member-form', ['project' => $this->project, 'team_users' => $team_users]);
    }

    public function addProjectMember()
    {
        $newProjectMember = Jetstream::findUserByIdOrFail($this->projectUserId);
        $this->project->users()->attach($newProjectMember, ['role' => 'Editor', 'created_by' => Auth()->id(), 'updated_by' => Auth()->id(), 'created_at' => now(), 'updated_at' => now()]);
        $this->projectUserId = '';
        $this->dispatch('saved');

        // dd($newProjectMember);
    }

    public function goBack($id)
    {

        // Navigate to the project/{id} route
        return redirect()->route('project/{id}', ['id' => $id]);
    }
}
