<div class="ms-3 relative">

    <x-dropdown align="right" width="60">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md">
                <button type="button"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150
                    ">
                    
                    {{ request()->routeIs('project') ? 'Project Dashboard' : '' }}
                    {{ request()->routeIs('project/create') ? 'Create New Project' : '' }}

                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </button>
            </span>
        </x-slot>

        <x-slot name="content">
            <div class="w-60">
                <!-- Management -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Project') }}
                </div>

                <!-- Infrastructure Settings -->
                <x-dropdown-link href="{{ route('project') }}" wire:navigate :active="request()->routeIs('project')">
                    {{ __('Project Dashboard') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('project/create') }}" wire:navigate :active="request()->routeIs('project/create')">
                    {{ __('Create New Project') }}
                </x-dropdown-link>

                
            </div>
        </x-slot>
    </x-dropdown>
</div>
