<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight">
                {{ __('Daftar Dokumen Kearsipan') }}
            </h2>
            
            @if(Auth::user()->role?->name === 'Admin' || Auth::user()->role?->name === 'Operator')
                <a href="{{ route('documents.create') }}" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition shadow-sm font-medium">
                    + Tambah Dokumen
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- KOTAK PENCARIAN & FILTER -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-wedel">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-wedel mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Pencarian Dokumen
                    </h3>
                    
                    <form action="{{ route('documents.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            
                            <!-- Kata Kunci -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kata Kunci</label>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Judul / Deskripsi..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                            </div>

                            <!-- Jenis Peraturan -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jenis Dokumen</label>
                                <select name="document_type_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                                    <option value="">Semua Jenis</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subjek -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Subjek</label>
                                <select name="subject_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                                    <option value="">Semua Subjek</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nomor -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nomor</label>
                                <input type="text" name="number" value="{{ request('number') }}" placeholder="Misal: 1, 2A" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                            </div>

                            <!-- Tahun -->
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tahun</label>
                                <select name="year" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                                    <option value="">Semua Tahun</option>
                                    @for ($i = date('Y'); $i >= date('Y') - 20; $i--)
                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-4 flex justify-end gap-2">
                            <a href="{{ route('documents.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition text-sm font-medium">Reset Filter</a>
                            <button type="submit" class="bg-wedel text-primisima px-6 py-2 rounded-md hover:bg-soga transition text-sm font-medium flex items-center">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL HASIL PENCARIAN -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    
                    @if(request()->anyFilled(['keyword', 'document_type_id', 'subject_id', 'number', 'year']))
                        <div class="mb-4 text-sm text-gray-600">
                            Menampilkan hasil pencarian. Ditemukan <span class="font-bold text-wedel">{{ $documents->total() }}</span> dokumen.
                        </div>
                    @endif

                    <table class="w-full text-sm text-left text-gray-500 border-collapse">
                        <thead class="text-xs text-wedel uppercase bg-primisima border-b-2 border-soga">
                            <tr>
                                <th class="px-6 py-3">Nomor</th>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Tahun</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $doc)
                                <tr class="bg-white border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-wedel whitespace-nowrap">{{ $doc->number }}</td>
                                    <td class="px-6 py-4">{{ $doc->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $doc->documentType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $doc->year }}</td>
                                    <td class="px-6 py-4 flex flex-wrap gap-3 items-center">
                                        
                                        <td class="px-6 py-4 flex flex-wrap gap-3 items-center">
    
    <!-- TOMBOL DETAIL (Selalu muncul untuk semua dokumen) -->
    <a href="{{ route('documents.show', $doc->id) }}" class="text-wedel hover:text-soga font-bold underline decoration-2 underline-offset-4">Detail</a>

    <!-- CEK: Jika punya lampiran PDF, tampilkan Lihat & Unduh -->
    @if($doc->file_path)
        <a href="{{ route('documents.preview', $doc->id) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-medium">Lihat PDF</a>
        <a href="{{ route('documents.download', $doc->id) }}" class="text-green-600 hover:text-green-900 font-medium">Unduh</a>
    <!-- JIKA TIDAK: Tampilkan lencana Tanpa Lampiran -->
    @else
        <span class="px-2 py-1 bg-gray-200 text-gray-500 text-xs rounded-md italic">Tanpa Lampiran</span>
    @endif
    
    @if(Auth::user()->role?->name === 'Admin' || Auth::user()->role?->name === 'Operator')
        <a href="{{ route('documents.edit', $doc->id) }}" class="text-yellow-600 hover:text-yellow-900 font-medium">Edit</a>
    @endif
    
    @if(Auth::user()->role?->name === 'Admin')
        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini secara permanen?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
        </form>
    @endif

</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm font-medium">Tidak ada dokumen yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <!-- Pagination Nav -->
                    <div class="mt-4">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>