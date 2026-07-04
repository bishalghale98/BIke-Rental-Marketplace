@extends('layouts.company')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Company Dashboard</h1>
        <p class="mt-1 text-zinc-500 dark:text-zinc-400">Manage your bike listings and rentals.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <x-stat-card label="Total Revenue" value="NPR {{ number_format($totalRevenue, 0) }}" color="primary"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        <x-stat-card label="Total Bikes" :value="$totalBikes" color="success"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>' />
        <x-stat-card label="Available Bikes" :value="$availableBikes" color="warning"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        <x-stat-card label="Active Bookings" :value="$activeBookings" color="blue"
            icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>' />
    </div>

    {{-- Charts + Top Bikes --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Revenue (Last 30 Days)</h3>
            <div id="revenueChart" class="w-full" style="min-height: 300px;"></div>
        </div>

        {{-- Top Bikes --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-5 sm:p-6">
            <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Top Performing Bikes</h3>
            @if ($topBikes->isEmpty())
                <div class="flex flex-col items-center py-8">
                    <svg class="w-10 h-10 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">No booking data yet.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($topBikes as $item)
                        <div class="flex items-center gap-3">
                            @if ($item->bike->images->where('is_primary', true)->first())
                                <img src="{{ asset('storage/' . $item->bike->images->where('is_primary', true)->first()->image_path) }}" alt="" class="w-10 h-10 rounded-xl object-cover">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $item->bike->name }}</p>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $item->total_bookings }} bookings</p>
                            </div>
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">NPR {{ number_format($item->total_revenue, 0) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Bookings --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">Recent Bookings</h2>
            <a href="{{ route('company.bookings.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">View all</a>
        </div>
        @if ($recentBookings->isEmpty())
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">No bookings received yet.</p>
            </div>
        @else
            <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                            <tr>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Booking</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Bike</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Customer</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Amount</th>
                                <th class="px-4 py-3.5 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            @foreach ($recentBookings as $booking)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                    <td class="px-4 py-3.5">
                                        <a href="{{ route('company.bookings.show', $booking) }}" class="font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">{{ $booking->booking_number }}</a>
                                    </td>
                                    <td class="px-4 py-3.5 text-zinc-600 dark:text-zinc-400">{{ $booking->bike->name }}</td>
                                    <td class="px-4 py-3.5 text-zinc-600 dark:text-zinc-400">{{ $booking->customer?->user?->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3.5 font-medium text-zinc-900 dark:text-zinc-100">NPR {{ number_format($booking->total_amount, 0) }}</td>
                                    <td class="px-4 py-3.5">
                                        <x-badge :variant="$booking->status->value === 'completed' ? 'green' : ($booking->status->value === 'cancelled' ? 'gray' : ($booking->status->value === 'ongoing' ? 'blue' : ($booking->status->value === 'confirmed' ? 'yellow' : 'gray')))">{{ ucfirst($booking->status->value) }}</x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dates = @json($dates->pluck('date'));
    const revenues = @json($dates->pluck('revenue'));

    const options = {
        series: [{
            name: 'Revenue',
            data: revenues,
        }],
        chart: {
            type: 'area',
            height: 300,
            fontFamily: 'inherit',
            toolbar: { show: false },
            zoom: { enabled: false },
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2, colors: ['#2563eb'] },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.25,
                opacityTo: 0,
                stops: [0, 100],
            },
        },
        xaxis: {
            categories: dates,
            labels: {
                style: { colors: '#71717a', fontSize: '11px' },
                rotate: -45,
                rotateAlways: true,
                hideOverlappingLabels: true,
                formatter: function (val) {
                    const d = new Date(val + 'T00:00:00');
                    return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                },
            },
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: '#71717a', fontSize: '11px' },
                formatter: function (val) { return 'NPR ' + val.toFixed(0); },
            },
        },
        grid: { borderColor: '#e4e4e7', strokeDashArray: 4 },
        tooltip: {
            y: {
                formatter: function (val) { return 'NPR ' + val.toFixed(2); },
            },
        },
        colors: ['#2563eb'],
    };

    if (document.getElementById('revenueChart')) {
        const chart = new ApexCharts(document.getElementById('revenueChart'), options);
        chart.render();
    }
});
</script>
@endpush
