<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Tambah Pengguna Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-soga">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-wedel">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-wedel">Alamat Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-wedel">Hak Akses (Role)</label>
                            <select name="role_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                                <option value="">-- Pilih Role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-wedel">Kata Sandi</label>
                                <input type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required autocomplete="new-password">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-wedel">Konfirmasi Kata Sandi</label>
                                <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('users.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">Batal</a>
                            <button type="submit" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition">Simpan Pengguna</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>