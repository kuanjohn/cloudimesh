<x-form-section submit="createProject">
    <x-slot name="title">
        {{ __('Project Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new project to start the new journey.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6">
            <x-label value="{{ __('Project Owner') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $user->profile_photo_url }}"
                    alt="{{ $user->name }}">

                <div class="ms-4 leading-tight">
                    <div class="text-gray-900 dark:text-white">{{ $user->name }}</div>
                    <div class="text-gray-700 dark:text-gray-300 text-sm">{{ $user->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.name" value="{{ __('Project Name') }}" />
            <x-input id="projectForm.name" type="text" class="mt-1 block w-full" wire:model="projectForm.name"
                autofocus />
            <x-input-error for="projectForm.name" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.description" value="{{ __('Description') }}" />
            <x-input id="projectForm.description" type="text" class="mt-1 block w-full"
                wire:model="projectForm.description" autofocus />
            <x-input-error for="projectForm.description" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.charge_code" value="{{ __('Charge Code') }}" />
            <x-input id="projectForm.charge_code" type="text" class="mt-1 block w-full"
                wire:model="projectForm.charge_code" autofocus />
            <x-input-error for="projectForm.charge_code" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.timeline" value="{{ __('Timeline') }}" />
            <x-input id="projectForm.timeline" type="datetime-local" class="mt-1 block w-full"
                wire:model="projectForm.timeline" />
            <x-input-error for="projectForm.timeline" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.budget" value="{{ __('Project Budget') }}" />
            <x-input id="projectForm.budget" type="text" class="mt-1 block w-full" wire:model="projectForm.budget"
                autofocus />
            <x-input-error for="projectForm.budget" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button>
            {{ __('Create') }}
        </x-button>
    </x-slot>
</x-form-section>
