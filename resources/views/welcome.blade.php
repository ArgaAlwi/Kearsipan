<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Produk Hukum</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-primisima text-wedel min-h-screen flex flex-col relative">
    
    <div class="absolute top-0 left-0 w-full h-2 bg-soga"></div>
    <div class="fixed -top-24 -right-24 w-96 h-96 bg-wedel opacity-5 rounded-full blur-3xl -z-10"></div>
    <div class="fixed -bottom-24 -left-24 w-72 h-72 bg-soga opacity-10 rounded-full blur-2xl -z-10"></div>

    <!-- Header Tombol Login -->
    <div class="w-full p-6 flex justify-end z-20">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-wedel hover:text-soga underline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-6 py-2 bg-soga text-primisima rounded-full font-bold text-sm hover:bg-wedel transition shadow">Log In</a>
            @endauth
        @endif
    </div>

    <div class="flex-grow flex flex-col items-center pt-10 px-6 z-10 w-full">
        <div class="max-w-5xl mx-auto text-center w-full">
            
            <div class="flex justify-center mb-6">
                <div class="p-4 bg-white rounded-2xl shadow border-b-4 border-soga">
                    <img src="{{ asset('images/d22b1-Logo_Kota_Yogyakarta.png') }}" alt="Logo Kota Yogyakarta" class="h-24 w-auto">
                </div>
            </div>

            <h1 class="text-3xl md:text-5xl font-extrabold mb-2 tracking-tight text-wedel uppercase">PRODUK HUKUM</h1>
            <p class="text-md md:text-lg text-gray-600 mb-8 uppercase tracking-widest font-medium">Manajemen Produk Hukum</p>

            <!-- KOTAK PENCARIAN -->
            <div class="w-full max-w-2xl mx-auto mb-10">
                <form action="{{ route('home') }}" method="GET" class="relative">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nomor, judul, atau perihal dokumen..." class="w-full pl-6 pr-16 py-4 rounded-full border border-gray-300 focus:ring-soga focus:border-soga shadow-lg text-gray-700 font-medium text-lg outline-none">
                    <button type="submit" class="absolute right-2 top-2 bottom-2 bg-soga text-primisima px-6 rounded-full hover:bg-wedel transition font-bold">
                        Cari
                    </button>
                </form>
            </div>

            <!-- TABEL DOKUMEN -->
            <div class="w-full bg-white rounded-xl shadow-lg border-t-4 border-wedel overflow-hidden mb-12 text-left">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                    <span class="text-wedel font-bold text-lg">
                        {{ request()->filled('keyword') ? 'Hasil Pencarian: "' . request('keyword') . '"' : 'Dokumen Hukum Terbaru' }}
                    </span>
                    @if(request()->filled('keyword'))
                        <a href="{{ route('home') }}" class="text-sm text-soga hover:underline">Reset</a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-600">
                        <thead class="bg-primisima text-wedel text-xs uppercase border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4">Nomor & Judul</th>
                                <th class="px-6 py-4">Jenis</th>
                                <th class="px-6 py-4 text-center">Tahun</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($documents as $doc)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-wedel text-base">{{ $doc->number }}</div>
                                        <div class="text-gray-500 mt-1">{{ $doc->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $doc->documentType->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center font-medium">{{ $doc->year }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2 items-center">
                                            <!-- Tombol Detail selalu muncul untuk publik -->
                                            <a href="{{ route('public.document.show', $doc->id) }}" class="px-4 py-2 bg-wedel text-white rounded hover:bg-soga font-medium text-xs shadow transition">Detail</a>
                                            
                                            <!-- Tombol Unduh hanya muncul jika ada file PDF -->
                                            @if($doc->file_path)
                                                <a href="{{ route('documents.download', $doc->id) }}" class="px-4 py-2 border border-wedel text-wedel rounded hover:bg-gray-100 font-medium text-xs transition">Unduh PDF</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        Tidak ada produk hukum yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination (hanya muncul jika menggunakan sistem pencarian) -->
                @if(request()->filled('keyword') && $documents instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-4 border-t bg-white">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    <footer class="py-6 text-center text-sm text-gray-500 z-10 border-t border-gray-200 bg-white">
        &copy; {{ date('Y') }} Hak Cipta Dilindungi. JDIH Produk Hukum.
    </footer>
</body>
</html>