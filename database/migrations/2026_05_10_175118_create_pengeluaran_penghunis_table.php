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
        Schema::create('pengeluaran_penghunis', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel penghuni
            $table->foreignId('penghuni_id')
                ->constrained('penghunis')
                ->onDelete('cascade');

            // Nama pengeluaran
            $table->string('nama_pengeluaran');

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            // Nominal pengeluaran
            $table->decimal('nominal', 15, 2);

            // Tanggal pengeluaran
            $table->date('tanggal_pengeluaran');

            // Status pengeluaran
            $table->enum('status', [
                'pending',
                'dibayar',
                'ditolak'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_penghunis');
    }
};