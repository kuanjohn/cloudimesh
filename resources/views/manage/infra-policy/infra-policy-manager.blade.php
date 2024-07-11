<div class="mt-10 sm:mt-0">
    <x-form-section submit="">
        <x-slot name="title">
            {{ __('Infrastructure Policy') }}
        </x-slot>

        <x-slot name="description">
            {{ __('The relationship between locations, environments, and tiers in a system represents how various components are organized and deployed. Locations typically refer to physical or logical places where resources are managed, while environments denote specific deployment contexts like development or production. Tiers, on the other hand, represent different layers within the system architecture, such as frontend or backend. While these elements aren\'t directly linked to profiles or policies, they may be subject to configuration guidelines or deployment policies to ensure consistency and adherence to best practices.') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6">

                <div class="bg-white sm:rounded-lg text-gray-500">

                    <div class="flex inline-flex">
                        <div class="flex flex-col">
                            <!-- Location Name -->
                            <div
                                class="font-bold bg-gray-100 relative px-4 py-3 inline-flex w-48 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                <div class="flex items-center">
                                    Location
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-col">
                            <div class="flex inline-flex">
                                <div class="flex flex-col">
                                    <div
                                        class="font-bold bg-gray-100 relative px-4 py-3 inline-flex sm:w-48 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                        Environment


                                    </div>

                                </div>
                                <div class="flex flex-col">

                                    <div class="flex flex-col">
                                        <div
                                            class="font-bold bg-gray-100 relative px-4 py-3 inline-flex sm:w-96 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                            Tier
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    @foreach ($locations as $location)
                        <div class="flex inline-flex">
                            <div class="flex flex-col">
                                <!-- Location Name -->
                                <div
                                    class="relative px-4 py-3 inline-flex w-48 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                    <div class="flex items-center">
                                        {{ $location->name }}
                                        <div>
                                            <button class="cursor-pointer ms-6 text-sm text-gray-500"
                                                wire:click="confirmEnvironmentAddition({{ $location->id }})">
                                                <x-icon.add />
                                            </button>
                                        </div>

                                    </div>

                                </div>
                                <div class="relative px-4 py-3 text-xs">
                                    @if ($location->vmspec_id === null)
                                        VM Policy:
                                        <button
                                            class="cursor-pointer p-1 text-xs text-gray-700 border border-gray-400 rounded-lg"
                                            wire:click="confirmManageVmspec(0, {{ $location->id }})">
                                            Default VM Policy
                                        </button>
                                    @else
                                        VM Policy:
                                        <button
                                            class="cursor-pointer p-1 text-xs text-gray-700 border border-gray-400 rounded-lg"
                                            wire:click="confirmManageVmspec({{ $location->vmspec_id }}, {{ $location->id }})">
                                            @if ($location->vmspec !== null)
                                                {{ $location->vmspec->name }}
                                            @else
                                                VM Policy not found!
                                            @endif


                                        </button>
                                    @endif
                                </div>

                                <div class="relative px-4 py-3 text-xs">

                                    <div>
                                        Storage Policy:
                                    </div>
                                    <div class="flex inline-flex items-center justify-center">
                                        <div>
                                            <button class="cursor-pointer text-sm text-gray-500"
                                                wire:click="confirmingAddStoragePolicy({{ $location->id }})">
                                                <x-icon.add />
                                            </button>
                                        </div>
                                        <div>
                                            Add Policy
                                        </div>
                                    </div>

                                    <div>
                                        @if ($location->storages->isEmpty())
                                            <button
                                                class="cursor-pointer p-1 text-xs text-gray-700 border border-gray-400 rounded-lg"
                                                wire:click="confirmingAddStoragePolicy({{ $location->id }})">
                                                Default Storage Policy
                                            </button>
                                        @else
                                            @foreach ($location->storages as $storage)
                                                <div class="py-1">
                                                    <button
                                                        class="cursor-pointer p-1 text-xs text-gray-700 border border-gray-400 rounded-lg"
                                                        wire:click="confirmStoragePolicyRemoval({{ $storage->pivot->id }})">
                                                        @if ($storage->name !== null)
                                                            {{ $storage->name }}
                                                        @else
                                                            Storage Policy not found!
                                                        @endif


                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="flex flex-col">
                                @forelse ($location->environments->sortBy('name') as $environment)
                                    <div class="flex inline-flex">
                                        <div class="flex flex-col">
                                            <div
                                                class="relative px-4 py-3 inline-flex sm:w-48 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                                {{ $environment->name }}
                                                <div>
                                                    <button class="cursor-pointer ms-6 text-sm text-gray-500"
                                                        wire:click="confirmTierAddition({{ $environment->pivot->id }})">
                                                        <x-icon.add />
                                                    </button>
                                                </div>

                                            </div>
                                            <div class="flex justify-start">
                                                <button class="cursor-pointer ms-4 text-sm text-red-500 inline-flex"
                                                    wire:click="confirmEnvironmentRemoval({{ $environment->pivot->id }})">
                                                    <div class="mr-1">
                                                        <x-icon.remove />
                                                    </div>
                                                    {{ __('Remove') }}
                                                </button>
                                            </div>

                                            <div class="relative px-4 py-3 text-xs">
                                                @if ($environment->pivot->vmspec_id === null)
                                                    VM Policy:
                                                    <button
                                                        class="cursor-pointer p-1 text-xs text-gray-700 border border-gray-400 rounded-lg"
                                                        wire:click="confirmManageVmspecforEnv(0, {{ $environment->pivot->id }})">
                                                        Inherit
                                                    </button>
                                                @else
                                                    VM Policy:
                                                    <button
                                                        class="cursor-pointer p-1 text-xs text-gray-700 border border-gray-400 rounded-lg"
                                                        wire:click="confirmManageVmspecforEnv({{ $environment->pivot->vmspec_id }}, {{ $environment->pivot->id }})">
                                                        @forelse ($environment->team->locationEnvironments as $locEnv)
                                                            @if ($locEnv->vmspec_id === $environment->pivot->vmspec_id && $environment->pivot->id === $locEnv->id)
                                                                @if ($locEnv->vmspec !== null)
                                                                    {{ $locEnv->vmspec->name }}
                                                                    {{-- @dump($environment->pivot->id, $locEnv->id) --}}
                                                                @else
                                                                    VM Policy not found!
                                                                @endif
                                                            @endif
                                                        @empty
                                                            VM Policy not found!
                                                        @endforelse
                                                        {{-- {{ $environment->pivot->vmspec_id }} --}}

                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            @forelse ($environment->team->locationEnvironments as $locEnv)
                                                @if ($locEnv->id === $environment->pivot->id)
                                                    <div class="flex flex-col">
                                                        @forelse ($locEnv->tiers->sortBy('name')  as $tier)
                                                            <div
                                                                class="relative px-4 py-2 inline-flex sm:w-96 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                                                {{ $tier->name }}
                                                            </div>
                                                            <div class="flex justify-start mb-1">
                                                                <button
                                                                    class="cursor-pointer ms-4 text-sm text-red-500 inline-flex"
                                                                    wire:click="confirmTierRemoval({{ $tier->pivot->id }})">
                                                                    <div class="mr-1">
                                                                        <x-icon.remove />
                                                                    </div>
                                                                    {{ __('Remove') }}

                                                                </button>



                                                            </div>
                                                        @empty
                                                        @endforelse
                                                    </div>
                                                @endif
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex inline-flex">
                                        <div class="flex flex-col">
                                            <div
                                                class="relative px-4 py-3 inline-flex w-48 rounded-lg border-t border-gray-400 dark:border-gray-700 focus:border-none rounded-t-none">
                                            </div>
                                            <div class="flex justify-end">
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Added.') }}
            </x-action-message>

            {{-- <x-button wire:click="confirmLocationAddition()">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-2">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('Add Location') }}
            </x-button> --}}
        </x-slot>
    </x-form-section>

    <livewire:alert-banner />

    <x-confirmation-modal wire:model.live="confirmingTierRemoval">
        <x-slot name="title">
            {{ __('Remove Tier from Environment') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this Tier from Environment?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingTierRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteEnvironmentTier()" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <x-confirmation-modal wire:model.live="confirmingEnvironmentRemoval">
        <x-slot name="title">
            {{ __('Remove Environment from Location') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this Environment from Location?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingEnvironmentRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteLocationEnvironment()" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- Adding Environment Management Modal -->
    <x-dialog-modal wire:model.live="confirmingEnvironmentAddition">
        <x-slot name="title">
            {{ __('Adding Environment to Location') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    @if ($environmentsWithoutLocation == !null && count($environmentsWithoutLocation) > 0)
                        <div class="col-span-6 text-lg bold">
                            <x-label for="role" value="{{ __('Available Environment') }}" />
                            <button></button>
                        </div>
                        <div class="p-2 h-48 overflow-y-auto">

                            @foreach ($environmentsWithoutLocation as $environmentWithoutLocation)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('environmentId', {{ $environmentWithoutLocation->id }})">
                                    <div
                                        class="{{ isset($environmentId) && $environmentId !== $environmentWithoutLocation->id ? 'opacity-50' : '' }}">
                                        <!-- Environment Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $environmentWithoutLocation->name }}
                                            </div>

                                            @if ($environmentId == $environmentWithoutLocation->id)
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
                            <x-input-error for="environmentId" class="mt-2" />

                        </div>
                    @else
                        <div class="col-span-6 text-lg bold flex inline-flex">
                            <div class="px-2">
                                <x-icon.info />
                            </div>
                            <x-label for="role"
                                value="{{ __('All Environments are associated with location. To add more environment under this location, you need to create new environment first.') }}" />
                        </div>
                    @endif

                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingEnvironmentAddition')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            @if ($environmentsWithoutLocation == !null && count($environmentsWithoutLocation) > 0)
                <x-button class="ms-3" wire:click="addLocationEnvironment" wire:loading.attr="disabled">
                    {{ __('Add to Location') }}
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>

    <!-- Adding Tier Management Modal -->
    <x-dialog-modal wire:model.live="confirmingTierAddition">
        <x-slot name="title">
            {{ __('Adding Tier to Environment') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    @if ($tiersWithoutEnvironment == !null && count($tiersWithoutEnvironment) > 0)
                        <div class="col-span-6 text-lg bold">
                            <x-label for="role" value="{{ __('Available Tier') }}" />
                            <button></button>
                        </div>
                        <div class="p-2 h-48 overflow-y-auto">

                            @foreach ($tiersWithoutEnvironment as $tierWithoutEnvironment)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('tierId', {{ $tierWithoutEnvironment->id }})">
                                    <div
                                        class="{{ isset($tierId) && $tierId !== $tierWithoutEnvironment->id ? 'opacity-50' : '' }}">
                                        <!-- Environment Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $tierWithoutEnvironment->name }}
                                            </div>

                                            @if ($tierId == $tierWithoutEnvironment->id)
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
                            <x-input-error for="tierId" class="mt-2" />

                        </div>
                    @else
                        <div class="col-span-6 text-lg bold flex inline-flex">
                            <div class="px-2">
                                <x-icon.info />
                            </div>
                            <x-label for="role"
                                value="{{ __('All Tiers are associated with environment. To add more tier under this location, you need to create new tier first.') }}" />
                        </div>
                    @endif

                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingTierAddition')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            @if ($tiersWithoutEnvironment == !null && count($tiersWithoutEnvironment) > 0)
                <x-button class="ms-3" wire:click="addEnvironmentTier" wire:loading.attr="disabled">
                    {{ __('Add to Location') }}
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>

    <!-- VM Policy Display Modal -->
    <x-dialog-modal wire:model.live="false">
        <x-slot name="title">
            {{ __('Change VM Policy') }}
        </x-slot>

        <x-slot name="content">
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 bg-white sm:p-6 sm:rounded-tl-md sm:rounded-tr-md">
                    <x-label for="VMPolicyForm.name" value="{{ __('Current Policy') }}" />

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label for="VMPolicyForm.name" value="{{ __('Name') }}" />
                            <x-input id="VMPolicyForm.name" type="text" class="mt-1 block w-full"
                                wire:model="VMPolicyForm.name" disabled />
                            <x-input-error for="VMPolicyForm.name" class="mt-2" />
                        </div>

                    </div>

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label class="font-bold text-lg" for=""
                                value="{{ __('vCPU Configuration') }}" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <x-range-slider id="cpu_range" :min="0" :min_value="$VMPolicyForm['min_vcpu']" :max="$VMPolicyForm['max_vcpu']"
                                :value="$cpu_range" :step="$cpu_range_step" wire:model.live="cpu_range" />
                        </div>

                        <div class="col-span-2 sm:col-span-2 mt-2">
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vcpu" value="{{ __('Cost per vCPU (Daily)') }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cpu_range" value="{{ __('No of vCPU') }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.total" value="{{ __('Total Cost (Daily)') }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.total" value="{{ __('Total Cost (Monthly)') }}" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-2 mt-2">
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vcpu" value="${{ $VMPolicyForm['cost_vcpu'] }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vcpu" value="{{ $cpu_range }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vcpu"
                                    value="${{ intval($cpu_range) * floatval($VMPolicyForm['cost_vcpu']) }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vcpu"
                                    value="${{ number_format(($cpu_range * floatval($VMPolicyForm['cost_vcpu']) * 365) / 12, 2) }}" />
                            </div>
                        </div>


                    </div>

                    <div class="grid grid-cols-6 gap-6 shadow px-4 py-4">
                        <!-- Name -->
                        <div class="col-span-6 sm:col-span-6">
                            <x-label class="font-bold text-lg" for=""
                                value="{{ __('vMEM Configuration') }}" />
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <x-range-slider id="cpu_range" :min="0" :min_value="$VMPolicyForm['min_vmem']" :max="$VMPolicyForm['max_vmem']"
                                :value="$mem_range" :step="$mem_range_step" wire:model.live="mem_range" />
                        </div>

                        <div class="col-span-2 sm:col-span-2 mt-2">
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vmem" value="{{ __('Cost per vMem (Daily)') }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.mem_range" value="{{ __('No of vMem') }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.total" value="{{ __('Total Cost (Daily)') }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.total" value="{{ __('Total Cost (Monthly)') }}" />
                            </div>
                        </div>
                        <div class="col-span-2 sm:col-span-2 mt-2">
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vmem" value="${{ $VMPolicyForm['cost_vmem'] }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vmem" value="{{ $mem_range }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vmem"
                                    value="${{ intval($mem_range) * floatval($VMPolicyForm['cost_vmem']) }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-label for="VMPolicyForm.cost_vmem"
                                    value="${{ number_format(($mem_range * floatval($VMPolicyForm['cost_vmem']) * 365) / 12, 2) }}" />
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            {{-- <x-secondary-button wire:click="cancelAddingVmspec()" wire:loading.attr="disabled"> --}}
            <x-secondary-button wire:click="$toggle('managingVmspec')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Update VM Policy Management Modal -->
    <x-dialog-modal wire:model.live="managingVmspec">
        <x-slot name="title">
            {{ __('Update VM Policy for Location') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    @if ($vmspecs == !null && count($vmspecs) > 0)
                        <div class="col-span-6 text-lg bold">
                            <x-label for="role" value="{{ __('VM Policy') }}" />
                            <button></button>
                        </div>
                        <div class="p-2 h-48 overflow-y-auto">
                            @foreach ($vmspecs as $vmspec)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('vmspecId', {{ $vmspec->id }})">
                                    <div
                                        class="{{ isset($vmspecId) && $vmspecId !== $vmspec->id ? 'opacity-50' : '' }}">
                                        <!-- Environment Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $vmspec->name }}
                                            </div>

                                            @if ($vmspecId == $vmspec->id)
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
                            <x-input-error for="vmspecId" class="mt-2" />

                        </div>
                    @else
                        <div class="col-span-6 text-lg bold flex inline-flex">
                            <div class="px-2">
                                <x-icon.info />
                            </div>
                            <x-label for="role"
                                value="{{ __('There is no new VM Policy defined.') }}" />
                        </div>
                    @endif

                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('managingVmspec')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            @if ($vmspecId !== 0)
                <x-button class="ms-3" wire:click="detachLocationVMPolicy" wire:loading.attr="disabled">
                    {{ __('Dettach') }}
                </x-button>
                <x-button class="ms-3" wire:click="updateLocationVMPolicy" wire:loading.attr="disabled">
                    {{ __('Update') }}
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>

    <!-- Update VM Policy Management Modal -->
    <x-dialog-modal wire:model.live="managingVmspecforEnv">
        <x-slot name="title">
            {{ __('Update VM Policy for Environment') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    @if ($vmspecs == !null && count($vmspecs) > 0)
                        <div class="col-span-6 text-lg bold">
                            <x-label for="role" value="{{ __('VM Policy') }}" />
                            <button></button>
                        </div>
                        <div class="p-2 h-48 overflow-y-auto">
                            @foreach ($vmspecs as $vmspec)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('vmspecId', {{ $vmspec->id }})">
                                    <div
                                        class="{{ isset($vmspecId) && $vmspecId !== $vmspec->id ? 'opacity-50' : '' }}">
                                        <!-- Environment Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $vmspec->name }}
                                            </div>

                                            @if ($vmspecId == $vmspec->id)
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
                            <x-input-error for="vmspecId" class="mt-2" />

                        </div>
                    @else
                        <div class="col-span-6 text-lg bold flex inline-flex">
                            <div class="px-2">
                                <x-icon.info />
                            </div>
                            <x-label for="role"
                                value="{{ __('There is no new VM policy defined.') }}" />
                        </div>
                    @endif

                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('managingVmspecforEnv')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            @if ($vmspecId !== 0)
                <x-button class="ms-3" wire:click="detachLocationEnvironmentVMPolicy" wire:loading.attr="disabled">
                    {{ __('Dettach') }}
                </x-button>
                <x-button class="ms-3" wire:click="updateLocationEnvironmentVMPolicy" wire:loading.attr="disabled">
                    {{ __('Update') }}
                </x-button>
            @endif
        </x-slot>
    </x-dialog-modal>


    <!-- Add Storage Policy Management Modal -->
    <x-dialog-modal wire:model.live="confirmingStoragePolicyAddition">
        <x-slot name="title">
            {{ __('Associate Storage Policy for Location') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    @if ($availableStorages == !null && count($availableStorages) > 0)
                        <div class="col-span-6 text-lg bold">
                            <x-label for="role" value="{{ __('Storage Policy') }}" />
                            <button></button>
                        </div>
                        <div class="p-2 h-48 overflow-y-auto">
                            @foreach ($availableStorages as $availableStorage)
                                <button type="button"
                                    class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('storageId', {{ $availableStorage->id }})">
                                    <div
                                        class="{{ isset($vmspecId) && $vmspecId !== $availableStorage->id ? 'opacity-50' : '' }}">
                                        <!-- Environment Name -->
                                        <div class="flex items-center">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $availableStorage->name }}
                                            </div>

                                            @if ($vmspecId == $availableStorage->id)
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
                            <x-input-error for="vmspecId" class="mt-2" />

                        </div>
                    @else
                        <div class="col-span-6 text-lg bold flex inline-flex">
                            <div class="px-2">
                                <x-icon.info />
                            </div>
                            <x-label for="role"
                                value="{{ __('There is no new Storage Policy defined.') }}" />
                        </div>
                    @endif

                </div>

            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingStoragePolicyAddition')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            {{-- <x-button class="ms-3" wire:click="detachLocationVMPolicy" wire:loading.attr="disabled">
                {{ __('Dettach') }}
            </x-button> --}}
            @if ($storageId !== '')
                <x-button class="ms-3" wire:click="attachLocationStoragePolicy" wire:loading.attr="disabled">
                    {{ __('Add') }}
                </x-button>
            @endif

        </x-slot>
    </x-dialog-modal>

    <x-confirmation-modal wire:model.live="confirmingStoragePolicyRemoval">
        <x-slot name="title">
            {{ __('Remove Storage Policy from Location') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this Storage Policy from Location?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingStoragePolicyRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteStoragePolicyforLocation()"
                wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
