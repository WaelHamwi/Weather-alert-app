<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($notificationId)
    {
        $user = Auth::user();

        $notification = $user->notifications()->find($notificationId); 
        if ($notification) {
            $notification->markAsRead(); 
        }

        return redirect()->back(); 
    }

    public function index()
    {
        $user = Auth::user(); 
        $notifications = $user->notifications; 
        return view('notifications.index', compact('notifications')); 
    }
}
