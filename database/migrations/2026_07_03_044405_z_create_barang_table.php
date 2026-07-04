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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('barang_id');
            $table->string('nama');
            $table->integer('stok')->default(0);
            $table->decimal('harga', 15, 2);
            $table->decimal('diskon_persen', 5, 2)->default(0.00);
            $table->text('deskripsi')->nullable();
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->onDelete('restrict');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
