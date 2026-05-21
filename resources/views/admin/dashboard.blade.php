@extends('layouts.app')

@section('content')

<div class="space-y-8">

    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            📊 Dashboard Admin
        </h1>
        <p class="text-sm text-gray-500">
            Monitoring bisnis barbershop secara realtime
        </p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Total Booking</p>
            <h2 class="text-2xl font-bold">{{ $totalBooking }}</h2>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Booking Hari Ini</p>
            <h2 class="text-2xl font-bold">{{ $today }}</h2>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Total Revenue</p>
            <h2 class="text-xl font-bold">
                Rp {{ number_format($revenue) }}
            </h2>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-5 rounded-2xl shadow">
            <p class="text-sm opacity-80">Payment Pending</p>
            <h2 class="text-2xl font-bold">{{ $paymentPending }}</h2>
        </div>

    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">Pending</p>
            <h2 class="text-xl font-bold text-gray-700">{{ $pending }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">Approved</p>
            <h2 class="text-xl font-bold text-blue-600">{{ $approved }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">Selesai</p>
            <h2 class="text-xl font-bold text-green-600">{{ $done }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow text-center">
            <p class="text-gray-500 text-sm">Total</p>
            <h2 class="text-xl font-bold text-indigo-600">{{ $totalBooking }}</h2>
        </div>

    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="p-4 border-b">
            <h2 class="font-semibold text-gray-700">
                Booking Terbaru
            </h2>
        </div>

        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left">User</th>
                    <th class="p-4 text-left">Service</th>
                    <th class="p-4 text-left">Tanggal</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($bookings as $booking)

                <tr class="border-t hover:bg-gray-50">

                    <td class="p-4 font-medium text-gray-800">
                        {{ $booking->user->name ?? '-' }}
                    </td>

                    <td class="p-4">
                        <div class="flex flex-wrap gap-1">
                            @foreach($booking->services as $s)
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                                {{ $s->name }}
                            </span>
                            @endforeach
                        </div>
                    </td>

                    <td class="p-4 text-gray-600">
                        {{ $booking->booking_date }}
                    </td>

                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($booking->status=='pending') bg-gray-200 text-gray-700
                        @elseif($booking->status=='approved') bg-blue-100 text-blue-700
                        @elseif($booking->status=='done') bg-green-100 text-green-700
                        @else bg-red-100 text-red-700
                        @endif
                    ">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>

                    <td class="p-4 text-center">

                        @if($booking->status === 'pending')

                        <form method="POST"
                            action="{{ route('admin.booking.approve',$booking->id) }}"
                            class="inline">
                            @csrf
                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs shadow">
                                ✔
                            </button>
                        </form>

                        <form method="POST"
                            action="{{ route('admin.booking.reject',$booking->id) }}"
                            class="inline">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs shadow">
                                ✖
                            </button>
                        </form>

                        @endif

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-500">
                        Belum ada data booking
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection