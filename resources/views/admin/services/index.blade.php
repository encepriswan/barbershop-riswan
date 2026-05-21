@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">✂️ Layanan</h1>
            <p class="text-sm text-gray-500">Kelola layanan barbershop</p>
        </div>

        <a href="{{ route('admin.services.create') }}"
            class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition text-sm">
            + Tambah Layanan
        </a>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

        @forelse($services as $s)

        <div class="bg-white rounded-xl shadow hover:shadow-lg transition p-5 flex flex-col justify-between">


            <div>
                <h2 class="font-semibold text-lg text-indigo-600 mb-2">
                    {{ $s->name }}
                </h2>


                <div class="space-y-1 text-sm text-gray-600">
                    <p>⏱ Durasi:
                        <span class="font-medium">{{ $s->duration }} menit</span>
                    </p>

                    <p>💰 Harga:
                        <span class="font-bold text-indigo-600">
                            Rp {{ number_format($s->price) }}
                        </span>
                    </p>
                </div>
            </div>


            <div class="mt-4 flex gap-2">


                <a href="{{ route('admin.services.edit',$s->id) }}"
                    class="flex-1 text-center bg-blue-500 text-white py-1.5 rounded text-xs hover:bg-blue-600 transition">
                    Edit
                </a>


                <form method="POST"
                    action="{{ route('admin.services.destroy',$s->id) }}"
                    class="flex-1 delete-form">
                    @csrf
                    @method('DELETE')

                    <button type="button"
                        class="w-full bg-red-500 text-white py-1.5 rounded text-xs hover:bg-red-600 transition btn-delete">
                        Delete
                    </button>
                </form>

            </div>

        </div>

        @empty
        <div class="col-span-full text-center text-gray-500 py-10">
            Belum ada layanan
        </div>
        @endforelse

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.querySelectorAll('.btn-delete').forEach((btn, index) => {
        btn.addEventListener('click', function() {

            const form = this.closest('form');

            Swal.fire({
                title: 'Hapus layanan?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection