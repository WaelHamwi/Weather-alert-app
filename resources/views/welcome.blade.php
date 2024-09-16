@extends('layout')

@section('title', 'Weather App - Welcome')

@section('content')
@vite('resources/css/app.css')
@vite('resources/js/app.js')

@include('checkWeather.explore')
@component('subscription.card',  ['trialDays' => $trialDays]) @endcomponent

@component('subscription.subscribtion-area') @endcomponent



<!-- Manage Locations Section -->
<div class="card mt-6 p-6  shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">Manage Your Subscribed Locations</h2>
    <p class="mb-4">Click the button below to manage your subscribed locations.</p>
    <a href="{{ route('locations.manage') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
        Manage Locations
    </a>
</div>


@livewire('subscription-checkout', ['plan' => 'monthly'])


@endsection