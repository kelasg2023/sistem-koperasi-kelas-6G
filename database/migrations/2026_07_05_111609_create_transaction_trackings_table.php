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
        Schema::create('transaction_trackings', function (Blueprint $table) {
            $table->id('tracking_id');
            $table->unsignedBigInteger('transaction_id');
            $table->enum('status_pengiriman', ['pending', 'dikemas', 'dikirim', 'selesai']);
            $table->string('keterangan'); // Misal: "Pesanan telah diserahkan ke JNE"
            $table->timestamps();

            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_trackings');
    }
};
