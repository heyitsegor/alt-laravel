@props(['selectedLocationId'])

@php
    use App\Models\Location;
    $location = Location::find($selectedLocationId);
@endphp

<div
    class="location-item scale-100  mb-4 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl dark:text-gray-400 from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex transition-all duration-250">
    <form class="w-full flex justify-between"
        action="{{ $selectedLocationId ? route('locations.update', ['location' => $selectedLocationId]) : route('locations.store') }}"
        method="post">
        @csrf
        @if ($selectedLocationId)
            @method('put')
        @endif

        <div>
            <h1 class=" text-lg font-bold text-gray-900 dark:text-white">
                <a class="text-red-600 underline" href="{{ route('locations.index') }}">Create new</a>
                or select item to edit
            </h1>
            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <x-input-label for="label" :value="__('Label')" />
                <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" :value="old('label', $location->label ?? '')"
                    required autofocus />

                <x-input-error class="mt-2" :messages="$errors->get('label')" />
            </div>

            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <x-input-label for="latitude" :value="__('Latitude')" />
                <x-text-input id="latitude" name="latitude" type="text" class="mt-1 block w-full" :value="old('label', $location->latitude ?? '')"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('latitude')" />
            </div>

            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <x-input-label for="longitude" :value="__('Longitude')" />
                <x-text-input id="longitude" name="longitude" type="text" class="mt-1 block w-full" :value="old('label', $location->longitude ?? '')"
                    required />
                <x-input-error class="mt-2" :messages="$errors->get('longitude')" />
            </div>

        </div>
        <div class="inline-flex items-center">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'location-saved')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</div>
