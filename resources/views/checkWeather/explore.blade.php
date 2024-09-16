<div class="card p-6 shadow-md rounded-lg bg-cover bg-center bg-no-repeat min-h-screen" style="background-image: url('{{ asset('images/cover.webp') }}');">
    <header class="hero">
        <div class="flex justify-center">
            <h1 class="text-white justify-center ml-4 font-extrabold text-2xl al">Welcome to the Weather App</h1>
        </div>
    </header>
    <h2 class="text-3xl font-bold mb-6 text-white">Explore Weather Features</h2>
    <p class="mb-6 text-lg text-white">Discover detailed and accurate weather information to help you plan your day. Whether you're looking for the current conditions or a detailed hourly forecast, we've got you covered.</p>

    <div class="mb-6 text-white">
        <h3 class="text-xl font-semibold mb-2">Current Weather</h3>
        <p class="mb-4">Stay up-to-date with the latest weather conditions in your area. Get real-time updates on temperature, humidity, wind speed, and more.</p>
        <h3 class="text-xl font-semibold mb-2">Hourly Forecast</h3>
        <p class="mb-4">Plan your day with precision by checking the hourly forecast. See how the weather will change throughout the day and prepare accordingly.</p>
    </div>

    <!-- Navigation Buttons -->
    <div class="flex justify-center gap-5 mt-10">
        <a href="{{ route('weather.current') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 mr-2">
            Check Current Weather
        </a>

        <a href="{{ route('weather.forecast') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
            Check Hourly Forecast
        </a>
    </div>
</div>