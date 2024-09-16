<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Weather App')</title>
    <!-- CSS file -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @livewireStyles
</head>

<body class="flex flex-col min-h-screen">

    <!-- Navbar -->
    @include('navbar')

    <!-- Main content area -->
    <main class="flex-grow ">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-6 mt-auto">
        <p>&copy; 2024 Weather App. All rights reserved.</p>
    </footer>

    @livewireScripts
</body>

</html>