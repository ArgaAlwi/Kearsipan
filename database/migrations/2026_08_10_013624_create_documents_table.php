<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            
            // Kolom utama yang di-index untuk pencarian cepat
            $table->string('number')->index();
            $table->string('title')->index();
            $table->integer('year')->index();
            
            $table->date('document_date');
            $table->text('description')->nullable();
            
            // Metadata File
            $table->string('file_name'); // Nama enkripsi/hash (contoh: xxxx.pdf)
            $table->string('file_path'); // Path di storage (contoh: documents/xxxx.pdf)
            $table->integer('file_size'); // Ukuran dalam bytes
            $table->string('mime_type'); // Tipe MIME, contoh: application/pdf
            
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};