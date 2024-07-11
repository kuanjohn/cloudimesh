<x-form-section submit="createVM">
    <x-slot name="title">
        {{ __('Virtual Machine Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('You can create virtual machines in seconds. You can use Cloudimesh, either standalone or as part of a larger, cloud based infrastructure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-label value="{{ __('VM Owner') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}"
                    alt="{{ $this->user->name }}">

                <div class="ms-4 leading-tight">
                    <div class="text-gray-900 dark:text-white">{{ $this->user->name }}</div>
                    <div class="text-gray-700 dark:text-gray-300 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="vmForm.name" value="{{ __('VM Name') }}" />
            <x-input id="vmForm.name" type="text" class="mt-1 block w-full" wire:model.live="vmForm.name"
                autofocus />
            <x-input-error for="vmForm.name" class="mt-2" />
        </div>


        @if ($locations->isNotEmpty())
            <!-- Location Dropdown -->
            <div class="col-span-6 sm:col-span-4">
                <x-section-border />

                <x-label for="vmForm.location_id" value="{{ __('Location') }}" />
                <x-select id="vmForm.location_id" name="vmForm.location_id" wire:model.live="vmForm.location_id"
                    error="$errors->first('location')">
                    <!-- Location -->
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                    {{-- <option value="us-east">US East</option> --}}
                    <!-- Add more options as needed -->
                </x-select>
            </div>
        @endif
        {{-- @dump($vmForm['location'])
        @dump($vmForm['name']) --}}

        <!-- Environment Dropdown -->
        @if (!is_array($environments) && $environments->isNotEmpty())
            <div class="col-span-6 sm:col-span-4">

                <x-label for="vmForm.environment_id" value="{{ __('Environment') }}" />
                <x-select id="vmForm.environment_id" name="vmForm.environment_id"
                    wire:model.live="vmForm.environment_id" error="$errors->first('location')">
                    <!-- Environment -->
                    @forelse ($environments as $environment)
                        <option value="{{ $environment->id }}">{{ $environment->name }}</option>
                    @empty
                    @endforelse
                    {{-- <option value="us-east">US East</option> --}}
                    <!-- Add more options as needed -->
                </x-select>
            </div>
        @endif

        <!-- Tier Dropdown -->
        @if (!is_array($tiers))
            <div class="col-span-6 sm:col-span-4">

                <x-label for="vmForm.tier_id" value="{{ __('Tier') }}" />
                <x-select id="vmForm.tier_id" name="vmForm.tier_id" wire:model.live="vmForm.tier_id"
                    error="$errors->first('location')">
                    <!-- Tier -->
                    @forelse ($tiers as $tier)
                        <option value="{{ $tier->id }}">{{ $tier->name }}</option>
                    @empty
                    @endforelse
                    {{-- <option value="us-east">US East</option> --}}
                    <!-- Add more options as needed -->
                </x-select>
            </div>
        @endif

        <!-- CPU and Memory Fields -->
        <div class="col-span-6 sm:col-span-4">
            <x-section-border />
            <div class="flex justify-between">
                <x-label for="vmForm.vcpu" value="{{ __('vCPU') }}" />
                <x-label for="vmForm.cost_vcpu">{{ $vmForm['cost_vcpu'] }}</x-label>
            </div>

            <x-input id="vmForm.vcpu" type="number" class="mt-1 block w-full" wire:model="vmForm.vcpu" />
            <x-input-error for="vmForm.vcpu" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <div class="flex justify-between">

                <x-label for="vmForm.vmem" value="{{ __('Memory (GB)') }}" />
                <x-label for="vmForm.cost_vmem">{{ $vmForm['cost_vmem'] }}</x-label>

            </div>

            <x-input id="vmForm.vmem" type="number" class="mt-1 block w-full" wire:model="vmForm.vmem" />
            <x-input-error for="vmForm.vmem" class="mt-2" />
        </div>

        {{-- <!-- OS Selection -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="os" value="{{ __('Operating System') }}" />
            <x-select id="os" wire:model="os">
                <option value="linux">Linux</option>
                <option value="windows">Windows</option>
                <!-- Add more options as needed -->
            </x-select>
        </div> --}}

        <!-- Storage Dropdown -->
        @if (!is_array($storages))
            <div class="col-span-6 sm:col-span-4">
                <div class="flex justify-between">
                    <x-label for="vmForm.storage" value="{{ __('Storage') }}" />
                    <x-label for="vmForm.storage_cost">{{ $vmForm['storage_cost'] }}/GB</x-label>
                </div>
                <x-select id="vmForm.storage" name="vmForm.storage" wire:model.live="vmForm.storage"
                    error="$errors->first('location')">
                    <!-- Operating System -->
                    @forelse ($storages as $storage)
                        <option value="{{ $storage->id }}">{{ $storage->name }}</option>
                    @empty
                        <option value="storagepolicy.ssd.name">{{ config('storagepolicy.ssd.name') }}</option>
                    @endforelse
                    {{-- <option value="us-east">US East</option> --}}
                    <!-- Add more options as needed -->
                </x-select>
            </div>
            {{-- @dump($storages) --}}
        @endif

        <!-- Tier Dropdown -->
        @if (!is_array($operating_systems))
            <div class="col-span-6 sm:col-span-4">
                <div class="flex justify-between">
                    <x-label for="vmForm.operating_system" value="{{ __('Operating System') }}" />
                    <x-label
                        for="vmForm.storage_cost">{{ $vmForm['operating_system_cost'] }}/{{ $vmForm['operating_system_cost_type'] }}</x-label>
                </div>
                <x-select id="vmForm.operating_system" name="vmForm.operating_system"
                    wire:model.live="vmForm.operating_system" error="$errors->first('location')">
                    <!-- Operating System -->
                    @forelse ($operating_systems as $operating_system)
                        <option value="{{ $operating_system->id }}">{{ $operating_system->name }}</option>
                    @empty
                    @endforelse
                    {{-- <option value="us-east">US East</option> --}}
                    <!-- Add more options as needed -->
                </x-select>
            </div>
            {{-- @dump($operating_system->min_disk) --}}

            <!-- Additional Storage Field -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="vmForm.min_disk" value="{{ __('OS Storage (GB)') }}" />
                <x-input id="vmForm.min_disk" type="number" class="mt-1 block w-full" wire:model="vmForm.min_disk"
                    disabled />
                <x-input-error for="vmForm.min_disk" class="mt-2" />
            </div>
        @endif

        <!-- Additional Storage Field -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="vmForm.add_storage" value="{{ __('Additional Storage (GB)') }}" />
            <x-input id="vmForm.add_storage" type="number" class="mt-1 block w-full"
                wire:model="vmForm.add_storage" />
            <x-input-error for="vmForm.add_storage" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Monthly Cost: </h3>
                <x-label class="text-lg font-medium" for="vmForm.project_cost" value="{{ __($vmForm['project_cost']) }}" />
            </div>
        </div>
        
        <livewire:alert-banner />

    </x-slot>

    <x-slot name="actions">
        <div class="flex justify-between">

                <x-button>
                    {{ __('Create') }}
                </x-button>
            
        </div>


    </x-slot>

</x-form-section>
