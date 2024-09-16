@extends('layout')

@section('title', 'Hourly Forecast')

@section('content')

@if(isset($error))
<p class="text-red-600">{{ $error }}</p>
@elseif(isset($forecast))
<h2 class="text-2xl font-semibold mb-4">Hourly Forecast for {{ $location->name }}</h2>
<div class="container mx-auto">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3  md:grid-cols-4 lg:grid-cols-5 mb-4 mt-4">
        @foreach($forecast['list'] as $hour)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-4">
            <div class="w-full h-32 flex justify-center items-center bg-red-500">
                <i class="{{ \App\Helpers\WeatherHelper::getWeatherIcon($hour['weather'][0]['description']) }} text-6xl"></i>
            </div>
            <div class="p-4">
                <h5 class="text-xl font-semibold">{{ $hour['dt_txt'] }}</h5>
                <p class="text-gray-700">
                    Temperature: {{ $hour['main']['temp'] }}°C<br>
                    Condition: {{ $hour['weather'][0]['description'] }}
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
