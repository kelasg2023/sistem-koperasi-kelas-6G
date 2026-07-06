<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$w = \App\Models\Wallet::where('user_id', 7)->first();
echo "RESULT: " . json_encode($w) . "\n";
echo "BALANCE: " . $w->balance . "\n";
echo "SALDO: " . $w->saldo . "\n";
