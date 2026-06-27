<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::published()
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->paginate(9)->withQueryString();

        return Inertia::render('News/Index', [
            'news' => $news->through(fn ($n) => [
                'id' => $n->id,
                'slug' => $n->slug,
                'title' => $n->localized('title'),
                'excerpt' => $n->localized('excerpt'),
                'image_path' => $n->image_path,
                'category' => $n->category,
                'published_at' => $n->published_at?->format('d-m-Y'),
            ]),
            'categories' => News::published()->pluck('category')->unique()->filter()->values(),
            'selectedCategory' => $request->category,
        ]);
    }

    public function show(string $slug)
    {
        $news = News::where('slug', $slug)->published()->firstOrFail();

        $related = News::published()->where('id', '!=', $news->id)->limit(3)->get();

        return Inertia::render('News/Show', [
            'news' => [
                'id' => $news->id,
                'title' => $news->localized('title'),
                'content' => $news->localized('content'),
                'image_path' => $news->image_path,
                'category' => $news->category,
                'tags' => $news->tags,
                'published_at' => $news->published_at?->format('d-m-Y'),
                'meta_title' => $news->meta_title,
                'meta_description' => $news->meta_description,
            ],
            'related' => $related->map(fn ($n) => [
                'slug' => $n->slug,
                'title' => $n->localized('title'),
                'image_path' => $n->image_path,
                'published_at' => $n->published_at?->format('d-m-Y'),
            ]),
        ]);
    }
}
