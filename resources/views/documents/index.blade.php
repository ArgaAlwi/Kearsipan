<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight">
                {{ __('Daftar Dokumen Kearsipan') }}
            </h2>
            
            <div class="flex gap-2">
                @if(Auth::user()->role?->name === 'Admin')
                    <a href="{{ route('documents.trash') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition shadow-sm font-medium flex items-center text-sm">
                        🗑️ Tong Sampah
                    </a>
                    <a href="{{ route('documents.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition shadow-sm font-medium flex items-center text-sm">
                        📊 Export Excel
                    </a>
                @endif
                
                @if(Auth::user()->role?->name === 'Admin' || Auth::user()->role?->name === 'Operator')
                    <a href="{{ route('documents.create') }}" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition shadow-sm font-medium text-sm">
                        + Tambah Dokumen
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KOTAK PENCARIAN & FILTER -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border-t-4 border-wedel">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold text-wedel mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Pencarian Dokumen
                    </h3>
                    
                    <form action="{{ route('documents.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kata Kunci</label>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Judul / Deskripsi..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                            </div>

                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jenis Dokumen</label>
                                <select name="document_type_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                                    <option value="">Semua Jenis</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Subjek</label>
                                <select name="subject_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                                    <option value="">Semua Subjek</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="lg:col-span-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nomor</label>
                                <input type="text" name="number" value="{{ request('number') }}" placeholder="Misal: 1, 2A" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga text-sm">
                            </div>

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
                                <th class="px-6 py-3">Nomor & Status</th>
                                <th class="px-6 py-3">Judul</th>
                                <th class="px-6 py-3">Jenis</th>
                                <th class="px-6 py-3">Tahun</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $doc)
                                <tr class="bg-white border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-wedel whitespace-nowrap">
                                        <div class="text-base">{{ $doc->number }}</div>
                                        <div class="mt-1">
                                            @if($doc->status === 'Berlaku')
                                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded border border-green-200 uppercase">BERLAKU</span>
                                            @elseif($doc->status === 'Dicabut')
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded border border-red-200 uppercase">DICABUT</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded border border-yellow-200 uppercase">MENGUBAH</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">{{ $doc->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $doc->documentType->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $doc->year }}</td>
                                    <td class="px-6 py-4 flex flex-wrap gap-3 items-center">
                                        
                                        <a href="{{ route('documents.show', $doc->id) }}" class="text-wedel hover:text-soga font-bold underline decoration-2 underline-offset-4">Detail</a>

                                        @if($doc->file_path || $doc->attachments->count() > 0)
    <a href="{{ route('documents.show', $doc->id) }}#lampiran" class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-md hover:bg-blue-200 transition flex items-center gap-1 border border-blue-200">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
        {{ $doc->file_path ? 1 : 0 + $doc->attachments->count() }} File
    </a>
@else
    <span class="px-2 py-1 bg-gray-100 text-gray-400 text-[10px] uppercase font-bold rounded-md border border-gray-200 border-dashed">Kosong</span>
@endif
                                        
                                        @if(Auth::user()->role?->name === 'Admin' || Auth::user()->role?->name === 'Operator')
                                            <a href="{{ route('documents.edit', $doc->id) }}" class="text-yellow-600 hover:text-yellow-900 font-medium">Edit</a>
                                        @endif
                                        
                                        @if(Auth::user()->role?->name === 'Admin')
                                            <!-- Form Hapus Hidden -->
                                            <form id="delete-form-{{ $doc->id }}" action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                            <!-- Tombol Hapus dengan SweetAlert -->
                                            <button type="button" onclick="confirmDelete({{ $doc->id }})" class="text-red-600 hover:text-red-900 font-medium cursor-pointer">Hapus</button>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        <p class="mt-2 text-sm font-medium">Tidak ada dokumen yang ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script SweetAlert Konfirmasi Hapus -->
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Pindahkan ke Tong Sampah?',
                text: "Dokumen ini akan disembunyikan dan dapat dipulihkan nanti.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-xl' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-app-layout>