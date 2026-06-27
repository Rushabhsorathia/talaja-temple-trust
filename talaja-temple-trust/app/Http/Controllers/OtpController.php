<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OtpController extends Controller
{
    public function show()
    {
        // The unified login page handles OTP. Redirect there (preset OTP tab).
        return Inertia::render('Auth/Login', [
            'canResetPassword' => \Illuminate\Support\Facades\Route::has('password.request'),
            'loginPrompt' => session()->has('url.intended') ? 'Please log in to continue.' : null,
            'presetMethod' => 'otp',
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'mobile' => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
        ]);

        $otp = random_int(100000, 999999);
        $key = "otp:{$validated['mobile']}";

        Cache::put($key, $otp, now()->addMinutes(5));

        // TODO: dispatch real SMS via MSG91 gateway (M5-T2). For now log it.
        logger("[OTP] Mobile {$validated['mobile']}: {$otp}");

        return back()->with('status', 'OTP sent (check logs in dev mode).');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'mobile' => ['required', 'string'],
            'otp' => ['required', 'string', 'digits:6'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        $key = "otp:{$validated['mobile']}";
        $stored = Cache::get($key);

        if (! $stored || (string) $stored !== (string) $validated['otp']) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.'])->withInput(['mobile' => $validated['mobile']]);
        }

        Cache::forget($key);

        $user = User::firstOrCreate(
            ['mobile' => $validated['mobile']],
            [
                'name' => $validated['name'] ?? 'Devotee',
                'email' => "devotee_{$validated['mobile']}@talajatemple.org",
                'password' => Hash::make(Str::random(32)),
                'type' => 'devotee',
                'is_active' => true,
                'mobile_verified_at' => now(),
            ]
        );

        Auth::login($user, true);
        $user->update(['last_login_at' => now(), 'last_login_ip' => $request->ip()]);

        return redirect()->intended('/dashboard');
    }
}
