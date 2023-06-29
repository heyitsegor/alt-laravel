@props(['locations', 'selectedLocationId' => -1])

<div class="location-list">
    <x-location-input :selectedLocationId="$selectedLocationId" />
    @foreach ($locations as $location)
        <x-location-item :id="$location->id" :label="$location->label" :latitude="$location->latitude" :longitude="$location->longitude" :selectedLocationId="$selectedLocationId" />
    @endforeach
</div>
