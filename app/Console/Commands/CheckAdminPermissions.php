<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CheckAdminPermissions extends Command
{
    protected $signature = 'admin:check-permissions {id? : Admin ID}';
    protected $description = 'Check and fix admin permissions';

    public function handle()
    {
        $adminId = $this->argument('id') ?: 1;

        $admin = Admin::find($adminId);

        if (!$admin) {
            $this->error("Admin with ID {$adminId} not found!");
            return 1;
        }

        $this->info("Checking permissions for admin: {$admin->name} ({$admin->email})");

        // التحقق من دور super_admin
        $superRole = Role::where('guard_name', 'admin')
            ->whereIn('name', ['super_admin', 'Super Admin'])
            ->first();

        if (!$superRole) {
            $this->warn("No super admin role exists. Creating super_admin role...");
            $superRole = Role::create(['name' => 'super_admin', 'guard_name' => 'admin']);
        }

        if (!$admin->hasRole($superRole)) {
            $this->warn("Admin does not have super admin role. Assigning {$superRole->name}...");
            $admin->assignRole($superRole);
            $this->info("✓ Assigned {$superRole->name} role");
        } else {
            $this->info("✓ Already has super admin role");
        }

        // التحقق من الصلاحيات
        $allPermissions = Permission::all();
        $adminPermissions = $admin->getAllPermissions();

        $this->info("Total permissions in system: " . $allPermissions->count());
        $this->info("Admin has permissions: " . $adminPermissions->count());

        if ($adminPermissions->count() < $allPermissions->count()) {
            $this->warn("Admin does not have all permissions. Syncing...");
            $admin->syncPermissions($allPermissions);
            $this->info("✓ Synced all permissions");
        } else {
            $this->info("✓ Already has all permissions");
        }

        // عرض الصلاحيات
        $this->newLine();
        $this->info("Admin permissions:");

        $permissionsByModule = $adminPermissions->groupBy('module');

        foreach ($permissionsByModule as $module => $permissions) {
            $this->line("  {$module}: " . $permissions->count() . " permissions");
        }

        $this->newLine();
        $this->info("✅ Admin #{$adminId} now has all permissions!");

        return 0;
    }
}