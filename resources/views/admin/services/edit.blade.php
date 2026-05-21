@extends('layouts.app')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 px-4">

    <div class="bg-white/95 backdrop-blur p-6 md:p-8 rounded-2xl shadow-xl w-full max-w-lg">


        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Layanan</h2>
            <p class="text-sm text-gray-500">Perbarui layanan barbershop</p>
        </div>


        @if(session('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded text-sm">
            {{ session('success') }}
        </div>
        @endif


        @if($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded text-sm">
            @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
            @endforeach
        </div>
        @endif

        <form method="POST"
            action="{{ route('admin.services.update', $service->id) }}"
            class="space-y-5">
            @csrf
            @method('PUT')


            <div>
                <label class="text-sm font-medium text-gray-600">Nama Layanan</label>
                <input type="text"
                    name="name"
                    value="{{ old('name', $service->name) }}"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400"
                    required>
            </div>


            <div>
                <label class="text-sm font-medium text-gray-600">Durasi (menit)</label>
                <input type="number"
                    name="duration"
                    value="{{ old('duration', $service->duration) }}"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400"
                    required>
            </div>


            <div>
                <label class="text-sm font-medium text-gray-600">Harga (Rp)</label>
                <input type="number"
                    name="price"
                    id="priceInput"
                    value="{{ old('price', $service->price) }}"
                    class="w-full mt-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400"
                    required>


                <p id="pricePreview" class="text-sm text-indigo-600 mt-2 font-semibold"></p>
            </div>


            <div class="flex justify-between items-center pt-2">

                <a href="{{ route('admin.services.index') }}"
                    class="text-gray-500 hover:underline text-sm">
                    ← Kembali
                </a>

                <button
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition shadow">
                    Update
                </button>

            </div>

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
    window.onload = updatePrice;
</script>

@endsection