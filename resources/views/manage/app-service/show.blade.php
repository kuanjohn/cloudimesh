<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->currentTeam->name }}
        </h2>
    </x-slot>

    <div>

        <div class="max-w-7xl mx-auto py-6 sm:px-4 lg:px-6">
            @livewire('appservice-manager', ['id' => $id])
        </div>
</x-admin-layout>
