<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Send the email
        Mail::send([], [], function ($message) use ($request) {
            $message->to('waellhamwii@gmail.com')
                ->subject('New Contact Message')
                ->html("<p><strong>Name:</strong> {$request->name}</p>
                        <p><strong>Email:</strong> {$request->email}</p>
                        <p><strong>Message:</strong> {$request->message}</p>");
        });

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
