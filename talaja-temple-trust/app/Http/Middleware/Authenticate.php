<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Redirect guests to the devotee OTP login (instead of the email/password
     * Breeze login). The intended URL is preserved by the framework so that,
     * after a successful login, the user returns to the page they came from
     * (e.g. /bookings when they clicked "Book Now").
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Admin/filament requests still go to the admin login.
        if ($request->is('admin*')) {
            return '/admin/login';
        }

        return '/otp/login';
    }
}
