<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $rateLimiterKey = Str::lower($request->string('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            return back()->withErrors([
                'email' => 'Too many login attempts. Please try again later.',
            ])->onlyInput('email');
        }

        $credentials = $request->only('email', 'password');
        $remember = (bool) $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($rateLimiterKey, 60);
            AuditLogger::log('admin.login.failed', null, [
                'email' => $request->string('email')->value(),
            ], $request);

            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->onlyInput('email');
        }

        RateLimiter::clear($rateLimiterKey);
        $request->session()->regenerate();

        if (! $request->user()?->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'You are not authorized for admin access.',
            ]);
        }

        AuditLogger::log('admin.login.success', $request->user(), [], $request);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(): RedirectResponse
    {
        AuditLogger::log('admin.logout');

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
