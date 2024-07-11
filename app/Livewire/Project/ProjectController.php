<?php

namespace App\Livewire\Project ;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;

class ProjectController extends Controller
{
    /**
     * Show the team management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        if (Gate::denies('view', $project)) {
            abort(403);
        }

        return view('project.show', [
            'user' => $request->user(),
            'project' => $project,
        ]);
    }

        /**
     * Show the add project member management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\View\View
     */
    public function addMember(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        if (Gate::denies('view', $project)) {
            abort(403);
        }
        // dd($project);
        return view('project.add-project-member', [
            'user' => $request->user(),
            'project' => $project,
        ]);
    }

        /**
     * Show the add project member management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\View\View
     */
    public function projectManager(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        if (Gate::denies('view', $project)) {
            abort(403);
        }
        // dd($project);
        return view('project.project', [
            'user' => $request->user(),
            'project' => $project,
        ]);
    }

    /**
     * Show the create vm screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\View\View
     */
    public function createVm(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        if (Gate::denies('view', $project)) {
            abort(403);
        }
        // dd($project);
        return view('project.vm.create', [
            'user' => $request->user(),
            'project' => $project,
        ]);
    }

    /**
     * Show the team creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Jetstream::newTeamModel());

        return view('teams.create', [
            'user' => $request->user(),
        ]);
    }
}
