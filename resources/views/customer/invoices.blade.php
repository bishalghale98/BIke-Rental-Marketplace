@extends('layouts.customer')
@section('title', 'Invoices')

@section('content')
<h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
<p class="mt-1 text-gray-600">View your rental invoices.</p>
<div class="mt-6">
    <x-card>
        <p class="text-gray-500 text-center py-8">No invoices yet.</p>
    </x-card>
</div>
@endsection
