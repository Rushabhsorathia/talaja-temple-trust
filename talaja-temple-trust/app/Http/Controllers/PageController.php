<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Temple;
use App\Models\Trustee;
use Inertia\Inertia;

class PageController extends Controller
{
    public function cms(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return Inertia::render('CmsPage', [
            'page' => [
                'title' => $page->localized('title'),
                'content' => $page->localized('content'),
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
            ],
        ]);
    }

    public function about()
    {
        $temple = Temple::where('is_primary', true)->with('translation')->first();

        return Inertia::render('About', ['temple' => $temple]);
    }

    public function history()
    {
        $temple = Temple::where('is_primary', true)->with('translation')->firstOrFail();

        return Inertia::render('History', ['temple' => $temple]);
    }

    public function trustees()
    {
        $trustees = Trustee::active()->ordered()->get()->map(fn ($t) => [
            'name' => $t->name,
            'designation' => $t->localized('designation'),
            'bio' => $t->localized('bio'),
            'photo_path' => $t->photo_path,
        ]);

        return Inertia::render('Trustees', ['trustees' => $trustees]);
    }

    public function facilities()
    {
        $page = CmsPage::where('slug', 'facilities')->where('is_published', true)->first();

        return Inertia::render('Facilities', ['page' => $page]);
    }

    public function contact()
    {
        $temple = Temple::where('is_primary', true)->first();

        return Inertia::render('Contact', ['temple' => $temple]);
    }
}
