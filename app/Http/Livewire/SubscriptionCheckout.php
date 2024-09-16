<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use Carbon\Carbon;
use App\Models\User;
use Exception;

class SubscriptionCheckout extends Component
{
    public $plan = 'monthly';
    public $trialDays = 0;
    public $subscriptionDays = 0;
    public $showTrial = false;
    public $proceedAnyway = false;

    public function mount($plan = 'monthly')
    {
        $this->plan = $plan;
        $this->checkTrial($plan);
    }
    public function proceedCheckout($plan, $proceed = false)
    {
        $this->plan = $plan;
        $this->checkTrial($plan);

        if ($proceed) {
            $this->checkout($plan);
        }
    }


    public function checkTrial($plan, $proceed = false)
    {
        $user = Auth::user();

        if (!$user) {
            $this->redirect(route('login'));
            return;
        }
        //check if the users have whehter trial or subscriptions
        $this->trialDays = $user->trial_ends_at && now()->lessThan($user->trial_ends_at)
            ? now()->diffInDays($user->trial_ends_at)
            : 0;
        $this->subscriptionDays = $user->subscription_ends_at && now()->lessThan($user->subscription_ends_at)
            ? now()->diffInDays($user->subscription_ends_at)
            : 0;

        if ($this->trialDays || $this->subscriptionDays > 0 && !$this->proceedAnyway) {
            $this->showTrial = true;
        } else {
            if ($proceed) $this->checkout($plan);
        }
    }

    public function proceedWithTrial($plan)
    {
        $user = Auth::user();
        $userId = $user->id;
        $user = User::findOrFail($userId);

        if (!$user) {
            $this->redirect(route('login'));
            return;
        }

        if ($this->trialDays > 0 || $this->subscriptionDays) {
            $newTrialEndDate = Carbon::now()->addDays($this->trialDays);
            $user->trial_ends_at = $newTrialEndDate;
            $user->save();
        }

        $this->proceedAnyway = true;
        $this->checkout($plan);
    }

    public function updateSubscriptionEndDate($plan)
    {
        $user = Auth::user();
        $userId = $user->id;
        $user = User::findOrFail($userId);
        if (!$user) {
            $this->redirect(route('login'));
            return;
        }

        $currentEndDate = $user->subscription_ends_at ? Carbon::parse($user->subscription_ends_at) : null;
        if ($currentEndDate && now()->lessThan($currentEndDate)) {
            $newEndDate = ($plan === 'yearly') ? $currentEndDate->addYear() : $currentEndDate->addMonth();
        } else {
            $newEndDate = ($plan === 'yearly') ? now()->addYear() : now()->addMonth();
        }

        $user->subscription_ends_at = $newEndDate;
        $user->save();
    }

    public function checkout($plan)
    {
        try {
            $user = Auth::user();
            $userId = $user->id;
            $user = User::findOrFail($userId);
            if (!$user) {
                $this->redirect(route('login'));
                return;
            }

            $this->updateSubscriptionEndDate($plan);

            Stripe::setApiKey(config('cashier.secret'));

            $priceId = $plan === 'yearly' ? config('cashier.yearly_price_id') : config('cashier.monthly_price_id');

            if (empty($user->stripe_id)) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                ]);
                $user->stripe_id = $customer->id;
                $user->save();
            }

            // Create a checkout session
            $checkoutSession = CheckoutSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'customer' => $user->stripe_id,
                'success_url' => route('subscribe.success', ['plan' => $plan]),
                'cancel_url' => route('subscribe.cancel'),
            ]);

            return redirect($checkoutSession->url);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $this->addError('error', 'Stripe error: ' . $e->getMessage());
        } catch (Exception $e) {
            $this->addError('error', 'Unexpected error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.subscription-checkout');
    }
}
