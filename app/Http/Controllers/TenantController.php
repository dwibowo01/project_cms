<?php

namespace App\Http\Controllers;

use App\Enums\CentralPermissions;
use App\Enums\CentralRoles;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\Http\Requests\Tenant\StoreTenantRequest;
use App\Http\Requests\Tenant\UpdateTenantRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Middleware\PermissionMiddleware;

class TenantController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using(CentralPermissions::CREATE_TEAM), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using(CentralPermissions::VIEW_TEAM), only: ['index', 'show']),
            new Middleware(PermissionMiddleware::using(CentralPermissions::UPDATE_TEAM), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using(CentralPermissions::DELETE_TEAM), only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('tenants.index', [
            'tenants' => Tenant::with('domains')->paginate(),
        ]);
    }

    public function create(): View
    {
        return view('tenants.create');
    }

    public function store(StoreTenantRequest $request): RedirectResponse
    {
        $tenantData = collect($request->only('id'))
            ->merge(json_decode($request->input('data'), true) ?? [])
            ->toArray();

        $tenant = Tenant::create($tenantData);

        $tenant->domains()->createMany(
            $request->collect('domains')
                ->map(fn ($domain) => ['domain' => $domain])
        );

        $superAdminIds = User::role(CentralRoles::SUPER_ADMIN->value)->pluck('id');

        $tenant->run(function () use ($superAdminIds, $tenant) {
            $adminRoleEnum = Roles::adminForTenant($tenant->id);
            $adminRoleInstance = null;

            foreach (Roles::byTenant($tenant->id) as $roleEnum) {
                $role = Role::firstOrCreate(
                    ['name' => $roleEnum->value, 'tenant_id' => $tenant->id],
                    ['guard_name' => 'web']
                );

                $permissions = collect(Permissions::byRole($roleEnum))
                    ->map(fn (Permissions $perm) => Permission::firstOrCreate(
                        ['name' => $perm->value, 'tenant_id' => $tenant->id],
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

        return redirect()->intended(route('tenants.index'));
    }

    public function show(Tenant $tenant): View
    {
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant): View
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $tenantData = json_decode($request->input('data'), true) ?? [];

        $attributes = Arr::except($tenant->getAttributes(), Tenant::getCustomColumns());

        foreach (array_keys($attributes) as $key) {
            unset($tenant->$key);
        }

        $tenant->update($tenantData);

        $tenant->domains()->delete();
        $tenant->domains()->createMany(
            $request->collect('domains')
                ->map(fn ($domain) => ['domain' => $domain])
        );

        return redirect()->intended(route('tenants.index'));
    }

    public function destroy(Request $request, Tenant $tenant): RedirectResponse
    {
        $request->validateWithBag($tenant->id, [
            'password' => ['required', 'current_password'],
        ]);

        Permission::withoutCentralApp()->where('tenant_id', $tenant->id)->delete();
        Role::withoutCentralApp()->where('tenant_id', $tenant->id)->delete();
        User::withoutCentralApp()->where('tenant_id', $tenant->id)->delete();

        $tenant->delete();

        return redirect()->intended(route('tenants.index'));
    }
}
