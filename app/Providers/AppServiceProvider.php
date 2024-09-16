<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //register the service in the method bootstrap
        Stripe::setApiKey(config('cashier.secret'));
        Livewire::component('manage-locations-component', \App\Http\Livewire\ManageLocationsComponent::class);
        Livewire::component('subscription-check-trial', \App\Http\Livewire\SubscriptionCheckTrial::class);
        Livewire::component('subscription-checkout', \App\Http\Livewire\SubscriptionCheckout::class);
        Livewire::component('subscription-renew', \App\Http\Livewire\SubscriptionRenew::class);
        Livewire::component('subscription-cancel', \App\Http\Livewire\SubscriptionCancel::class);
        Livewire::component('customer-portal', \App\Http\Livewire\CustomerPortal::class);
        require_once base_path('app/Helpers/WeatherHelper.php');
    }
}
