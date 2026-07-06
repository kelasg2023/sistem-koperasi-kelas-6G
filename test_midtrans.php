<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Midtrans\Config::$serverKey = config('midtrans.server_key');
\Midtrans\Config::$isProduction = config('midtrans.is_production');

$order_id = "TOPUP-7-123456"; // wait I need a real order ID from DB
$topup = \App\Models\WalletTopup::orderBy('id', 'desc')->first();

if (!$topup) {
    echo "NO TOPUPS IN DB\n";
    exit;
}
echo "ORDER ID: " . $topup->order_id . "\n";
echo "STATUS IN DB: " . $topup->status . "\n";

try {
    $statusResponse = \Midtrans\Transaction::status($topup->order_id);
    echo "MIDTRANS STATUS: " . $statusResponse->transaction_status . "\n";
} catch (\Exception $e) {
    echo "MIDTRANS ERROR: " . $e->getMessage() . "\n";
}
