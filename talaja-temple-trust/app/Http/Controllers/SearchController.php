<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Publication;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        $results = collect();

        if (mb_strlen($q) >= 2) {
            $like = '%'.$q.'%';

            $news = News::published()->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('content', 'like', $like))
                ->limit(10)->get()->map(fn ($n) => ['type' => 'News', 'title' => $n->localized('title'), 'url' => "/news/{$n->slug}", 'excerpt' => $n->localized('excerpt')]);

            $pages = CmsPage::where('is_published', true)->where(fn ($query) => $query->where('title', 'like', $like)->orWhere('content', 'like', $like))
                ->limit(10)->get()->map(fn ($p) => ['type' => 'Page', 'title' => $p->localized('title'), 'url' => "/page/{$p->slug}", 'excerpt' => null]);

            $galleries = Gallery::active()->where('title', 'like', $like)
                ->limit(10)->get()->map(fn ($g) => ['type' => 'Gallery', 'title' => $g->localized('title'), 'url' => '/photo-gallery', 'excerpt' => null]);

            $pubs = Publication::active()->where('title', 'like', $like)
                ->limit(10)->get()->map(fn ($p) => ['type' => 'Publication', 'title' => $p->localized('title'), 'url' => '/downloads', 'excerpt' => null]);

            $results = collect()->merge($news)->merge($pages)->merge($galleries)->merge($pubs)->values();
        }

        return Inertia::render('Search', ['query' => $q, 'results' => $results]);
    }
}
