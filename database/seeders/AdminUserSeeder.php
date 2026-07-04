<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // updateOrCreate supaya aman dijalankan berkali-kali
        // (tidak akan membuat akun admin duplikat kalau sudah ada).
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Akun admin berhasil dibuat/diperbarui: username=admin, password=admin123');
    }
}