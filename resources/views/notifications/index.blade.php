@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-6 min-h-screen">
    <h1 class="text-4xl font-extrabold text-gray-600 mb-8">Your Notifications</h1>

    @foreach ($notifications as $notification)
    <div class="{{ $notification->read_at ? 'bg-gray-200 border-gray-300' : 'bg-blue-50 border-blue-200' }} shadow-lg rounded-lg p-6 mb-6 border">
        <p class="text-gray-800 text-lg mb-4">{{ $notification->data['message'] }}</p>
        <a href="{{ route('notifications.read', ['id' => $notification->id]) }}" class="inline-block bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition duration-300">
            {{ $notification->read_at ? 'Read' : 'Mark as Read' }}
        </a>
    </div>
    @endforeach
</div>
@endsection