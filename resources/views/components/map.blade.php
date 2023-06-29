<div id="map" style="width: 600px; height: 400px"></div>
<!-- <script src="https://api-maps.yandex.ru/2.1/?apikey=cf986674-7103-4197-9b22-50d1b429afb1&lang=ru_RU"></script> -->
<script type="text/javascript">
    ymaps.ready(init);

    function init() {
        var geolocation = ymaps.geolocation,
            myMap = new ymaps.Map('map', {
                center: [0, 0],
                zoom: 10,
                controls: []
            }, {
                searchControlProvider: 'yandex#search'
            });

        geolocation.get({
            provider: 'browser',
            mapStateAutoApply: true
        }).then(function(result) {
            result.geoObjects.options.set('preset', 'islands#blueCircleIcon');
            myMap.geoObjects.add(result.geoObjects);
        });
    }
</script>
