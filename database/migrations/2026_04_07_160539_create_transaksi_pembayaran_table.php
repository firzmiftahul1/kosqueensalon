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
        Schema::create('transaksi_pembayaran', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->date('tanggal');

            $table->unsignedBigInteger('id_penghuni')->nullable();
            $table->unsignedBigInteger('id_kontrak')->nullable();
            $table->unsignedBigInteger('id_metode');
            $table->unsignedBigInteger('id_supplier')->nullable();

            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);

            $table->decimal('total_bayar', 12, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembayaran');
    }
};