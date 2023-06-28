<head>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=cf986674-7103-4197-9b22-50d1b429afb1&lang=ru_RU"></script>
    <script type="text/javascript">
        ymaps.ready(init);

        function init() {
            var myMap = new ymaps.Map("map", {
                center: [55.76, 37.64],
                zoom: 7
            });
        }
    </script>
</head>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Map') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('Map goes here') }}
                    <div id="map" style="width: 600px; height: 400px"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
