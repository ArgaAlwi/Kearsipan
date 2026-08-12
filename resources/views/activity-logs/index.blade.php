<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Rekam Jejak Aktivitas (Audit Log)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-wedel">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 border-collapse">
                        <thead class="text-xs text-wedel uppercase bg-primisima border-b-2 border-soga">
                            <tr>
                                <th class="px-6 py-3">Waktu</th>
                                <th class="px-6 py-3">Pengguna</th>
                                <th class="px-6 py-3">Aksi</th>
                                <th class="px-6 py-3">Deskripsi / Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td class="px-6 py-4 font-medium text-wedel">{{ $log->user->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">{{ $log->description }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas yang terekam.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>