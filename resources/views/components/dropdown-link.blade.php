@props(['active' => false])


@if($active)
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 border-indigo-400 dark:border-indigo-600 text-indigo-700 dark:text-indigo-300 bg-indigo-50 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-indigo-100 dark:focus:bg-indigo-900 dark:bg-indigo-900/50 focus:text-indigo-800 dark:focus:text-indigo-200 focus:border-indigo-700 dark:focus:border-indigo-300 transition duration-150 ease-in-out']) }}> 

    {{ $slot }}
</a>
@else
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out']) }}> {{ $slot }}</a>
@endif
