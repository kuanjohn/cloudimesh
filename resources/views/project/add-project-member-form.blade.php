<div>
    @if (Gate::check('addProjectMember', $project))
        {{-- <x-section-border /> --}}

        <!-- Add Project Member -->
        <div class="mt-10 sm:mt-0">
            <x-form-section submit="addProjectMember">
                <x-slot name="title">
                    {{ __('Add Project Member') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Add a new project member to your project, allowing them to collaborate with you.') }}
                </x-slot>

                <x-slot name="form">

                    <!-- Project Name -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="projectForm.name" value="{{ __('Project Name') }}" />
                        <x-input id="projectForm.name" type="text" class="mt-1 block w-full"
                            value="{{ $project->name }}" disabled />
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Please provide the email address of the person you would like to add to this project. User must be invited to the team first. By default, user have the ability to read, create and update the project.') }}
                        </div>
                    </div>

                    <!-- Member Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input wire:model.live.debounced.300ms="search" placeholder="Search Email..."
                            class="mt-1 block w-full" />
                        {{-- <x-input-error for="email" class="mt-2" /> --}}
                        {{-- </div>

                    <div class="col-span-6 sm:col-span-4"> --}}
                        <div class="p-2 overflow-y-auto">
                            {{-- @dump($team_users) --}}


                            @foreach ($team_users as $team_user)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('projectUserId', {{ $team_user->id }})">
                                    <div
                                        class="{{ isset($projectUserId) && $projectUserId !== $team_user->id ? 'opacity-50' : '' }}">
                                        <!-- User Name -->
                                        <div class="flex items-center">
                                            <div
                                                class="text-sm text-gray-600 dark:text-gray-400 {{ $projectUserId == $team_user->id ? 'font-semibold' : '' }}">
                                                {{ $team_user->name }}

                                            </div>

                                            @if ($projectUserId == $team_user->id)
                                                <svg class="ms-2 h-5 w-5 text-green-400"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <!-- Role Description -->
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-start">
                                            {{ $team_user->email }}
                                        </div>


                                    </div>
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $team_users->links(data: ['scrollTo' => false]) }}
                        </div>

                        {{-- <div class="col-span-6 sm:col-span-4">
                            <x-label for="role" value="{{ __('Role') }}" />
                     
                        </div> --}}

                        {{-- <div class="p-2 overflow-y-auto">
                            <!-- Roles-->
                            @foreach (config('projectroles') as $role => $desc)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('projetRoleId', {{ $role }})">
                                    <div
                                        class="{{ isset($projectUserId) && $projectUserId !== $role ? 'opacity-50' : '' }}">
                                        <!-- Environment Name -->
                                        <div class=" items-center">
                                            <div
                                                class="text-sm text-gray-600 dark:text-gray-400 {{ $projectUserId == $role ? 'font-semibold' : '' }}">
                                                {{ $desc[0] }}
                                            </div>
                                            @if ($projectUserId == $role)
                                                <svg class="ms-2 h-5 w-5 text-green-400"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-start">
                                                {{ $desc[1] }}
                                            </div>

                                            @if ($projectUserId == $role)
                                                <svg class="ms-2 h-5 w-5 text-green-400"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>

                                    </div>


                                </button>
                            @endforeach
                        </div> --}}
                    </div>

                </x-slot>

                <x-slot name="actions">
                    <div class="flex flex-row justify-end px-6 py-4 text-end">

                        <x-secondary-button wire:click="goBack({{ $project->id }})" wire:loading.attr="disabled">
                            {{ __('Back To Project Setting') }}
                        </x-secondary-button>
                    </div>
                        <x-action-message class="me-3" on="saved">
                            {{ __('Added.') }}
                        </x-action-message>

                        <x-button>
                            {{ __('Add') }}
                        </x-button>
                    
                </x-slot>
            </x-form-section>
        </div>
    @endif
</div>
