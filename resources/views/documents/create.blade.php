<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-wedel leading-tight">
            {{ __('Tambah Dokumen Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-soga">
                <div class="p-6 text-gray-900">
                    
                    <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Jenis Dokumen -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Jenis Dokumen <span class="text-red-500">*</span></label>
                                <select name="document_type_id" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subjek -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Subjek <span class="text-red-500">*</span></label>
                                <select name="subject_id" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                                    <option value="">-- Pilih Subjek --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nomor Dokumen -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Nomor Dokumen <span class="text-red-500">*</span></label>
                                <input type="text" name="number" value="{{ old('number') }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Judul -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Judul / Perihal <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Tahun <span class="text-red-500">*</span></label>
                                <input type="number" name="year" value="{{ old('year', date('Y')) }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Tanggal Ditetapkan -->
                            <div>
                                <label class="block text-sm font-semibold text-wedel mb-2">Tanggal Ditetapkan <span class="text-red-500">*</span></label>
                                <input type="date" name="document_date" value="{{ old('document_date') }}" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                            </div>

                            <!-- Status Peraturan -->
                            <div>
                                <label for="status" class="block text-sm font-semibold text-wedel mb-2">Status Peraturan <span class="text-red-500">*</span></label>
                                <select name="status" id="status" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2" required>
                                    <option value="Berlaku">Berlaku</option>
                                    <option value="Mengubah">Mengubah</option>
                                    <option value="Dicabut">Dicabut</option>
                                </select>
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-wedel mb-2">Keterangan / Deskripsi Singkat</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md focus:ring-soga focus:border-soga p-2">{{ old('description') }}</textarea>
                            </div>

                            <!-- Upload File -->
                            <div class="md:col-span-2">
                                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                                    <label class="block text-sm font-semibold text-wedel mb-2">Unggah Lampiran (Bisa pilih lebih dari satu file)</label>
                                    <input type="file" name="document_files[]" multiple accept=".pdf,.tif,.txt,.xlsx,.docx,.jpg,.jpeg,.png" class="w-full border border-gray-300 rounded-md bg-white focus:ring-soga focus:border-soga p-2">
                                    <p class="text-xs text-gray-500 mt-2">Format: PDF, TIF, TXT, Excel, Word, JPG, PNG. (Total Maks: 1GB).</p>
                                </div>
                            </div>
                        </div>

                        <!-- WADAH PROGRESS BAR (Awalnya Disembunyikan) -->
                        <div id="progressContainer" class="hidden mt-6 bg-gray-100 rounded-full h-6 w-full overflow-hidden shadow-inner relative">
                            <div id="progressBar" class="bg-soga h-6 w-0 transition-all duration-300 ease-out flex items-center justify-center"></div>
                            <div id="progressText" class="absolute inset-0 flex items-center justify-center text-xs font-bold text-gray-800 drop-shadow-md">0%</div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                            <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Batal</a>
                            <button type="submit" id="submitBtn" class="px-6 py-2 bg-wedel text-primisima rounded-md hover:bg-soga transition font-bold shadow">Simpan Dokumen</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    
    <!-- SCRIPT AJAX PROGRESS BAR -->
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Menghentikan pengiriman formulir standar

            let form = this;
            let submitBtn = document.getElementById('submitBtn');
            let progressContainer = document.getElementById('progressContainer');
            let progressBar = document.getElementById('progressBar');
            let progressText = document.getElementById('progressText');
            
            // Siapkan data dari formulir
            let formData = new FormData(form);

            // Ubah tampilan UI saat mulai
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.innerText = 'Mengunggah...';
            progressContainer.classList.remove('hidden');
            progressBar.style.width = '0%';
            progressText.innerText = '0%';

            // Gunakan XMLHttpRequest agar bisa melacak progress (AJAX)
            let xhr = new XMLHttpRequest();
            xhr.open(form.method, form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Beritahu Laravel ini adalah request AJAX

            // Fungsi pelacak persentase
            xhr.upload.addEventListener('progress', function(event) {
                if (event.lengthComputable) {
                    let percentComplete = Math.round((event.loaded / event.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.innerText = percentComplete + '%';
                    
                    if(percentComplete === 100) {
                        progressText.innerText = 'Menyimpan ke Database... Mohon Tunggu.';
                        progressBar.classList.add('animate-pulse');
                    }
                }
            });

            // Fungsi ketika selesai merespon
            xhr.onload = function() {
                if (xhr.status === 200 || xhr.status === 201 || xhr.status === 302 || xhr.status === 204) {
                    // Berhasil! Arahkan paksa ke halaman Index
                    window.location.href = "{{ route('documents.index') }}";
                } else if (xhr.status === 422) {
                    // Gagal Validasi (Ada isian kosong atau file salah)
                    resetUI();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Silakan periksa kembali kelengkapan form dan tipe file Anda.',
                        customClass: { popup: 'rounded-xl' }
                    });
                } else {
                    // Error Server (500)
                    resetUI();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan pada server saat memproses data.',
                        customClass: { popup: 'rounded-xl' }
                    });
                }
            };

            // Fungsi jika koneksi terputus
            xhr.onerror = function() {
                resetUI();
                Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Periksa jaringan Anda.' });
            };

            // Mengirim data ke Controller
            xhr.send(formData);

            function resetUI() {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.innerText = 'Simpan Dokumen';
                progressContainer.classList.add('hidden');
                progressBar.classList.remove('animate-pulse');
            }
        });
    </script>
</x-app-layout>