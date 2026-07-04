@extends('layouts.customer')
@section('title', 'Book ' . $bike->name)

@section('content')
<div class="max-w-3xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('bikes.index') }}" class="hover:text-gray-900">Bikes</a>
        <span class="mx-2">/</span>
        <a href="{{ route('bikes.show', $bike) }}" class="hover:text-gray-900">{{ $bike->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Book</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="md:col-span-3">
            <form method="POST" action="{{ route('customer.bookings.store', $bike) }}" class="space-y-6" x-data="bookingForm()">
                @csrf

                <x-card header="<h3 class='text-lg font-medium text-gray-900'>Select Dates</h3>">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                            <input type="datetime-local" name="start_date" x-model="startDate" @change="calculate()"
                                value="{{ old('start_date') }}"
                                min="{{ now()->format('Y-m-d\TH:i') }}"
                                class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">End Date & Time</label>
                            <input type="datetime-local" name="end_date" x-model="endDate" @change="calculate()"
                                value="{{ old('end_date') }}"
                                min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                    </div>

                    @error('dates')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </x-card>

                <x-card header="<h3 class='text-lg font-medium text-gray-900'>Price Breakdown</h3>">
                    <div class="space-y-3 text-sm">
                        <template x-if="totalWeeks > 0">
                            <div class="flex justify-between"><span class="text-gray-500"><span x-text="totalWeeks"></span> week(s) x NPR <span x-text="weeklyPrice"></span></span><span class="font-medium" x-text="'NPR ' + weeklyCost.toFixed(2)"></span></div>
                        </template>
                        <template x-if="totalDays > 0">
                            <div class="flex justify-between"><span class="text-gray-500"><span x-text="totalDays"></span> day(s) x NPR <span x-text="dailyPrice"></span></span><span class="font-medium" x-text="'NPR ' + dailyCost.toFixed(2)"></span></div>
                        </template>
                        <template x-if="totalHours > 0 && totalWeeks === 0 && totalDays === 0">
                            <div class="flex justify-between"><span class="text-gray-500"><span x-text="totalHours"></span> hour(s) x NPR <span x-text="hourlyPrice"></span></span><span class="font-medium" x-text="'NPR ' + hourlyCost.toFixed(2)"></span></div>
                        </template>
                        <hr>
                        <div class="flex justify-between text-base"><span class="font-semibold text-gray-900">Total</span><span class="font-bold text-gray-900" x-text="'NPR ' + total.toFixed(2)"></span></div>
                    </div>
                </x-card>

                <div class="flex justify-end gap-4">
                    <x-button href="{{ route('bikes.show', $bike) }}" variant="secondary">Cancel</x-button>
                    <x-button type="submit">Confirm Booking</x-button>
                </div>
            </form>
        </div>

        <div class="md:col-span-2">
            <x-card>
                <div class="space-y-3">
                    @if ($bike->images->where('is_primary', true)->first())
                        <img src="{{ asset('storage/' . $bike->images->where('is_primary', true)->first()->image_path) }}" class="w-full h-40 object-cover rounded-lg">
                    @endif
                    <h3 class="font-semibold text-gray-900">{{ $bike->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $bike->brand }} {{ $bike->model }} ({{ $bike->year }})</p>
                    <div class="text-sm text-gray-500 space-y-1">
                        <p>Fuel: {{ $bike->fuel_type ?? 'N/A' }} &middot; Transmission: {{ $bike->transmission ?? 'N/A' }}</p>
                    </div>
                    <hr>
                    <div class="space-y-1 text-sm">
                        @if ($bike->hourly_price)
                            <div class="flex justify-between"><span class="text-gray-500">Hourly</span><span class="font-medium">NPR {{ number_format($bike->hourly_price, 2) }}</span></div>
                        @endif
                        <div class="flex justify-between"><span class="text-gray-500">Daily</span><span class="font-medium">NPR {{ number_format($bike->daily_price, 2) }}</span></div>
                        @if ($bike->weekly_price)
                            <div class="flex justify-between"><span class="text-gray-500">Weekly</span><span class="font-medium">NPR {{ number_format($bike->weekly_price, 2) }}</span></div>
                        @endif
                    </div>
                    @if ($bike->company)
                        <hr>
                        <div class="flex items-center gap-2">
                            @if ($bike->company->logo)
                                <img src="{{ asset('storage/' . $bike->company->logo) }}" class="w-8 h-8 rounded object-cover">
                            @else
                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-400">{{ strtoupper(substr($bike->company->company_name, 0, 1)) }}</div>
                            @endif
                            <span class="text-sm text-gray-600">{{ $bike->company->company_name }}</span>
                        </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function bookingForm() {
    return {
        startDate: '{{ old('start_date') }}',
        endDate: '{{ old('end_date') }}',
        hourlyPrice: {{ $bike->hourly_price ?? 0 }},
        dailyPrice: {{ $bike->daily_price ?? 0 }},
        weeklyPrice: {{ $bike->weekly_price ?? 0 }},
        totalHours: 0,
        totalDays: 0,
        totalWeeks: 0,
        hourlyCost: 0,
        dailyCost: 0,
        weeklyCost: 0,
        total: 0,
        calculate() {
            if (!this.startDate || !this.endDate) return;
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);
            if (end <= start) return;

            const diffMs = end - start;
            const diffHours = diffMs / (1000 * 60 * 60);
            const diffDays = Math.ceil(diffMs / (1000 * 60 * 60 * 24));

            this.totalHours = Math.floor(diffHours);
            this.totalDays = diffDays;
            this.totalWeeks = Math.floor(diffDays / 7);
            const remainingDays = diffDays % 7;

            this.hourlyCost = 0;
            this.dailyCost = 0;
            this.weeklyCost = 0;
            let total = 0;

            if (this.weeklyPrice && this.totalWeeks > 0) {
                this.weeklyCost = this.totalWeeks * this.weeklyPrice;
                total += this.weeklyCost;
            }

            if (this.dailyPrice && remainingDays > 0) {
                this.dailyCost = remainingDays * this.dailyPrice;
                total += this.dailyCost;
            } else if (this.dailyPrice && this.totalWeeks === 0 && diffHours < 24) {
                this.totalDays = 1;
                this.dailyCost = this.dailyPrice;
                total += this.dailyCost;
            }

            if (this.hourlyPrice && total === 0 && diffHours < 24) {
                this.totalHours = Math.ceil(diffHours);
                this.hourlyCost = this.totalHours * this.hourlyPrice;
                total += this.hourlyCost;
            }

            this.total = total;
        }
    }
}
</script>
@endpush
