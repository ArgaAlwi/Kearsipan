<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Tambah Dokumen Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-soga">
                <div class="p-6 text-gray-900">
                    
                    <!-- Perhatikan enctype="multipart/form-data" wajib untuk upload file -->
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Jenis Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Jenis Dokumen</label>
                                <select name="document_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subjek -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Subjek</label>
                                <select name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                                    <option value="">-- Pilih Subjek --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nomor Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Nomor Dokumen</label>
                                <input type="text" name="number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>

                            <!-- Judul Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Judul / Perihal</label>
                                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Tahun</label>
                                <input type="number" name="year" value="{{ date('Y') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>

                            <!-- Tanggal Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Tanggal Ditetapkan</label>
                                <input type="date" name="document_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-wedel">Keterangan / Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga"></textarea>
                        </div>

                        <!-- Upload File -->
<div class="md:col-span-2">
    <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
        <label for="document_file" class="block text-sm font-semibold text-wedel mb-2">
            Unggah File PDF (Opsional, Maks. 10MB)
        </label>
        <!-- Perhatikan kata 'required' sudah dihapus pada tag input di bawah ini -->
        <input type="file" name="document_file" id="document_file" accept=".pdf" class="w-full border border-gray-300 rounded-md bg-white focus:ring-soga focus:border-soga p-2">
        @error('document_file')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('documents.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">Batal</a>
                            <button type="submit" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition">Simpan Data</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>