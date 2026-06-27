<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\News;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect([
            ['loc' => url('/'), 'priority' => '1.0'],
            ['loc' => url('/about-us'), 'priority' => '0.8'],
            ['loc' => url('/temple-info'), 'priority' => '0.8'],
            ['loc' => url('/photo-gallery'), 'priority' => '0.7'],
            ['loc' => url('/video-gallery'), 'priority' => '0.7'],
            ['loc' => url('/news-and-updates'), 'priority' => '0.9'],
            ['loc' => url('/contact-us'), 'priority' => '0.6'],
            ['loc' => url('/faqs'), 'priority' => '0.5'],
        ]);

        foreach (News::published()->limit(200)->get() as $n) {
            $urls[] = ['loc' => url("/view-news/{$n->slug}"), 'priority' => '0.6'];
        }
        foreach (CmsPage::where('is_published', true)->get() as $p) {
            $urls[] = ['loc' => url("/page/{$p->slug}"), 'priority' => '0.6'];
        }

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
