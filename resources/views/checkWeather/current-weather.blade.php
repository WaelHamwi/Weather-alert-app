@extends('layout')

@section('title', 'Current Weather')

@section('content')
<div class="min-h-screen bg-gradient-to-r from-blue-600 via-indigo-500 to-purple-600 dark:from-gray-800 dark:via-gray-900 dark:to-black p-6 flex items-center justify-center">
    <div class="max-w-md w-full bg-white dark:bg-gray-800 shadow-md dark:shadow-xl rounded-xl overflow-hidden transition-shadow duration-300 ease-in-out">
        <div class="p-6 text-center bg-gradient-to-b from-blue-500 to-purple-600 dark:from-gray-700 dark:to-gray-900 text-white">
            <h1 class="text-3xl font-extrabold mb-2">Current Weather</h1>
            <p class="text-sm tracking-wide">Get up-to-date weather information</p>
        </div>

        @if(isset($error))
        <div class="p-6 text-red-500 dark:text-red-400">
            <p class="text-lg font-semibold">{{ $error }}</p>
        </div>
        @elseif(isset($weather))
        <div class="p-6 flex flex-col justify-center items-center">
            <h2 class="text-2xl font-bold text-gray-500 dark:text-gray-300 mb-4">Weather in {{ $location->name }}</h2>

            <div class="bg-gradient-to-r from-blue-200 to-indigo-200 dark:from-gray-700 dark:to-gray-800 p-4 rounded-lg shadow-inner dark:shadow-md">
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Temperature:
                    <span class="text-blue-600 dark:text-blue-400">{{ $weather['main']['temp'] }}°C</span>
                </p>
                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100 mt-2">
                    Condition:
                    <span class="text-blue-600 dark:text-blue-400">{{ ucfirst($weather['weather'][0]['description']) }}</span>
                </p>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-600 font-medium">Check Another Location</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection