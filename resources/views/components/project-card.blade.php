@props([
    'project' => null,
])
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    {{-- <img alt="{{ $project->description }}" class="w-full h-32 object-cover object-center"> --}}
    <div class="p-4">
        <h2 class="text-xl font-semibold mb-2">{{ $project->name }}</h2>
        <p class="text-gray-600">{{ $project->description }}</p>
        <!-- Charge Code -->
        <p class="text-gray-600 mt-2"><strong>Charge Code:</strong> {{ $project->charge_code }}</p>

        <!-- Budget -->
        <p class="text-gray-600 mt-2"><strong>Budget:</strong> {{ $project->budget }}</p>

        <!-- Cost -->
        <p class="text-gray-600 mt-2"><strong>Cost:</strong> {{ $project->cost }}</p>

        <!-- Last Updated Information -->
    <div class="text-gray-600 mt-2">
        <strong>Last Updated:</strong> {{ $project->updated_at->diffForHumans() }}
        <br>
        <strong>Updated By:</strong> {{ $project->updater->name }} ({{ $project->updater->email }})
    </div>

        <div class="mt-4 flex justify-between items-center">
            <span class="text-sm text-gray-500">Created {{ $project->created_at->diffForHumans() }}. Last updated by {{ $project->updater->name }} ({{ $project->updater->email }}) {{ $project->updated_at->diffForHumans() }}.</span>
            <a href="{{ route('project/{id}/createvm', $project) }}" class="text-blue-500 hover:text-blue-600">Create VM</a>
        </div>
    </div>
</div>
