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
    
    <!-- Background Dekorasi -->
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

            <!-- KOTAK PENCARIAN & FILTER -->
            <div class="w-full max-w-4xl mx-auto mb-10 bg-white p-4 rounded-xl shadow-lg border-t-4 border-soga text-left">
                <form action="{{ route('home') }}" method="GET">
                    <div class="flex flex-col md:flex-row gap-3">
                        
                        <!-- Input Kata Kunci -->
                        <div class="flex-grow">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Pencarian</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Nomor, judul, atau perihal..." class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:ring-soga focus:border-soga text-gray-700 outline-none transition shadow-sm">
                            </div>
                        </div>

                        <!-- Filter Jenis -->
                        <div class="w-full md:w-56">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Jenis Produk</label>
                            <select name="type" class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-soga focus:border-soga text-gray-700 bg-white outline-none transition shadow-sm cursor-pointer">
                                <option value="">Semua Jenis</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tahun -->
                        <div class="w-full md:w-32">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Tahun</label>
                            <select name="year" class="w-full px-4 py-3 rounded-md border border-gray-300 focus:ring-soga focus:border-soga text-gray-700 bg-white outline-none transition shadow-sm cursor-pointer">
                                <option value="">Semua</option>
                                @for ($i = date('Y'); $i >= date('Y') - 20; $i--)
                                    <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Tombol Cari -->
                        <div class="flex items-end mt-2 md:mt-0">
                            <button type="submit" class="w-full md:w-auto h-[50px] bg-wedel text-primisima px-8 rounded-md hover:bg-soga transition font-bold shadow-md tracking-wider">
                                CARI
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- TABEL DOKUMEN -->
            <div class="w-full bg-white rounded-xl shadow-lg border-t-4 border-wedel overflow-hidden mb-12 text-left">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                    <span class="text-wedel font-bold text-lg">
                        {{ request()->anyFilled(['keyword', 'type', 'year']) ? 'Hasil Pencarian Dokumen' : 'Dokumen Hukum Terbaru' }}
                    </span>
                    @if(request()->anyFilled(['keyword', 'type', 'year']))
                        <a href="{{ route('home') }}" class="text-sm text-soga hover:underline font-semibold">Reset Filter</a>
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
                                        
                                        <!-- Lencana Status -->
                                        <div class="mt-1 mb-1">
                                            @if($doc->status === 'Berlaku')
                                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded border border-green-200 uppercase">BERLAKU</span>
                                            @elseif($doc->status === 'Dicabut')
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded border border-red-200 uppercase">DICABUT</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded border border-yellow-200 uppercase">MENGUBAH</span>
                                            @endif
                                        </div>

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
                
                <!-- Pagination -->
                @if(request()->anyFilled(['keyword', 'type', 'year']) && $documents instanceof \Illuminate\Pagination\LengthAwarePaginator)
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