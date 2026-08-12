<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Tambah Subjek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-soga">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('subjects.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-wedel">Nama Subjek</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            @error('name')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-wedel">Deskripsi</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('subjects.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">Batal</a>
                            <button type="submit" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>