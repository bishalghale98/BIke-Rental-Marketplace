@extends('layouts.customer')
@section('title', 'My Reviews')

@section('content')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">My Reviews</h1>
        <p class="mt-1 text-gray-600">Reviews you've left for completed rentals.</p>
    </div>
</div>

@if (session('success'))
    <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
@endif

@if ($reviews->isEmpty())
    <div class="mt-6">
        <x-card>
            <p class="text-gray-500 text-center py-8">No reviews yet. Complete a booking to leave a review.</p>
        </x-card>
    </div>
@else
    <div class="mt-6 space-y-4">
        @foreach ($reviews as $review)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-start gap-4">
                    @if ($review->bike->images->where('is_primary', true)->first())
                        <img src="{{ asset('storage/' . $review->bike->images->where('is_primary', true)->first()->image_path) }}" class="w-16 h-16 rounded-lg object-cover">
                    @endif
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $review->bike->name }}</h3>
                                <div class="flex items-center gap-1 mt-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        @if ($review->review)
                            <p class="mt-2 text-sm text-gray-600">{{ $review->review }}</p>
                        @endif
                        @if ($review->reply)
                            <div class="mt-3 pl-4 border-l-2 border-gray-200">
                                <p class="text-xs text-gray-500 font-medium">Company reply</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $review->reply }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($reviews->hasPages())
        <div class="mt-6">{{ $reviews->links() }}</div>
    @endif
@endif
@endsection
