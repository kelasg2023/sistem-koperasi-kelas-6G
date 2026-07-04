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
        Schema::create('supplier', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->foreignId('merk_id')->constrained('merk', 'merk_id')->onDelete('restrict');
            $table->foreignId('barang_id')->constrained('barang', 'barang_id')->onDelete('restrict');
            $table->decimal('harga_beli', 15, 2);
            $table->integer('jumlah');
            $table->boolean('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier');
    }
};
