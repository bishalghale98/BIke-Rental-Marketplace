@extends('layouts.customer')
@section('title', 'Review ' . $booking->bike->name)

@section('content')
<div class="max-w-2xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('customer.reviews.index') }}" class="hover:text-gray-900">My Reviews</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Write a Review</span>
    </nav>

    <x-card>
        <div class="flex items-center gap-4 mb-6">
            @if ($booking->bike->images->where('is_primary', true)->first())
                <img src="{{ asset('storage/' . $booking->bike->images->where('is_primary', true)->first()->image_path) }}" class="w-16 h-16 rounded-lg object-cover">
            @endif
            <div>
                <h2 class="font-semibold text-gray-900">{{ $booking->bike->name }}</h2>
                <p class="text-sm text-gray-500">{{ $booking->bike->brand }} {{ $booking->bike->model }}</p>
                <p class="text-xs text-gray-400">{{ $booking->start_date->format('M d, Y') }} &mdash; {{ $booking->end_date->format('M d, Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.reviews.store', $booking) }}" class="space-y-6" x-data="{ rating: 0, hovered: 0 }">
            @csrf

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Rating</label>
                <div class="flex gap-1">
                    <template x-for="i in 5" :key="i">
                        <button type="button" @click="rating = i" @mouseenter="hovered = i" @mouseleave="hovered = 0" class="p-0.5 transition-colors">
                            <svg class="w-8 h-8 cursor-pointer" :class="i <= (hovered || rating) ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                    </template>
                </div>
                <input type="hidden" name="rating" x-model="rating">
                @error('rating')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Your Review</label>
                <textarea name="review" rows="5" class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900" placeholder="Share your experience...">{{ old('review') }}</textarea>
                @error('review')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-4">
                <x-button href="{{ route('customer.reviews.index') }}" variant="secondary">Cancel</x-button>
                <x-button type="submit">Submit Review</x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
