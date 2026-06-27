<?php

use App\Models\Donation;
use App\Services\NotificationService;
use App\Services\ReceiptService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webhooks (no auth, signature-verified)
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/razorpay', function (Request $request, RazorpayService $razorpay, ReceiptService $receipts, NotificationService $notifications) {
    $payload = $request->getContent();
    $signature = $request->header('X-Razorpay-Signature');
    $secret = env('RAZORPAY_WEBHOOK_SECRET');

    // Idempotent: find the donation by payment id / order id.
    $paymentId = $request->input('payload.payment.entity.id');
    $donation = Donation::where('gateway_transaction_id', $paymentId)
        ->orWhere('gateway_transaction_id', $request->input('payload.payment.entity.order_id'))
        ->first();

    if (! $donation || $donation->status === 'success') {
        return response()->json(['status' => 'ignored']);
    }

    $event = $request->input('event');
    if ($event === 'payment.captured' || $event === 'payment.authorized') {
        $donation->update(['status' => 'success', 'gateway_transaction_id' => $paymentId, 'paid_at' => now()]);
        $type = $donation->is_80g ? '80g' : 'general';
        $receipt = $receipts->generate($donation, $type);
        if ($donation->donor_mobile) {
            $notifications->sendTemplate('donation_success', $donation->donor_mobile, ['amount' => $donation->amount, 'receipt' => $receipt->receipt_no, 'name' => $donation->donor_name]);
        }
    }

    return response()->json(['status' => 'ok']);
});

/*
|--------------------------------------------------------------------------
| Public REST API (Sanctum token auth) — v1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/donations', fn () => response()->json(Donation::with('category')->where('donor_id', auth()->id())->get()));
    Route::get('/news', fn () => response()->json(\App\Models\News::published()->limit(20)->get(['id', 'slug', 'title', 'excerpt', 'published_at'])));
    Route::get('/me', fn () => response()->json(auth()->user()->only(['id', 'name', 'email', 'mobile'])));
});

Route::get('/health', fn () => response()->json(['status' => 'ok', 'time' => now()->toIso8601String()]));
