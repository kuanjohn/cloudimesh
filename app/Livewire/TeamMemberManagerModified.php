<?php

namespace App\Livewire;

use App\Actions\Jetstream\UpdateTeamMemberRole;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Mail\TeamInvitation;
use Laravel\Jetstream\Role;
use Livewire\Component;
use Livewire\WithPagination;

class TeamMemberManagerModified extends Component
{
    use WithPagination;
    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;
    public $departments;
    public $searchTeamInvitation = '';
    public $searchUser = '';
    public $perPageforInvite = 5;
    public $perPageforUser = 5;
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedPageRowsforUser = false;
    public $selectedRowsforUser = [];
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
     * Indicates if the application is confirming if a user wishes to leave the current team.
     *
     * @var bool
     */
    public $confirmingLeavingTeam = false;

    /**
     * Indicates if the application is confirming if a team member should be removed.
     *
     * @var bool
     */
    public $confirmingTeamMemberRemoval = false;

    /**
     * The ID of the team member being removed.
     *
     * @var int|null
     */
    public $teamMemberIdBeingRemoved = null;

    /**
     * The "add team member" form state.
     *
     * @var array
     */
    public $addTeamMemberForm = [
        'email' => '',
        'role' => null,
        'department_id' => 0,
        'name' => '',
        'created_at' => '',
        'updated_at' => '',
    ];

    /**
     * Mount the component.
     *
     * @param  mixed  $team
     * @return void
     */
    public function mount($team)
    {
        $this->team = $team;
        try {
            $this->departments = $team->departments()->where('published', 1)->get();
        } catch (ModelNotFoundException $exception) {
            // If no departments are found or the departments relationship is empty, maybe you want to do something here.
        }
    }

    /**
     * Add a new team member to a team.
     *
     * @return void
     */
    public function addTeamMember()
    {
        $this->resetErrorBag();
        // dd($this->addTeamMemberForm['department_id']);
        if (Features::sendsTeamInvitations()) {
            app(InvitesTeamMembers::class)->invite($this->user, $this->team, $this->addTeamMemberForm['email'], $this->addTeamMemberForm['role'], $this->addTeamMemberForm['department_id']);
        } else {
            app(AddsTeamMembers::class)->add($this->user, $this->team, $this->addTeamMemberForm['email'], $this->addTeamMemberForm['role'], $this->addTeamMemberForm['department_id']);
        }

        $this->addTeamMemberForm = [
            'email' => '',
            'role' => null,
            'department_id' => null,
            'name' => '',
            'created_at' => '',
            'updated_at' => '',
        ];

        $this->team = $this->team->fresh();

        $this->dispatch('saved');
    }

    public function resendTeamInvitation($invitationId)
    {
        if (!empty($invitationId)) {
            $invitation = $this->team->teamInvitations()->find($invitationId);
            Mail::to($invitation->email)->send(new TeamInvitation($invitation));

            $this->dispatch('sent');
            $data = ['style' => 'success', 'message' => 'Successfully sent email to ' . $invitation->email . '.'];
            $this->dispatch('showBanner', $data);
        }
    }

    /**
     * Cancel a pending team member invitation.
     *
     * @param  int  $invitationId
     * @return void
     */
    public function cancelTeamInvitation($invitationId)
    {
        if (!empty($invitationId)) {
            $model = Jetstream::teamInvitationModel();
            $model::whereKey($invitationId)->delete();
        }

        $this->team = $this->team->fresh();
        $data = ['style' => 'success', 'message' => 'Invitation has been cancelled.'];
        $this->dispatch('showBanner', $data);
    }

    /**
     * Allow the given user's role to be managed.
     *
     * @param  int  $userId
     * @return void
     */
    public function manageRole($manageUser)
    {
        // Overwrite default input ($userId)
        $manageUserData = json_decode($manageUser); //update from string to Json object
        $userId = $manageUserData->id; // Overwrite default input ($userId)
        $this->currentlyManagingRole = true;
        $this->managingRoleFor = Jetstream::findUserByIdOrFail($userId);
        $this->currentRole = $this->managingRoleFor->teamRole($this->team)->key;
        // dd($this->currentRole);
        if($this->currentRole === 'owner'){
            $this->currentRole = 'admin';
        }
        $this->addTeamMemberForm['name'] = $this->managingRoleFor->name;
        $this->addTeamMemberForm['email'] = $this->managingRoleFor->email;

        $validDepartment = $this->team->departments()->find($manageUserData->membership->department_id);
        // dd($validDepartment);
        if($validDepartment){
            $this->addTeamMemberForm['department_id'] = $manageUserData->membership->department_id;
        } else {
            $this->addTeamMemberForm['department_id'] = null;
        }
    }

