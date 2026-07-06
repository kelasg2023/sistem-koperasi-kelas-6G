<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::all();
foreach($users as $user) {
    $user->notify(new App\Notifications\SystemNotification('Sistem Notifikasi Sukses!', 'Notifikasi ini langsung disimpan di tabel database dan dibroadcast ke layar Anda secara real-time!', 'success'));
    echo "Notification sent to user {$user->id_users}\n";
}
