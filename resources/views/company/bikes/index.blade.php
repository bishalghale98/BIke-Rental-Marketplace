@extends('layouts.company')
@section('title', 'My Bikes')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">My Bikes</h1>
<p class="mt-1 text-gray-600">Manage your bike inventory.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No bikes listed yet.</p>
    </x-card>
</div>
@endsection
