<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Publication;
use Inertia\Inertia;

class InfoController extends Controller
{
    public function faqs()
    {
        $faqs = Faq::active()->ordered()->get()->map(fn ($f) => [
            'question' => $f->localized('question'),
            'answer' => $f->localized('answer'),
            'category' => $f->category,
        ]);

        $grouped = $faqs->groupBy('category');

        return Inertia::render('Faqs', ['faqs' => $grouped]);
    }

    public function downloads()
    {
        $publications = Publication::active()->orderByDesc('id')->get()->map(fn ($p) => [
            'id' => $p->id,
            'title' => $p->localized('title'),
            'file_path' => $p->file_path,
            'category' => $p->category,
        ]);

        return Inertia::render('Downloads', ['publications' => $publications]);
    }
}
