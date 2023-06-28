<head>
    <script src="https://api-maps.yandex.ru/3.0/?apikey=<ваш API-ключ>&lang=ru_RU"></script>
    <script type="text/javascript">
        ymaps3.ready.then(init());

        function init() {
            const map = new ymaps3.YMap(document.getElementById('YMapsID'), {
                location: {
                    center: [37.64, 55.76],
                    zoom: 10
                }
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
