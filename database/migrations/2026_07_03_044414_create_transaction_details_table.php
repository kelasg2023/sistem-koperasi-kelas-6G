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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id('detail_id');
            $table->foreignId('transaction_id')->constrained('transactions', 'transaction_id')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang', 'barang_id')->onDelete('restrict');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->foreignId('id_voucher')->nullable()->constrained('vouchers', 'id_voucher')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