    /**
     * Save the role for the user being managed.
     *
     * @param  \Laravel\Jetstream\Actions\UpdateTeamMemberRole  $updater
     * @return void
     */
    public function updateRole(UpdateTeamMemberRole $updater)
    {
        // dd($this->addTeamMemberForm['department_id']);
        if($this->managingRoleFor->teamRole($this->team)->key === 'owner'){
            $this->currentRole = 'admin';
        }
        $updater->update($this->user, $this->team, $this->managingRoleFor->id, $this->currentRole, $this->addTeamMemberForm['department_id']);

        // Retrieve the user by their ID
        $managingUser = Jetstream::findUserByIdOrFail($this->managingRoleFor->id);
        // Update the user's name attribute
        $managingUser->name = $this->addTeamMemberForm['name'];

        // Save the changes to the database
        $managingUser->save();

        $this->team = $this->team->fresh();

        $this->stopManagingRole();
        $data = ['style' => 'success', 'message' => 'User "' . $managingUser->name . '" updated successfully.'];
            $this->dispatch('showBanner', $data);
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
     * Remove the currently authenticated user from the team.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesTeamMembers  $remover
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leaveTeam(RemovesTeamMembers $remover)
    {
        $remover->remove($this->user, $this->team, $this->user);

        $this->confirmingLeavingTeam = false;

        $this->team = $this->team->fresh();

        return redirect(config('fortify.home'));
    }

    /**
     * Confirm that the given team member should be removed.
     *
     * @param  int  $userId
     * @return void
     */
    public function confirmTeamMemberRemoval($userId)
    {
        $this->confirmingTeamMemberRemoval = true;

        $this->teamMemberIdBeingRemoved = $userId;
    }

    /**
     * Remove a team member from the team.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesTeamMembers  $remover
     * @return void
     */
    public function removeTeamMember(RemovesTeamMembers $remover)
    {
        $remover->remove($this->user, $this->team, $user = Jetstream::findUserByIdOrFail($this->teamMemberIdBeingRemoved));

        $this->confirmingTeamMemberRemoval = false;

        $this->teamMemberIdBeingRemoved = null;

        $this->team = $this->team->fresh();
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Get the available team member roles.
     *
     * @return array
     */
    public function getRolesProperty()
    {
        return collect(Jetstream::$roles)
            ->transform(function ($role) {
                return with($role->jsonSerialize(), function ($data) {
                    return (new Role($data['key'], $data['name'], $data['permissions']))->description($data['description']);
                });
            })
            ->values()
            ->all();
    }

    public function getcurUsersProperty()
    {        
        return $this->team
            ->users()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . strtolower($this->searchUser) . '%')
                      ->orWhere('email', 'like', '%' . strtolower($this->searchUser) . '%');
            })
            // ->where('email', 'like', '%' . strtolower($this->searchUser) . '%')
            // ->orWhere('name', 'like', '%' . strtolower($this->searchUser) . '%')
            // ->orWhere('name','like', '%' . strtolower($this->searchUser) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPageforUser);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // $curInvitations = ModelsTeamInvitation::
        $curInvitations = $this->team
            ->teamInvitations()
            ->where('email', 'like', '%' . strtolower($this->searchTeamInvitation) . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPageforInvite);

        $curUsers = $this->curUsers;

        // $curUsers = $this->team->users()
        // ->where('email','like', '%' . strtolower($this->searchUser) . '%')
        // ->orderBy($this->sortField, $this->sortDirection)
        // ->paginate($this->perPageforUser);
        // dd($curInvitations);

        return view('livewire.team-member-manager-modified', ['curInvitations' => $curInvitations, 'curUsers' => $curUsers]);
    }

    public function updatedSelectedPageRowsforUser($value)
    {
        if ($value) {
            $this->selectedRowsforUser = $this->curUsers->pluck('id')->map(function ($id) {
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
        $row = $this->curUsers->pluck('id')->map(function ($id) {
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

    public function deleteSelectedRowsforUser(RemovesTeamMembers $remover)
    {
        // dd(Tier::whereIn('id', $this->selectedRows)->get());

        // dd($this->selectedRowsforUser);

        foreach ($this->selectedRowsforUser as $selectedRowforUser) {
            $this->teamMemberIdBeingRemoved = $selectedRowforUser;
            // dd($this->teamMemberIdBeingRemoved);
            // $this->removeTeamMember();
            $remover->remove($this->user, $this->team, $user = Jetstream::findUserByIdOrFail($this->teamMemberIdBeingRemoved));

            $this->confirmingTeamMemberRemoval = false;

            $this->teamMemberIdBeingRemoved = null;

            $this->team = $this->team->fresh();
        }

        $this->confirmingSelectedUserRemoval = false;

        $this->reset(['selectedPageRowsforUser', 'selectedRowsforUser']);

        // $data = ['style' => 'success', 'message' => 'Selected Location deleted successfully.'];
        // $this->dispatch('showBanner', $data);

        $this->resetPage();
    }
}
