@extends('layouts.company')
@section('title', 'Bank Accounts')

@section('content')
<div class="max-w-2xl">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('company.payouts.index') }}" class="hover:text-gray-900">Payouts</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">Bank Accounts</span>
    </nav>

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bank Accounts</h1>
            <p class="mt-1 text-gray-600">Manage your saved bank accounts for payouts.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Add Bank Account</h3>">
        <form method="POST" action="{{ route('company.bank-details.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                        <input type="text" name="bank_name" required value="{{ old('bank_name') }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Account Name</label>
                        <input type="text" name="account_name" required value="{{ old('account_name') }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Account Number</label>
                        <input type="text" name="account_number" required value="{{ old('account_number') }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Branch (optional)</label>
                        <input type="text" name="branch" value="{{ old('branch') }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">QR Code Image</label>
                    <input type="file" name="qr_code" accept="image/png,image/jpeg"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                    <p class="mt-1 text-xs text-gray-400">PNG or JPG, max 2MB</p>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300">
                    <span class="text-gray-700">Set as default account</span>
                </label>
                <button type="submit" class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800 transition-colors">
                    Add Account
                </button>
            </div>
        </form>
    </x-card>

    <x-card class="mt-6" header="<h3 class='text-lg font-medium text-gray-900'>Saved Accounts</h3>">
        @if ($bankDetails->count())
            <div class="space-y-3">
                @foreach ($bankDetails as $detail)
                    <div class="flex items-center justify-between py-3 border-b border-zinc-100 last:border-0">
                        <div class="flex items-center gap-3">
                            @if ($detail->qr_code)
                                <img src="{{ asset('storage/' . $detail->qr_code) }}" alt="QR" class="w-10 h-10 rounded object-cover">
                            @endif
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $detail->bank_name }} {{ $detail->is_default ? '(Default)' : '' }}</p>
                                <p class="text-xs text-gray-500">{{ $detail->account_name }} • {{ $detail->account_number }}</p>
                                <p class="text-xs text-gray-400">{{ $detail->branch }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="editBankDetail({{ $detail->id }}, '{{ $detail->bank_name }}', '{{ $detail->account_name }}', '{{ $detail->account_number }}', '{{ $detail->branch || '' }}', {{ $detail->is_default ? 'true' : 'false' }})"
                                class="text-sm text-primary-600 hover:text-primary-700">Edit</button>
                            <form method="POST" action="{{ route('company.bank-details.destroy', $detail) }}" onsubmit="return confirm('Remove this account?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-700">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No bank accounts saved yet.</p>
        @endif
    </x-card>
</div>

<div id="edit-modal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" style="display:none">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 p-6">
        <h3 class="text-lg font-medium text-gray-900">Edit Bank Account</h3>
        <form id="edit-form" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                        <input type="text" name="bank_name" id="edit-bank-name" required class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Account Name</label>
                        <input type="text" name="account_name" id="edit-account-name" required class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Account Number</label>
                        <input type="text" name="account_number" id="edit-account-number" required class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Branch</label>
                        <input type="text" name="branch" id="edit-branch" class="mt-1 block w-full rounded-lg border border-gray-300 text-sm px-3 py-2">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">QR Code Image</label>
                    <input type="file" name="qr_code" accept="image/png,image/jpeg"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                    <p class="mt-1 text-xs text-gray-400">PNG or JPG, max 2MB. Leave empty to keep current.</p>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_default" value="1" id="edit-is-default" class="rounded border-gray-300">
                    <span class="text-gray-700">Set as default account</span>
                </label>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-zinc-900 text-white rounded-lg text-sm font-medium hover:bg-zinc-800">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editBankDetail(id, bankName, accountName, accountNumber, branch, isDefault) {
    document.getElementById('edit-form').action = '{{ url("company/bank-details") }}' + '/' + id;
    document.getElementById('edit-bank-name').value = bankName;
    document.getElementById('edit-account-name').value = accountName;
    document.getElementById('edit-account-number').value = accountNumber;
    document.getElementById('edit-branch').value = branch;
    document.getElementById('edit-is-default').checked = isDefault;
    document.getElementById('edit-modal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
}
</script>
@endsection
