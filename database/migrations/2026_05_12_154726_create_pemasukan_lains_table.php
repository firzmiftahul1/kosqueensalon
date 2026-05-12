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
        Schema::create('pemasukan_lain', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal');

        $table->foreignId('kamar_id')->constrained();
        $table->foreignId('penghuni_id')->constrained('penghuni');

        $table->string('jenis'); // langsung isi: listrik, wifi, air

        $table->integer('jumlah')->default(1);
        $table->integer('harga');
        $table->integer('total');

        $table->text('keterangan')->nullable();

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasukan_lain');
    }
};
