<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function photos(Request $request)
    {
        $categories = Gallery::active()->pluck('category')->unique()->filter()->values();

        $photos = Gallery::active()
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->ordered()->paginate(24)->withQueryString();

        return Inertia::render('PhotoGallery', [
            'photos' => $photos->through(fn ($g) => [
                'id' => $g->id,
                'title' => $g->localized('title'),
                'image_path' => $g->image_path,
                'alt_text' => $g->alt_text,
                'category' => $g->category,
            ]),
            'categories' => $categories,
            'selectedCategory' => $request->category,
        ]);
    }

    public function videos()
    {
        $videos = Video::active()->ordered()->get()->map(fn ($v) => [
            'id' => $v->id,
            'title' => $v->localized('title'),
            'embed_url' => $v->embed_url,
            'thumbnail_path' => $v->thumbnail_path,
            'category' => $v->category,
        ]);

        return Inertia::render('VideoGallery', ['videos' => $videos]);
    }
}
