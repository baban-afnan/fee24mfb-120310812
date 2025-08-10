<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationAdd extends Controller
{
    /**
     * Store a newly created notification in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'link'    => 'nullable|url',
                'status'  => 'required|in:active,inactive',
            ]);

            $imagePath = null;

            // Handle image upload if present
            if ($request->hasFile('image')) {
                $path      = $request->file('image')->store('notifications', 'public');
                $imagePath = asset('storage/' . $path);
            }

            // Create notification
            Notification::create([
                'title'   => $validated['title'],
                'content' => $validated['content'],
                'image'   => $imagePath,
                'link'    => $validated['link'] ?? null,
                'status'  => $validated['status'],
            ]);

            // Redirect with success message
            return redirect()
                ->route('notification.index')
                ->with('success', 'Notification created successfully!');
        } catch (\Exception $e) {
            // Redirect with error message if something goes wrong
            return redirect()
                ->route('notification.index')
                ->with('error', 'Failed to create notification: ' . $e->getMessage());
        }
    }
}
