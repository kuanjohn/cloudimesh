<?php

namespace App\Livewire\Project;

use Livewire\Component;
use Laravel\Jetstream\Jetstream;
use Livewire\WithPagination;

class ProjectMemberManager extends Component
{
    use WithPagination;

    public $search;
    public $perPageforProjectUser = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $project;
    public $team;
    // public $project_users;
    // public $projectUserId;
    // public $projectRoleId;
    public $searchProjectUser;
    public $selectedRowsforUser = [];
    public $selectedPageRowsforUser = false;
    public $confirmingSelectedUserRemoval = false;


    /**
     * Indicates if a user's role is currently being managed.
     *
     * @var bool
     */
    public $currentlyManagingRole = false;

    /**
     * The user that is having their role managed.
     *
     * @var mixed
     */
    public $managingRoleFor;

    /**
     * The current role for the user that is having their role managed.
     *
     * @var string
     */
    public $currentRole;

        /**
     * Indicates if the application is confirming if a project member should be removed.
     *
     * @var bool
     */
    public $confirmingProjectMemberRemoval = false;

    /**
     * The ID of the project member being removed.
     *
     * @var int|null
     */
    public $projectMemberIdBeingRemoved = null;

        /**
     * Indicates if the application is confirming if a user wishes to leave the current team.
     *
     * @var bool
     */
    public $confirmingLeavingProject = false;


    public function mount($project)
    {
        $this->project = $project;
        $this->team = Jetstream::newTeamModel()->findOrFail(Auth()->User()->currentTeam->id);
        // dd($this->project);
        

        // dd($this->team_users);
    }

    // public function getteamUsersProperty()
    // {
    //     return $this->team->users()
    //     ->whereNotIn('user_id', function ($query) {
    //         $query
    //             ->select('user_id')
    //             ->from('project_users')
    //             ->where('project_id', $this->project->id);
    //     })
    //     ->where(function ($query) {
    //         $query->where('name', 'like', '%' . strtolower($this->search) . '%')
    //             ->orWhere('email', 'like', '%' . strtolower($this->search) . '%');
    //     })
    //     ->orderBy('name', 'asc')
    //     ->paginate($this->perPageforTeamUser);
    // }

    public function getprojectUsersProperty()
    {        
        return $this->project->users()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . strtolower($this->searchProjectUser) . '%')
                      ->orWhere('email', 'like', '%' . strtolower($this->searchProjectUser) . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPageforProjectUser);
    }

    public function render()
    {
        // $team_users = $this->teamUsers;
        $project_users = $this->projectUsers;
        // dd($project_users);
        return view('project.project-member-manager', ['project' => $this->project, 'project_users' => $project_users]);
    }
    
        /**
     * Allow the given user's role to be managed.
     *
     * @param  int  $userId
     * @return void
     */
    public function manageRole($userId)
    {
        $this->currentlyManagingRole = true;
        $this->managingRoleFor = Jetstream::findUserByIdOrFail($userId);
        $this->currentRole = $this->managingRoleFor->projects->find($this->project->id)->pivot->role;
    }

        /**
     * Stop managing the role of a given user.
     *
     * @return void
     */
    public function stopManagingRole()
    {
        $this->currentlyManagingRole = false;
    }

        /**
     * Save the role for the user being managed.
     *
     * @param  \Laravel\Jetstream\Actions\UpdateProjectMemberRole  $updater
     * @return void
     */
    public function updateRole()
    {
        $this->project->users()->updateExistingPivot($this->managingRoleFor->id, [
            'role' => $this->currentRole,
        ]);

        $this->stopManagingRole();
        $data = ['style' => 'success', 'message' => 'User "' . $this->managingRoleFor->name . '" updated successfully.'];
            $this->dispatch('showBanner', $data);
    }

        /**
     * Confirm that the given project member should be removed.
     *
     * @param  int  $userId
     * @return void
     */
    public function confirmProjectMemberRemoval($userId)
    {
        $this->confirmingProjectMemberRemoval = true;

        $this->projectMemberIdBeingRemoved = $userId;
    }

        /**
     * Remove a project member from the project.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesProjectMembers  $remover
     * @return void
     */
    public function removeProjectMember()
    {
        // $remover->remove($this->user, $this->project, $user = Jetstream::findUserByIdOrFail($this->projectMemberIdBeingRemoved));
        $this->project->users()->detach($this->projectMemberIdBeingRemoved);

        // 
        // dd($this->project->users->where('id',$this->projectMemberIdBeingRemoved));
        // ::whereIn('id', $this->selectedRows)->delete();
        $this->confirmingProjectMemberRemoval = false;

        $this->projectMemberIdBeingRemoved = null;

        $this->project = $this->project->fresh();

        $data = ['style' => 'success', 'message' => 'User removed successfully.'];
        $this->dispatch('showBanner', $data);
    }

        /**
     * Remove the currently authenticated user from the project.
     *
     */
    public function leaveProject()
    {

        $this->project->users()->detach(Auth()->id());

        $this->confirmingLeavingProject = false;

        $this->project = $this->project->fresh();

        return redirect(config('fortify.home'));
    }

    public function updatedSelectedPageRowsforUser($value)
    {
        if ($value) {
            $this->selectedRowsforUser = $this->project_users->pluck('id')->map(function ($id) {
                return (string) $id;
            });
            // dd($value);
        } else {
            $this->reset(['selectedPageRowsforUser', 'selectedRowsforUser']);
        }
    }

    public function updatedSelectedRowsforUser()
    {
        // dd(count($this->selectedPageRowsforUser));
        $row = $this->project_users->pluck('id')->map(function ($id) {
            return (string) $id;
        });
        if (count($this->selectedRowsforUser) === count($row)) {
            $this->selectedPageRowsforUser = true;
        } else {
            $this->reset(['selectedPageRowsforUser']);
        }
    }

    public function confirmSelectedUserRemoval()
    {
        // $this->previousPage();
        $this->confirmingSelectedUserRemoval = true;
        // dd($this->selectedRows );
    }

    public function deleteSelectedRowsforUser()
    {
        // dd(Tier::whereIn('id', $this->selectedRows)->get());

        // dd($this->selectedRowsforUser);

        foreach ($this->selectedRowsforUser as $selectedRowforUser) {
            $this->projectMemberIdBeingRemoved = $selectedRowforUser;
            // dd($this->teamMemberIdBeingRemoved);
            // $this->removeTeamMember();
            // $remover->remove($this->user, $this->team, $user = Jetstream::findUserByIdOrFail($this->teamMemberIdBeingRemoved));
            $this->project->users()->detach($this->projectMemberIdBeingRemoved);

            $this->confirmingProjectMemberRemoval = false;

            $this->projectMemberIdBeingRemoved = null;

            $this->team = $this->team->fresh();
        }

        $this->confirmingSelectedUserRemoval = false;

        $this->reset(['selectedPageRowsforUser', 'selectedRowsforUser']);

        // $data = ['style' => 'success', 'message' => 'Selected Location deleted successfully.'];
        // $this->dispatch('showBanner', $data);

        $this->resetPage();
    }
}
