@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto space-y-6">

    <div class="bg-white p-6 rounded-2xl shadow space-y-6">

        <!-- HEADER -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                💳 Pembayaran
            </h2>
            <p class="text-sm text-gray-500">
                Selesaikan pembayaran booking kamu
            </p>
        </div>


        <!-- INFO -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white p-5 rounded-2xl">

            <p class="font-semibold text-lg">
                {{ $transaction->booking && $transaction->booking->services
                    ? $transaction->booking->services->pluck('name')->join(', ')
                    : '-' }}
            </p>

            <p class="text-sm opacity-80">
                {{ $transaction->booking->booking_date ?? '-' }} • 
                {{ $transaction->booking->booking_time ?? '-' }}
            </p>

            <p class="text-2xl font-bold mt-2">
                Rp {{ number_format($transaction->total_price) }}
            </p>

        </div>


        <!-- FORM -->
        <form method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data" id="paymentForm">
            @csrf

            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

            <!-- METODE -->
            <div>
                <label class="text-sm text-gray-600">Metode Pembayaran</label>

                <select name="method" id="method"
                    class="w-full border border-gray-200 p-3 rounded-xl mt-1 focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Metode --</option>
                    <option value="cash">💵 Cash</option>
                    <option value="transfer">🏦 Transfer Bank</option>
                    <option value="qris">📱 QRIS</option>
                    <option value="ewallet">👛 E-Wallet</option>
                </select>
            </div>


        <!-- ================= INFO BOX ================= -->
        <div id="infoBox" class="hidden p-4 rounded-xl text-sm"></div>


        <!-- QRIS -->
        <div id="qrisBox" class="hidden text-center">
            <img src="/qris.png" class="w-48 mx-auto rounded-xl shadow">
            <p class="text-xs text-gray-400 mt-2">Scan QRIS</p>
        </div>


        <!-- TRANSFER -->
        <div id="transferBox" class="hidden">
            <div class="bg-gray-100 p-4 rounded-xl">
                <p>BCA: 1234567890</p>
                <p>Mandiri: 9876543210</p>
            </div>
        </div>


        <!-- E-WALLET -->
        <div id="ewalletBox" class="hidden">
            <div class="grid grid-cols-2 gap-3">

                @foreach(['gopay','ovo','dana','shopeepay'] as $e)
                <label class="border p-3 rounded-xl flex items-center gap-2 cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="ewallet_type" value="{{ $e }}">
                    <span class="capitalize">{{ $e }}</span>
                </label>
                @endforeach

            </div>
        </div>


        <!-- UPLOAD -->
        <div id="uploadBox" class="hidden">
            <label class="text-sm text-gray-600">Upload Bukti</label>

            <input type="file"
                name="proof"
                accept="image/*"
                id="proofInput"
                class="w-full mt-2">

            <img id="preview" class="mt-3 hidden w-32 rounded-xl shadow">
        </div>


        <!-- BUTTON -->
        <button
            class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition shadow">
            Kirim Pembayaran
        </button>

        </form>

    </div>

</div>


<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const method = document.getElementById('method');
const qrisBox = document.getElementById('qrisBox');
const transferBox = document.getElementById('transferBox');
const uploadBox = document.getElementById('uploadBox');
const ewalletBox = document.getElementById('ewalletBox');
const infoBox = document.getElementById('infoBox');
const proofInput = document.getElementById('proofInput');
const preview = document.getElementById('preview');

method.addEventListener('change', function () {

    // reset
    [qrisBox, transferBox, uploadBox, ewalletBox].forEach(el => el.classList.add('hidden'));
    infoBox.classList.add('hidden');

    if (this.value === 'cash') {
        infoBox.className = "bg-blue-100 text-blue-700 p-4 rounded-xl text-sm";
        infoBox.innerHTML = "💡 Bayar langsung di tempat. Menunggu konfirmasi admin.";
        infoBox.classList.remove('hidden');
    }

    if (this.value === 'transfer') {
        transferBox.classList.remove('hidden');
        uploadBox.classList.remove('hidden');
    }

    if (this.value === 'qris') {
        qrisBox.classList.remove('hidden');
        uploadBox.classList.remove('hidden');
    }

    if (this.value === 'ewallet') {
        ewalletBox.classList.remove('hidden');
        uploadBox.classList.remove('hidden');
    }
});

// PREVIEW
proofInput.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }
});

// SUBMIT
document.getElementById('paymentForm').addEventListener('submit', function () {
    Swal.fire({
        title: 'Mengirim...',
        text: 'Memproses pembayaran',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
});
</script>

@endsection