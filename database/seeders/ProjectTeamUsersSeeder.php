<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Seeds sample "docking" tenant users meant to be picked as Project Team
// members: one Project Manager Docking and several Member Docking, all with
// password "password". These also receive the matching tenant role/permissions
// so they can sign in and use the app with the expected access level.
class ProjectTeamUsersSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::find('docking');

        if (! $tenant) {
            return;
        }

        $users = [
            ['name' => 'Boni', 'email' => 'Boni@caputra.com', 'role' => Roles::PROJECT_MANAGER_DOCKING],
            ['name' => 'Afwan', 'email' => 'Afwan@caputra.com', 'role' => Roles::MEMBER_DOCKING],
            ['name' => 'Fitra', 'email' => 'Fitra@caputra.com', 'role' => Roles::MEMBER_DOCKING],
            ['name' => 'Slamet', 'email' => 'Slamet@caputra.com', 'role' => Roles::MEMBER_DOCKING],
            ['name' => 'Awi', 'email' => 'Awi@caputra.com', 'role' => Roles::MEMBER_DOCKING],
        ];

        foreach ($users as $data) {
            // withoutCentralApp(): outside a tenant context, the default scope only
            // sees users with no tenant memberships at all, which would hide this
            // user (and cause a duplicate-email insert) on subsequent seeder runs.
            $user = User::withoutCentralApp()->firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (! $user->tenants()->where('tenant_id', $tenant->id)->exists()) {
                $tenant->users()->attach($user->id);
            }

            $tenant->run(function () use ($user, $data) {
                $role = Role::where('name', $data['role']->value)
                    ->where('tenant_id', tenant('id'))
                    ->first();

                if ($role) {
                    DB::table(config('permission.table_names.model_has_roles'))->insertOrIgnore([
                        'role_id' => $role->id,
                        'model_type' => $user->getMorphClass(),
                        'model_id' => $user->id,
                    ]);
                }
            });
        }
    }
}
