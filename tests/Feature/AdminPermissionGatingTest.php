<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPermissionGatingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = $this->createSuperAdmin();
    }

    public function test_super_admin_bypasses_permission_gating(): void
    {
        $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.payments.index'))
            ->assertOk();

        $this->actingAs($this->superAdmin, 'admin')
            ->get(route('admin.admins.index'))
            ->assertOk();
    }

    public function test_admin_without_permission_is_forbidden(): void
    {
        $editor = $this->createAdminWithRole('editor');

        $this->actingAs($editor, 'admin')
            ->get(route('admin.payments.index'))
            ->assertForbidden();

        $this->actingAs($editor, 'admin')
            ->get(route('admin.admins.index'))
            ->assertForbidden();
    }

    public function test_admin_with_direct_permission_is_allowed_only_there(): void
    {
        Permission::firstOrCreate(['name' => 'payments.view', 'guard_name' => 'admin']);

        $admin = Admin::create([
            'name' => 'Payments Clerk',
            'email' => 'payments_clerk@example.com',
            'password' => bcrypt('password123'),
        ]);
        $admin->givePermissionTo('payments.view');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.payments.index'))
            ->assertOk();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.admins.index'))
            ->assertForbidden();
    }

    public function test_unauthenticated_admin_route_is_redirected_to_login(): void
    {
        $this->get(route('admin.admins.index'))
            ->assertRedirect(route('admin.login.page'));
    }

    private function createAdminWithRole(string $roleName): Admin
    {
        Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'admin']);

        $admin = $this->createSuperAdmin(['email' => $roleName.'_admin@example.com']);
        $admin->syncRoles($roleName);

        return $admin;
    }
}