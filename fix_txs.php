<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

DB::table('transactions')
    ->whereIn('status', ['berhasil', 'proses'])
    ->update(['payment_status' => 'success']);

DB::table('transactions')
    ->where('status', 'gagal')
    ->update(['payment_status' => 'failed']);
    
DB::table('transactions')
    ->where('status', 'refund')
    ->update(['payment_status' => 'refund']);
    
echo "Updated payment statuses based on old statuses.";
