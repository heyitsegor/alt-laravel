<div
    class="location-item flex justify-between scale-100  mb-4 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl dark:text-gray-400 from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250">
    >
    <form action="/locations" method="post">
        @csrf

        <label for="label">Label: </label>
        <input type="text" id="label" name="label">

        <label for="latitude">Latitude: </label>
        <input type="text" id="latitude" name="latitude">

        <label for="longitude">Longitude: </label>
        <input type="text" id="longitude" name="longitude">

        <button type="submit">Create</button>
    </form>
</div>
