@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto space-y-6">


    <div>
        <h1 class="text-3xl font-bold text-gray-800">➕ Tambah User</h1>
        <p class="text-sm text-gray-500">Buat akun baru</p>
    </div>


    <div class="bg-white p-6 rounded-2xl shadow">

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf


            <div>
                <label class="text-sm text-gray-600">Nama</label>
                <input name="name"
                    value="{{ old('name') }}"
                    class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                    required>
            </div>


            <div>
                <label class="text-sm text-gray-600">Email</label>
                <input name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                    required>
            </div>


            <div>
                <label class="text-sm text-gray-600">Password</label>
                <input name="password"
                    type="password"
                    class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-400"
                    required>
            </div>


            <div>
                <label class="text-sm text-gray-600">Role</label>
                <select name="role"
                    class="w-full mt-1 px-4 py-2 border rounded-xl focus:ring-2 focus:ring-indigo-400">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="flex gap-3 pt-3">

                <a href="{{ route('admin.users.index') }}"
                    class="flex-1 text-center border py-2 rounded-xl hover:bg-gray-100">
                    Batal
                </a>

                <button class="flex-1 bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 shadow">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection