# Auth Module (Framework Agnostic)

Module ini berisi layanan autentikasi sederhana untuk:
- register
- login
- forgot password

Struktur tabel yang digunakan:
- users
- users_profiles

Contoh pemakaian:

```php
<?php
require 'src/AuthService.php';

$pdo = new PDO('mysql:host=localhost;dbname=your_db;charset=utf8mb4', 'root', '');
$auth = new Auth\Src\AuthService($pdo);

$result = $auth->register([
    'username' => 'admin1',
    'password' => '123456',
    'role' => 'admin',
    'name' => 'Admin One',
    'address' => 'Bandung',
    'profile_picture' => '',
    'phone' => '081234567890',
    'member' => 'true',
]);

print_r($result);
```
