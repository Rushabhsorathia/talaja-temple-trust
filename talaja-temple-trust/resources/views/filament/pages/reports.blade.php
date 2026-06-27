<x-filament-panels::page>
    <form wire:submit="exportCsv">
        {{ $this->form }}
    </form>

    <x-filament::section>
        <x-slot name="heading">Summary ({{ $from ?? 'start' }} → {{ $to ?? 'today' }})</x-slot>
        @php $s = $this->getSummary(); @endphp
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <x-filament::stat value="₹{{ number_format((float) $s['total_donations'], 0) }}" description="Donations ({{ $s['donations_count'] }})" />
            <x-filament::stat value="₹{{ number_format((float) $s['total_receipts'], 0) }}" description="Total Receipts" />
            <x-filament::stat value="₹{{ number_format((float) $s['total_payments'], 0) }}" description="Total Payments" />
            <x-filament::stat value="₹{{ number_format((float) ($s['occupancy_revenue'] + $s['orders_revenue']), 0) }}" description="Bookings + Shop" />
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">Export</x-slot>
        <x-slot name="actions">
            <x-filament::button wire:click="exportCsv" icon="heroicon-o-arrow-down-tray">Export CSV</x-filament::button>
        </x-slot>
        <p class="text-sm text-gray-500">Download the selected report as CSV for the chosen date range.</p>
    </x-filament::section>
</x-filament-panels::page>
