@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                ✂️ Booking Saya
            </h1>
            <p class="text-sm text-gray-500">
                Kelola semua booking kamu
            </p>
        </div>

        <a href="{{ route('booking.create') }}"
           class="w-full md:w-auto text-center bg-indigo-600 text-white px-5 py-2 rounded-xl shadow hover:bg-indigo-700 transition">
            + Booking Baru
        </a>

    </div>


    <!-- ================= LIST ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($bookings as $b)

        <div class="bg-white rounded-2xl shadow hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden">

            <!-- TOP STRIP -->
            <div class="h-1 bg-gradient-to-r from-indigo-500 to-blue-500"></div>

            <div class="p-5 space-y-4">

                <!-- HEADER -->
                <div class="flex justify-between items-start">

                    <div>
                        <h2 class="font-semibold text-gray-800 text-lg leading-tight">
                            {{ $b->services->pluck('name')->join(', ') }}
                        </h2>
                        <p class="text-xs text-gray-400">
                            Booking #{{ $b->id }}
                        </p>
                    </div>

                    <!-- STATUS -->
                    <span class="text-xs px-3 py-1 rounded-full font-medium whitespace-nowrap
                        @if($b->status=='pending') bg-gray-100 text-gray-700
                        @elseif($b->status=='approved') bg-blue-100 text-blue-700
                        @elseif($b->status=='checked_in') bg-indigo-100 text-indigo-700
                        @elseif($b->status=='in_progress') bg-orange-100 text-orange-700
                        @elseif($b->status=='done') bg-green-100 text-green-700
                        @endif
                    ">
                        {{ ucfirst(str_replace('_',' ', $b->status)) }}
                    </span>

                </div>


                <!-- INFO -->
                <div class="text-sm text-gray-600 space-y-1">

                    <p>📅 {{ $b->booking_date }}</p>
                    <p>⏰ {{ $b->booking_time }}</p>

                    @if($b->transaction)
                        <p class="font-semibold text-indigo-600">
                            💰 Rp {{ number_format($b->transaction->total_price) }}
                        </p>
                    @endif

                </div>


                <!-- ACTION -->
                <div class="flex flex-wrap gap-2 pt-2">

                    <!-- EDIT + DELETE -->
                    @if($b->status == 'pending')

                        <a href="{{ route('booking.edit',$b->id) }}"
                           class="flex-1 text-center bg-blue-500 text-white py-2 rounded-lg text-sm hover:bg-blue-600 transition">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('booking.delete',$b->id) }}" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-red-500 text-white py-2 rounded-lg text-sm hover:bg-red-600 transition">
                                Delete
                            </button>
                        </form>

                    @endif


                    <!-- BAYAR -->
                    @if($b->status == 'done' && $b->transaction && $b->transaction->status == 'unpaid')

                        <a href="{{ route('payment.create', $b->transaction->id) }}"
                           class="w-full text-center bg-red-500 text-white py-2 rounded-lg text-sm hover:bg-red-600 transition">
                            💳 Bayar Sekarang
                        </a>

                    @endif


                    <!-- SUDAH BAYAR -->
                    @if($b->transaction && $b->transaction->status == 'paid')

                        <span class="w-full text-center bg-green-500 text-white py-2 rounded-lg text-sm">
                            ✔ Sudah Lunas
                        </span>

                    @endif

                </div>

            </div>

        </div>

        @empty

        <!-- EMPTY -->
        <div class="col-span-full">

            <div class="bg-white rounded-2xl shadow p-10 text-center">

                <div class="text-5xl mb-3">📭</div>

                <p class="text-gray-500 mb-4">
                    Belum ada booking
                </p>

                <a href="{{ route('booking.create') }}"
                   class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700 transition">
                    Buat Booking Pertama
                </a>

            </div>

        </div>

        @endforelse

    </div>

</div>

@endsection