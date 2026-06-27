<?php

namespace App\Http\Controllers;

use App\Models\LiveDarshanConfig;
use Inertia\Inertia;

class LiveDarshanController extends Controller
{
    public function __invoke()
    {
        $config = LiveDarshanConfig::first();

        return Inertia::render('LiveDarshan', [
            'streamUrl' => $config?->stream_url,
            'isLive' => (bool) $config?->is_live,
        ]);
    }
}
