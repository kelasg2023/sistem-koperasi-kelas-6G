<?php
/**
 * Koneksi Database MySQL dengan membaca file .env di parent folder.
 * Mendukung sinkronisasi konfigurasi otomatis antara Python & PHP.
 */

$envPath = __DIR__ . '/../.env';
// Jika di parent folder tidak ada (karena dipindah ke Laragon www), cek di folder saat ini
if (!file_exists($envPath)) {
    $envPath = __DIR__ . '/.env';
}
$host = 'localhost';
$port = '3306';
$dbname = 'db_koperasi';
$user = 'root';
$pass = '';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Abaikan komentar
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $value = trim($parts[1], " \t\n\r\0\x0B\"'");
            
            if ($name === 'DATABASE_URL') {
                // Regex parsing untuk format mysql+pymysql://user:pass@host:port/dbname
                // Mendukung password kosong
                if (preg_match('/mysql(?:\+pymysql)?:\/\/([^:]*):([^@]*)@([^:]*):([^\/]*)\/(.*)/', $value, $matches)) {
                    $user = $matches[1];
                    $pass = $matches[2];
                    $host = $matches[3];
                    $port = $matches[4];
                    $dbname = $matches[5];
                }
            }
        }
    }
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage() . "<br>Pastikan server MySQL menyala dan database 'db_koperasi' sudah ada.");
}
