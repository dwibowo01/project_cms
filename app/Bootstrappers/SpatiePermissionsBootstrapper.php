<?php

namespace App\Bootstrappers;

use Spatie\Permission\PermissionRegistrar;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant;

class SpatiePermissionsBootstrapper implements TenancyBootstrapper
{
    public function __construct(
        protected PermissionRegistrar $registrar,
    ) {}

    public function bootstrap(Tenant $tenant): void
    {
        $this->registrar->cacheKey = 'spatie.permission.cache.tenant.'.$tenant->getTenantKey();
        $this->registrar->forgetCachedPermissions();
    }

    public function revert(): void
    {
        $this->registrar->cacheKey = 'spatie.permission.cache';
        $this->registrar->forgetCachedPermissions();
    }
}
