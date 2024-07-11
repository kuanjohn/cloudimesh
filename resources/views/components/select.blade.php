<!-- Example Select Component -->
@props(['id', 'name' => '', 'error' => null])

<div>
    {{-- <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $slot }}</label> --}}
    <select id="{{ $id }}" name="{{ $name }}"  {{ $attributes->merge(['wire:model.live' => '', 'class' => 'mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm'])}}>
        {{ $slot }}
    </select>
    @error($name) <span class="text-red-500">{{ $error }}</span> @enderror
</div>
