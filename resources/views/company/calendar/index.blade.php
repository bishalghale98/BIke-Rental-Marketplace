@extends('layouts.company')
@section('title', 'Calendar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Booking Calendar</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('company.calendar', ['month' => $month->copy()->subMonth()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">&larr; Prev</a>
            <span class="text-sm font-medium text-gray-700 w-32 text-center">{{ $month->format('F Y') }}</span>
            <a href="{{ route('company.calendar', ['month' => $month->copy()->addMonth()->format('Y-m-d')]) }}" class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">Next &rarr;</a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-7 border-b border-gray-200">
            @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase text-center bg-gray-50">{{ $dayName }}</div>
            @endforeach
        </div>
        @foreach ($weeks as $week)
            <div class="grid grid-cols-7 border-b border-gray-200 last:border-0">
                @foreach ($week as $dayData)
                    @php
                        $day = $dayData['day'];
                        $dayBookings = $dayData['dayBookings'];
                        $isCurrentMonth = $day->month === $month->month;
                        $isToday = $day->isToday();
                    @endphp
                    <div class="min-h-[100px] p-1.5 border-r border-gray-100 last:border-0 {{ $isCurrentMonth ? '' : 'bg-gray-50' }}">
                        <div class="text-xs font-semibold mb-1 {{ $isToday ? 'bg-gray-900 text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-700' }}">
                            {{ $isToday ? $day->format('j') : $day->format('j') }}
                        </div>
                        @foreach ($dayBookings->take(3) as $booking)
                            <a href="{{ route('company.bookings.show', $booking) }}" class="block text-xs px-1 py-0.5 rounded mb-0.5 truncate 
                                @switch($booking->status->value)
                                    @case('pending') bg-yellow-100 text-yellow-700 @break
                                    @case('confirmed') bg-blue-100 text-blue-700 @break
                                    @case('ongoing') bg-green-100 text-green-700 @break
                                    @case('completed') bg-gray-100 text-gray-500 @break
                                    @case('cancelled') bg-red-100 text-red-500 @break
                                @endswitch
                            ">{{ $booking->bike->name ?? 'N/A' }}</a>
                        @endforeach
                        @if ($dayBookings->count() > 3)
                            <div class="text-xs text-gray-400 pl-1">+{{ $dayBookings->count() - 3 }} more</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
@endsection
