@extends('layout')

@section('title', 'Weather App - Welcome')

@section('content')
<div class="container mx-auto p-4 min-h-screen">
    <div class="bg-white shadow-md rounded-lg p-6 text-center ">
        <h1 class="text-2xl font-bold mb-4 bg-red-600">Unsubscribed Successfully</h1>
        <p class="mb-6 bg-red-600">You have successfully unsubscribed from our service. We hope to see you again soon!</p>
        <a href="/" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Back to Home</a>
    </div>
</div>
@endsection
