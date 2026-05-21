@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">👥 Kelola User</h1>
            <p class="text-sm text-gray-500">Manajemen pengguna sistem</p>
        </div>

        <a href="{{ route('admin.users.create') }}"
            class="bg-indigo-600 text-white px-5 py-2 rounded-xl shadow hover:bg-indigo-700 transition">
            + Tambah User
        </a>

    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Total User</p>
            <h2 class="text-2xl font-bold">{{ $users->count() }}</h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Admin</p>
            <h2 class="text-2xl font-bold text-red-500">
                {{ $users->where('role','admin')->count() }}
            </h2>
        </div>

        <div class="bg-white p-4 rounded-xl shadow">
            <p class="text-gray-500 text-sm">Customer</p>
            <h2 class="text-2xl font-bold text-blue-500">
                {{ $users->where('role','customer')->count() }}
            </h2>
        </div>

    </div>

    <form method="GET"
        class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row gap-3 items-center">

        <input type="text" name="search"
            value="{{ request('search') }}"
            placeholder="🔍 Cari nama atau email..."
            class="border px-4 py-2 rounded-xl w-full md:w-1/3 focus:ring-2 focus:ring-indigo-400">

        <button class="bg-indigo-600 text-white px-5 py-2 rounded-xl hover:bg-indigo-700">
            Cari
        </button>

    </form>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded-xl shadow">
        {{ session('success') }}
    </div>
    @endif

    <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-4 text-left">User</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-center">Role</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($users as $user)
                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>
                            <div>
                                <p class="font-semibold">{{ $user->name }}</p>
                            </div>
                        </div>
                    </td>

                    <td class="p-4 text-gray-600">
                        {{ $user->email }}
                    </td>

                    <td class="p-4 text-center">
                        <span class="px-3 py-1 text-xs rounded-full font-semibold
                            {{ $user->role == 'admin'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-blue-100 text-blue-700' }}">
                            {{ strtoupper($user->role) }}
                        </span>
                    </td>

                    <td class="p-4 text-center space-x-2">

                        <a href="{{ route('admin.users.edit',$user->id) }}"
                            class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600">
                            Edit
                        </a>

                        <form action="{{ route('admin.users.destroy',$user->id) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Hapus user ini?')">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                                Delete
                            </button>
                        </form>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-6 text-gray-500">
                        Tidak ada user
                    </td>
                </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    <div class="md:hidden space-y-4">

        @forelse($users as $user)
        <div class="bg-white p-4 rounded-xl shadow border">

            <div class="flex justify-between items-center mb-2">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>

                    <div>
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <span class="text-xs px-2 py-1 rounded
                    {{ $user->role == 'admin'
                        ? 'bg-red-100 text-red-700'
                        : 'bg-blue-100 text-blue-700' }}">
                    {{ strtoupper($user->role) }}
                </span>

            </div>

            <div class="flex gap-2 mt-3">

                <a href="{{ route('admin.users.edit',$user->id) }}"
                    class="flex-1 text-center bg-yellow-500 text-white py-1 rounded text-xs">
                    Edit
                </a>

                <form action="{{ route('admin.users.destroy',$user->id) }}"
                    method="POST"
                    class="flex-1"
                    onsubmit="return confirm('Hapus user ini?')">
                    @csrf
                    @method('DELETE')

                    <button class="w-full bg-red-500 text-white py-1 rounded text-xs">
                        Delete
                    </button>
                </form>

            </div>

        </div>
        @empty
        <p class="text-center text-gray-500">Tidak ada user</p>
        @endforelse

    </div>

</div>

@endsection