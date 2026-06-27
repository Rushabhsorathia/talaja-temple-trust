<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #333; margin: 0; padding: 40px; }
        .header { text-align: center; border-bottom: 3px double #c74808; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { color: #9c2d2d; margin: 0; font-size: 22px; }
        .header p { margin: 4px 0; color: #666; font-size: 12px; }
        .badge { display: inline-block; background: #ffefd4; color: #c74808; padding: 4px 12px; border-radius: 12px; font-weight: bold; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 8px 4px; border-bottom: 1px solid #eee; font-size: 13px; }
        td.label { color: #888; width: 35%; }
        .amount-box { background: #fff8ed; border: 1px solid #ffc070; border-radius: 8px; padding: 16px; text-align: center; margin: 20px 0; }
        .amount-box .amt { font-size: 28px; color: #c74808; font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 12px; }
        .sig { margin-top: 50px; text-align: right; }
        .sig span { display: block; border-top: 1px solid #333; width: 180px; margin-left: auto; padding-top: 4px; font-size: 12px; }
        @if($is80G)
        .g80 { font-size: 10px; color: #555; margin-top: 16px; line-height: 1.5; }
        @endif
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Talaja, Bhavnagar, Gujarat</p>
        <span class="badge">{{ $is80G ? '80G Tax Exemption Receipt' : 'Donation Receipt' }}</span>
    </div>

    <table>
        <tr><td class="label">Receipt No.</td><td>{{ $receiptNo }}</td></tr>
        <tr><td class="label">Date</td><td>{{ $date }}</td></tr>
        <tr><td class="label">Donor Name</td><td>{{ $donation->donor_name ?: ($donation->donor?->name ?? '-') }}</td></tr>
        @if($is80G)
        <tr><td class="label">Donor PAN</td><td>{{ $donation->donor_pan ?: ($donation->donor?->pan ?? '-') }}</td></tr>
        @endif
        <tr><td class="label">Mobile</td><td>{{ $donation->donor_mobile ?: ($donation->donor?->mobile ?? '-') }}</td></tr>
        <tr><td class="label">Category</td><td>{{ $donation->category?->name ?? 'General' }}</td></tr>
        <tr><td class="label">Payment Mode</td><td>{{ ucfirst($donation->payment_mode) }}</td></tr>
        <tr><td class="label">Transaction ID</td><td>{{ $donation->gateway_transaction_id ?: '-' }}</td></tr>
    </table>

    <div class="amount-box">
        <div class="amt">₹ {{ number_format((float) $donation->amount, 2) }}</div>
        <div>Received with thanks</div>
    </div>

    @if($is80G)
    <div class="g80">
        This donation is eligible for deduction under Section 80G of the Income Tax Act, 1961.
        Registration No.: __________ | Validity: AY 2024-25 onwards.
        This is a computer-generated receipt and does not require a physical signature.
    </div>
    @endif

    <div class="sig"><span>Authorised Signatory</span></div>

    <div class="footer">
        Thank you for your generous contribution. · {{ config('app.name') }} · This is a system-generated document.
    </div>
</body>
</html>
