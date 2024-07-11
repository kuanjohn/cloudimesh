<div>

    {{-- <x-section-border /> --}}

    <!-- Add Team Member -->
    <div class="mt-10 sm:mt-0">
        <x-form-section submit="addDepartment">
            <x-slot name="title">
                {{ __('Add Team Department') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Add a new team department to your team, allowing them to collaborate with you.') }}

                <div class="py-4">
                    <button class="cursor-pointer text-sm text-blue-500" wire:click="confirmImport()">
                        You can use this {{ __('Import Wizard') }} to bulk insert departments.
                    </button>
                </div>

            </x-slot>

            <x-slot name="form">
                <div class="col-span-6">
                    <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Please provide the name of the department you would like to add to this team.') }}
                    </div>
                </div>

                <!-- Department Name -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="name" value="{{ __('Department') }}" />
                    <x-input id="name" type="text" class="mt-1 block w-full" wire:model="name" />
                    <x-input-error for="name" class="mt-2" />
                </div>
                <!-- Code -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="code" value="{{ __('Charge Code (Optional)') }}" />
                    <x-input id="code" type="text" class="mt-1 block w-full" wire:model="code" />
                    <x-input-error for="code" class="mt-2" />
                </div>
                <!-- HOD -->
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="hod" value="{{ __('Head of Department') }}" />
                    <select wire:model="hod"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="" class="">
                            Choose one... </option>
                        @foreach ($users as $user)
                            <option value={{ $user->id }} class="">
                                {{ $user->name }} | {{ $user->email }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="departId" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-action-message class="me-3" on="saved">
                    {{ __('Added.') }}
                </x-action-message>

                <x-button>
                    {{ __('Add') }}
                </x-button>
            </x-slot>
        </x-form-section>
    </div>
    @if ($team->departments->isNotEmpty())
        <x-section-border />

        <!-- Manage Team Department -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Team Departments') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('All of the department that are part of this team.') }}
                </x-slot>

                <!-- Team Department List -->
                <x-slot name="content">
                    <div class="flex items-center justify-end">
                        <x-action-message class="me-3" on="toggled">
                            <div class="mb-6">
                                {{ __('Updated.') }}
                            </div>
                        </x-action-message>
                    </div>
                    <div class="flex justify-between">
                        <div class="flex items-center justify-end mb-6">
                            <x-input wire:model.live.debounced.300ms="search" placeholder="Search Department..." />
                        </div>
                        <div>
                            <select wire:model.live="perPage" id="perPage"
                                class="text-sm bg-white border border-gray-300 rounded-md leading-tight focus:outline-none focus:border-gray-500 focus:bg-white">
                                @foreach (config('pagination.options') as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            <label class="text-sm px-2" for="perPage">Per Page</label>

                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="flex justify-between">
                            <div class="flex items-center justify-start">
                                @if (Gate::check('updateTeamMember', $team) && Laravel\Jetstream\Jetstream::hasRoles())
                                    <input type="checkbox" wire:model.live="selectedPageRowsforDepartment" />
                                @endif
                                @if (count($selectedRowsforDepartment) > 0)
                                    <div class="md:px-4 flex items-center">
                                        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                            {{ count($selectedRowsforDepartment) . ' ' . Str::plural('Department', count($selectedRowsforDepartment)) }}
                                            Selected
                                        </div>

                                    </div>
                                @endif
                            </div>

                            @if (count($selectedRowsforDepartment) > 0)
                                <div class="md:px-2 flex items-center">
                                    <x-dropdown align="right">
                                        <x-slot name="trigger">
                                            <span class="rounded-md">
                                                <div class="py-1">
                                                    <button type="button"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-200 hover:text-gray-700 focus:outline-none focus:bg-gray-200 active:bg-gray-200 transition ease-in-out duration-150">
                                                        Bulk Action
                                                        <svg class="ms-2 -me-0.5 h-4 w-4"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                        </svg>
                                                    </button>
                                                </div>

                                            </span>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-responsive-nav-link wire:click.prevent="confirmSelectedDepartmentRemoval"
                                                href="#" class="text-sm">
                                                {{ __('Remove Selected') }}
                                            </x-responsive-nav-link>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            @endif
                        </div>
                        @foreach ($departments as $department)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center justify-start">
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model.live="selectedRowsforDepartment"
                                            value="{{ $department->id }}" id="{{ $department->id }}" />
                                    </div>
                                    <div class="flex items-center">
                                        <div class="ms-4 dark:text-white">{{ $department->name }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <!-- Manage Team Member Role -->
                                    <label class="flex items-center cursor-pointer justify-center">
                                        <input type="checkbox" value="" class="sr-only peer" disabled
                                            wire:click="toggleActive({{ $department->id }})"
                                            {{ $department->published ? 'checked' : '' }}>
                                        <div
                                            class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                    </label>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500"
                                        wire:click="manageDepartment({{ $department->id }})">
                                        {{ __('Edit') }}
                                    </button>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500"
                                        wire:click="confirmDepartmentRemoval({{ $department->id }})">
                                        {{ __('Remove') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $departments->links(data: ['scrollTo' => false]) }}
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif

    <livewire:alert-banner />

    <!-- Department Management Modal (edit)-->
    <x-confirmation-modal wire:model.live="confirmingDepartmentRemoval">
        <x-slot name="title">
            {{ __('Remove Department') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this department from the team?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingDepartmentRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="removeDepartment()" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Department Management Modal (edit)-->
    <x-dialog-modal wire:model.live="managingDepartment">
        <x-slot name="title">
            {{ __('Manage Department') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">

                    <div class="grid grid-cols-6 gap-6">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="addDepartmentForm.name" value="{{ __('Name') }}" />
                            <x-input id="addDepartmentForm.name" type="text" class="mt-1 block w-full"
                                wire:model="addDepartmentForm.name" />
                            <x-input-error for="addDepartmentForm.name" class="mt-2" />
                        </div>
                        <!-- Department Charge Code -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="addDepartmentForm.code" value="{{ __('Charge Code (Optional)') }}" />
                            <x-input id="addDepartmentForm.code" type="text" class="mt-1 block w-full"
                                wire:model="addDepartmentForm.code" />
                            <x-input-error for="addDepartmentForm.code" class="mt-2" />
                        </div>
                        <!-- HOD -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="hod" value="{{ __('Head of Department') }}" />
                            <select wire:model="addDepartmentForm.hod"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option selected value="0"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    Choose one...</option>
                                @foreach ($Users as $user)
                                    <option value={{ $user->id }}
                                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                            <!-- Published -->
                            <x-input-error for="hod" class="mt-2" />
                        </div>
                        <div class="flex col-span-6 sm:col-span-6">
                            <x-label for="addDepartmentForm.published" value="{{ __('Published') }}" />
                            <label class="flex px-4 items-center mb-5 cursor-pointer justify-center">
                                <input id="addDepartmentForm.published" type="checkbox" value="" class="sr-only peer"
                                    wire:model="addDepartmentForm.published" {{-- wire:click="toggleActive({{ $department->id }})" --}}
                                    {{ $addDepartmentForm['published'] ? 'checked' : '' }}>
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
            <x-secondary-button wire:click="$toggle('managingDepartment')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateDepartment()" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>


    {{-- @foreach (config('pagination.options') as $option) --}}
    <!-- Department Management Modal (edit)-->
    <x-dialog-modal wire:model.live="managingImport">
        <x-slot name="title">
            {{ __('Import Department') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">

                    <div class="grid grid-cols-9 gap-6">
                        <x-label class="col-span-4 flex item-center justify-center"
                            value="{{ __('Import List') }}" />
                        <div> </div>
                        <x-label class="col-span-4 flex item-center justify-center"
                            value="{{ __('Exclusion List') }}" />
                        <select class="col-span-4" name="selectedDepartments" wire:model="selectedDepartments"
                            multiple size="15">
                            @foreach ($commonDepartments as $commonDepartment)
                                <option value="{{ $commonDepartment }}">{{ $commonDepartment }}</option>
                            @endforeach
                        </select>

                        <div class="ml-3 flex flex-col col-span-1 item-center justify-center">
                            <button wire:click="removeSelected">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-6 h-6">
                                    <path fill-rule="evenodd"
                                        d="M13.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L11.69 12 4.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M19.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 1 1-1.06-1.06L17.69 12l-6.97-6.97a.75.75 0 0 1 1.06-1.06l7.5 7.5Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <button wire:click="addSelected">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-6 h-6">
                                    <path fill-rule="evenodd"
                                        d="M10.72 11.47a.75.75 0 0 0 0 1.06l7.5 7.5a.75.75 0 1 0 1.06-1.06L12.31 12l6.97-6.97a.75.75 0 0 0-1.06-1.06l-7.5 7.5Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M4.72 11.47a.75.75 0 0 0 0 1.06l7.5 7.5a.75.75 0 1 0 1.06-1.06L6.31 12l6.97-6.97a.75.75 0 0 0-1.06-1.06l-7.5 7.5Z"
                                        clip-rule="evenodd" />
                                </svg>

                            </button>
                        </div>



                        <select class="col-span-4" name="availableDepartments" wire:model="availableDepartments"
                            multiple size="15">
                            @foreach ($excludedDepartments as $excludedDepartment)
                                <option value="{{ $excludedDepartment }}">{{ $excludedDepartment }}</option>
                            @endforeach
                        </select>



                        <x-label class="col-span-9 flex item-center justify-center"
                            value="{{ __('If department name has already existed, import will skip that department.') }}" />

                    </div>


                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('managingImport')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="importDepartment()" wire:loading.attr="disabled">
                {{ __('Import') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

        <!-- Remove Team Department Confirmation Modal (Bulk)-->
        <x-confirmation-modal wire:model.live="confirmingSelectedDepartmentRemoval">
            <x-slot name="title">
                {{ __('Remove Selected Department') }}
            </x-slot>
    
            <x-slot name="content">
                {{ __('You have selected ' . count($selectedRowsforDepartment) . ' ' . Str::plural('department', count($selectedRowsforDepartment)) . '. Are you sure you would like to remove all the selected ' . Str::plural('department', count($selectedRowsforDepartment)) . '?') }}
            </x-slot>
    
            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingSelectedDepartmentRemoval')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-secondary-button>
    
                <x-danger-button class="ms-3" wire:click="deleteSelectedRowsforDepartment" wire:loading.attr="disabled">
                    {{ __('Remove') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>

</div>
