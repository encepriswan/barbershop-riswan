@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-10">

    <!-- ================= TITLE ================= -->
    <div class="text-center space-y-1">
        <h1 class="text-3xl font-bold text-gray-800">
            💈 Antrian Barbershop
        </h1>
        <p class="text-sm text-gray-500">
            Pantau posisi antrian secara realtime
        </p>
    </div>


    <!-- ================= AREA BARBER ================= -->
    <div>

        <h2 class="text-center font-semibold mb-4 text-gray-700">
            🪞 Area Barber
        </h2>

        <div class="bg-gradient-to-r from-gray-800 to-gray-700 text-white text-center py-2 rounded-full mb-8 shadow">
            CERMIN / BARBER
        </div>

        @php
            $inProgress = $bookings->where('status','in_progress')->values();
        @endphp

        <div class="flex justify-center gap-8 flex-wrap">

            @for($i=0; $i<4; $i++)
                @php $b = $inProgress->get($i); @endphp

                <div class="w-32 h-32 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg transition
                    {{ $b ? 'bg-gradient-to-br from-red-500 to-red-600 scale-105 ring-4 ring-red-200' : 'bg-gray-200 text-gray-500' }}">

                    <p class="font-bold text-lg">💈 {{ $i+1 }}</p>

                    @if($b)
                        <p class="text-xs mt-1 text-center px-2">
                            {{ $b->user->name }}
                        </p>
                    @else
                        <p class="text-xs mt-2">Kosong</p>
                    @endif

                </div>
            @endfor

        </div>

    </div>


    <!-- ================= WAITING ================= -->
    <div>

        <h2 class="text-center font-semibold mb-4 text-gray-700">
            🪑 Kursi Tunggu
        </h2>

        @php
            $waiting = $bookings->where('status','checked_in')->values();
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-5">

            @for($i=0; $i<10; $i++)
                @php $b = $waiting->get($i); @endphp

                <div class="h-28 rounded-2xl flex flex-col justify-center items-center shadow-md transition
                    {{ $b ? 'bg-gradient-to-br from-indigo-500 to-indigo-600 text-white' : 'bg-gray-100 text-gray-400' }}">

                    <p class="font-semibold">Seat {{ $i+1 }}</p>

                    @if($b)
                        <p class="text-xs mt-1 text-center px-2">
                            {{ $b->user->name }}
                        </p>
                    @else
                        <p class="text-xs mt-2">Kosong</p>
                    @endif

                </div>
            @endfor

        </div>

    </div>


    <!-- ================= INFO ANTRIAN USER ================= -->
    @php
        $myQueue = $waiting->search(function($item){
            return $item->user_id == auth()->id();
        });
    @endphp

    @if($myQueue !== false)
    <div class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white p-6 rounded-2xl text-center shadow-lg">

        <p class="text-sm opacity-80">Posisi Antrian Kamu</p>

        <h2 class="text-3xl font-bold mt-1">
            #{{ $myQueue + 1 }}
        </h2>

        <p class="text-sm mt-1 opacity-80">
            Harap menunggu hingga dipanggil
        </p>

    </div>
    @endif


    <!-- ================= LEGEND ================= -->
    <div class="flex justify-center gap-8 text-sm">

        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-gray-300 rounded"></div>
            Kosong
        </div>

        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-indigo-500 rounded"></div>
            Menunggu
        </div>

        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-red-500 rounded"></div>
            Diproses
        </div>

    </div>

</div>

@endsection