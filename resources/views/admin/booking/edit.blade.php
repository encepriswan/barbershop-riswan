@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Booking</h1>
        <p class="text-sm text-gray-500">
            Update layanan dan jadwal booking
        </p>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow">

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.booking.update', $booking->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="font-semibold block mb-3 text-gray-700">
                    ✂️ Pilih Layanan
                </label>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    @foreach($services as $s)
                    <label class="border rounded-xl p-3 cursor-pointer transition hover:shadow service-card
                        {{ $booking->services->contains($s->id) ? 'border-indigo-500 bg-indigo-50' : '' }}">

                        <input type="checkbox"
                            name="services[]"
                            value="{{ $s->id }}"
                            data-price="{{ $s->price }}"
                            class="hidden service-checkbox"
                            {{ $booking->services->contains($s->id) ? 'checked' : '' }}>

                        <div>
                            <p class="font-semibold">{{ $s->name }}</p>
                            <p class="text-sm text-gray-500">
                                Rp {{ number_format($s->price) }}
                            </p>
                        </div>

                    </label>
                    @endforeach

                </div>
            </div>

            <div class="mb-6">
                <p class="text-gray-600">Total Harga</p>
                <h2 id="total" class="text-2xl font-bold text-indigo-600">
                    Rp 0
                </h2>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-6">

                <div>
                    <label class="text-sm text-gray-600">Tanggal</label>
                    <input type="date"
                        name="date"
                        value="{{ $booking->booking_date }}"
                        class="w-full mt-1 px-3 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                        required>
                </div>

                <div>
                    <label class="text-sm text-gray-600">Jam</label>
                    <input type="time"
                        name="time"
                        value="{{ $booking->booking_time }}"
                        class="w-full mt-1 px-3 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                        required>
                </div>

            </div>

            <div class="flex gap-3">

                <a href="{{ route('admin.booking.index') }}"
                    class="flex-1 text-center border py-2 rounded-xl hover:bg-gray-100">
                    Batal
                </a>

                <button class="flex-1 bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 shadow">
                    💾 Update Booking
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    const checkboxes = document.querySelectorAll('.service-checkbox');
    const cards = document.querySelectorAll('.service-card');
    const totalEl = document.getElementById('total');

    function calculateTotal() {
        let total = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total += parseInt(cb.dataset.price);
            }
        });

        totalEl.innerText = 'Rp ' + new Intl.NumberFormat().format(total);
    }

    checkboxes.forEach((cb, i) => {
        cb.addEventListener('change', function() {

            if (cb.checked) {
                cards[i].classList.add('border-indigo-500', 'bg-indigo-50');
            } else {
                cards[i].classList.remove('border-indigo-500', 'bg-indigo-50');
            }

            calculateTotal();
        });
    });


    calculateTotal();
</script>

@endsection