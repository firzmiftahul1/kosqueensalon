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
        Schema::create('transaksikontraksewa', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi_kontraksewa')->unique();
            $table->foreignId('id_kontrak_sewa')->constrained('kontrak_sewa')->onDelete('cascade');
            $table->foreignId('id_penghuni')->constrained('penghuni')->onDelete('cascade');
            $table->foreignId('id_kamars')->constrained('kamars')->onDelete('cascade');
            
            // Perbaikan di sini: menggunakan integer atau bigInteger
            $table->bigInteger('nominal'); 
            
            $table->string('keterangan')->nullable(); // Ditambah nullable jika keterangan boleh kosong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksikontraksewa');
    }
};