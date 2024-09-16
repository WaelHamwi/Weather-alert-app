<div class="mt-6 flex gap-4 justify-center mx-2">
    <!-- Include Modal Component -->
    @include('subscription.modal')

    <!-- Renew Monthly Subscription -->
    @component('subscription.subscription-form', [
        'action' => route('subscribe.renew'),
        'plan' => 'monthly',
        'buttonText' => 'Renew Monthly Subscription',
        'btnClass' => 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-green-700 sm:w-30 xs:h-15'
    ]) @endcomponent

    <!-- Renew Yearly Subscription -->
    @component('subscription.subscription-form', [
        'action' => route('subscribe.renew'),
        'plan' => 'yearly',
        'buttonText' => 'Renew Yearly Subscription',
        'btnClass' => 'bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 sm:w-30 xs:h-15'
    ]) @endcomponent
</div>
