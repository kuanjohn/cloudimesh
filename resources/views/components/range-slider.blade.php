@props(['id', 'min', 'min_value', 'max', 'value', 'step'])

<div class="relative mb-6">
    <label for="labels-range-input" class="sr-only">Labels range</label>

    <input id="{{ $id }}" type="range" min="{{ $min }}" max="{{ $max }}"
        value="{{ $value }}" step="{{ $step }}"
        {{ $attributes->merge(['class' => 'w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700']) }}>
    <span class="text-sm text-gray-500 dark:text-gray-400 absolute start-0 -bottom-6">Min ({{ $min_value }})</span>
    <span class="text-sm text-gray-500 dark:text-gray-400 absolute end-0 -bottom-6">Max ({{ $max }})</span>
</div>

