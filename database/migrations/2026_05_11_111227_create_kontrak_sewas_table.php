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
        Schema::create('kontrak_sewa', function (Blueprint $table) {
    $table->id();
    $table->string('kode_kontrak')->unique();
    
    // Relasi ke tabel penghunis dan kamars
    $table->foreignId('id_penghuni')->constrained('penghuni')->onDelete('cascade');
    $table->foreignId('id_kamars')->constrained('kamars')->onDelete('cascade');

    // Detail Kontrak
    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai')->nullable(); // Bisa kosong jika sewa bulanan tanpa batas
    
    // Status Kontrak
    $table->enum('status_kontrak', ['Aktif', 'Selesai'])->default('Aktif');

    $table->text('keterangan')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_sewa');
    }
};
