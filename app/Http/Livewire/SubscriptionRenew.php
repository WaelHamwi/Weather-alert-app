<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Stripe\StripeClient;
use Stripe\Checkout\Session as CheckoutSession;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Stripe\Stripe;
use Carbon\Carbon;

class SubscriptionRenew extends Component
{
    public $plan;

    public function mount($plan)
    {
        $this->plan = $plan;
    }

    public function renew()
    {
        try {
            $userId = Auth::id();
            $user = User::find($userId);
            if (!$user) {
                return redirect()->route('login');
            }

            Stripe::setApiKey(config('cashier.secret'));
            $priceId = $this->plan === 'yearly' ? config('cashier.yearly_price_id') : config('cashier.monthly_price_id');

            if (empty($user->stripe_id)) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                ]);
                $user->stripe_id = $customer->id;
                $user->save();
            }

            $checkout_session = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'customer' => $user->stripe_id,
                'success_url' => route('subscribe.success', ['plan' => $this->plan]),
                'cancel_url' => route('subscribe.cancel'),
            ]);

            $currentEndDate = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;
            if ($currentEndDate && now()->lessThan($currentEndDate)) {
                $newEndDate = ($this->plan === 'yearly') ? $currentEndDate->addYear() : $currentEndDate->addMonth();
            } else {
                $newEndDate = ($this->plan === 'yearly') ? now()->addYear() : now()->addMonth();
            }

            $user->subscription_plan = $this->plan;
            $user->subscription_ends_at = $newEndDate;
            $user->save();

            return redirect($checkout_session->url);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            return back()->withErrors(['error' => 'There was an issue processing your payment: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.subscription-renew');
    }
}
