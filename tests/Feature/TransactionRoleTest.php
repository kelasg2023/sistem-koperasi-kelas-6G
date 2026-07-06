<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TransactionRoleTest extends TestCase
{
    use DatabaseTransactions;

    private function createUser($role)
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    public function test_admin_can_get_all_transactions()
    {
        $admin = $this->createUser('admin');
        $response = $this->actingAs($admin)->getJson('/api/transactions/all');
        $response->assertStatus(200);
    }

    public function test_staff_can_get_all_transactions()
    {
        $staff = $this->createUser('staff');
        $response = $this->actingAs($staff)->getJson('/api/transactions/all');
        $response->assertStatus(200);
    }

    public function test_customer_cannot_get_all_transactions()
    {
        $customer = $this->createUser('customer');
        $response = $this->actingAs($customer)->getJson('/api/transactions/all');
        $response->assertStatus(403);
    }

    public function test_staff_cannot_access_admin_user_management()
    {
        $staff = $this->createUser('staff');
        $response = $this->actingAs($staff)->getJson('/api/admin/users');
        $response->assertStatus(403);
    }
}
