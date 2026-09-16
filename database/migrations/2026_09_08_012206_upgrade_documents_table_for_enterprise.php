<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1. Tambah Status
            $table->enum('status', ['Berlaku', 'Dicabut', 'Mengubah'])->default('Berlaku')->after('description');
            
            // 2. Fitur Tong Sampah (Soft Deletes)
            $table->softDeletes(); 
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
};