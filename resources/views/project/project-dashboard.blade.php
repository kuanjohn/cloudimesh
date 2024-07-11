<div>
    <div
        class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
        {{-- <x-application-logo class="block h-12 w-auto" /> --}}

        <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
            Hi {{ $user->name }}, Welcome back to Cloudimesh!
        </h1>

        <h2 class="mt-8 text-xl font-medium text-gray-900 dark:text-white">

            @if (count($projects) === 0)
                You do not have any project created! Let's begin with new project!
            @else
                You currently own <span class="text-2xl text-indigo-700"> {{ count($projects) }}
                </span>{{ Str::plural(' project', count($projects)) }}.
            @endif
        </h2>

        <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
            <button class="cursor-pointer text-sm text-blue-500" wire:click="createProject()">
                Create new project
            </button>
            @foreach ($projects as $project)
                <div>
                    <button type="button"
                        class="bg-gray-100 relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                        {{-- class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}" --}} wire:click="$set('projectId', {{ $project->id }})">
                        <div class="{{ isset($projectId) && $projectId !== $project->id ? 'opacity-50' : '' }}">
                            <!-- Environment Name -->
                            <div class="flex items-center">
                                <div class="text-xl text-gray-600 dark:text-gray-400">
                                    {{ $project->name }}
                                </div>

                                @if ($projectId == $project->id)
                                    <svg class="ms-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Budget - ${{ number_format($project->budget, 2, '.', ',') }}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    Cost - ${{ number_format($project->cost, 2, '.', ',') }}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    @if (is_null($this->daysLeft($project->timeline)))
                                        Timeline not set.
                                    @else
                                        Timeline - {{ $project->timeline }}. You have
                                        {{ $this->daysLeft($project->timeline) }} Days Left.
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-start">
                                Project owned by {{ $project->project_owner->name }} (
                                {{ $project->project_owner->email }})
                            </div>


                        </div>

                    </button>
                </div>
                <div class="py-2">
                    @if (isset($projectId) && $projectId == $project->id)
                        <x-button wire:click="goToProject({{ $project->id }}, '')" wire:loading.attr="disabled">
                            Project Setting
                        </x-button>

                        <x-button wire:click="goToProject({{ $project->id }}, '/manage')"
                            wire:loading.attr="disabled">
                            Manage
                        </x-button>

                        <x-danger-button class="ms-3" wire:click="confirmProjectRemoval('{{ $project->id }}')"
                            wire:loading.attr="disabled">
                            {{ __('Remove') }}
                        </x-danger-button>
                    @endif
                </div>
                {{-- @dump($project->timeline) --}}
            @endforeach
        </p>
    </div>

    @if (count($teamProjects) === 0)
    @else
        <x-section-border />


        <div
            class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
            {{-- <x-application-logo class="block h-12 w-auto" /> --}}
            <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                Team's Project
            </h1>
            <h2 class="mt-8 text-xl font-medium text-gray-900 dark:text-white">

                You have access to <span class="text-2xl text-indigo-700"> {{ count($teamProjects) }}
                </span> of your team's projects.
            </h2>

            <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">

                @foreach ($teamProjects as $project)
                    <div>
                        <button type="button"
                            class="bg-gray-100 relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                            {{-- class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}" --}} wire:click="$set('projectId', {{ $project->id }})">
                            <div class="{{ isset($projectId) && $projectId !== $project->id ? 'opacity-50' : '' }}">
                                <!-- Environment Name -->
                                <div class="flex items-center">
                                    <div class="text-xl text-gray-600 dark:text-gray-400">
                                        {{ $project->name }}
                                    </div>

                                    @if ($projectId == $project->id)
                                        <svg class="ms-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Budget - ${{ number_format($project->budget, 2, '.', ',') }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Cost - ${{ number_format($project->cost, 2, '.', ',') }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        @if (is_null($this->daysLeft($project->timeline)))
                                            Timeline not set.
                                        @else
                                            Timeline - {{ $project->timeline }}. You have
                                            {{ $this->daysLeft($project->timeline) }} Days Left.
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-start">
                                    Project owned by {{ $project->project_owner->name }} (
                                    {{ $project->project_owner->email }})
                                </div>


                            </div>

                        </button>
                    </div>
                    <div class="py-2">
                        @if (isset($projectId) && $projectId == $project->id)
                            <x-button wire:click="goToProject({{ $project->id }})" wire:loading.attr="disabled">
                                Project Setting
                            </x-button>

                            <x-button>
                                Manage
                            </x-button>

                            <x-danger-button class="ms-3" wire:click="confirmProjectRemoval('{{ $project->id }}')"
                                wire:loading.attr="disabled">
                                {{ __('Remove') }}
                            </x-danger-button>
                        @endif
                    </div>
                    {{-- @dump($project->timeline) --}}
                @endforeach
            </p>
        </div>
    @endif

    @if (Auth()->User()->ownsTeam($user->currentTeam))
        <x-section-border />
        <div
            class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

            <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                Team's Project
            </h1>
            <h2 class="mt-8 text-xl font-medium text-gray-900 dark:text-white">

                You are the owner of this team. You have access to all projects. There are

                @if (count($projects) === 0)
                    no project created in your team so far.
                @else
                    <span class="text-2xl text-indigo-700"> {{ count($allProjects) + count($projects) }}
                    </span> {{ Str::plural(' project', count($allProjects)) }} in your team, including the one you have
                    created, if any.
                @endif

            </h2>

            <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">

                @foreach ($allProjects as $project)
                    <div>
                        <button type="button"
                            class="bg-gray-100 relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                            {{-- class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}" --}} wire:click="$set('projectId', {{ $project->id }})">
                            <div class="{{ isset($projectId) && $projectId !== $project->id ? 'opacity-50' : '' }}">
                                <!-- Environment Name -->
                                <div class="flex items-center">
                                    <div class="text-xl text-gray-600 dark:text-gray-400">
                                        {{ $project->name }}
                                    </div>

                                    @if ($projectId == $project->id)
                                        <svg class="ms-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Budget - ${{ number_format($project->budget, 2, '.', ',') }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Cost - ${{ number_format($project->cost, 2, '.', ',') }}
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        @if (is_null($this->daysLeft($project->timeline)))
                                            Timeline not set.
                                        @else
                                            Timeline - {{ $project->timeline }}. You have
                                            {{ $this->daysLeft($project->timeline) }} Days Left.
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-start">
                                    Project owned by {{ $project->project_owner->name }} (
                                    {{ $project->project_owner->email }})
                                </div>


                            </div>

                        </button>
                    </div>
                    <div class="py-2">
                        @if (isset($projectId) && $projectId == $project->id)
                            <x-button wire:click="goToProject({{ $project->id }})" wire:loading.attr="disabled">
                                Project Setting
                            </x-button>

                            <x-button>
                                Manage
                            </x-button>

                            <x-danger-button class="ms-3" wire:click="confirmProjectRemoval('{{ $project->id }}')"
                                wire:loading.attr="disabled">
                                {{ __('Remove') }}
                            </x-danger-button>
                        @endif
                    </div>
                    {{-- @dump($project->timeline) --}}
                @endforeach
            </p>

        </div>
    @endif

    <div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
        <div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    class="w-6 h-6 stroke-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                    <a href="#">Documentation</a>
                </h2>
            </div>

            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                xx
            </p>

            <p class="mt-4 text-sm">
                <a href="#" class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                    Explore the documentation

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        class="ms-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                        <path fill-rule="evenodd"
                            d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>

        <div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    class="w-6 h-6 stroke-gray-400">
                    <path stroke-linecap="round"
                        d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                    <a href="https://laracasts.com">Cloudimesh</a>
                </h2>
            </div>

            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                xx.
            </p>

            <p class="mt-4 text-sm">
                <a href="#" class="inline-flex items-center font-semibold text-indigo-700 dark:text-indigo-300">
                    Start watching Cloudimesh

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        class="ms-1 w-5 h-5 fill-indigo-500 dark:fill-indigo-200">
                        <path fill-rule="evenodd"
                            d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </p>
        </div>

        {{-- <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                class="w-6 h-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
            </svg>
            <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                <a href="https://tailwindcss.com/">Tailwind</a>
            </h2>
        </div>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Laravel Jetstream is built with Tailwind, an amazing utility first CSS framework that doesn't get in your
            way. You'll be amazed how easily you can build and maintain fresh, modern designs with this wonderful
            framework at your fingertips.
        </p>
    </div>

    <div>
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                class="w-6 h-6 stroke-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <h2 class="ms-3 text-xl font-semibold text-gray-900 dark:text-white">
                Authentication
            </h2>
        </div>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Authentication and registration views are included with Laravel Jetstream, as well as support for user email
            verification and resetting forgotten passwords. So, you're free to get started with what matters most:
            building your application.
        </p>
    </div> --}}
    </div>

    <!-- Remove Project Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingProjectRemoval">
        <x-slot name="title">
            {{ __('Remove Project') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove project? All the VMs and other information in the project will be removed as well.') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingProjectRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="removeProject" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
