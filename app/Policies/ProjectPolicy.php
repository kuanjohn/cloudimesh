<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->ownsTeam(Auth()->User()->currentTeam) || $user->ownsProject($project);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // dd($user);
        // dd();
        return $user->ownsTeam(Auth()->User()->currentTeam) || $user->belongsToProject($project);
    }

    /**
     * Determine whether the user can add project members.
     */
    public function addProjectMember(User $user, Project $project): bool
    {
        // dd('hee');
        return $user->ownsTeam(Auth()->User()->currentTeam) || $user->hasProjectRole($project, 'Administrator');
    }

    /**
     * Determine whether the user can update project member permissions.
     */
    public function updateProjectMember(User $user, Project $project): bool
    {
        return $user->ownsTeam(Auth()->User()->currentTeam) || $user->hasProjectRole($project, 'Administrator');
    }

    /**
     * Determine whether the user can update project member permissions.
     */
    public function removeProjectMember(User $user, Project $project): bool
    {
        return $user->ownsTeam(Auth()->User()->currentTeam) || $user->hasProjectRole($project, 'Administrator');
    }
}
