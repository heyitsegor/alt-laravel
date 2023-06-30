<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Locations') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('locations.partials.location-list')
            <div class="overflow-hidden shadow-sm sm:rounded-lg" style="width: 600px; height: 400px">
                <x-map :locations="$locations" />
            </div>
        </div>
    </div>
</x-app-layout>
