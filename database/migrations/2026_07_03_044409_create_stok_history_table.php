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
        Schema::create('stok_history', function (Blueprint $table) {
            $table->id('stok_history_id');
            $table->foreignId('supplier_id')->nullable()->constrained('supplier', 'supplier_id')->onDelete('set null');
            $table->foreignId('barang_id')->constrained('barang', 'barang_id')->onDelete('cascade');
            $table->integer('jumlah');
            $table->integer('stok_awal');
            $table->integer('stok_akhir');
            $table->string('keterangan')->nullable();
            $table->enum('stok_mutasi', ['keluar', 'lainnya', 'masuk']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_history');
    }
};
