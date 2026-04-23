<?php

namespace Database\Seeders;

use App\Enums\CentralRoles;
use App\Enums\Roles;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CentralPermissionsSeeder::class);

        $superAdmin = User::factory()->create([
            'name' => 'Developer Projects',
            'email' => 'dev@caputra.com',
        ]);

        $superAdmin->assignRole(Role::where('name', CentralRoles::SUPER_ADMIN->value)->first());

        $this->call(TenantSeeder::class);
        $this->call(RoleSeeder::class);

        $tenantUsers = [
            [
                'name'      => 'Docking User',
                'email'     => 'docking@caputra.com',
                'tenant_id' => 'docking',
                'role'      => Roles::MEMBER_DOCKING,
            ],
            [
                'name'      => 'New Building User',
                'email'     => 'newbuilding@caputra.com',
                'tenant_id' => 'new-building',
                'role'      => Roles::MEMBER_NEW_BUILDING,
            ],
            [
                'name'      => 'Site User',
                'email'     => 'site@caputra.com',
                'tenant_id' => 'site',
                'role'      => Roles::MEMBER_SITE,
            ],
        ];

        foreach ($tenantUsers as $data) {
            $user = User::factory()->create([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            $tenant = Tenant::find($data['tenant_id']);
            $tenant->users()->attach($user->id);

            $tenant->run(function () use ($user, $data) {
                $role = Role::where('name', $data['role']->value)
                    ->where('tenant_id', tenant('id'))
                    ->first();

                if ($role) {
                    DB::table(config('permission.table_names.model_has_roles'))->insertOrIgnore([
                        'role_id'    => $role->id,
                        'model_type' => $user->getMorphClass(),
                        'model_id'   => $user->id,
                    ]);
                }
            });
        }
    }
}
