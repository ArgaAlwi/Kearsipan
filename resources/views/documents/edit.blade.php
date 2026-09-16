<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Edit Dokumen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-yellow-500">
                <div class="p-6 text-gray-900">
                    
                    <form id="uploadForm" action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Jenis Dokumen -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Jenis Dokumen <span class="text-red-500">*</span></label>
                                <select name="document_type_id" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ $document->document_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subjek -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Subjek <span class="text-red-500">*</span></label>
                                <select name="subject_id" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $document->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nomor Dokumen -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Nomor Dokumen <span class="text-red-500">*</span></label>
                                <input type="text" name="number" value="{{ old('number', $document->number) }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Judul -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Judul / Perihal <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $document->title) }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Tahun <span class="text-red-500">*</span></label>
                                <input type="number" name="year" value="{{ old('year', $document->year) }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Tanggal Ditetapkan -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Tanggal Ditetapkan <span class="text-red-500">*</span></label>
                                <input type="date" name="document_date" value="{{ old('document_date', $document->document_date) }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Status Peraturan -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-wedel mb-2">Status Peraturan <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                                    <option value="Berlaku" {{ $document->status == 'Berlaku' ? 'selected' : '' }}>Berlaku</option>
                                    <option value="Mengubah" {{ $document->status == 'Mengubah' ? 'selected' : '' }}>Mengubah</option>
                                    <option value="Dicabut" {{ $document->status == 'Dicabut' ? 'selected' : '' }}>Dicabut</option>
                                </select>
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-wedel mb-2">Keterangan / Deskripsi Singkat</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2">{{ old('description', $document->description) }}</textarea>
                            </div>

                            <!-- Upload Lampiran Baru (Multi-file) -->
                            <div class="md:col-span-2">
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                                    <label class="block text-sm font-semibold text-wedel mb-2">Tambahkan Lampiran Baru (Opsional, bisa lebih dari satu)</label>
                                    <input type="file" name="document_files[]" multiple accept=".pdf,.tif,.txt,.xlsx,.docx,.jpg,.jpeg,.png" class="w-full border border-gray-300 rounded-md bg-white focus:ring-soga focus:border-soga p-2">
                                    <p class="text-xs text-gray-500 mt-2">Format: PDF, TIF, TXT, Excel, Word, JPG, PNG. (Total Maks: 1GB).</p>
                                    <p class="text-xs text-blue-500 mt-1">Biarkan kosong jika tidak ingin menambah lampiran baru.</p>
                                </div>
                            </div>
                        </div>

                        <!-- WADAH PROGRESS BAR (Awalnya Disembunyikan) -->
                        <div id="progressContainer" class="hidden mt-6 bg-gray-100 rounded-full h-6 w-full overflow-hidden shadow-inner relative">
                            <div id="progressBar" class="bg-yellow-500 h-6 w-0 transition-all duration-300 ease-out flex items-center justify-center"></div>
                            <div id="progressText" class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800 drop-shadow-md">0%</div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                            <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Batal</a>
                            <button type="submit" id="submitBtn" class="px-6 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition font-bold shadow">Perbarui Dokumen</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    <!-- SCRIPT AJAX PROGRESS BAR UNTUK EDIT -->
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            let form = this;
            let submitBtn = document.getElementById('submitBtn');
            let progressContainer = document.getElementById('progressContainer');
            let progressBar = document.getElementById('progressBar');
            let progressText = document.getElementById('progressText');
            
            let formData = new FormData(form);

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.innerText = 'Memperbarui...';
            progressContainer.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.innerText = '0%';

            let xhr = new XMLHttpRequest();
            // Harus POST karena PHP tidak bisa baca multipart/form-data via PUT murni.
            // Laravel akan membaca _method=PUT dari FormData secara otomatis.
            xhr.open('POST', form.action); 
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    let percentComplete = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.innerText = percentComplete + '%';
                    
                    if(percentComplete === 100) {
                        progressText.innerText = 'Menyimpan Pembaruan... Mohon Tunggu.';
                        progressBar.classList.add('animate-pulse');
                    }
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200 || xhr.status === 201 || xhr.status === 302 || xhr.status === 204) {
                    window.location.href = "{{ route('documents.index') }}";
                } else if (xhr.status === 422) {
                    resetUI();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Silakan periksa kembali kelengkapan form dan tipe file Anda.',
                        customClass: { popup: 'rounded-xl' }
                    });
                } else {
                    resetUI();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server saat memperbarui data.',
                        customClass: { popup: 'rounded-xl' }
                    });
                }
            };

            xhr.onerror = function() {
                resetUI();
                Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Periksa jaringan Anda.' });
            };

            xhr.send(formData);

            function resetUI() {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.innerText = 'Perbarui Dokumen';
                progressContainer.classList.add('hidden');
                progressBar.classList.remove('animate-pulse');
            }
        });
    </script>
</x-app-layout>