@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">
        💳 Verifikasi Pembayaran
    </h1>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left">User</th>
                    <th class="p-4 text-left">Service</th>
                    <th class="p-4 text-center">Total</th>
                    <th class="p-4 text-center">Metode</th>
                    <th class="p-4 text-center">Bukti</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($payments as $p)

                @php
                $proofUrl = $p->proof ? asset('storage/'.$p->proof) : null;
                $services = $p->transaction->booking->services ?? collect();
                @endphp

                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-4 font-medium text-gray-800">
                        {{ $p->transaction->user->name ?? '-' }}
                    </td>
                    <td class="p-4 text-gray-600">
                        {{ $services->pluck('name')->join(', ') ?: '-' }}
                    </td>
                    <td class="p-4 text-center font-semibold text-indigo-600">
                        Rp {{ number_format($p->transaction->total_price ?? 0) }}
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 bg-gray-100 rounded text-xs">
                            {{ strtoupper(str_replace('_',' ', $p->method)) }}
                        </span>
                    </td>

                    <td class="p-4 text-center">
                        @if($proofUrl)
                        <img src="{{ $proofUrl }}"
                            data-url="{{ $proofUrl }}"
                            class="preview-img w-14 h-14 object-cover rounded-lg shadow cursor-pointer hover:scale-110 transition">
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>

                    <!-- STATUS -->
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 text-xs rounded-full font-medium
                        @if($p->status=='pending') bg-gray-200 text-gray-700
                        @elseif($p->status=='paid') bg-green-100 text-green-700
                        @elseif($p->status=='rejected') bg-red-100 text-red-700
                        @endif
                    ">
                            {{ strtoupper($p->status) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex gap-2 justify-center flex-wrap">

                            @if($p->status == 'pending')

                            <form method="POST" action="{{ route('admin.payments.update', $p->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="paid">

                                <button type="submit"
                                    onclick="return confirmAction(event,'approve')"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs shadow">
                                    ✔ Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.payments.update', $p->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">

                                <button type="submit"
                                    onclick="return confirmAction(event,'reject')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs shadow">
                                    ✖ Reject
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.payments.destroy', $p->id) }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    onclick="return confirmDelete(event)"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs shadow">
                                    🗑 Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection