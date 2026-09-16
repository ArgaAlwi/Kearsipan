<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight">
                {{ __('Tong Sampah Dokumen') }}
            </h2>
            <a href="{{ route('documents.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition shadow-sm font-medium text-sm">
                Kembali ke Daftar Dokumen
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-red-500">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    <table class="w-full text-sm text-left text-gray-500 border-collapse">
                        <thead class="text-xs text-wedel uppercase bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3">Nomor</th>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Waktu Dihapus</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $doc)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-wedel">{{ $doc->number }}</td>
                                    <td class="px-6 py-4">{{ $doc->title }}</td>
                                    <td class="px-6 py-4">{{ $doc->documentType->name ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $doc->deleted_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 flex gap-3">
                                        <!-- Form Pulihkan (Restore) -->
                                        <form action="{{ route('documents.restore', $doc->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 font-medium">Pulihkan</button>
                                        </form>

                                        <!-- Form Hapus Permanen (Force Delete) -->
                                        <form id="force-delete-form-{{ $doc->id }}" action="{{ route('documents.forceDelete', $doc->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                        <button type="button" onclick="confirmForceDelete({{ $doc->id }})" class="text-red-600 hover:text-red-900 font-medium">Hapus Permanen</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        Tong sampah kosong.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!-- Script SweetAlert Konfirmasi Hapus Permanen -->
    <script>
        function confirmForceDelete(id) {
            Swal.fire({
                title: 'Hapus Permanen?',
                text: "Dokumen beserta lampiran PDF aslinya akan dihapus selamanya dan TIDAK BISA dipulihkan!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Basmi!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('force-delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-app-layout>