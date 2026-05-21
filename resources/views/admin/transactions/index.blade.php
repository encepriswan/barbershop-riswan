@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <h1 class="text-2xl font-bold">💰 Transaksi</h1>

    <form method="GET" class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row gap-3">

        <input type="text" name="search" placeholder="Cari user / layanan..."
            value="{{ request('search') }}"
            class="border px-3 py-2 rounded w-full md:w-1/3">

        <input type="date" name="start_date"
            value="{{ request('start_date') }}"
            class="border px-3 py-2 rounded">

        <input type="date" name="end_date"
            value="{{ request('end_date') }}"
            class="border px-3 py-2 rounded">

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            🔍 Filter
        </button>

    </form>
    <div class="bg-green-100 text-green-700 p-4 rounded-xl font-semibold">
        Total Pendapatan: Rp {{ number_format($totalRevenue) }}
    </div>


    <div class="bg-white rounded-xl shadow overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">User</th>
                    <th class="p-3 text-left">Service</th>
                    <th class="p-3 text-center">Tanggal</th>
                    <th class="p-3 text-center">Total</th>
                    <th class="p-3 text-center">Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $t)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $t->booking->user->name ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ $t->service_names }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $t->created_at->format('d M Y') }}
                    </td>

                    <td class="p-3 text-center text-indigo-600 font-semibold">
                        Rp {{ number_format($t->total_price) }}
                    </td>

                    <td class="p-3 text-center">
                        <span class="px-2 py-1 text-xs rounded
                        {{ $t->status == 'paid'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-yellow-100 text-yellow-700' }}">
                            {{ strtoupper($t->status) }}
                        </span>
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center p-5 text-gray-500">
                        Tidak ada transaksi
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection