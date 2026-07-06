<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'superadmin@koperasi.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $admin->profile()->create([
                'name' => 'Administrator',
                'address' => 'Kantor Pusat Koperasi',
                'phone' => '081234567890',
            ]);
        }

        // Buat Dummy Customer untuk ditest checkout
        $customer = User::firstOrCreate(
            ['username' => 'customer1'],
            [
                'email' => 'customer1@koperasi.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        if ($customer->wasRecentlyCreated) {
            $customer->profile()->create([
                'name' => 'Budi Pelanggan',
                'address' => 'Jl. Pelanggan No. 1',
                'phone' => '081298765432',
            ]);

            $customer->customer()->create([
                'point' => 0,
                'is_member' => false
            ]);
        }
    }
}
