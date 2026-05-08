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
        Schema::create('kontrak_sewas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kontrak', 10)->unique();
            $table->unsignedBigInteger('penghuni_id');
            $table->unsignedBigInteger('kamar_id');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->decimal('harga_sewa', 12, 2);
            $table->enum('status_kontrak', ['aktif', 'selesai'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak_sewas');
    }
};