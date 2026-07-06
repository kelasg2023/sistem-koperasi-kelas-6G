<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Tambahkan kolom payment status, snap_token, order_id
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'success', 'failed', 'expire', 'cancel', 'deny'])->default('pending')->after('status');
            $table->string('midtrans_order_id')->nullable()->after('payment_status');
            $table->string('snap_token')->nullable()->after('midtrans_order_id');
        });

        // 2. Modifikasi enum status_pengiriman 
        // Tambahkan 'diproses' ke enum dulu (sementara pertahankan pending untuk transisi)
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status_pengiriman ENUM('pending', 'diproses', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'diproses'");
        
        // Update data yang sudah ada dari 'pending' menjadi 'diproses'
        DB::table('transactions')->where('status_pengiriman', 'pending')->update(['status_pengiriman' => 'diproses']);
        
        // Hapus 'pending' dari enum
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status_pengiriman ENUM('diproses', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'diproses'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status_pengiriman ENUM('pending', 'diproses', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'pending'");
        DB::table('transactions')->where('status_pengiriman', 'diproses')->update(['status_pengiriman' => 'pending']);
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status_pengiriman ENUM('pending', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'pending'");
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'midtrans_order_id', 'snap_token']);
        });
    }
};
