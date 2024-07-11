<x-form-section submit="updateProject">
    <x-slot name="title">
        {{ __('Project Name') }}
    </x-slot>

    <x-slot name="description">
        {{ __('The project\'s name and other information.') }}

        <div class="py-4">
            <button class="cursor-pointer text-sm text-blue-500" wire:click="goToProject({{ $project->id }})">
                Add project member to start collaborating.
            </button>
        </div>
    </x-slot>

    <x-slot name="form">
        <!-- Project Owner Information -->
        <div class="col-span-6">
            <x-label value="{{ __('Project Owner') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $project->project_owner->profile_photo_url }}"
                    alt="{{ $project->project_owner->name }}">

                <div class="ms-4 leading-tight">
                    <div class="text-gray-900 dark:text-white">{{ $project->project_owner->name }}</div>
                    <div class="text-gray-700 dark:text-gray-300 text-sm">{{ $project->project_owner->email }}</div>
                </div>
            </div>
        </div>

        <!-- Project Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.name" value="{{ __('Project Name') }}" />
            <x-input id="projectForm.name" type="text" class="mt-1 block w-full" wire:model="projectForm.name"
                autofocus :disabled="!Gate::check('update', $project)" />
            <x-input-error for="projectForm.name" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.description" value="{{ __('Description') }}" />
            <x-input id="projectForm.description" type="text" class="mt-1 block w-full"
                wire:model="projectForm.description" />
            <x-input-error for="projectForm.description" class="mt-2" />
        </div>
        <div class="col-span-6 sm:col-span-4">
            <x-label for="projectForm.charge_code" value="{{ __('Charge Code') }}" />
            <x-input id="projectForm.charge_code" type="text" class="mt-1 block w-full"
                wire:model="projectForm.charge_code" />
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
             />
            <x-input-error for="projectForm.budget" class="mt-2" />
        </div>
    </x-slot>

    @if (Gate::check('update', $project))
        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button>
                {{ __('Save') }}
            </x-button>
        </x-slot>
    @endif
</x-form-section>
