<x-guest-layout>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 px-4">

    <div class="w-full max-w-md bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl p-8 animate-fade-in">

        <!-- Title -->
        <div class="text-center mb-6">
            <h2 class="text-3xl font-extrabold text-gray-800">✨ Buat Akun</h2>
            <p class="text-sm text-gray-500">Daftar untuk mulai booking</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div class="relative">
                <input type="text" name="name" required
                    class="peer w-full px-4 pt-5 pb-2 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-transparent"
                    placeholder="Nama">
                <label class="absolute left-4 top-2 text-gray-500 text-sm 
                    peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base 
                    peer-placeholder-shown:text-gray-400 transition-all">
                    Nama
                </label>
            </div>

            <!-- Email -->
            <div class="relative">
                <input type="email" name="email" required
                    class="peer w-full px-4 pt-5 pb-2 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-transparent"
                    placeholder="Email">
                <label class="absolute left-4 top-2 text-gray-500 text-sm 
                    peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base 
                    peer-placeholder-shown:text-gray-400 transition-all">
                    Email
                </label>
            </div>

            <!-- Password -->
            <div class="relative">
                <input type="password" name="password" required
                    class="peer w-full px-4 pt-5 pb-2 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-transparent"
                    placeholder="Password">
                <label class="absolute left-4 top-2 text-gray-500 text-sm 
                    peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base 
                    peer-placeholder-shown:text-gray-400 transition-all">
                    Password
                </label>
            </div>

            <!-- Confirm -->
            <div class="relative">
                <input type="password" name="password_confirmation" required
                    class="peer w-full px-4 pt-5 pb-2 border rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none placeholder-transparent"
                    placeholder="Konfirmasi Password">
                <label class="absolute left-4 top-2 text-gray-500 text-sm 
                    peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base 
                    peer-placeholder-shown:text-gray-400 transition-all">
                    Konfirmasi Password
                </label>
            </div>

            <!-- Button -->
            <button
                class="w-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-3 rounded-xl font-semibold shadow-lg hover:scale-105 hover:shadow-xl transition duration-300">
                Register
            </button>

            <p class="text-center text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">
                    Login
                </a>
            </p>
        </form>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>

</x-guest-layout>