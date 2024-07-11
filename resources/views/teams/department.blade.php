<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Department Settings') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            {{-- @dump($team) --}}
            @if (Gate::check('isAdmin', $team))
                <div class="mt-10 sm:mt-0">
                    @livewire('department-manager', ['team' => $team])
                </div>
            @else
                {{ abort(404) }}
            @endif

        </div>
    </div>
</x-app-layout>
