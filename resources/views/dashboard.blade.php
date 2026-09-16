<<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight uppercase tracking-wider">
            {{ __('Dashboard Produk Hukum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
           
            
            <!-- (Biarkan kode Grid Kartu Statistik dan Tabel Dokumen Terbaru yang sudah ada di bawahnya) -->

            <!-- Grid Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1: Dokumen -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-soga hover:shadow-md transition">
                    <div class="p-6 text-wedel flex items-center justify-between">
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500 uppercase tracking-wider">Total Dokumen</p>
                            <p class="text-3xl font-bold text-wedel">{{ $totalDocuments }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-primisima text-soga">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pengguna -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-soga hover:shadow-md transition">
                    <div class="p-6 text-wedel flex items-center justify-between">
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                            <p class="text-3xl font-bold text-wedel">{{ $totalUsers }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-primisima text-soga">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Jenis Dokumen -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-b-4 border-soga hover:shadow-md transition">
                    <div class="p-6 text-wedel flex items-center justify-between">
                        <div>
                            <p class="mb-1 text-sm font-medium text-gray-500 uppercase tracking-wider">Jenis Dokumen</p>
                            <p class="text-3xl font-bold text-wedel">{{ $totalDocumentTypes }}</p>
                        </div>
                        <div class="p-3 rounded-full bg-primisima text-soga">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Dokumen Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-wedel">Dokumen Baru Ditambahkan</h3>
                        <a href="{{ route('documents.index') }}" class="text-sm text-soga hover:text-wedel font-semibold transition">Lihat Semua &rarr;</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 border-collapse">
                            <thead class="text-xs text-wedel uppercase bg-primisima border-b-2 border-soga">
                                <tr>
                                    <th class="px-6 py-3">Nomor</th>
                                    <th class="px-6 py-3">Judul</th>
                                    <th class="px-6 py-3">Jenis</th>
                                    <th class="px-6 py-3">Tahun</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentDocuments as $doc)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-wedel">{{ $doc->number }}</td>
                                        <td class="px-6 py-4">{{ $doc->title }}</td>
                                        <td class="px-6 py-4">{{ $doc->documentType->name }}</td>
                                        <td class="px-6 py-4">{{ $doc->year }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada dokumen yang diunggah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>