<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::find(7);
if (!$user) {
    echo "USER 7 NOT FOUND\n";
    exit;
}
$token = $user->createToken('test')->plainTextToken;
echo "TOKEN: " . $token . "\n";

$wallet = \App\Models\Wallet::where('user_id', 7)->first();
echo "Wallet Balance Direct: " . ($wallet ? $wallet->balance : 'NULL') . "\n";

// simulate what dashboard controller does
$dashboardData = [
    'wallet_balance' => $wallet ? (float) $wallet->balance : 0,
];
echo "DASHBOARD DATA: " . json_encode($dashboardData) . "\n";
