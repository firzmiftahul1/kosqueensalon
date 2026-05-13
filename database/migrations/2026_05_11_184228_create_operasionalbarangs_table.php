<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operasional_barang', function (Blueprint $table) {
            $table->id();
            $table->string('kode_op_barang')->unique();
            // Menghubungkan ke tabel barang
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            // Menghubungkan ke tabel kamars (untuk tahu lokasi kejadiannya)
            $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');
            
            $table->date('tanggal');
            $table->string('kegiatan'); // Contoh: Perbaikan, Penggantian, Pembersihan
            $table->decimal('biaya', 12, 2)->default(0);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operasional_barang');
    }
};