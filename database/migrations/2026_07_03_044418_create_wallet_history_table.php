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
        Schema::create('wallet_history', function (Blueprint $table) {
            $table->id('id_wt_history');
            $table->foreignId('id_wallet')->constrained('wallet', 'id_wallet')->onDelete('cascade');
            $table->decimal('balance_transaction', 15, 2);
            $table->enum('wt_status_history', ['penambahan', 'pengembalian', 'terpakai']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_history');
    }
};
