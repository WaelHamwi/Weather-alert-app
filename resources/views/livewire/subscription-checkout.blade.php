<div x-data="{ showToast: false, toastMessage: '', plan: '', showButtons: true, proceed: false }">
    <!-- Toast Message -->
    <template x-if="showToast">
        <div class="fixed bottom-4 right-4 bg-blue-500 text-white p-4 rounded shadow-lg" x-text="toastMessage"></div>
    </template>

    <!-- Main Content -->
    <h2 class="text-xl font-semibold text-white ml-5" x-show="!showToast">Subscription Checkout livewire</h2>

    <!-- Subscription Buttons -->
    <template x-if="showButtons">
        <div class="w-full  mt-4 flex gap-4 justify-center items-center mb-4">
            <!-- Monthly Subscription Button -->
            <button
                class="w-48 py-2 px-4 text-white font-semibold rounded-lg shadow-md
                transition-colors duration-300 ease-in-out
                focus:outline-none focus:ring-2 focus:ring-offset-2
                flex items-center justify-center
                "
                :class="proceed ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                x-on:click="
                if($wire.trialDays || $wire.subscriptionDays){
                    if (!proceed) {
                        showToast = true;
                        toastMessage = 'You have ' + $wire.trialDays + ' trial days left and ' + $wire.subscriptionDays + ' subscription days remaining.';
                        proceed = true;
                    } else {
                        plan = 'monthly';
                        $wire.call('proceedCheckout', plan, proceed).then(() => {
                            handleSubscription(plan);
                        });
                    }
                } else {
                    plan = 'monthly';
                    $wire.call('checkout', plan).then(() => {
                        handleSubscription(plan);
                    });
                }
                ">
                <span x-text="proceed ? 'Subscribe Monthly Anyway' : ($wire.trialDays ? 'Subscribe Monthly' : 'Subscribe Monthly')"></span>
            </button>

            <!-- Yearly Subscription Button -->
            <button
                class="w-48 py-2 px-4 text-white font-semibold rounded-lg shadow-md
                transition-colors duration-300 ease-in-out
                focus:outline-none focus:ring-2 focus:ring-offset-2
                flex items-center justify-center
                "
                :class="proceed ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'"
                x-on:click="
                if($wire.trialDays || $wire.subscriptionDays){
                    if (!proceed) {
                        showToast = true;
                        toastMessage = 'You have ' + $wire.trialDays + ' trial days left and ' + $wire.subscriptionDays + ' subscription days remaining.';
                        proceed = true; 
                    } else {
                        plan = 'yearly';
                        $wire.call('proceedCheckout', plan, proceed).then(() => {
                            handleSubscription(plan);
                        });
                    }
                } else {
                    plan = 'yearly';
                    $wire.call('checkout', plan).then(() => {
                        handleSubscription(plan);
                    });
                }
                ">
                <span x-text="proceed ? 'Subscribe Yearly Anyway' : ($wire.trialDays ? 'Subscribe Yearly' : 'Subscribe Yearly')"></span>
            </button>
        </div>
    </template>

    <!-- Error Handling -->
    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>
