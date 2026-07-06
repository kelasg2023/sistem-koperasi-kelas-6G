<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'staff', 'supplier', 'manager', 'customer'];

        foreach ($roles as $role) {
            DB::transaction(function () use ($role) {
                // Buat user
                $user = User::updateOrCreate(
                    ['email' => "{$role}@koperasi.com"],
                    [
                        'username' => "{$role}123",
                        'password' => Hash::make('password'),
                        'role'     => $role,
                    ]
                );

                // Buat profile jika belum ada
                if (!$user->profile) {
                    $user->profile()->create([
                        'name'    => ucfirst($role) . ' User',
                        'phone'   => '08123456789' . rand(0, 9),
                        'address' => "Alamat {$role}",
                    ]);
                }

                // Buat data customer jika role customer
                if ($role === 'customer' && !$user->customer) {
                    $user->customer()->create([
                        'point'     => 0,
                        'is_member' => false,
                    ]);
                }
                
                // Buat dompet (wallet) jika belum ada
                if (!$user->wallet) {
                    $user->wallet()->create([
                        'balance' => 0
                    ]);
                }
            });
        }
    }
}

