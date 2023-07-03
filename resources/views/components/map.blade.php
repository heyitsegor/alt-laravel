@props(['locations', 'selectedLocationId'])

<div class="flex justify-between scale-100  mb-4 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl dark:text-gray-400 from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex  transition-all duration-250"
    id="map" style="width: 600px; height: 400px"></div>
<script src="https://api-maps.yandex.ru/2.1/?apikey=cf986674-7103-4197-9b22-50d1b429afb1&lang=ru_RU"></script>
<script type="text/javascript">
    ymaps.ready(init);

    function init() {
        const locations = <?php echo json_encode($locations); ?>;
        const selectedLocationId = <?php echo json_encode($selectedLocationId); ?>;
        const selectedLocation = locations.filter((location) => location.id == selectedLocationId)[0]

        const geolocation = ymaps.geolocation
        const myMap = new ymaps.Map('map', {
            center: selectedLocationId ? [selectedLocation.latitude, selectedLocation.longitude] : [0, 0],
            zoom: 10,
            controls: []
        }, {
            searchControlProvider: 'yandex#search'
        });

        if (!selectedLocation) {
            geolocation.get({
                provider: 'browser',
                mapStateAutoApply: true
            }).then(function(result) {
                result.geoObjects.options.set('preset', 'islands#blueCircleIcon');
                myMap.geoObjects.add(result.geoObjects);
            });
        }


        locations.forEach((location) => {
            const myPlacemark = new ymaps.Placemark(
                [location.latitude, location.longitude], {
                    balloonContentBody: location.label,
                }, {
                    preset: 'islands#redCircleIcon',

                });
            myMap.geoObjects.add(myPlacemark);
        })
    }
</script>
