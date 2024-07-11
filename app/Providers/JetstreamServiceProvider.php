<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        Jetstream::ignoreRoutes();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');

        Jetstream::role('approver1', 'Approver Group 1', [
            'read',
            'create',
            'update',
        ])->description('Approver Group 1 users have the ability to approve project, read, create, and update.');

        Jetstream::role('approver2', 'Approver Group 2', [
            'read',
            'create',
            'update',
        ])->description('Approver Group 2 users have the ability to approve project, read, create, and update.');

        Jetstream::role('approver3', 'Approver Group 3', [
            'read',
            'create',
            'update',
        ])->description('Approver Group 3 users have the ability approve project, to read, create, and update.');

        Jetstream::role('approver4', 'Approver Group 4', [
            'read',
            'create',
            'update',
        ])->description('Approver Group 4 users have the ability approve project, to read, create, and update.');

        Jetstream::role('approver5', 'Approver Group 5', [
            'read',
            'create',
            'update',
        ])->description('Approver Group 5 users have the ability approve project, to read, create, and update.');
        
    }
}
