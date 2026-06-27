<?php

namespace App\Filament\Pages;

use App\Models\BankStatement;
use App\Models\Donation;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\RoomBooking;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Reports';

    protected static string $view = 'filament.pages.reports';

    protected static ?string $title = 'MIS Reports';

    public ?string $report = 'donation';
    public ?string $from = null;
    public ?string $to = null;

    public function form(Form $form): Form
    {
        return $form->schema([
            Select::make('report')->options([
                'donation' => 'Donation Report',
                'collection' => 'Daily Collection',
                'booking' => 'Booking & Occupancy',
                'finance' => 'Financial (Receipts/Payments)',
                'reconciliation' => 'Bank Reconciliation',
                'shop' => 'Shop Orders',
            ])->default('donation')->live(),
            DatePicker::make('from')->default(now()->startOfMonth()),
            DatePicker::make('to')->default(now()),
        ])->columns(3);
    }

    public function getSummary(): array
    {
        $from = $this->from ?? now()->startOfMonth()->toDateString();
        $to = $this->to ?? now()->toDateString();

        return [
            'total_donations' => Donation::where('status', 'success')->whereBetween('paid_at', [$from, $to])->sum('amount'),
            'donations_count' => Donation::where('status', 'success')->whereBetween('paid_at', [$from, $to])->count(),
            'total_receipts' => Receipt::whereBetween('date', [$from, $to])->sum('amount'),
            'total_payments' => Payment::whereBetween('date', [$from, $to])->sum('amount'),
            'bookings' => RoomBooking::whereBetween('check_in', [$from, $to])->count(),
            'occupancy_revenue' => RoomBooking::whereBetween('check_in', [$from, $to])->sum('amount'),
            'orders_revenue' => Order::where('payment_status', 'paid')->whereBetween('created_at', [$from, $to])->sum('total'),
        ];
    }

    public function exportCsv()
    {
        $from = $this->from ?? now()->startOfMonth()->toDateString();
        $to = $this->to ?? now()->toDateString();

        $rows = match ($this->report) {
            'donation' => Donation::where('status', 'success')->whereBetween('paid_at', [$from, $to])->get(['receipt_no', 'donor_name', 'amount', 'payment_mode', 'paid_at']),
            'collection' => Donation::where('status', 'success')->whereBetween('paid_at', [$from, $to])->get(['receipt_no', 'amount', 'payment_mode', 'paid_at']),
            'finance' => Payment::whereBetween('date', [$from, $to])->get(['voucher_no', 'payee', 'amount', 'payment_mode', 'date']),
            'reconciliation' => BankStatement::whereBetween('date', [$from, $to])->get(['date', 'description', 'debit', 'credit', 'reconciliation_status']),
            'shop' => Order::whereBetween('created_at', [$from, $to])->get(['order_no', 'customer_name', 'total', 'payment_status', 'fulfilment_status']),
            default => RoomBooking::whereBetween('check_in', [$from, $to])->get(['booking_no', 'guest_name', 'amount', 'status', 'check_in']),
        };

        $fileName = "report-{$this->report}-".now()->format('Ymd').'.csv';
        $headers = ['Content-Type' => 'text/csv'];

        $callback = function () use ($rows) {
            $fh = fopen('php://output', 'w');
            if ($rows->isNotEmpty()) {
                fputcsv($fh, array_keys($rows->first()->getAttributes()));
                foreach ($rows as $r) {
                    fputcsv($fh, $r->getAttributes());
                }
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers)->withHeaders(['Content-Disposition' => "attachment; filename={$fileName}"]);
    }
}
