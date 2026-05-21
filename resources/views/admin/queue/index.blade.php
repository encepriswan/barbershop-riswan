@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-10">

    <h1 class="text-3xl font-bold text-center">💈 Kelola Antrian</h1>
    <div>

        <h2 class="text-center font-semibold mb-4">
            🪞 Kursi Barber (Sedang Diproses)
        </h2>
        <div class="bg-gray-800 text-white text-center py-2 rounded-full mb-6 shadow">
            CERMIN / BARBER
        </div>

        <div class="flex justify-center gap-6 flex-wrap">

            @php
            $inProgress = $bookings->where('status','in_progress')->take(4);
            @endphp

            @for($i=0; $i<4; $i++)
                @php $b=$inProgress->values()->get($i); @endphp

                <div class="w-32 h-32 rounded-2xl shadow-lg flex flex-col justify-center items-center text-white
                    {{ $b ? 'bg-red-500' : 'bg-gray-300 text-gray-500' }}">

                    <p class="font-bold text-lg">💈 {{ $i+1 }}</p>

                    @if($b)
                    <p class="text-xs mt-2 text-center px-2">
                        {{ $b->user->name }}
                    </p>
                    <form method="POST" action="{{ route('admin.booking.finish', $b->id) }}">
                        @csrf
                        <button class="mt-2 bg-white text-black px-2 py-1 rounded text-xs hover:bg-gray-200">
                            Selesai
                        </button>
                    </form>
                    @else
                    <p class="text-xs mt-2">Kosong</p>
                    @endif

                </div>
                @endfor

        </div>

    </div>

    <div>

        <h2 class="text-center font-semibold mb-4">
            🪑 Kursi Tunggu (Check-in)
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-5">

            @php
            $waiting = $bookings->where('status','checked_in')->take(10);
            @endphp

            @for($i=0; $i<10; $i++)
                @php $b=$waiting->values()->get($i); @endphp

                <div class="h-28 rounded-xl shadow flex flex-col justify-center items-center text-white
                    {{ $b ? 'bg-yellow-500' : 'bg-blue-400' }}">

                    <p class="font-bold">Seat {{ $i+1 }}</p>

                    @if($b)
                    <p class="text-xs mt-1 text-center px-2">
                        {{ $b->user->name }}
                    </p>
                    <form method="POST" action="{{ route('admin.booking.start', $b->id) }}">
                        @csrf
                        <button class="mt-2 bg-white text-black px-2 py-1 rounded text-xs hover:bg-gray-200">
                            Mulai
                        </button>
                    </form>
                    @else
                    <p class="text-xs mt-2 opacity-70">Kosong</p>
                    @endif

                </div>
                @endfor

        </div>

    </div>

    <div class="flex justify-center gap-6 text-sm">

        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-blue-400 rounded"></div>
            Kosong
        </div>

        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-yellow-500 rounded"></div>
            Menunggu
        </div>

        <div class="flex items-center gap-2">
            <div class="w-4 h-4 bg-red-500 rounded"></div>
            Sedang Diproses
        </div>

    </div>

</div>

@endsection