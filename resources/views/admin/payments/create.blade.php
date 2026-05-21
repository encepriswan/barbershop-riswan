@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto">

    <h1 class="text-2xl font-bold mb-4">💳 Pembayaran</h1>

    <div class="bg-white p-6 rounded-xl shadow space-y-4">

        <!-- INFO -->
        <div>
            <p class="font-semibold">{{ $transaction->user->name }}</p>
            <p class="text-sm text-gray-500">
                {{ $transaction->booking->service->name }}
            </p>
            <p class="text-lg font-bold mt-2">
                Rp {{ number_format($transaction->total_price) }}
            </p>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('admin.payments.store') }}" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            <!-- METHOD -->
            <div>
                <label class="block mb-2 font-medium">Metode Pembayaran</label>

                <select name="method" id="method" class="w-full p-2 border rounded">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="ewallet">E-Wallet</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <!-- BANK -->
            <div id="bankSection" class="hidden">
                <label class="block mt-3">Pilih Bank</label>
                <select name="bank" class="w-full p-2 border rounded">
                    <option>BCA</option>
                    <option>Mandiri</option>
                    <option>BRI</option>
                    <option>BNI</option>
                </select>
            </div>

            <!-- EWALLET -->
            <div id="ewalletSection" class="hidden">
                <label class="block mt-3">E-Wallet</label>
                <select name="ewallet" class="w-full p-2 border rounded">
                    <option>OVO</option>
                    <option>DANA</option>
                    <option>GoPay</option>
                    <option>ShopeePay</option>
                </select>
            </div>

            <!-- QRIS -->
            <div id="qrisSection" class="hidden text-center mt-4">
                <p class="mb-2 text-sm">Scan QRIS</p>
                <img src="/qris.png" class="mx-auto w-40">
            </div>

            <!-- UPLOAD BUKTI -->
            <div id="uploadSection" class="hidden">
                <label class="block mt-3">Upload Bukti</label>
                <input type="file" name="proof" class="w-full border p-2 rounded">
            </div>

            <!-- BUTTON -->
            <button class="w-full mt-5 bg-indigo-600 text-white py-2 rounded">
                Konfirmasi Pembayaran
            </button>

        </form>

    </div>

</div>

<script>
const method = document.getElementById('method');
const bank = document.getElementById('bankSection');
const ewallet = document.getElementById('ewalletSection');
const qris = document.getElementById('qrisSection');
const upload = document.getElementById('uploadSection');

method.addEventListener('change', function () {
    bank.classList.add('hidden');
    ewallet.classList.add('hidden');
    qris.classList.add('hidden');
    upload.classList.add('hidden');

    if (this.value === 'transfer') {
        bank.classList.remove('hidden');
        upload.classList.remove('hidden');
    }

    if (this.value === 'ewallet') {
        ewallet.classList.remove('hidden');
        upload.classList.remove('hidden');
    }

    if (this.value === 'qris') {
        qris.classList.remove('hidden');
    }
});
</script>

@endsection