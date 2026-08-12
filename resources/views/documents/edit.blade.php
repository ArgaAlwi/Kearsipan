<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Edit Dokumen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-soga">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Wajib untuk proses Update di Laravel -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Jenis Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Jenis Dokumen</label>
                                <select name="document_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ $document->document_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subjek -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Subjek</label>
                                <select name="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                                    <option value="">-- Pilih Subjek --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $document->subject_id == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nomor Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Nomor Dokumen</label>
                                <input type="text" name="number" value="{{ old('number', $document->number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>

                            <!-- Judul Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Judul / Perihal</label>
                                <input type="text" name="title" value="{{ old('title', $document->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Tahun</label>
                                <input type="number" name="year" value="{{ old('year', $document->year) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>

                            <!-- Tanggal Dokumen -->
                            <div>
                                <label class="block text-sm font-medium text-wedel">Tanggal Ditetapkan</label>
                                <input type="date" name="document_date" value="{{ old('document_date', $document->document_date) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga" required>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-wedel">Keterangan / Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-soga focus:ring-soga">{{ old('description', $document->description) }}</textarea>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-6 p-4 border border-dashed border-gray-400 rounded-md bg-primisima">
                            <label class="block text-sm font-medium text-wedel mb-2">Perbarui File PDF (Biarkan kosong jika tidak ingin mengubah file)</label>
                            <p class="text-sm text-gray-500 mb-3">File saat ini: <span class="font-semibold text-wedel">{{ $document->file_name }}</span></p>
                            
                            <input type="file" name="document_file" accept="application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-soga file:text-primisima hover:file:bg-wedel cursor-pointer">
                            @error('document_file')
                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('documents.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition">Batal</a>
                            <button type="submit" class="bg-soga text-primisima px-4 py-2 rounded-md hover:bg-wedel transition">Simpan Perubahan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>