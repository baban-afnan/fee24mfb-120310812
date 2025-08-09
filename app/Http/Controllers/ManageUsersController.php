<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users001;
use App\Models\User;
use App\Models\Wallet;
use App\Models\VirtualAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManageUsersController extends Controller
{
    public function index(Request $request)
    {
        $searchemail = $request->input('search_email');
        $statusFilter = $request->input('status');

        $query = users001::query();

        if ($searchemail) {
            $query->where('email', 'like', "%$searchemail%");
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $enrollments = $query->orderByDesc('created_at')->paginate(10);

        $statusCounts = [
            'inactive' => users001::where('status', 'inactive')->count(),
            'suspended' => users001::where('status', 'suspended')->count(),
            'active' => users001::where('status', 'active')->count(),
        ];

        return view('users', compact('enrollments', 'searchemail', 'statusFilter', 'statusCounts'));
    }

    public function show($id)
    {
        $user = users001::findOrFail($id);
        $agent = $user->user_id ? User::find($user->user_id) : null;
        $wallet = Wallet::where('user_id', $id)->first();
        $virtualAccount = VirtualAccount::where('user_id', $id)->first();
        $transactions = Transaction::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $statusHistory = collect([
            [
                'status' => $user->status,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);

        return view('users-view', compact(
            'user',
            'agent',
            'wallet',
            'virtualAccount',
            'transactions',
            'statusHistory'
        ));
    }

   public function update(Request $request, $id)
{
    $request->validate([
        'status' => 'nullable|in:inactive,suspended,active',
        'role' => 'nullable|in:user,agent,admin',
        'wallet_status' => 'nullable|in:active,inactive,suspended,closed',
        'comment' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();

    try {
        $user = users001::findOrFail($id);
        $updates = [];
        $messageParts = [];

        // Update user status if provided
        if ($request->filled('status')) {
            $user->status = $request->status;
            $messageParts[] = 'status to ' . $request->status;
        }

        // Update user role if provided
        if ($request->filled('role')) {
            $user->role = $request->role;
            $messageParts[] = 'role to ' . $request->role;
        }

        // Update comment if provided
        if ($request->filled('comment')) {
            $user->comment = $request->comment;
        }

        // Save user updates if any
        if ($user->isDirty()) {
            $user->save();
        }

        // Update wallet status if provided
        if ($request->filled('status')) {
            Wallet::updateOrCreate(
                ['user_id' => $id],
                ['status' => $request->status]
            );
            $messageParts[] = 'wallet status to ' . $request->status;
        }

        DB::commit();

        $successMessage = !empty($messageParts) 
            ? 'Successfully updated ' . implode(', ', $messageParts)
            : 'No changes were made';

        return redirect()->route('users.index')->with('successMessage', $successMessage);
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('users.index')->with('errorMessage', 'Failed to update: ' . $e->getMessage());
    }
}
}