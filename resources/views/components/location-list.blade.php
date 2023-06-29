@props(['locations', 'selectedLocationId'])

<div class="location-list">
    <x-location-input />
    @foreach ($locations as $location)
        <x-location-item :id="$location->id" :label="$location->label" :latitude="$location->latitude" :longitude="$location->longitude" :selectedLocationId="$selectedLocationId" />
    @endforeach
</div>
