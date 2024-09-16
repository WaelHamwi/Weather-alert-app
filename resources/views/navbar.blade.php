<!-- resources/views/components/navbar.blade.php -->
<nav class="bg-gray-800 p-4">
    <div class="container mx-auto flex items-center justify-center">
        <div class="flex space-x-4">
            <a href="/" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Home</a>
            <a href="/contact" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
            <a href="{{ route('notifications.index') }}" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                Notifications
                @if(isset($notifications) && $notifications->count() > 0)
                <span class="ml-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs">{{ $notifications->count() }}</span>
                @endif
            </a>
        </div>
    </div>
</nav>
