<?php

namespace App\Services;

use App\Models\Donation;
use App\Models\DonationReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptService
{
    public function generate(Donation $donation, string $type = 'general'): DonationReceipt
    {
        $receiptNo = $type === '80g'
            ? '80G-'.date('Y').'-'.str_pad((string) DonationReceipt::where('receipt_type', '80g')->count() + 1, 5, '0', STR_PAD_LEFT)
            : 'DON-'.date('Y').'-'.str_pad((string) DonationReceipt::where('receipt_type', 'general')->count() + 1, 5, '0', STR_PAD_LEFT);

        $existing = $donation->receipts()->where('receipt_type', $type)->where('is_void', false)->first();
        if ($existing && $existing->pdf_path) {
            return $existing;
        }

        $pdf = Pdf::loadView('receipts.donation', [
            'donation' => $donation,
            'receiptNo' => $receiptNo,
            'is80G' => $type === '80g',
            'date' => now()->format('d M Y'),
        ])->setPaper('a4', 'portrait');

        $path = "receipts/{$receiptNo}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        return DonationReceipt::updateOrCreate(
            ['donation_id' => $donation->id, 'receipt_type' => $type],
            ['receipt_no' => $receiptNo, 'pdf_path' => $path, 'is_void' => false]
        );
    }
}
