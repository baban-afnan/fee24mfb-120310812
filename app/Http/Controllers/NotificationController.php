<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $searchBatchId = $request->input('search_title');
        $statusFilter  = $request->input('status');

        $query = Notification::query();

        if ($searchBatchId) {
            $query->where('title', 'like', "%{$searchBatchId}%");
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $enrollments = $query->orderByDesc('created_at')->paginate(10);

        $statusCounts = [
            'active'   => Notification::where('status', 'active')->count(),
            'inactive' => Notification::where('status', 'inactive')->count(),
        ];

        return view('notification', compact('enrollments', 'searchBatchId', 'statusFilter', 'statusCounts'));
    }

    
    /**
     * Display the specified notification.
     */
    public function show($id)
    {
        $enrollmentInfo = Notification::findOrFail($id);
        $user           = User::find($enrollmentInfo->user_id);

        $statusHistory = collect([
            [
                'status'       => $enrollmentInfo->status,
                'created_at'   => $enrollmentInfo->created_at,
                'updated_at'   => $enrollmentInfo->updated_at,
                'content'      => $enrollmentInfo->content ?? null,
                'submission_date' => $enrollmentInfo->created_at,
            ],
        ]);

        return view('notification-view', compact('enrollmentInfo', 'statusHistory', 'user'));
    }

    /**
     * Update the specified notification.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status'  => 'required|in:active,inactive',
            'content' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $notification = Notification::findOrFail($id);

            $messageParts = [];

            if ($notification->status !== $request->status) {
                $notification->status = $request->status;
                $messageParts[] = "status to {$request->status}";
            }

            if ($notification->content !== $request->content) {
                $notification->content = $request->content;
                $messageParts[] = "content";
            }

            $notification->save();

            DB::commit();

            $successMessage = $messageParts
                ? 'Successfully updated ' . implode(', ', $messageParts)
                : 'No changes were made';

             // Redirect with success message
            return redirect()
                ->route('notification.index')
                ->with('success', 'Notification Updated successfully!');
        } catch (\Exception $e) {
            // Redirect with error message if something goes wrong
            return redirect()
                ->route('notification.index')
                ->with('error', 'Failed to Update notification: ' . $e->getMessage());
        }
    }
}
