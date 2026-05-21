@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 px-4">

    <div class="bg-white/95 backdrop-blur p-6 md:p-8 rounded-2xl shadow-xl w-full max-w-lg">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">➕ Tambah Layanan</h2>
            <p class="text-sm text-gray-500">Buat layanan baru</p>
        </div>

        @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded text-sm">
            @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST"
            action="{{ route('admin.services.store') }}"
            class="space-y-5">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-600">Nama Layanan</label>
                <input name="name"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400"
                    placeholder="Contoh: Potong Rambut"
                    required>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Durasi (menit)</label>
                <input name="duration"
                    type="number"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400"
                    placeholder="Contoh: 30"
                    required>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-600">Harga (Rp)</label>
                <input name="price"
                    type="number"
                    id="priceInput"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400"
                    placeholder="Contoh: 25000"
                    required>

                <p id="pricePreview" class="text-sm text-indigo-600 mt-2 font-semibold"></p>
            </div>

            <button
                class="w-full bg-indigo-600 text-white py-2.5 rounded-lg hover:bg-indigo-700 transition shadow">
                Simpan Layanan
            </button>

            <a href="{{ route('admin.services.index') }}"
                class="block text-center text-sm text-gray-500 hover:underline">
                ← Kembali
            </a>

        </form>

    </div>

</div>


<script>
    const priceInput = document.getElementById('priceInput');
    const preview = document.getElementById('pricePreview');

    function updatePrice() {
        let val = priceInput.value || 0;
        preview.innerHTML = "Preview: Rp " + new Intl.NumberFormat().format(val);
    }

    priceInput.addEventListener('input', updatePrice);
</script>

@endsection