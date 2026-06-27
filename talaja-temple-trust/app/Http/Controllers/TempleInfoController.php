<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use App\Models\Temple;
use App\Models\TempleTiming;
use Inertia\Inertia;

class TempleInfoController extends Controller
{
    public function index()
    {
        $temple = Temple::where('is_primary', true)->first();

        $timings = TempleTiming::with('temple')
            ->active()->ordered()->get()
            ->groupBy('type')
            ->map(fn ($group) => $group->map(fn ($t) => [
                'title' => $t->localized('title'),
                'start_time' => $t->start_time?->format('H:i'),
                'end_time' => $t->end_time?->format('H:i'),
                'day_of_week' => $t->day_of_week,
                'fee' => $t->fee,
            ]));

        $festivals = Festival::active()->orderBy('start_date')->get()->map(fn ($f) => [
            'title' => $f->localized('title'),
            'description' => $f->description,
            'start_date' => $f->start_date->format('d-m-Y'),
            'end_date' => $f->end_date?->format('d-m-Y'),
            'image_path' => $f->image_path,
        ]);

        return Inertia::render('TempleInfo', [
            'temple' => $temple,
            'timings' => $timings,
            'festivals' => $festivals,
        ]);
    }
}
