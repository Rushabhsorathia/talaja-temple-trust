<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Temple;
use App\Models\Trustee;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __invoke()
    {
        $temple = Temple::where('is_primary', true)->with('translation')->first();

        $data = cache()->remember('home.data', now()->addMinutes(15), function () {
            return [
                'banners' => Banner::live()->get(),
                'latestNews' => News::published()->limit(6)->get()->map(fn ($n) => [
                    'id' => $n->id,
                    'slug' => $n->slug,
                    'title' => $n->localized('title'),
                    'excerpt' => $n->localized('excerpt'),
                    'image_path' => $n->image_path,
                    'published_at' => $n->published_at?->format('d-m-Y'),
                ]),
                'galleryPreview' => Gallery::active()->ordered()->limit(6)->get(),
                'trustees' => Trustee::active()->ordered()->limit(4)->get(),
            ];
        });

        // Hero slides from CMS page sections take precedence over the
        // dedicated HomeSlide table, but we keep both for backwards compat.
        $cmsSlides = cache()->remember('home.cms_slides', 30, function () {
            $home = \App\Models\Page::with(['sections' => fn ($q) => $q->ordered()])->where('slug', 'home')->first();
            $heroSection = $home?->sections->firstWhere('section_key', 'hero');
            if (! $heroSection || ! isset($heroSection->data['slides'])) {
                return null;
            }

            return collect($heroSection->data['slides'])->map(fn ($s) => [
                'img'          => isset($s['image_path']) ? asset('storage/'.$s['image_path']) : null,
                'title'        => $s['title'] ?? null,
                'sub'          => $s['subtitle'] ?? null,
                'tag'          => $s['tag'] ?? null,
                'button_label' => $s['button_label'] ?? null,
                'button_href'  => $s['button_href'] ?? null,
            ])->all();
        });

        return Inertia::render('Home', array_merge($data, [
            'temple' => $temple,
            'cmsHeroSlides' => $cmsSlides,
        ]));
    }
}
