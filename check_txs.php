<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$txs = DB::table('transactions')->get(['transaction_id', 'status', 'status_pengiriman', 'payment_status', 'payment_method']);
foreach($txs as $tx) {
    print_r($tx);
}
