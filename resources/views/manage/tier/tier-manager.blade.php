<div class="mt-10 sm:mt-0">
    <x-form-section submit="">
        <x-slot name="title">
            {{ __('Tier') }}
        </x-slot>

        <x-slot name="description">
            {{ __('A Tier represents a logical segment within an environment, providing administrators with the ability to customize distinct segments for VM networks. Analogous to network VLANs or virtual networks, Tiers define the network port group in VMware environments. Through Tiers, administrators can delineate specific network configurations and segmentations to efficiently manage and organize virtualized resources within an environment.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">

                <div class="inline-flex py-2 ">
                    <div>
                        <x-input wire:model.live.debounced.300ms="search" placeholder="Search Tier..." />
                    </div>
                    @if (count($selectedRows) > 0)
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
                                    <x-responsive-nav-link wire:click.prevent="confirmSelectedTierRemoval"
                                        href="#" class="text-sm">
                                        {{ __('Delete Selected') }}
                                    </x-responsive-nav-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif
                </div>
                <div class="bg-white sm:rounded-lg text-gray-500">
                    <x-table>
                        <x-slot name="head">
                            <x-table-head class="flex justify-start max-w-[1.25rem]">
                                <input type="checkbox" wire:model.live="selectedPageRow" />
                            </x-table-head>
                            <x-table-head sortable wire:click="sortBy('name')" :sortDirection="$sortField === 'name' ? $sortDirection : null"> Name </x-table-head>
                            <x-table-head sortable wire:click="sortBy('published')" :sortDirection="$sortField === 'published' ? $sortDirection : null">
                                Published
                            </x-table-head>
                            <x-table-head class="max-w-[1.25rem]">
                                {{-- <button disabled> --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                {{-- </button> --}}
                            </x-table-head>
                        </x-slot>
                        <x-slot name="body">
                            @forelse ($tiers as $tier)
                                <x-table-row>
                                    <x-table-cell>
                                        <input type="checkbox" wire:model.live="selectedRows"
                                            value="{{ $tier->id }}" id="{{ $tier->id }}" />
                                    </x-table-cell>
                                    <x-table-cell> {{ $tier->name }}
                                    </x-table-cell>
                                    <x-table-cell>
                                        <div class="flex item-center justify-center">
                                            <label class="cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer"
                                                    {{-- wire:click="toggleActive({{ $tier->id }})" --}}
                                                    {{ $tier->published ? 'checked' : 'unchecked' }} disabled />

                                                <div
                                                    class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>
                                    </x-table-cell>
                                    <x-table-cell>
                                        <div class="flex item-center justify-center">
                                            <button class="inline-flex">
                                                <x-icon.edit :wireClick="'confirmManageTier(' . $tier->id . ')'" />
                                            </button>
                                            <button class="inline-flex ml-2">
                                                <x-icon.delete :wireClick="'confirmTierRemoval(' . $tier->id . ')'" />
                                            </button>
                                        </div>
                                    </x-table-cell>
                                </x-table-row>
                            @empty
                                <x-table-row>
                                    <td colspan="4">
                                        <div class="flex justify-center items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-300 ">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>

                                            <span class="py-4 px-4 font-medium text-gray-500 text-sm"> No record
                                                found!
                                            </span>
                                        </div>
                                    </td>
                                </x-table-row>
                            @endforelse
                        </x-slot>
                    </x-table>
                </div>
                <div class="py-2 bg-white sm:rounded-lg text-gray-500 inline-flex items-center">
                    <select wire:model.live="perPage" id="perPage"
                        class="text-sm bg-white border border-gray-300 rounded-md leading-tight focus:outline-none focus:border-gray-500 focus:bg-white">
                        @foreach (config('pagination.options') as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    <x-label class="ml-2">Per Page</x-label>
                </div>
                <div class="py-1 bg-white sm:rounded-lg text-gray-500">
                    {{ $tiers->onEachSide(1)->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Added.') }}
            </x-action-message>

            <x-button wire:click="confirmTierAddition()">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z"
                        clip-rule="evenodd" />
                </svg>


                {{ __('Add Tier') }}
            </x-button>
        </x-slot>
    </x-form-section>

    <livewire:alert-banner />


    <!-- Tier Management Modal (Add)-->
    <x-dialog-modal wire:model.live="addingTier">
        <x-slot name="title">
            {{ __('Add Tier') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="addTierForm.name" value="{{ __('Name') }}" />
                            <x-input id="addTierForm.name" type="text" class="mt-1 block w-full"
                                wire:model="addTierForm.name" />
                            <x-input-error for="addTierForm.name" class="mt-2" />
                        </div>
                        <div class="flex col-span-6 sm:col-span-6  justify-right">
                            <x-label for="addTierForm.published" value="{{ __('Published') }}" />
                            <label class="flex px-4 items-center mb-5 cursor-pointer justify-center">
                                <input id="addTierForm.published" type="checkbox" value=""
                                    class="sr-only peer" wire:model="addTierForm.published"
                                    {{ $addTierForm['published'] ? 'checked' : 'unchecked' }}>
                                <div
                                    class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            {{-- <x-secondary-button wire:click="cancelAddingTier()" wire:loading.attr="disabled"> --}}
            <x-secondary-button wire:click="$toggle('addingTier')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="addTier()" wire:loading.attr="disabled">
                {{ __('Add') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Tier Management Modal (Remove)-->
    <x-confirmation-modal wire:model.live="confirmingTierRemoval">
        <x-slot name="title">
            {{ __('Remove Tier') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this tier?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingTierRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteTier()" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Tier Management Modal (edit)-->
    <x-dialog-modal wire:model.live="managingTier">
        <x-slot name="title">
            {{ __('Manage Tier') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
                    {{-- <div class="col-span-6 sm:col-span-4 text-lg bold">
                        Tier: {{ $updateTierForm['name'] }}
                    </div> --}}
                    {{-- <div class="col-span-6 sm:col-span-4 text-sm">
                        Created by: {{ $creator }}, at
                        {{ $created_at }}
                        (Asia/Kuala_Lumpur UTC+8)
                    </div>
                    <div class="col-span-6 sm:col-span-4 text-sm">
                        Last updated by: {{ $updater }}, at
                        {{ $updated_at }}
                        (Asia/Kuala_Lumpur UTC+8)
                    </div> --}}

                    {{-- <x-section-border /> --}}
                    <div class="grid grid-cols-6 gap-6">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="updateTierForm.name" value="{{ __('Name') }}" />
                            <x-input id="updateTierForm.name" type="text" class="mt-1 block w-full"
                                wire:model="updateTierForm.name" />
                            <x-input-error for="updateTierForm.name" class="mt-2" />
                        </div>
                        <div class="flex col-span-6 sm:col-span-6">
                            <x-label for="updateTierForm.published" value="{{ __('Published') }}" />
                            <label class="flex px-4 items-center mb-5 cursor-pointer justify-center">
                                <input id="updateTierForm.published" type="checkbox" class="sr-only peer"
                                    wire:model="updateTierForm.published"
                                    {{ $updateTierForm['published'] ? 'checked' : 'unchecked' }}>
                                <div
                                    class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('managingTier')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateTier()" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <x-confirmation-modal wire:model.live="confirmingSelectedTierRemoval">
        <x-slot name="title">
            {{ __('Remove Selected Tier') }}
        </x-slot>

        <x-slot name="content">
            {{ __('You have selected ' . count($selectedRows) . ' ' . Str::plural('Tier', count($selectedRows)) . '. Are you sure you would like to remove all the selected ' . Str::plural('Tier', count($selectedRows)) . '?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingSelectedTierRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteSelectedRows" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
