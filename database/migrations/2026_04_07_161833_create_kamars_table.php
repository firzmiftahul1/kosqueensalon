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
    Schema::create('kamars', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kamar');
        $table->string('nama_kamar')->nullable(); 
        $table->string('tipe_kamar')->nullable(); 
        $table->integer('harga')->default(0);
        $table->string('status_kamar');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
