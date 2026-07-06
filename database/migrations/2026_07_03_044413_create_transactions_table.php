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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('user_id')->constrained('users', 'id_users')->onDelete('restrict');
            $table->decimal('total_harga', 15, 2)->default(0.00);
            $table->enum('status', ['berhasil', 'proses', 'gagal', 'refund'])->default('proses');
            $table->enum('payment_method', ['cash', 'qris', 'transfer', 'wallet'])->default('cash');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
