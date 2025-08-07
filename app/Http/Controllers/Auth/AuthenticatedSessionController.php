<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    protected int $maxAttempts = 5;

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->input('email');
        $throttleKey = Str::lower($email) . '|' . $request->ip();

        // Check if user exists
        $user = User::where('email', $email)->first();

        // If user is suspended, block immediately
        if ($user && $user->status === 'suspended') {
            return back()->withErrors([
                'email' => 'Your account has been suspended due to multiple failed login attempts.',
            ]);
        }

        // Try authentication
        try {
            $request->authenticate();
        } catch (\Throwable $e) {
            // Count failed login attempts
            RateLimiter::hit($throttleKey);

            // Suspend user if they exceeded allowed attempts
            if ($user && RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
                $user->update(['status' => 'suspended']);
            }

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Allow only active users
        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account is not active.',
            ]);
        }

        // Allow only admin users
        if ($user->role !== 'admin') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Only admin users are allowed to log in.',
            ]);
        }

        // Successful login — reset rate limiter
        RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
