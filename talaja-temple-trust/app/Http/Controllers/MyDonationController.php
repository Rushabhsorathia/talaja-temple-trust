<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MyDonationController extends Controller
{
    public function __construct(protected ReceiptService $receipts) {}

    public function index(Request $request)
    {
        $donations = Donation::with(['category', 'receipts'])
            ->where('donor_id', $request->user()->id)
            ->orderByDesc('id')->get()->map(fn ($d) => [
                'id' => $d->id,
                'receipt_no' => $d->receipt_no,
                'amount' => $d->amount,
                'category' => $d->category?->name,
                'status' => $d->status,
                'is_80g' => $d->is_80g,
                'paid_at' => $d->paid_at?->format('d-m-Y'),
                'receipts' => $d->receipts->map(fn ($r) => ['type' => $r->receipt_type, 'url' => asset("storage/{$r->pdf_path}")]),
            ]);

        $total = $donations->where('status', 'success')->sum(fn ($d) => (float) $d['amount']);

        return Inertia::render('Donate/My', ['donations' => $donations, 'total' => $total]);
    }
}
