<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use DatabaseTransactions;

    private function createUser($role)
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = $this->createUser('admin');
        $response = $this->actingAs($admin)->getJson('/api/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_cannot_access_staff_dashboard()
    {
        $admin = $this->createUser('admin');
        $response = $this->actingAs($admin)->getJson('/api/staff/dashboard');
        $response->assertStatus(403);
    }

    public function test_staff_can_access_staff_dashboard()
    {
        $staff = $this->createUser('staff');
        $response = $this->actingAs($staff)->getJson('/api/staff/dashboard');
        $response->assertStatus(200);
    }

    public function test_staff_cannot_access_admin_dashboard()
    {
        $staff = $this->createUser('staff');
        $response = $this->actingAs($staff)->getJson('/api/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_manager_can_access_manager_dashboard()
    {
        $manager = $this->createUser('manager');
        $response = $this->actingAs($manager)->getJson('/api/manager/dashboard');
        $response->assertStatus(200);
    }

    public function test_supplier_can_access_supplier_dashboard()
    {
        $supplier = $this->createUser('supplier');
        $response = $this->actingAs($supplier)->getJson('/api/supplier/dashboard');
        $response->assertStatus(200);
    }
}
