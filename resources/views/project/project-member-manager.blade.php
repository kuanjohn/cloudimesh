<div>
    @if ($project->users->isNotEmpty())

        <x-section-border />



        <!-- Manage Team Members -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Team Members') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('All of the people that are part of this team.') }}
                </x-slot>

                <!-- Team Member List -->
                <x-slot name="content">

                    <div class="flex justify-between">
                        <div class="flex items-center justify-end mb-6">
                            <x-input wire:model.live.debounced.300ms="searchProjectUser" placeholder="Search User..." />
                        </div>
                        <div>
                            <select wire:model.live="perPageforProjectUser" id="perPageforUser"
                                class="text-sm bg-white border border-gray-300 rounded-md leading-tight focus:outline-none focus:border-gray-500 focus:bg-white">
                                @foreach (config('pagination.options') as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            <label class="text-sm px-2" for="perPageforUser">Per Page</label>

                        </div>
                    </div>

                    <div class="flex justify-between">
                        <div class="flex items-center justify-start">
                            @if (Gate::check('updateProjectMember', $project))
                                <input type="checkbox" wire:model.live="selectedPageRowsforUser" />
                            @endif
                            @if (count($selectedRowsforUser) > 0)
                                <div class="md:px-4 flex items-center">
                                    <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                        {{ count($selectedRowsforUser) . ' ' . Str::plural('User', count($selectedRowsforUser)) }}
                                        Selected
                                    </div>

                                </div>
                            @endif
                        </div>

                        @if (count($selectedRowsforUser) > 0)
                            <div class="md:px-2 flex items-center">
                                <x-dropdown align="right">
                                    <x-slot name="trigger">
                                        <span class="rounded-md">
                                            <div class="py-1">
                                                <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition ease-in-out duration-150">
                                                    Bulk Action
                                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                    </svg>
                                                </button>
                                            </div>

                                        </span>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-responsive-nav-link wire:click.prevent="confirmSelectedUserRemoval"
                                            href="#" class="text-sm">
                                            {{ __('Remove Selected') }}
                                        </x-responsive-nav-link>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif
                    </div>

                    @foreach ($project_users as $projectUser)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center justify-start">
                                @if (Gate::check('updateProjectMember', $project))
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model.live="selectedRowsforUser"
                                            value="{{ $projectUser->id }}" id="{{ $projectUser->id }}" />
                                    </div>
                                @endif
                                <div class="flex items-center ml-2">
                                    <img class="w-8 h-8 rounded-full object-cover"
                                        src="{{ $projectUser->profile_photo_url }}" alt="{{ $projectUser->name }}">
                                </div>
                                <div class="sm:flex-col sm:items-start">
                                    <div class="flex items-center justify-start">

                                        <div class="ms-2 dark:text-white">{{ $projectUser->name }}</div>
                                    </div>

                                    <div class="flex inline-flex items-center justify-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="ms-4 w-4 h-4">
                                            <path
                                                d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                                            <path
                                                d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                                        </svg>
                                        <div class="ms-2 text-gray-600 dark:text-gray-400 text-sm">
                                            {{ $projectUser->email }}
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="ms-4 w-4 h-4">
                                            <path fill-rule="evenodd"
                                                d="M7.5 5.25a3 3 0 0 1 3-3h3a3 3 0 0 1 3 3v.205c.933.085 1.857.197 2.774.334 1.454.218 2.476 1.483 2.476 2.917v3.033c0 1.211-.734 2.352-1.936 2.752A24.726 24.726 0 0 1 12 15.75c-2.73 0-5.357-.442-7.814-1.259-1.202-.4-1.936-1.541-1.936-2.752V8.706c0-1.434 1.022-2.7 2.476-2.917A48.814 48.814 0 0 1 7.5 5.455V5.25Zm7.5 0v.09a49.488 49.488 0 0 0-6 0v-.09a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5Zm-3 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                                                clip-rule="evenodd" />
                                            <path
                                                d="M3 18.4v-2.796a4.3 4.3 0 0 0 .713.31A26.226 26.226 0 0 0 12 17.25c2.892 0 5.68-.468 8.287-1.335.252-.084.49-.189.713-.311V18.4c0 1.452-1.047 2.728-2.523 2.923-2.12.282-4.282.427-6.477.427a49.19 49.19 0 0 1-6.477-.427C4.047 21.128 3 19.852 3 18.4Z" />
                                        </svg>

                                        <div class="ms-2 text-gray-600 dark:text-gray-400 text-sm">
                                            {{ Auth()->User()->currentTeam->departments()->find($projectUser->teams->find(Auth()->User()->currentTeam->id)->membership->department_id)->name ?? '<<Not defined>>' }}

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="flex items-center">
                                <!-- Manage Team Member Role -->
                                @if (Gate::check('updateProjectMember', $project))
                                    <button class="ms-2 text-sm text-gray-400 underline"
                                        wire:click="manageRole('{{ $projectUser->id }}')">
                                        {{ $projectUser->projects->find($project->id)->pivot->role }}
                                        {{-- {{ Laravel\Jetstream\Jetstream::findRole($projectUser->membership->role)->name }} --}}
                                    </button>
                                @else
                                    <div class="ms-2 text-sm text-gray-400">
                                        {{ $projectUser->projects->find($project->id)->pivot->role }}
                                        {{-- {{ Laravel\Jetstream\Jetstream::findRole($projectUser->membership->role)->name }} --}}
                                    </div>
                                @endif

                                <!-- Leave Project -->
                                @if (Auth()->id() === $projectUser->id)
                                    <button class="cursor-pointer ms-6 text-sm text-red-500"
                                        wire:click="$toggle('confirmingLeavingProject')">
                                        {{ __('Leave') }}
                                    </button>

                                    <!-- Remove Project Member -->
                                @elseif (Gate::check('removeProjectMember', $project))
                                    <button class="cursor-pointer ms-6 text-sm text-red-500"
                                        wire:click="confirmProjectMemberRemoval('{{ $projectUser->id }}')">
                                        {{ __('Remove') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
        </div>

        <div class="mt-4">
            {{ $project_users->links(data: ['scrollTo' => false]) }}
        </div>
        </x-slot>
        </x-action-section>
    @endif
    <livewire:alert-banner />

    <!-- Role Management Modal -->
    <x-dialog-modal wire:model.live="currentlyManagingRole">
        <x-slot name="title">
            {{ __('Manage Role') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">

                    <div class="col-span-6 py-5 text-lg bold">
                        <x-label for="role" value="{{ __('Role') }}" />
                    </div>
                    <div class="p-2 h-48 overflow-y-auto">
                        @foreach (config('projectroles') as $role => $desc)
                            <button type="button"
                                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                wire:click="$set('currentRole', '{{ $role }}')">
                                <div class="{{ $currentRole !== $role ? 'opacity-50' : '' }}">
                                    <!-- Role Name -->
                                    <div class="flex items-center">
                                        <div
                                            class="text-sm text-gray-600 dark:text-gray-400 {{ $currentRole == $role ? 'font-semibold' : '' }}">
                                            {{ $role }}
                                        </div>

                                        @if ($currentRole == $role)
                                            <svg class="ms-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Role Description -->
                                    <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                        {{ $desc[1] }}
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>

                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="stopManagingRole" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateRole" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Remove Project Member Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingProjectMemberRemoval">
        <x-slot name="title">
            {{ __('Remove Project Member') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this person from the project?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingProjectMemberRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="removeProjectMember" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Leave Team Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingLeavingProject">
        <x-slot name="title">
            {{ __('Leave Project') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to leave this project?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingLeavingProject')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="leaveProject" wire:loading.attr="disabled">
                {{ __('Leave') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Remove Project Member Confirmation Modal (Bulk)-->
    <x-confirmation-modal wire:model.live="confirmingSelectedUserRemoval">
        <x-slot name="title">
            {{ __('Remove Selected User') }}
        </x-slot>

        <x-slot name="content">
            {{ __('You have selected ' . count($selectedRowsforUser) . ' ' . Str::plural('User', count($selectedRowsforUser)) . '. Are you sure you would like to remove all the selected ' . Str::plural('User', count($selectedRowsforUser)) . '?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingSelectedUserRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteSelectedRowsforUser" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
