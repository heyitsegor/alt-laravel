@props(['locations', 'selectedLocationId' => -1])

<!-- php
    $currentLocation = $locations[0];
    if ($selectedLocationId == -1) {
        $currentLocation->label = ' ';
        $currentLocation->latitude = 0;
        $currentLocation->longitude = 0;
    } else {
        $currentLocation = $locations[$selectedLocationId];
    }
endphp -->

<div class="location-list">
    <x-location-input :selectedLocationId="$selectedLocationId" />
    @foreach ($locations as $location)
        <x-location-item :id="$location->id" :label="$location->label" :latitude="$location->latitude" :longitude="$location->longitude" :selectedLocationId="$selectedLocationId" />
    @endforeach
</div>
