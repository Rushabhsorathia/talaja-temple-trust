<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected ?\Razorpay\Api\Api $api = null;

    public function api(): \Razorpay\Api\Api
    {
        if ($this->api === null) {
            $this->api = new \Razorpay\Api\Api(
                config('services.razorpay.key', env('RAZORPAY_KEY')),
                config('services.razorpay.secret', env('RAZORPAY_SECRET'))
            );
        }

        return $this->api;
    }

    public function isConfigured(): bool
    {
        return filled(env('RAZORPAY_KEY')) && filled(env('RAZORPAY_SECRET'));
    }

    public function createOrder(int $amountPaisa, string $receipt, array $notes = []): array
    {
        if (! $this->isConfigured()) {
            // Sandbox-less dev fallback: return a fake order id.
            Log::info('[Razorpay] Not configured — returning mock order.');

            return ['id' => 'order_mock_'.uniqid(), 'amount' => $amountPaisa, 'receipt' => $receipt];
        }

        $order = $this->api()->order->create([
            'amount' => $amountPaisa,
            'currency' => 'INR',
            'receipt' => $receipt,
            'notes' => $notes,
        ]);

        return ['id' => $order['id'], 'amount' => $order['amount'], 'receipt' => $receipt];
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        if (! $this->isConfigured()) {
            return true; // dev fallback
        }

        try {
            $this->api()->utility->verifyPaymentSignature([
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('[Razorpay] Signature verification failed: '.$e->getMessage());

            return false;
        }
    }

    public function publicKey(): string
    {
        return (string) env('RAZORPAY_KEY');
    }
}
