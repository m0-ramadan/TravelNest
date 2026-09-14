<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private const ADMIN_EMAIL = 'admin@etrotours.com';

    public function up(): void
    {
        $admin = Admin::query()
            ->whereRaw('LOWER(email) = ?', [self::ADMIN_EMAIL])
            ->first();

        // Keep fresh installs and test databases migratable when this production
        // account has not been created yet.
        if (! $admin) {
            return;
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'admin',
        ]);

        $admin->assignRole($superAdminRole);
        $admin->givePermissionTo(
            Permission::query()->where('guard_name', 'admin')->get()
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Access grants are intentionally not revoked automatically on rollback:
        // the role or permissions may have existed before this migration ran.
    }
};
