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
        Schema::create('market_trend', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tren');
            $table->text('analisis_ai');
            $table->json('referensi_visual')->nullable(); 
            $table->string('saran_barang')->nullable();
            $table->string('warna_populer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_trend');
    }
};