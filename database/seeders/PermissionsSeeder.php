<?php

namespace Database\Seeders;

use App\Enums\Permissions;
use App\Enums\Roles;
use App\Models\Permission;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::all()->runForEach(function () {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $tenantId = tenant('id');

            foreach (Roles::byTenant($tenantId) as $roleEnum) {
                foreach (Permissions::byRole($roleEnum) as $permissionEnum) {
                    Permission::firstOrCreate(
                        ['name' => $permissionEnum->value, 'tenant_id' => $tenantId],
                        ['guard_name' => 'web']
                    );
                }
            }
        });
    }
}
