<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\CustomEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    /**
     * Show the email form.
     */
    public function create()
    {
        return view('admin.send-email');
    }

    /**
     * Queue email sending to all users.
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'button_url' => 'nullable|url',
            'button_text' => 'nullable|string|max:100',
        ]);

        $users = User::all();

        foreach ($users as $index => $user) {
            // Queue each email with a slight delay to avoid mail provider throttling
            Mail::to($user->email)->later(
                now()->addSeconds($index * 2), // stagger emails by 2 seconds each
                new CustomEmail(
                    $request->subject,
                    $request->title,
                    $request->content,
                    $request->button_url,
                    $request->button_text
                )
            );
        }

        return back()->with('success', 'Emails have been queued and will be sent shortly!');
    }
}
