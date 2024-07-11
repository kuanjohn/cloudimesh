<?php

namespace App\Models;

use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\OwnerRole;
use Laravel\Sanctum\HasApiTokens;

trait HasProjects
{
 
    /**
     * Determine if the user belongs to the given project.
     *
     * @param  mixed  $project
     * @return bool
     */
    public function belongsToProject($project)
    {
        if (is_null($project)) {
            return false;
        }
        // dd($this->projects);
        return $this->ownsProject($project) || $this->projects->contains(function ($p) use ($project) {
            return $p->id === $project->id;
        });
    }

        /**
     * Determine if the user owns the given project.
     *
     * @param  mixed  $project
     * @return bool
     */
    public function ownsProject($project)
    {
        if (is_null($project)) {
            return false;
        }

        return $this->id == $project->owner;
    }

        /**
     * Determine if the user has the given role on the given projet.
     *
     * @param  mixed  $project
     * @param  string  $role
     * @return bool
     */
    public function hasProjectRole($project, string $role)
    {
        if ($this->ownsProject($project)) {
            return true;
        }

        // dd($this);
        // $role = $project->users->where('id',$this->id);
        // $role = $this->projects->find($project->id)->pivot->role;
        // dd($role);

        return $this->belongsToProject($project) && $this->projects->find($project->id)->pivot->role === $role;
    }

}
