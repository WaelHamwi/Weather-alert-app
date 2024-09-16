@extends('layout')

@section('title', 'Weather App - Welcome')

@section('content')
<div class="min-h-screen">
    <h2 class="text-green-600">Subscription Successful!</h2>
    <p>Thank you for subscribing to our {{ $plan }} plan.</p>
</div>

@endsection