<?php

namespace Tests;

use App\Models\Admin;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createSuperAdmin(array $attributes = []): Admin
    {
        $admin = Admin::create(array_merge([
            'name' => 'Test Admin',
            'email' => 'test_admin_'.uniqid().'@example.com',
            'password' => bcrypt('password123'),
        ], $attributes));

        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);

        $admin->assignRole($role);

        return $admin;
    }
}