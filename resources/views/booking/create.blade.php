@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <!-- ================= HEADER ================= -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            ✂️ Booking Layanan
        </h2>
        <p class="text-sm text-gray-500">
            Pilih layanan favorit kamu
        </p>
    </div>


    <!-- ================= ERROR ================= -->
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded-xl text-sm">
        @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
        @endforeach
    </div>
    @endif


    <form method="POST" action="{{ route('booking.store') }}" id="bookingForm" class="space-y-6">
        @csrf

        <!-- ================= SERVICES ================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            @foreach($services as $s)
            <label class="service-card group border border-gray-200 rounded-2xl p-5 cursor-pointer transition duration-300 hover:shadow-lg relative overflow-hidden">

                <!-- ACTIVE BAR -->
                <div class="absolute inset-0 border-2 border-transparent group-[.active]:border-indigo-500 rounded-2xl pointer-events-none"></div>

                <div class="flex items-start gap-4">

                    <input type="checkbox"
                           name="services[]"
                           value="{{ $s->id }}"
                           data-price="{{ $s->price }}"
                           class="service-checkbox mt-1 w-5 h-5 accent-indigo-600">

                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-base">
                            {{ $s->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Rp {{ number_format($s->price) }}
                        </p>
                    </div>

                    <!-- CHECK ICON -->
                    <div class="hidden check-icon text-indigo-600 text-lg">
                        ✔
                    </div>

                </div>

            </label>
            @endforeach

        </div>


        <!-- ================= TOTAL ================= -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white p-5 rounded-2xl shadow">

            <p class="text-sm opacity-80">Total Harga</p>

            <h2 id="total" class="text-3xl font-bold">
                Rp 0
            </h2>

        </div>


        <!-- ================= DATE TIME ================= -->
        <div class="grid md:grid-cols-2 gap-4">

            <!-- DATE -->
            <div>
                <label class="text-sm text-gray-600">Tanggal</label>
                <input type="date" name="date" required
                    class="w-full mt-1 border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

            <!-- TIME -->
            <div>
                <label class="text-sm text-gray-600">Jam</label>
                <input type="time" name="time" required
                    class="w-full mt-1 border border-gray-200 p-3 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>

        </div>


        <!-- ================= BUTTON ================= -->
        <button
            class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg">
            Booking Sekarang
        </button>

    </form>

</div>


<!-- ================= SCRIPT ================= -->
<script>
const checkboxes = document.querySelectorAll('.service-checkbox');
const totalEl = document.getElementById('total');
const form = document.getElementById('bookingForm');

function updateTotal() {
    let total = 0;

    checkboxes.forEach(cb => {
        if (cb.checked) {
            total += parseInt(cb.dataset.price);
        }
    });

    totalEl.innerText = "Rp " + new Intl.NumberFormat().format(total);
}

// UI SELECT EFFECT
checkboxes.forEach(cb => {
    cb.addEventListener('change', function () {

        updateTotal();

        const card = this.closest('.service-card');
        const icon = card.querySelector('.check-icon');

        if (this.checked) {
            card.classList.add('bg-indigo-50','border-indigo-500','active');
            icon.classList.remove('hidden');
        } else {
            card.classList.remove('bg-indigo-50','border-indigo-500','active');
            icon.classList.add('hidden');
        }
    });
});

// VALIDASI
form.addEventListener('submit', function(e) {
    let checked = false;

    checkboxes.forEach(cb => {
        if (cb.checked) checked = true;
    });

    if (!checked) {
        e.preventDefault();
        alert('Pilih minimal 1 layanan!');
    }
});
</script>

@endsection