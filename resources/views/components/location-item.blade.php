@props(['id', 'label', 'latitude', 'longitude', 'selectedLocationId'])

<a href="{{ route('locations.index', ['location' => $id]) }}"
    style="{{ $id == $selectedLocationId ? 'outline:2px solid #ef4444;' : '' }}"
    class="location-item flex justify-between scale-100  mb-4 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250">
    <div>
        <div class="text-lg font-medium text-gray-900 dark:text-white">
            {{ $label }}
        </div>

        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Latitude: {{ $latitude }}
        </div>

        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Longitude: {{ $longitude }}
        </div>
    </div>

    <div class="inline-flex items-center">
        <form style="margin:0 .5rem 0 0 " method="post" action=" {{ route('locations.destroy', $id) }}">
            @csrf
            @method('delete')

            <x-danger-button>
                {{ __('Delete') }}
            </x-danger-button>
        </form>
    </div>
</a>
