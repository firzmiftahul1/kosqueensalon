<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kontrak_sewas')) {
            Schema::table('kontrak_sewas', function (Blueprint $table): void {
                if (!self::foreignKeyExists('kontrak_sewas', 'penghuni_id')) {
                    $table->foreign('penghuni_id')
                        ->references('id')
                        ->on('penghunis')
                        ->cascadeOnDelete();
                }

                if (!self::foreignKeyExists('kontrak_sewas', 'kamar_id')) {
                    $table->foreign('kamar_id')
                        ->references('id')
                        ->on('kamars')
                        ->cascadeOnDelete();
                }
            });
        }

        if (Schema::hasTable('transaksi_pembayaran')) {
            Schema::table('transaksi_pembayaran', function (Blueprint $table): void {
                if (!self::foreignKeyExists('transaksi_pembayaran', 'id_penghuni')) {
                    $table->foreign('id_penghuni')
                        ->references('id')
                        ->on('penghunis')
                        ->nullOnDelete();
                }

                if (!self::foreignKeyExists('transaksi_pembayaran', 'id_kontrak')) {
                    $table->foreign('id_kontrak')
                        ->references('id')
                        ->on('kontrak_sewas')
                        ->nullOnDelete();
                }

                if (!self::foreignKeyExists('transaksi_pembayaran', 'id_metode')) {
                    $table->foreign('id_metode')
                        ->references('id_metode')
                        ->on('metode_pembayaran')
                        ->cascadeOnDelete();
                }

                if (!self::foreignKeyExists('transaksi_pembayaran', 'id_supplier')) {
                    $table->foreign('id_supplier')
                        ->references('id')
                        ->on('supplier')
                        ->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        $this->dropForeignKeyIfExists('kontrak_sewas', 'penghuni_id');
        $this->dropForeignKeyIfExists('kontrak_sewas', 'kamar_id');

        $this->dropForeignKeyIfExists('transaksi_pembayaran', 'id_penghuni');
        $this->dropForeignKeyIfExists('transaksi_pembayaran', 'id_kontrak');
        $this->dropForeignKeyIfExists('transaksi_pembayaran', 'id_metode');
        $this->dropForeignKeyIfExists('transaksi_pembayaran', 'id_supplier');
    }

    private static function foreignKeyExists(string $table, string $column): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::select(
            'SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$database, $table, $column]
        );

        return !empty($result);
    }

    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        $database = DB::getDatabaseName();

        $result = DB::select(
            'SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = ?
               AND TABLE_NAME = ?
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL',
            [$database, $table, $column]
        );

        if (empty($result)) {
            return;
        }

        $constraint = $result[0]->CONSTRAINT_NAME;
        DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
    }
};
