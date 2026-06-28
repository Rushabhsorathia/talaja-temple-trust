<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountSettingController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Account/Settings', [
            'guideMode' => (bool) $request->user()->guide_mode,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'guide_mode' => ['boolean'],
        ]);

        $request->user()->update([
            'guide_mode' => $validated['guide_mode'] ?? false,
        ]);

        return back()->with('success', 'Settings updated.');
    }
}
