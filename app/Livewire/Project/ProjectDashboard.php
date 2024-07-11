<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\Gate;

class ProjectDashboard extends Component
{
    public $team;
    public $projects;
    public $teamProjects;
    public $projectId;
    public $allProjects;
    public $confirmingProjectRemoval = false;
    public $projectIdBeingRemoved;

    public function mount()
    {
        $this->team = Jetstream::newTeamModel()->findOrFail(Auth()->User()->currentTeam->id);
        if (Gate::denies('view', $this->team)) {
            abort(403);
        }

        $this->projects = $this->team->projects->where('owner', Auth()->id());
        $this->teamProjects = Auth()
            ->User()
            ->projects->where('team_id', $this->team->id);
        $this->allProjects = $this->team->projects->whereNotIn('owner', Auth()->id());
        // dd($this->teamProjects);
    }

    public function render()
    {
        return view('project.project-dashboard', [
            'user' => Auth()->User(),
            'projects' => $this->projects,
        ]);
    }

    public function daysLeft($date)
    {
        if ($date === '') {
            // dd('here');
            return null;
        }
        $deadline = strtotime($date);
        $currentDate = time();
        $difference = $deadline - $currentDate;
        $daysLeft = round($difference / (60 * 60 * 24));

        return $daysLeft;
    }

    public function goToProject($id, $url)
    {
        // Navigate to the project/{id} route
        return redirect()->route('project/{id}' . $url, ['id' => $id]);
    }

    public function createProject()
    {
        // Navigate to the project/{id} route
        return redirect()->route('project/create');
    }

    /**
     * Confirm that the given project should be removed.
     *
     * @param  int  $projectId
     * @return void
     */
    public function confirmProjectRemoval($projectId)
    {
        $this->confirmingProjectRemoval = true;

        $this->projectIdBeingRemoved = $projectId;
    }

    /**
     * Remove a project from the team.
     *
     */
    public function removeProject()
    {
        Project::where('id', $this->projectIdBeingRemoved)->delete();

        $this->confirmingProjectRemoval = false;

        $this->projectIdBeingRemoved = null;

        return redirect()->route('project');

        // $this->team = $this->team->fresh();
    }
}
