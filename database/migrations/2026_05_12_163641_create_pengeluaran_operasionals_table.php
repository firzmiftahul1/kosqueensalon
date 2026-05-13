<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_operasional', function (Blueprint $table) {

    $table->id();

    $table->string('kode_pengeluaran')->unique();

    $table->string('kode_supplier');

    $table->foreign('kode_supplier')
        ->references('kode_supplier')
        ->on('supplier')
        ->onDelete('cascade');

    $table->date('tanggal');

    $table->string('jenis_pengeluaran');

    $table->integer('jumlah');

    $table->text('keterangan')->nullable();

    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_operasionals');
    }
};