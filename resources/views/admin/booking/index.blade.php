@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- ================= HEADER ================= -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                📅 Kelola Booking
            </h1>
            <p class="text-sm text-gray-500">
                Manajemen booking pelanggan
            </p>
        </div>

        <!-- SEARCH + FILTER -->
        <div class="flex gap-2">

            <input type="text" id="searchInput"
                placeholder="Cari user..."
                class="border px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">

            <select id="statusFilter"
                class="border px-3 py-2 rounded-lg text-sm">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="checked_in">Check-in</option>
                <option value="in_progress">Proses</option>
                <option value="done">Selesai</option>
            </select>

        </div>

    </div>


    <!-- SUCCESS -->
    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif


    <!-- ================= TABLE ================= -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-4 text-left">User</th>
                    <th class="p-4 text-left">Service</th>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Jam</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody id="bookingTable">

            @forelse($bookings as $b)

            <tr class="border-t hover:bg-gray-50 transition">

                <td class="p-4 font-medium text-gray-800">
                    {{ $b->user->name }}
                </td>

                <td class="p-4">
                    <div class="flex flex-wrap gap-1">
                        @foreach($b->services as $s)
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                                {{ $s->name }}
                            </span>
                        @endforeach
                    </div>
                </td>

                <td class="p-4 text-gray-600">
                    {{ $b->booking_date }}
                </td>

                <td class="p-4 text-gray-600">
                    {{ $b->booking_time }}
                </td>

                <td class="p-4 text-center">
                    <span class="px-3 py-1 text-xs rounded-full font-medium
                        @if($b->status=='pending') bg-gray-200 text-gray-700
                        @elseif($b->status=='approved') bg-blue-100 text-blue-700
                        @elseif($b->status=='checked_in') bg-indigo-100 text-indigo-700
                        @elseif($b->status=='in_progress') bg-orange-100 text-orange-700
                        @elseif($b->status=='done') bg-green-100 text-green-700
                        @endif
                    ">
                        {{ strtoupper(str_replace('_',' ', $b->status)) }}
                    </span>
                </td>

                <td class="p-4">
                    <div class="flex flex-wrap gap-2 justify-center">

                        @if($b->status === 'pending')
                            <form method="POST" action="{{ route('admin.booking.approve',$b->id) }}">
                                @csrf
                                <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">✔</button>
                            </form>

                            <form method="POST" action="{{ route('admin.booking.reject',$b->id) }}">
                                @csrf
                                <button class="bg-red-500 text-white px-2 py-1 rounded text-xs">✖</button>
                            </form>
                        @endif

                        @if($b->status == 'approved')
                            <form method="POST" action="{{ route('admin.booking.checkin',$b->id) }}">
                                @csrf
                                <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs">Check-in</button>
                            </form>
                        @endif

                        @if($b->status == 'checked_in')
                            <form method="POST" action="{{ route('admin.booking.start',$b->id) }}">
                                @csrf
                                <button class="bg-indigo-500 text-white px-2 py-1 rounded text-xs">Mulai</button>
                            </form>
                        @endif

                        @if($b->status == 'in_progress')
                            <form method="POST" action="{{ route('admin.booking.finish',$b->id) }}">
                                @csrf
                                <button class="bg-green-600 text-white px-2 py-1 rounded text-xs">Selesai</button>
                            </form>
                        @endif

                        <a href="{{ route('admin.booking.edit',$b->id) }}"
                           class="bg-indigo-500 text-white px-2 py-1 rounded text-xs">
                           Edit
                        </a>

                        <form method="POST" action="{{ route('admin.booking.delete',$b->id) }}">
                            @csrf
                            @method('DELETE')
                            <button class="bg-gray-500 text-white px-2 py-1 rounded text-xs">
                                Delete
                            </button>
                        </form>

                    </div>
                </td>

            </tr>

            @empty

            <tr>
                <td colspan="6" class="text-center p-10 text-gray-500">
                    Tidak ada data
                </td>
            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


<!-- ================= AJAX SCRIPT ================= -->
<script>
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');
const table = document.getElementById('bookingTable');

let timeout = null;

function loadData() {

    fetch(`?search=${searchInput.value}&status=${statusFilter.value}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {

        let html = '';

        if (data.length === 0) {
            html = `<tr><td colspan="6" class="text-center p-6 text-gray-500">Data tidak ditemukan</td></tr>`;
        }

        data.forEach(b => {

            let services = b.services.map(s => s.name).join(', ');

            html += `
            <tr class="border-t">

                <td class="p-4">${b.user.name}</td>
                <td class="p-4">${services}</td>
                <td class="p-4">${b.booking_date}</td>
                <td class="p-4">${b.booking_time}</td>
                <td class="p-4 text-center">${b.status}</td>

                <td class="p-4 text-center">
                    <small class="text-gray-400">Aksi tetap aktif setelah refresh</small>
                </td>

            </tr>`;
        });

        table.innerHTML = html;
    });
}

// SEARCH
searchInput.addEventListener('keyup', () => {
    clearTimeout(timeout);
    timeout = setTimeout(loadData, 400);
});

// FILTER
statusFilter.addEventListener('change', loadData);

</script>

@endsection