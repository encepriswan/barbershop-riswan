@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-6">Tambah Booking</h1>

<div class="bg-white p-6 rounded-xl shadow max-w-lg">

    <form method="POST" action="{{ route('admin.booking.store') }}">
        @csrf

        <label class="block mb-2 font-semibold">Pilih Layanan</label>

        <div class="space-y-2 mb-4">
            @foreach($services as $s)
            <label class="flex items-center gap-2">
                <input type="checkbox" name="services[]" value="{{ $s->id }}">
                {{ $s->name }} (Rp {{ number_format($s->price) }})
            </label>
            @endforeach
        </div>

        <label>Tanggal</label>
        <input type="date" name="date" class="w-full border p-2 rounded mb-4">

        <label>Jam</label>
        <input type="time" name="time" class="w-full border p-2 rounded mb-4">

        <button class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Simpan
        </button>

    </form>

</div>

@endsection