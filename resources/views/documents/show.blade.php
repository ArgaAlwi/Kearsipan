<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight">
                {{ __('Rincian Dokumen') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('documents.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition shadow-sm font-medium text-sm">
                    Kembali
                </a>
                @if(Auth::user()->role?->name === 'Admin' || Auth::user()->role?->name === 'Operator')
                    <a href="{{ route('documents.edit', $document->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition shadow-sm font-medium text-sm">
                        Edit Dokumen
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-wedel">
                <div class="p-8 text-gray-900">
                    
                    <!-- Lencana Status & Nomor -->
                    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between border-b pb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-wedel mb-2">{{ $document->title }}</h1>
                            <p class="text-lg text-gray-600 font-semibold">{{ $document->number }} / Tahun {{ $document->year }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            @if($document->status === 'Berlaku')
                                <span class="px-4 py-2 bg-green-100 text-green-700 text-sm font-bold rounded-lg border border-green-200 uppercase tracking-widest shadow-sm">BERLAKU</span>
                            @elseif($document->status === 'Dicabut')
                                <span class="px-4 py-2 bg-red-100 text-red-700 text-sm font-bold rounded-lg border border-red-200 uppercase tracking-widest shadow-sm">DICABUT</span>
                            @else
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-700 text-sm font-bold rounded-lg border border-yellow-200 uppercase tracking-widest shadow-sm">MENGUBAH</span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Metadata -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Dokumen</p>
                            <p class="text-gray-800 font-medium">{{ $document->documentType->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Subjek / Urusan</p>
                            <p class="text-gray-800 font-medium">{{ $document->subject->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Ditetapkan</p>
                            <p class="text-gray-800 font-medium">{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Diupload Oleh</p>
                            <p class="text-gray-800 font-medium">{{ $document->uploader->name ?? 'Sistem' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi / Keterangan</p>
                            <p class="text-gray-800 bg-gray-50 p-4 rounded-md border border-gray-100">{{ $document->description ?: 'Tidak ada keterangan.' }}</p>
                        </div>
                    </div>

                    <!-- DAFTAR LAMPIRAN (MULTI-FILE) -->
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-bold text-wedel mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-soga" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            File Lampiran
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            
                            <!-- 1. Tampilkan file lama (Jika sebelumnya pernah upload via sistem 1 PDF) -->
                            @if($document->file_path)
                                <div class="flex flex-col justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition group">
                                    <div class="flex items-start mb-3">
                                        <div class="text-3xl mr-3 bg-red-100 p-2 rounded-lg">📄</div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-bold text-gray-800 truncate" title="{{ $document->file_name }}">File Utama (Lama)</p>
                                            <p class="text-xs text-gray-500 mt-0.5">PDF</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full mt-auto">
                                        <a href="{{ route('documents.preview', $document->id) }}" target="_blank" class="flex-1 text-center py-2 bg-gray-100 text-gray-700 hover:bg-wedel hover:text-white rounded-lg text-xs font-bold transition">Lihat</a>
                                        <a href="{{ route('documents.download', $document->id) }}" class="flex-1 text-center py-2 bg-soga text-white hover:bg-orange-700 rounded-lg text-xs font-bold transition">Unduh</a>
                                    </div>
                                </div>
                            @endif

                            <!-- 2. Tampilkan banyak file baru dari tabel document_attachments -->
                            @forelse($document->attachments as $attachment)
                                <div class="flex flex-col justify-between p-4 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition group">
                                    <div class="flex items-start mb-3">
                                        <div class="text-3xl mr-3 bg-blue-50 p-2 rounded-lg">
                                            @if(str_contains($attachment->mime_type, 'image')) 🖼️
                                            @elseif(str_contains($attachment->mime_type, 'pdf')) 📄
                                            @elseif(str_contains($attachment->mime_type, 'spreadsheet') || str_contains($attachment->mime_type, 'excel') || str_contains($attachment->file_name, '.xlsx') || str_contains($attachment->file_name, '.csv')) 📊
                                            @elseif(str_contains($attachment->mime_type, 'word') || str_contains($attachment->file_name, '.docx')) 📝
                                            @elseif(str_contains($attachment->mime_type, 'text') || str_contains($attachment->file_name, '.txt')) 📝
                                            @else 📎
                                            @endif
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-bold text-gray-800 truncate" title="{{ $attachment->file_name }}">
                                                {{ explode('_', $attachment->file_name, 3)[2] ?? $attachment->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full mt-auto">
                                        <!-- Lihat File -->
                                        <a href="{{ route('attachments.preview', $attachment->id) }}" target="_blank" class="flex-1 text-center py-2 bg-gray-100 text-gray-700 hover:bg-wedel hover:text-white rounded-lg text-xs font-bold transition">Lihat</a>
                                        <!-- Unduh File -->
                                        <a href="{{ route('attachments.download', $attachment->id) }}" class="flex-1 text-center py-2 bg-soga text-white hover:bg-orange-700 rounded-lg text-xs font-bold transition">Unduh</a>
                                    </div>
                                </div>
                            @empty
                                @if(!$document->file_path)
                                    <div class="col-span-full flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                                        <span class="text-4xl mb-3 grayscale opacity-50">📁</span>
                                        <p class="text-gray-500 font-medium">Tidak ada file lampiran pada dokumen ini.</p>
                                    </div>
                                @endif
                            @endforelse

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>