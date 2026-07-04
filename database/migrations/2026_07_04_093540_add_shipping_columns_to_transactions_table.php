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
        Schema::table('transactions', function (Blueprint $table) {
            $table->text('alamat_pengiriman')->nullable();
            $table->string('jasa_kurir')->nullable();
            $table->string('nomor_resi')->nullable();
            $table->enum('status_pengiriman', ['pending', 'dikemas', 'dikirim', 'selesai'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['alamat_pengiriman', 'jasa_kurir', 'nomor_resi', 'status_pengiriman']);
        });
    }
};
