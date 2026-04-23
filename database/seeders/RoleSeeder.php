<?php

namespace Database\Seeders;

use App\Enums\CentralRoles;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmins = User::role(CentralRoles::SUPER_ADMIN->value)->get();
        $superAdminIds = $superAdmins->pluck('id');

        Tenant::all()->runForEach(function () use ($superAdminIds) {
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            $tenantId = tenant('id');
            $adminRoleEnum = Roles::adminForTenant($tenantId);
            $adminRoleInstance = null;

            foreach (Roles::byTenant($tenantId) as $roleEnum) {
                $role = Role::firstOrCreate(
                    ['name' => $roleEnum->value, 'tenant_id' => $tenantId],
                    ['guard_name' => 'web']
                );

                $permissions = collect(Permissions::byRole($roleEnum))
                    ->map(fn (Permissions $perm) => Permission::firstOrCreate(
                        ['name' => $perm->value, 'tenant_id' => $tenantId],
                        ['guard_name' => 'web']
                    ));

                $role->syncPermissions($permissions);

                if ($adminRoleEnum && $roleEnum === $adminRoleEnum) {
                    $adminRoleInstance = $role;
                }
            }

            if ($adminRoleInstance) {
                foreach ($superAdminIds as $superAdminId) {
                    DB::table(config('permission.table_names.model_has_roles'))->insertOrIgnore([
                        'role_id'    => $adminRoleInstance->id,
                        'model_type' => (new User)->getMorphClass(),
                        'model_id'   => $superAdminId,
                    ]);
                }
            }
        });
    }
}
