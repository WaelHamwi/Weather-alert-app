<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="open = true" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">
        Cancel Subscription
    </button>

    <!-- Modal Background -->
    <div x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50" @click.outside="open = false">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-extrabold text-gray-500 mb-4 ">Are you sure you want to cancel your subscription?</h2>
            <div class="flex gap-4">
                <form method="POST" action="{{ route('subscribe.cancelSubscription') }}">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700">Yes, Cancel</button>
                </form>
                <button @click="open = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">No, Keep Subscription</button>
            </div>
        </div>
    </div>
</div>
