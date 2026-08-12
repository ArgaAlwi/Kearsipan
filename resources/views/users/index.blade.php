<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-wedel leading-tight">
                {{ __('Kelola Pengguna') }}
            </h2>
            <a href="{{ route('users.create') }}" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition">
                + Tambah Pengguna
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

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 border-collapse">
                        <thead class="text-xs text-wedel uppercase bg-primisima border-b-2 border-soga">
                            <tr>
                                <th class="px-6 py-3">Nama Lengkap</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Hak Akses (Role)</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-wedel">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded 
                                            {{ $user->role->name === 'Admin' ? 'bg-red-100 text-red-800' : 
                                              ($user->role->name === 'Operator' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $user->role->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 flex gap-3">
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                        
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>