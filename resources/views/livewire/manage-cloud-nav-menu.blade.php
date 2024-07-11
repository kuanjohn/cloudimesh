<div class="ms-3 relative">

    <x-dropdown align="right" width="60">
        <x-slot name="trigger">
            <span class="inline-flex rounded-md">
                <button type="button"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150
                    ">
                    Manage
                    {{ request()->routeIs('manage') ? 'Cloud' : '' }}
                    {{ request()->routeIs('location') ? 'Location' : '' }}
                    {{ request()->routeIs('environment') ? 'Environment' : '' }}
                    {{ request()->routeIs('tier') ? 'Tier' : '' }}
                    {{ request()->routeIs('infrapolicy') ? 'Infrastructure Policy' : '' }}
                    {{ request()->routeIs('vmconfig') ? 'VM Policy' : '' }}
                    {{ request()->routeIs('storage') ? 'Storage Policy' : '' }}
                    {{ request()->routeIs('os') ? 'Operating System' : '' }}
                    {{ request()->routeIs('appservice') ? 'Application Service' : '' }}

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
                    {{ __('Infrastructure') }}
                </div>

                <!-- Infrastructure Settings -->
                <x-dropdown-link href="{{ route('location', ['id' => Auth::user()->currentTeam->id]) }}" wire:navigate :active="request()->routeIs('location')">
                    {{ __('Location') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('environment', ['id' => Auth::user()->currentTeam->id]) }}"
                    wire:navigate :active="request()->routeIs('environment')">
                    {{ __('Environment') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('tier', ['id' => Auth::user()->currentTeam->id]) }}" wire:navigate :active="request()->routeIs('tier')">
                    {{ __('Tier') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('infrapolicy', ['id' => Auth::user()->currentTeam->id]) }}"
                    wire:navigate :active="request()->routeIs('infrapolicy')">
                    {{ __('Policy') }}
                </x-dropdown-link>

                <!-- VM Policy Settings -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('VM Policy') }}
                </div>

                <!-- VM Settings -->
                <x-dropdown-link href="{{ route('vmconfig', ['id' => Auth::user()->currentTeam->id]) }}" wire:navigate :active="request()->routeIs('vmconfig')">
                    {{ __('vCPU & vMemory') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('storage', ['id' => Auth::user()->currentTeam->id]) }}" wire:navigate :active="request()->routeIs('storage')">
                    {{ __('Storage') }}
                </x-dropdown-link>

                {{-- <x-dropdown-link href="{{ route('manage', ['id' => 1]) }}" wire:navigate>
                    {{ __('Network Policy') }}
                </x-dropdown-link> --}}

                <!-- Software Policy Settings -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Software Policy') }}
                </div>

                <!-- Software Settings -->
                <x-dropdown-link href="{{ route('os', ['id' => Auth::user()->currentTeam->id]) }}" wire:navigate :active="request()->routeIs('os')">
                    {{ __('Operating System') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('appservice', ['id' => Auth::user()->currentTeam->id]) }}" wire:navigate :active="request()->routeIs('appservice')">
                    {{ __('Application Service') }}
                </x-dropdown-link>

                {{-- <!-- Cost Profie Settings -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Cost Profile') }}
                </div>

                <!-- Cost Settings -->
                <x-dropdown-link href="{{ route('manage', ['id' => 1]) }}" wire:navigate>
                    {{ __('VM Cost Policy') }}
                </x-dropdown-link>

                <x-dropdown-link href="{{ route('manage', ['id' => 1]) }}" wire:navigate>
                    {{ __('Cost Management') }}
                </x-dropdown-link> --}}
            </div>
        </x-slot>
    </x-dropdown>
</div>
