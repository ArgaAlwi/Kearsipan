<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight">
                {{ __('Kelola Subjek') }}
            </h2>
            <a href="{{ route('subjects.create') }}" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition">
                + Tambah Subjek
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 border-collapse">
                        <thead class="text-xs text-wedel uppercase bg-primisima border-b-2 border-soga">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Nama Subjek</th>
                                <th class="px-6 py-3">Deskripsi</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $index => $subject)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $subjects->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 font-medium text-wedel">{{ $subject->name }}</td>
                                    <td class="px-6 py-4">{{ $subject->description ?: '-' }}</td>
                                    <td class="px-6 py-4 flex gap-3">
                                        <a href="{{ route('subjects.edit', $subject->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                        
                                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus subjek ini juga akan menghapus SELURUH DOKUMEN yang menggunakan subjek ini. Lanjutkan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data subjek.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $subjects->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>