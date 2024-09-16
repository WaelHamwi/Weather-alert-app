<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;

class CustomerPortal extends Component
{
    public function openPortal()
    {
        $stripe = new StripeClient(config('cashier.secret'));
        $user = Auth::user();

        $portalSession = $stripe->billingPortal->sessions->create([
            'customer' => $user->stripe_id,
            'return_url' => route('subscribe.success'),
        ]);

        return redirect($portalSession->url);
    }

    public function render()
    {
        return view('livewire.customer-portal');
    }
}
