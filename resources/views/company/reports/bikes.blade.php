@extends('layouts.company')
@section('title', 'Bike Performance Report')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Bike Performance</h1>
        <p class="mt-1 text-gray-600">Per-bike booking and revenue stats.</p>
    </div>
    <a href="{{ route('company.reports.index') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; All Reports</a>
</div>

<form method="GET" class="mt-6 flex items-end gap-4">
    <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">From</label>
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
    </div>
    <div class="space-y-1">
        <label class="text-sm font-medium text-gray-700">To</label>
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
    </div>
    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800">Filter</button>
    <a href="{{ route('company.reports.bikes.export', request()->only(['from', 'to'])) }}" class="px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">Download CSV</a>
</form>

<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
    <x-stat-card label="Total Bookings" :value="$summary?->total_bookings ?? 0" />
    <x-stat-card label="Total Revenue" value="NPR {{ number_format($summary?->total_revenue ?? 0, 2) }}" />
</div>

<div class="mt-8 overflow-x-auto">
    <x-card>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-left text-gray-500">
                    <th class="pb-3 font-medium">Bike</th>
                    <th class="pb-3 font-medium">Brand / Model</th>
                    <th class="pb-3 font-medium">Bookings</th>
                    <th class="pb-3 font-medium">Revenue</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($bikes as $bike)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                @if ($bike->images->first())
                                    <img src="{{ asset('storage/' . $bike->images->first()->image_path) }}" class="w-10 h-10 rounded-lg object-cover">
                                @endif
                                <span class="font-medium text-gray-900">{{ $bike->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-gray-600">{{ $bike->brand }} {{ $bike->model }}</td>
                        <td class="py-3 text-gray-900">{{ $bike->total_bookings }}</td>
                        <td class="py-3 font-medium text-gray-900">NPR {{ number_format($bike->total_revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">No bikes with activity in this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
</div>
@endsection
