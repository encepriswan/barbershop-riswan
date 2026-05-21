@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 md:p-8 rounded-2xl shadow">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Booking</h2>
        <p class="text-sm text-gray-500">Ubah layanan & jadwal booking</p>
    </div>

    <!-- ERROR -->
    @if ($errors->any())
    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-lg text-sm">
        @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('booking.update',$booking->id) }}" id="formEdit">
        @csrf
        @method('PUT')

        <!-- SERVICES -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

            @foreach($services as $s)

            @php
                $checked = $booking->service_list->contains('id', $s->id);
            @endphp

            <label class="service-card border rounded-xl p-4 cursor-pointer transition hover:shadow-md
                {{ $checked ? 'border-indigo-500 bg-indigo-50' : '' }}">

                <div class="flex items-start gap-3">

                    <input type="checkbox"
                           name="services[]"
                           value="{{ $s->id }}"
                           data-price="{{ $s->price }}"
                           class="service-checkbox mt-1"
                           {{ $checked ? 'checked' : '' }}>

                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ $s->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Rp {{ number_format($s->price) }}
                        </p>
                    </div>

                </div>

            </label>
            @endforeach

        </div>

        <!-- TOTAL -->
        <div class="bg-indigo-50 p-4 rounded-xl mb-5">
            <p class="text-sm text-gray-500">Total Harga</p>
            <h2 id="total" class="text-2xl font-bold text-indigo-600">
                Rp 0
            </h2>
        </div>

        <!-- DATE -->
        <div class="mb-3">
            <label class="text-sm text-gray-600">Tanggal</label>
            <input type="date"
                   name="date"
                   value="{{ $booking->booking_date }}"
                   required
                   class="w-full mt-1 border p-2 rounded-lg focus:ring-2 focus:ring-indigo-400">
        </div>

        <!-- TIME -->
        <div class="mb-5">
            <label class="text-sm text-gray-600">Jam</label>
            <input type="time"
                   name="time"
                   value="{{ $booking->booking_time }}"
                   required
                   class="w-full mt-1 border p-2 rounded-lg focus:ring-2 focus:ring-indigo-400">
        </div>

        <!-- BUTTON -->
        <button
            class="w-full bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 transition shadow">
            Update Booking
        </button>

    </form>

</div>

<!-- SCRIPT -->
<script>
const checkboxes = document.querySelectorAll('.service-checkbox');
const totalEl = document.getElementById('total');
const form = document.getElementById('formEdit');

// hitung total awal
function updateTotal() {
    let total = 0;

    checkboxes.forEach(cb => {
        if (cb.checked) {
            total += parseInt(cb.dataset.price);
        }
    });

    totalEl.innerText = "Rp " + new Intl.NumberFormat().format(total);
}

// highlight + update
checkboxes.forEach(cb => {
    cb.addEventListener('change', function () {

        updateTotal();

        const card = this.closest('.service-card');

        if (this.checked) {
            card.classList.add('border-indigo-500','bg-indigo-50');
        } else {
            card.classList.remove('border-indigo-500','bg-indigo-50');
        }
    });
});

// validasi minimal 1
form.addEventListener('submit', function(e){
    let checked = false;

    checkboxes.forEach(cb => {
        if (cb.checked) checked = true;
    });

    if (!checked) {
        e.preventDefault();
        alert('Pilih minimal 1 layanan!');
    }
});

// 🔥 INIT TOTAL SAAT LOAD
window.onload = updateTotal;
</script>

@endsection