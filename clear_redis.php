<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Redis;
try {
    Redis::flushdb();
    echo "Simulasi Redis restart: Berhasil menghapus semua data di memori Redis (termasuk session).\n";
} catch (\Exception $e) {
    echo "Gagal: " . $e->getMessage() . "\n";
}
