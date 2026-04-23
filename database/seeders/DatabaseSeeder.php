<?php

namespace Database\Seeders;

use App\Enums\CentralRoles;
use App\Enums\Roles;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

    }
}
