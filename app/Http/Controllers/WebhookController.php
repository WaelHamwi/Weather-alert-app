<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use App\Models\User;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, config('cashier.webhook.secret'));
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info("Received event: {$event->type}");

        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutSessionCompleted($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            default:
                Log::info("Unhandled event type: {$event->type}");
        }

        return response()->json(['status' => 'success']);
    }

    protected function updateUserSubscription($user, $subscriptionData)
    {
        Log::info("Updating user subscription: " . json_encode($subscriptionData));

        $trialEnd = isset($subscriptionData['trial_end']) ? Carbon::createFromTimestamp($subscriptionData['trial_end']) : null;
        $subscriptionEnd = isset($subscriptionData['current_period_end']) ? Carbon::createFromTimestamp($subscriptionData['current_period_end']) : null;

        $user->subscription_plan = $subscriptionData['plan'] ?? $user->subscription_plan;
        $user->trial_ends_at = $trialEnd;
        $user->subscription_ends_at = $subscriptionEnd;
        $user->save();
    }
    protected function handleCheckoutSessionCompleted($session)
    {
        Log::info("Handling checkout session completed: " . json_encode($session));

        $user = User::where('stripe_id', $session->customer)->first();

        if ($user) {
            $subscriptionData = [
                'plan' => $session->mode ?? "subs",
                'trial_end' => $session->subscription->trial_end ?? null,
                'current_period_end' => $session->expires_at ?? null,
            ];

            $this->updateUserSubscription($user, $subscriptionData);
        } else {
            Log::error("User not found for Stripe customer ID: {$session->customer}");
        }
    }

    protected function handleSubscriptionUpdated($subscription)
    {
        Log::info("Handling subscription updated: " . json_encode($subscription));

        $user = User::where('stripe_id', $subscription->customer)->first();

        if ($user) {
            $subscriptionData = [
                'plan' => $subscription->items->data[0]->price->id ?? null,
                'trial_end' => $subscription->trial_end ?? null,
                'current_period_end' => $subscription->current_period_end ?? null,
            ];

            $this->updateUserSubscription($user, $subscriptionData);
        } else {
            Log::error("User not found for Stripe customer ID: {$subscription->customer}");
        }
    }



    protected function handleSubscriptionDeleted($subscription)
    {
        Log::info("Handling subscription deleted: " . json_encode($subscription));

        $user = User::where('stripe_id', $subscription->customer)->first();

        if ($user) {
            $user->subscription_plan = null;
            $user->trial_ends_at = null;
            $user->subscription_ends_at = null;
            $user->save();
        } else {
            Log::error("User not found for Stripe customer ID: {$subscription->customer}");
        }
    }
}
