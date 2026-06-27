<x-filament-panels::page>
    <form wire:submit="exportCsv">
        {{ $this->form }}
    </form>

    @php $s = $this->getSummary(); @endphp

    <x-filament::section>
        <x-slot name="heading">Summary ({{ $from ?? 'start' }} → {{ $to ?? 'today' }})</x-slot>
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-2xl font-bold text-orange-600">₹{{ number_format((float) $s['total_donations'], 0) }}</p>
                <p class="text-sm text-gray-500">Donations ({{ $s['donations_count'] }})</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-2xl font-bold text-orange-600">₹{{ number_format((float) $s['total_receipts'], 0) }}</p>
                <p class="text-sm text-gray-500">Total Receipts</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-2xl font-bold text-orange-600">₹{{ number_format((float) $s['total_payments'], 0) }}</p>
                <p class="text-sm text-gray-500">Total Payments</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-2xl font-bold text-orange-600">₹{{ number_format((float) ($s['occupancy_revenue'] + $s['orders_revenue']), 0) }}</p>
                <p class="text-sm text-gray-500">Bookings + Shop</p>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <p class="text-2xl font-bold text-orange-600">{{ $s['bookings'] }}</p>
                <p class="text-sm text-gray-500">Room Bookings</p>
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Export</x-slot>
        <x-slot name="actions">
            <x-filament::button wire:click="exportCsv" icon="heroicon-o-arrow-down-tray">Export CSV</x-filament::button>
        </x-slot>
        <p class="text-sm text-gray-500">Download the selected report as CSV for the chosen date range.</p>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-2">Report</th>
                        <th class="p-2">Records in range</th>
                    </tr>
                </thead>
                <tbody>
                    @php $counts = ['donation'=>'Donations','collection'=>'Daily Collection','booking'=>'Bookings & Occupancy','finance'=>'Financial (Payments)','reconciliation'=>'Bank Reconciliation','shop'=>'Shop Orders']; @endphp
                    @foreach ($counts as $key => $label)
                        <tr class="border-b">
                            <td class="p-2">{{ $label }}</td>
                            <td class="p-2 text-gray-500">{{ $key }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
