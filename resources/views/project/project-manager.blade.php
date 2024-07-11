<div>
    <div
        class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
        {{-- <x-application-logo class="block h-12 w-auto" /> --}}

        <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
            {{ $project->name }}
        </h1>

        <h2 class="mt-8 text-xl font-medium text-gray-900 dark:text-white">
            Description: {{ $project->description }}
        </h2>
        
        <div class="p-4">
            <div class="col-span-6 sm:col-span-4">

                <x-project-card :project="$project" />

                <x-section-border />

                @if ($vms->isNotEmpty())
                <x-table>
                    <x-slot name="head">
                        <x-table-head class="flex justify-start max-w-[1.25rem]">
                            <input type="checkbox" wire:model.live="selectedPageRow" />
                        </x-table-head>
                        <x-table-head sortable wire:click="sortBy('name')" :sortDirection="$sortField === 'name' ? $sortDirection : null"> Name </x-table-head>
                       
                        <x-table-head class="max-w-[1.25rem]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </x-table-head>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($vms as $vm)
                            <x-table-row>
                                <x-table-cell>
                                    <input type="checkbox" wire:model.live="selectedRows"
                                        value="{{ $vm->id }}" id="{{ $vm->id }}" />
                                </x-table-cell>
                                <x-table-cell> {{ $vm->name }}
                                </x-table-cell>
                               
                                <x-table-cell>
                                    <div class="flex item-center justify-center">
                                        <button class="inline-flex">
                                            <x-icon.edit :wireClick="'confirmManageLocation(' . $vm->id . ')'" />
                                        </button>
                                        <button class="inline-flex ml-2">
                                            <x-icon.delete :wireClick="'confirmLocationRemoval(' . $vm->id . ')'" />
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
                    @foreach ($vms as $vm)

                        {{ $vm->name }}
                        
                    @endforeach
                    
                @endif
               
            </div>
        </div>
        

    </div>
</div>
