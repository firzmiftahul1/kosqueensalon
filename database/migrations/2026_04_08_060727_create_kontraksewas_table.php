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
        Schema::create('kontraksewa', function (Blueprint $table) {
            // Menggunakan id() sebagai Primary Key (id_kontrak)
            $table->id('id_kontrak'); 
            
            // Foreign Key ke tabel penghuni
            $table->unsignedBigInteger('id_penghuni');
            
            // Foreign Key ke tabel kamar
            $table->unsignedBigInteger('id_kamar');
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('status', 50); // Contoh: Aktif, Selesai, Dibatalkan
            
            $table->timestamps();

            // Definisi Relasi (Foreign Key Constraints)
            // Pastikan tabel 'penghuni' dan 'kamar' sudah dibuat sebelumnya
            $table->foreign('id_penghuni')->references('id_penghuni')->on('penghuni')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id_kamar')->on('kamar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontraksewa');
    }
};