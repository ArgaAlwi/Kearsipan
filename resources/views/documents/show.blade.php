<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight uppercase tracking-wider">
                {{ __('Rincian Produk Hukum') }}
            </h2>
            <a href="{{ route('documents.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition text-sm font-medium">
                &larr; Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-soga">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- Kolom Kiri: Metadata Dokumen -->
                        <div>
                            <h3 class="text-lg font-bold text-wedel mb-4 border-b-2 border-gray-100 pb-2">Informasi Utama</h3>
                            <table class="w-full text-sm text-left text-gray-600">
                                <tbody>
                                    <tr class="border-b"><th class="py-3 w-2/5 font-semibold">Nomor</th><td class="py-3 font-bold text-wedel text-base">{{ $document->number }}</td></tr>
                                    <tr class="border-b"><th class="py-3 font-semibold">Judul / Perihal</th><td class="py-3">{{ $document->title }}</td></tr>
                                    <tr class="border-b"><th class="py-3 font-semibold">Jenis Dokumen</th><td class="py-3">{{ $document->documentType->name ?? '-' }}</td></tr>
                                    <tr class="border-b"><th class="py-3 font-semibold">Subjek</th><td class="py-3">{{ $document->subject->name ?? '-' }}</td></tr>
                                    <tr class="border-b"><th class="py-3 font-semibold">Tahun</th><td class="py-3">{{ $document->year }}</td></tr>
                                    <tr class="border-b"><th class="py-3 font-semibold">Tanggal Ditetapkan</th><td class="py-3">{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}</td></tr>
                                    <tr><th class="py-3 font-semibold">Diinput Oleh</th><td class="py-3">{{ $document->uploader->name ?? '-' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Kolom Kanan: Deskripsi & File -->
                        <div>
                            <h3 class="text-lg font-bold text-wedel mb-4 border-b-2 border-gray-100 pb-2">Keterangan / Deskripsi</h3>
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200 text-gray-700 min-h-[120px] whitespace-pre-wrap">
                                {{ $document->description ?: 'Tidak ada keterangan tambahan.' }}
                            </div>

                            <h3 class="text-lg font-bold text-wedel mb-4 border-b-2 border-gray-100 pb-2 mt-8">Lampiran Fisik</h3>
                            @if($document->file_path)
                                <div class="flex gap-3 mt-4">
                                    <a href="{{ route('documents.preview', $document->id) }}" target="_blank" class="bg-wedel text-primisima px-6 py-2 rounded-md hover:bg-soga transition font-medium flex items-center">
                                        Lihat PDF
                                    </a>
                                    <a href="{{ route('documents.download', $document->id) }}" class="border border-wedel text-wedel px-6 py-2 rounded-md hover:bg-gray-50 transition font-medium">
                                        Unduh
                                    </a>
                                </div>
                            @else
                                <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 rounded-r-md text-sm">
                                    <span class="font-bold">Tanpa Lampiran.</span> Dokumen ini hanya berupa catatan rekapitulasi dan tidak memiliki file fisik (PDF) yang diunggah ke sistem.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>