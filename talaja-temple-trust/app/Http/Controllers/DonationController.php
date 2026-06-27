<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Services\NotificationService;
use App\Services\ReceiptService;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DonationController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpay,
        protected ReceiptService $receipts,
        protected NotificationService $notifications,
    ) {}

    public function index()
    {
        $categories = DonationCategory::active()->orderBy('sort_order')->get();

        $slabs = [51, 101, 251, 501, 1001, 5001];

        return Inertia::render('Donate/Index', [
            'categories' => $categories,
            'slabs' => $slabs,
            'razorpayKey' => $this->razorpay->publicKey(),
            'configured' => $this->razorpay->isConfigured(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donation_category_id' => ['nullable', 'exists:donation_categories,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'is_80g' => ['boolean'],
            'donor_name' => ['nullable', 'string', 'max:120'],
            'donor_email' => ['nullable', 'email'],
            'donor_mobile' => ['nullable', 'string', 'max:20'],
            'donor_pan' => ['nullable', 'string', 'max:10'],
            'donor_address' => ['nullable', 'string', 'max:1000'],
            'is_anonymous' => ['boolean'],
            'note' => ['nullable', 'string'],
        ]);

        $receiptNo = 'DON-PEND-'.strtoupper(uniqid());

        $donation = Donation::create(array_merge($validated, [
            'receipt_no' => $receiptNo,
            'donor_id' => $request->user()?->id,
            'currency' => 'INR',
            'payment_mode' => 'online',
            'status' => 'pending',
            'gateway' => 'razorpay',
            'donation_category_id' => $validated['donation_category_id'] ?? DonationCategory::first()?->id,
        ]));

        $order = $this->razorpay->createOrder(
            (int) round($donation->amount * 100),
            $donation->receipt_no,
            ['donation_id' => (string) $donation->id]
        );

        $donation->update(['gateway_transaction_id' => $order['id']]);

        return Inertia::render('Donate/Checkout', [
            'donation' => $donation->fresh(),
            'orderId' => $order['id'],
            'amountPaisa' => $order['amount'],
            'razorpayKey' => $this->razorpay->publicKey(),
            'configured' => $this->razorpay->isConfigured(),
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'donation_id' => ['required', 'exists:donations,id'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $donation = Donation::findOrFail($validated['donation_id']);

        $ok = $this->razorpay->verifySignature(
            $validated['razorpay_order_id'],
            $validated['razorpay_payment_id'],
            $validated['razorpay_signature']
        );

        if (! $ok) {
            $donation->update(['status' => 'failed']);

            return redirect()->route('donate.index')->with('error', 'Payment verification failed.');
        }

        $donation->update([
            'status' => 'success',
            'gateway_transaction_id' => $validated['razorpay_payment_id'],
            'paid_at' => now(),
        ]);

        $type = $donation->is_80g && $donation->category?->is_80g_eligible ? '80g' : 'general';
        $receipt = $this->receipts->generate($donation, $type);

        // Notify donor (SMS + email if provided)
        $vars = ['amount' => $donation->amount, 'receipt' => $receipt->receipt_no, 'name' => $donation->donor_name ?: 'Devotee'];
        if ($donation->donor_mobile) {
            $this->notifications->sendTemplate('donation_success', $donation->donor_mobile, $vars);
        }
        if ($donation->donor_email) {
            $this->notifications->sendEmail($donation->donor_email, 'Donation Received - '.$receipt->receipt_no, "Thank you {$vars['name']}. We have received your donation of ₹{$donation->amount}. Receipt no: {$receipt->receipt_no}.");
        }

        return Inertia::render('Donate/Success', [
            'donation' => $donation->fresh(),
            'receiptUrl' => asset("storage/{$receipt->pdf_path}"),
        ]);
    }

    public function qr(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'note' => ['nullable', 'string', 'max:80'],
        ]);

        // UPI deep-link QR payload. Replace VPA with trust's UPI ID.
        $vpa = env('UPI_VPA', 'talajatemple@upi');
        $name = urlencode(env('UPI_NAME', 'Talaja Temple Trust'));
        $note = urlencode($validated['note'] ?? 'Donation');
        $upiUrl = "upi://pay?pa={$vpa}&pn={$name}&am={$validated['amount']}&cu=INR&tn={$note}";

        return response()->json(['upiUrl' => $upiUrl, 'amount' => $validated['amount']]);
    }
}
