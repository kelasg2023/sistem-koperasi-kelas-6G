<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Ubah ENUM status_pengiriman di tabel transaction_trackings agar sinkron
        // Tambahkan 'diproses' ke enum dulu
        DB::statement("ALTER TABLE transaction_trackings MODIFY COLUMN status_pengiriman ENUM('pending', 'diproses', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'diproses'");
        
        // Update data yang sudah ada dari 'pending' menjadi 'diproses'
        DB::table('transaction_trackings')->where('status_pengiriman', 'pending')->update(['status_pengiriman' => 'diproses']);
        
        // Hapus 'pending' dari enum
        DB::statement("ALTER TABLE transaction_trackings MODIFY COLUMN status_pengiriman ENUM('diproses', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'diproses'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE transaction_trackings MODIFY COLUMN status_pengiriman ENUM('pending', 'diproses', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'pending'");
        DB::table('transaction_trackings')->where('status_pengiriman', 'diproses')->update(['status_pengiriman' => 'pending']);
        DB::statement("ALTER TABLE transaction_trackings MODIFY COLUMN status_pengiriman ENUM('pending', 'dikemas', 'dikirim', 'selesai') NOT NULL DEFAULT 'pending'");
    }
};
