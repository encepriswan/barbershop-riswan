<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barbershop_Riswan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">

<div x-data="{ open: false }" class="flex min-h-screen">

    <!-- OVERLAY -->
    <div x-show="open"
         x-transition
         @click="open = false"
         class="fixed inset-0 bg-black/40 z-40 md:hidden">
    </div>

    <!-- ================= SIDEBAR ================= -->
    <aside
        :class="open ? 'translate-x-0' : '-translate-x-full'"
        class="fixed md:static z-50 w-64 bg-gradient-to-b from-indigo-900 to-indigo-800 text-white flex flex-col transform md:translate-x-0 transition duration-300 shadow-xl">

        <!-- LOGO -->
        <div class="p-5 border-b border-indigo-700 flex items-center gap-3">

            <img src="{{ asset('/logo.png') }}"
                 class="w-10 h-10 object-contain bg-white rounded-lg p-1">

            <div>
                <h1 class="font-bold text-lg leading-tight">
                    Barbershop_Riswan
                </h1>
                <p class="text-xs text-indigo-300">
                    Management System
                </p>
            </div>

            <button @click="open = false"
                    class="ml-auto md:hidden text-white text-lg">
                ✖
            </button>
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2 text-sm">

            {{-- USER --}}
            @if(auth()->user()->role === 'customer')

                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    🏠 Dashboard
                </x-nav-link>

                <x-nav-link href="{{ route('booking.index') }}" :active="request()->routeIs('booking.*')">
                    ✂️ Booking
                </x-nav-link>

                <x-nav-link href="{{ route('queue.index') }}" :active="request()->routeIs('queue.*')">
                    📋 Antrian
                </x-nav-link>

            @endif


            {{-- ADMIN --}}
            @if(auth()->user()->role === 'admin')

                <p class="text-xs text-indigo-300 mt-4">MAIN</p>

                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    🏠 Dashboard
                </x-nav-link>

                <x-nav-link href="{{ route('admin.booking.index') }}" :active="request()->routeIs('admin.booking.*')">
                    📅 Booking
                </x-nav-link>


                <p class="text-xs text-indigo-300 mt-4">MASTER DATA</p>

                <x-nav-link href="{{ route('admin.services.index') }}" :active="request()->routeIs('admin.services.*')">
                    ✂️ Layanan
                </x-nav-link>

                <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                    👥 User
                </x-nav-link>


                <p class="text-xs text-indigo-300 mt-4">TRANSAKSI</p>

                <x-nav-link href="{{ route('admin.transactions.index') }}" :active="request()->routeIs('admin.transactions.*')">
                    💰 Transaksi
                </x-nav-link>

                <x-nav-link href="{{ route('admin.payments.index') }}" :active="request()->routeIs('admin.payments.*')">
                    💳 Pembayaran
                </x-nav-link>

                <x-nav-link href="{{ route('admin.queue.index') }}" :active="request()->routeIs('admin.queue.*')">
                    🪑 Antrian
                </x-nav-link>

            @endif

        </nav>

        <!-- FOOTER -->
        <div class="p-4 border-t border-indigo-700 text-xs text-indigo-300">
            © {{ date('Y') }} Barbershop_Riswan
        </div>

    </aside>


    <!-- ================= MAIN ================= -->
    <div class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <div class="bg-white shadow px-4 md:px-6 py-3 flex justify-between items-center">

            <!-- LEFT -->
            <div class="flex items-center gap-3">

                <button @click="open = true"
                        class="md:hidden text-xl text-gray-700 hover:text-indigo-600">
                    ☰
                </button>

                <!-- MINI LOGO -->
                <img src="{{ asset('/logo.png') }}"
                     class="w-8 h-8 object-contain md:hidden">

                <div>
                    <p class="text-xs text-gray-500">Welcome back 👋</p>
                    <p class="font-semibold text-gray-800">
                        {{ auth()->user()->name }}
                    </p>
                </div>

            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-3">

                <!-- AVATAR -->
                <div class="w-9 h-9 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>

                <!-- LOGOUT -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        onclick="return confirm('Logout sekarang?')"
                        class="bg-red-500 text-white px-4 py-1.5 rounded-lg hover:bg-red-600 transition text-sm shadow">
                        Logout
                    </button>
                </form>

            </div>

        </div>

        <!-- CONTENT -->
        <main class="p-4 md:p-6 animate-fadeIn">
            @yield('content')
        </main>

    </div>

</div>

<!-- ALPINE -->
<script src="//unpkg.com/alpinejs" defer></script>

<style>
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

</body>
</html>