@extends('layout.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Manage Your Locations</h1>
    <!-- This is where your Livewire component for managing locations goes -->
    @livewire('manage-locations-component')
</div>
@endsection