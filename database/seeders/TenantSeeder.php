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
use Spatie\Permission\PermissionRegistrar;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = [
            ['id' => 'docking', 'domain' => 'docking'],
            ['id' => 'new-building', 'domain' => 'new-building'],
            ['id' => 'site', 'domain' => 'site'],
        ];

        $superAdmins = User::role(CentralRoles::SUPER_ADMIN->value)->get();

        foreach ($tenants as $data) {
            $tenant = Tenant::create(['id' => $data['id']]);
            $tenant->domains()->create(['domain' => $data['domain']]);
        }
    }
}
