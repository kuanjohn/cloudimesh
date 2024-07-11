<div class="space-y-6 min-w-full sm:rounded-lg shadow overflow-x-auto"> 
    <table class="mx-auto whitespace-no-wrapw-full whitespace-no-wrap w-full">
        <thead>
            <tr class="text-sm font-medium text-gray-900 bg-gray-200 text-center">
                {{ $head }}
            </tr>
        </thead>

        <tbody wire:loading.class="opacity-50" class="text-sm divide-y divide-cool-gray-200">
            {{ $body }}
        </tbody>
    </table>
</div>
