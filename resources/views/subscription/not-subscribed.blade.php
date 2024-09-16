@extends('layout')
@section('title', 'Weather App - Welcome')

@section('content')
<div class="min-h-screen mx-auto ml-5 mt-2">
    <h2 class="text-2xl mb-4 text-red-500 font-extralight">You Are Not Subscribed</h2>
    <p class="mb-6 text-red-500">You are currently not subscribed to any plan. Please choose a plan to continue enjoying our services.</p>
    <a href="/" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">go home</a>
</div>



@endsection