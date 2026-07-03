@extends('layouts.customer')
@section('title', 'Saved Bikes')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Saved Bikes</h1>
<p class="mt-1 text-gray-600">Bikes you've saved for later.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No saved bikes yet.</p>
    </x-card>
</div>
@endsection
