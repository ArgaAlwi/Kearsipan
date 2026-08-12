<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rincian Produk Hukum - {{ $document->number }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-wedel min-h-screen flex flex-col relative">
    
    <!-- Garis Atas Sogan -->
    <div class="absolute top-0 left-0 w-full h-2 bg-soga"></div>

    <!-- Navigasi Atas Minimalis -->
    <div class="w-full p-4 bg-white shadow-sm flex justify-between items-center z-20">
        <div class="flex items-center gap-3 ml-4">
            <img src="{{ asset('images/d22b1-Logo_Kota_Yogyakarta.png') }}" alt="Logo Kota Yogyakarta" class="h-10 w-auto">
            <span class="font-extrabold text-lg text-wedel tracking-wider">PRODUK HUKUM</span>
        </div>
        <a href="{{ route('home') }}" class="mr-4 px-4 py-2 text-sm font-semibold text-gray-500 hover:text-soga hover:bg-gray-100 rounded transition">
            &larr; Kembali ke Beranda
        </a>
    </div>

    <!-- Konten Utama -->
    <div class="flex-grow flex flex-col items-center pt-8 px-4 z-10 w-full mb-12">
        <div class="max-w-4xl mx-auto w-full bg-white rounded-xl shadow-lg border-t-4 border-wedel overflow-hidden">
            <div class="p-8">
                
                <h2 class="text-2xl font-bold text-wedel mb-6 border-b-2 border-gray-100 pb-4">Rincian Dokumen</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Kolom Kiri: Metadata Dokumen -->
                    <div>
                        <table class="w-full text-sm text-left text-gray-600">
                            <tbody>
                                <tr class="border-b border-gray-100"><th class="py-3 w-2/5 font-semibold text-gray-800">Nomor</th><td class="py-3 font-bold text-wedel text-base">{{ $document->number }}</td></tr>
                                <tr class="border-b border-gray-100"><th class="py-3 font-semibold text-gray-800">Judul / Perihal</th><td class="py-3">{{ $document->title }}</td></tr>
                                <tr class="border-b border-gray-100"><th class="py-3 font-semibold text-gray-800">Jenis Dokumen</th><td class="py-3">{{ $document->documentType->name ?? '-' }}</td></tr>
                                <tr class="border-b border-gray-100"><th class="py-3 font-semibold text-gray-800">Subjek</th><td class="py-3">{{ $document->subject->name ?? '-' }}</td></tr>
                                <tr class="border-b border-gray-100"><th class="py-3 font-semibold text-gray-800">Tahun</th><td class="py-3">{{ $document->year }}</td></tr>
                                <tr><th class="py-3 font-semibold text-gray-800">Tanggal Ditetapkan</th><td class="py-3">{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Kolom Kanan: Deskripsi & File -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 mb-2 uppercase tracking-wide">Keterangan / Deskripsi</h3>
                        <div class="bg-gray-50 p-4 rounded-md border border-gray-200 text-gray-700 min-h-[100px] whitespace-pre-wrap text-sm leading-relaxed mb-6">{{ $document->description ?: 'Tidak ada keterangan tambahan.' }}</div>

                        <h3 class="text-sm font-bold text-gray-800 mb-2 uppercase tracking-wide">Lampiran Dokumen</h3>
                        @if($document->file_path)
                            <div class="flex gap-3">
                                <a href="{{ route('documents.preview', $document->id) }}" target="_blank" class="bg-wedel text-primisima px-5 py-2 rounded shadow hover:bg-soga transition font-semibold text-sm flex items-center">
                                    Lihat PDF
                                </a>
                                <a href="{{ route('documents.download', $document->id) }}" class="border border-wedel text-wedel px-5 py-2 rounded hover:bg-gray-50 transition font-semibold text-sm">
                                    Unduh PDF
                                </a>
                            </div>
                        @else
                            <div class="p-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-md text-sm">
                                <span class="font-bold">Tanpa Lampiran.</span> Dokumen ini hanya berupa catatan rekapitulasi pada sistem.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="py-6 text-center text-sm text-gray-500 z-10 border-t border-gray-200 bg-white mt-auto">
        &copy; {{ date('Y') }} Hak Cipta Dilindungi. JDIH Produk Hukum.
    </footer>
</body>
</html>