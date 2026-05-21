@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div class="flex items-center gap-3">

            <!-- LOGO -->
            <img src="{{ asset('/logo.png') }}"
                 alt="Logo"
                 class="w-10 h-10 object-contain">

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Dashboard
                </h1>
                <p class="text-gray-500 text-sm">
                    Pantau booking, pembayaran, dan antrian kamu
                </p>
            </div>

        </div>

        <a href="{{ route('booking.create') }}"
           class="bg-indigo-600 text-white px-5 py-2 rounded-xl shadow hover:bg-indigo-700 transition">
            ➕ Booking Baru
        </a>

    </div>


    <!-- ================= KPI ================= -->
    @php
        $total = $bookings->count();
        $done = $bookings->where('status','done')->count();
        $progress = $total ? round(($done/$total)*100) : 0;
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-4 rounded-xl shadow hover:scale-105 transition cursor-pointer">
            <p class="text-sm opacity-80">Total</p>
            <h2 class="text-2xl font-bold">{{ $total }}</h2>
        </div>

        <div class="bg-gradient-to-br from-gray-500 to-gray-600 text-white p-4 rounded-xl shadow hover:scale-105 transition cursor-pointer">
            <p class="text-sm opacity-80">Pending</p>
            <h2 class="text-2xl font-bold">{{ $bookings->where('status','pending')->count() }}</h2>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl shadow hover:scale-105 transition cursor-pointer">
            <p class="text-sm opacity-80">Approved</p>
            <h2 class="text-2xl font-bold">{{ $bookings->where('status','approved')->count() }}</h2>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-xl shadow hover:scale-105 transition cursor-pointer">
            <p class="text-sm opacity-80">Proses</p>
            <h2 class="text-2xl font-bold">{{ $bookings->where('status','in_progress')->count() }}</h2>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl shadow hover:scale-105 transition cursor-pointer">
            <p class="text-sm opacity-80">Selesai</p>
            <h2 class="text-2xl font-bold">{{ $done }}</h2>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-xl shadow hover:scale-105 transition cursor-pointer">
            <p class="text-sm opacity-80">Belum Bayar</p>
            <h2 class="text-2xl font-bold">{{ $transactions->where('status','unpaid')->count() }}</h2>
        </div>

    </div>


    <!-- ================= PROGRESS + NEXT ================= -->
    <div class="grid md:grid-cols-2 gap-6">

        <!-- PROGRESS -->
        <div class="bg-white p-6 rounded-2xl shadow">

            <h3 class="font-semibold mb-4 text-gray-700">
                📊 Progress Booking
            </h3>

            <div class="flex items-center gap-6">

                <!-- CIRCLE -->
                <div class="relative w-28 h-28">

                    <svg class="w-full h-full rotate-[-90deg]">
                        <circle cx="50%" cy="50%" r="45"
                                stroke="#e5e7eb"
                                stroke-width="10"
                                fill="none"/>
                        <circle cx="50%" cy="50%" r="45"
                                stroke="#6366f1"
                                stroke-width="10"
                                fill="none"
                                stroke-dasharray="283"
                                stroke-dashoffset="{{ 283 - (283 * $progress / 100) }}"
                                stroke-linecap="round"/>
                    </svg>

                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="font-bold text-lg">{{ $progress }}%</span>
                    </div>

                </div>

                <div>
                    <p class="text-gray-600 text-sm">Booking selesai</p>
                    <p class="font-bold text-xl text-indigo-600">
                        {{ $done }} / {{ $total }}
                    </p>
                </div>

            </div>

        </div>


        <!-- NEXT BOOKING -->
        <div class="bg-white p-6 rounded-2xl shadow">

            <h3 class="font-semibold mb-4 text-gray-700">
                ⏰ Booking Selanjutnya
            </h3>

            @php
                $next = $bookings->whereNotIn('status',['done'])->first();
            @endphp

            @if($next)
                <div class="p-4 border rounded-xl hover:shadow transition">

                    <p class="font-semibold text-gray-800">
                        {{ $next->service_list->pluck('name')->join(', ') }}
                    </p>

                    <p class="text-sm text-gray-500">
                        📅 {{ $next->booking_date }} • ⏰ {{ $next->booking_time }}
                    </p>

                    <span class="px-3 py-1 text-xs rounded-full font-medium bg-blue-100 text-blue-700 inline-block mt-2">
                        {{ ucfirst(str_replace('_',' ',$next->status)) }}
                    </span>

                </div>
            @else
                <p class="text-gray-500 text-sm">
                    Tidak ada booking mendatang
                </p>
            @endif

        </div>

    </div>


    <!-- ================= RECENT ================= -->
    <div class="bg-white rounded-2xl shadow p-6">

        <div class="flex justify-between items-center mb-5">
            <h2 class="font-semibold text-lg text-gray-700">
                Booking Terbaru
            </h2>

            <a href="{{ route('booking.index') }}"
               class="text-indigo-600 text-sm hover:underline">
                Lihat semua →
            </a>
        </div>

        <div class="space-y-3">

            @forelse($bookings->take(5) as $b)

            <div class="flex flex-col md:flex-row justify-between items-center gap-3 p-4 border rounded-xl hover:shadow transition">

                <div class="w-full">

                    <p class="font-semibold text-gray-800">
                        {{ $b->service_list->pluck('name')->join(', ') }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $b->booking_date }} • {{ $b->booking_time }}
                    </p>

                    <p class="text-indigo-600 font-semibold text-sm">
                        Rp {{ number_format($b->total_price) }}
                    </p>

                </div>

                <div class="flex gap-2 flex-wrap">

                    <span class="px-3 py-1 text-xs rounded-full font-medium
                        @if($b->status=='pending') bg-gray-100 text-gray-700
                        @elseif($b->status=='approved') bg-blue-100 text-blue-700
                        @elseif($b->status=='in_progress') bg-orange-100 text-orange-700
                        @elseif($b->status=='done') bg-green-100 text-green-700
                        @endif">
                        {{ ucfirst(str_replace('_',' ',$b->status)) }}
                    </span>

                    @if($b->status == 'done' && $b->transaction && $b->transaction->status == 'unpaid')
                        <a href="{{ route('payment.create', $b->transaction->id) }}"
                           class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                            Bayar
                        </a>
                    @endif

                    @if($b->transaction && $b->transaction->status == 'paid')
                        <span class="bg-green-500 text-white px-3 py-1 rounded text-xs">
                            ✔ Lunas
                        </span>
                    @endif

                </div>

            </div>

            @empty

            <div class="text-center py-10 text-gray-500">
                📭 Belum ada booking
            </div>

            @endforelse

        </div>

    </div>

</div>

@endsection