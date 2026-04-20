<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class CentralAppScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Models using BelongsToTenant (Role, Permission) have a tenant_id column directly.
        // The User model uses a belongsToMany pivot instead.
        $usesTenantColumn = in_array(BelongsToTenant::class, class_uses_recursive($model));

        if (tenancy()->initialized) {
            if ($usesTenantColumn) {
                $builder->where($model->qualifyColumn(BelongsToTenant::$tenantIdColumn), tenant('id'));
            } else {
                // Allow users who belong to this tenant OR superadmins (no tenant memberships)
                $builder->where(function (Builder $q) {
                    $q->whereHas('tenants', function (Builder $inner) {
                        $inner->where('tenant_id', tenant('id'));
                    })->orWhereDoesntHave('tenants');
                });
            }
        } else {
            if ($usesTenantColumn) {
                $builder->whereNull($model->qualifyColumn(BelongsToTenant::$tenantIdColumn));
            } else {
                $builder->whereDoesntHave('tenants');
            }
        }
    }

    public function extend(Builder $builder)
    {
        $builder->macro('withoutCentralApp', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
