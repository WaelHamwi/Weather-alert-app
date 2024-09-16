<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Alpine.js via CDN it is not the best choice for the big business -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


</head>
@include('navbar')
<main class="flex-grow ">
    @livewire('manage-locations-component')
</main>




@livewireScripts

<!--  JS file -->
<script src="{{ asset('js/app.js') }}"></script>
</body>

</html>