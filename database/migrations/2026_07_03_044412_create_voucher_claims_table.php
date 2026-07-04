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
        Schema::create('voucher_claims', function (Blueprint $table) {
            $table->id('claim_id');
            $table->foreignId('user_id')->constrained('users', 'id_users')->onDelete('cascade');
            $table->foreignId('id_voucher')->constrained('vouchers', 'id_voucher')->onDelete('cascade');
            $table->enum('status', ['claimed', 'used', 'expired'])->default('claimed');
            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamp('used_at')->nullable()->default(null);

            $table->unique(['user_id', 'id_voucher']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_claims');
    }
};
