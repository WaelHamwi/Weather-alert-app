<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SubscriptionCancel extends Component
{
    public function cancel()
    {
        $user = Auth::user();
        $userId = Auth::id();
        $user = User::where('id', $userId)->first();
        if ($user && $user->subscription_plan) {
            $user->subscription_ends_at = now();
            $user->subscription_plan = null;
            $user->save();
            return redirect()->route('subscribe.cancel')->with('status', 'Subscription canceled successfully');
        }
        return redirect()->route('subscribe.cancel')->with('status', 'You have no active subscription');
    }

    public function render()
    {
        return view('livewire.subscription-cancel');
    }
}
