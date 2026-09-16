<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rincian Dokumen - {{ $document->number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50 min-h-screen">
    
    <!-- Navbar Sederhana -->
    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="font-bold text-xl text-wedel tracking-wider">
                <span class="text-soga">JDIH</span> Instansi
            </div>
            <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 hover:text-soga transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Pencarian
            </a>
        </div>
    </nav>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border-t-8 border-soga">
                <div class="p-8 sm:p-12">
                    
                    <!-- Judul Dokumen -->
                    <div class="mb-10 border-b border-gray-100 pb-8">
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-wedel mb-4 leading-tight">{{ $document->title }}</h1>
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-700 font-bold rounded-full text-sm shadow-inner">
                                {{ $document->number }} / Tahun {{ $document->year }}
                            </span>
                            @if($document->status === 'Berlaku')
                                <span class="px-4 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200 uppercase tracking-widest shadow-sm">BERLAKU</span>
                            @elseif($document->status === 'Dicabut')
                                <span class="px-4 py-1.5 bg-red-100 text-red-700 text-xs font-bold rounded-full border border-red-200 uppercase tracking-widest shadow-sm">DICABUT</span>
                            @else
                                <span class="px-4 py-1.5 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full border border-yellow-200 uppercase tracking-widest shadow-sm">MENGUBAH</span>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Rincian (Tabel Clean) -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <div class="space-y-4">
                            <h3 class="text-lg font-bold text-wedel border-b border-gray-200 pb-2">Informasi Dokumen</h3>
                            <div class="flex justify-between py-2 border-b border-dashed border-gray-100">
                                <span class="text-gray-500 font-medium">Jenis</span>
                                <span class="text-gray-900 font-bold text-right">{{ $document->documentType->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-dashed border-gray-100">
                                <span class="text-gray-500 font-medium">Subjek / Urusan</span>
                                <span class="text-gray-900 font-bold text-right">{{ $document->subject->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-dashed border-gray-100">
                                <span class="text-gray-500 font-medium">Tanggal Ditetapkan</span>
                                <span class="text-gray-900 font-bold text-right">{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 h-full flex flex-col">
                            <h3 class="text-lg font-bold text-wedel border-b border-gray-200 pb-2">Keterangan Singkat</h3>
                            <div class="flex-grow bg-gray-50 p-4 rounded-xl text-sm text-gray-700 border border-gray-100 leading-relaxed">
                                {{ $document->description ?: 'Tidak ada keterangan khusus untuk dokumen ini.' }}
                            </div>
                        </div>
                    </div>

                    <!-- DAFTAR LAMPIRAN (MULTI-FILE) -->
                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-xl font-bold text-wedel mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-soga" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            File Lampiran Dokumen
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <!-- File Utama Lama -->
                            @if($document->file_path)
                                <div class="flex flex-col justify-between p-5 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-soga hover:shadow-md transition-all group">
                                    <div class="flex items-start mb-4">
                                        <div class="text-3xl mr-3 bg-red-50 p-3 rounded-xl group-hover:scale-110 transition-transform">📄</div>
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-bold text-gray-800 truncate" title="{{ $document->file_name }}">File Utama (Lama)</p>
                                            <p class="text-xs text-gray-500 mt-1">Dokumen PDF</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full mt-auto">
                                        <a href="{{ route('documents.preview', $document->id) }}" target="_blank" class="flex-1 text-center py-2 bg-gray-100 text-gray-700 hover:bg-wedel hover:text-white rounded-lg text-sm font-bold transition">Lihat</a>
                                        <a href="{{ route('documents.download', $document->id) }}" class="flex-1 text-center py-2 bg-soga text-white hover:bg-orange-700 rounded-lg text-sm font-bold transition flex justify-center items-center">
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            <!-- Lampiran Baru -->
                            @forelse($document->attachments as $attachment)
                                <div class="flex flex-col justify-between p-5 bg-white border-2 border-gray-100 rounded-2xl shadow-sm hover:border-soga hover:shadow-md transition-all group">
                                    <div class="flex items-start mb-4">
                                        <div class="text-3xl mr-3 bg-gray-50 p-3 rounded-xl group-hover:scale-110 transition-transform">
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
                                            <p class="text-xs text-gray-500 mt-1">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-2 w-full mt-auto">
                                        <a href="{{ route('attachments.preview', $attachment->id) }}" target="_blank" class="flex-1 text-center py-2 bg-gray-100 text-gray-700 hover:bg-wedel hover:text-white rounded-lg text-sm font-bold transition">Lihat</a>
                                        <a href="{{ route('attachments.download', $attachment->id) }}" class="flex-1 text-center py-2 bg-soga text-white hover:bg-orange-700 rounded-lg text-sm font-bold transition flex justify-center items-center">
                                            Unduh
                                        </a>
                                    </div>
                                </div>
                            @empty
                                @if(!$document->file_path)
                                    <div class="col-span-full bg-yellow-50 border border-yellow-200 text-yellow-800 p-6 rounded-2xl flex items-center">
                                        <svg class="w-8 h-8 mr-4 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <div>
                                            <p class="font-bold">Tanpa Lampiran Fisik</p>
                                            <p class="text-sm mt-1">Dokumen ini hanya berupa catatan rekapitulasi pada sistem dan tidak memiliki file yang dapat diunduh.</p>
                                        </div>
                                    </div>
                                @endif
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>