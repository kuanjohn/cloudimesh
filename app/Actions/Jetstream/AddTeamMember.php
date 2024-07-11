<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Events\AddingTeamMember;
use Laravel\Jetstream\Events\TeamMemberAdded;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;

class AddTeamMember implements AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     */
    public function add(User $user, Team $team, string $email, string $role = null, ?int $department_id = null): void
    {
        Gate::forUser($user)->authorize('addTeamMember', $team);
        // $this->validate($team, $email, $role, $department_id);
        $this->validate($team, $email, $role);
        // dd('here');

        $newTeamMember = Jetstream::findUserByEmailOrFail($email);

        AddingTeamMember::dispatch($team, $newTeamMember);
        //added department_id
        $team->users()->attach($newTeamMember, ['role' => $role, 'department_id' => $department_id]);

        TeamMemberAdded::dispatch($team, $newTeamMember);
    }

    /**
     * Validate the add member operation.
     */
    protected function validate(Team $team, string $email, ?string $role): void
    {
        Validator::make(
            [
                'email' => $email,
                'role' => $role,
                // 'department_id' => $department_id, //added department_id
            ],
            $this->rules(),
            [
                'email.exists' => __('We were unable to find a registered user with this email address.'),
                // 'department_id.not_in' => 'Please select a department.', //added department_id

            ],
        )
            // ->after($this->ensureUserIsNotAlreadyOnTeam($team, $email))
            ->validateWithBag('addTeamMember');
    }

    /**
     * Get the validation rules for adding a team member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(): array
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role' => Jetstream::hasRoles() ? ['required', 'string', new Role()] : null,
            // 'department_id' => ['required', 'not_in:0'],
        ]);
    }

    /**
     * Ensure that the user is not already on the team.
     */
    protected function ensureUserIsNotAlreadyOnTeam(Team $team, string $email): Closure
    {
        return function ($validator) use ($team, $email) {
            $validator->errors()->addIf($team->hasUserWithEmail($email), 'email', __('This user already belongs to the team.'));
        };
    }
}
