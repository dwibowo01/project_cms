<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SwitchTenantController extends Controller
{
    public function __invoke(Request $request, Tenant $tenant): RedirectResponse
    {
        $user = $request->user();

        // Super admins can switch to any tenant
        // Regular users must have a pivot row for the tenant
        if (! $user->isSuperAdmin() && ! $user->tenants()->where('tenant_id', $tenant->id)->exists()) {
            abort(403, 'You do not have access to this tenant.');
        }

        $domain = $tenant->domains()->first()?->domain;

        if (! $domain) {
            return redirect()->route('dashboard')
                ->with('error', 'This tenant has no domain configured.');
        }

        $centralDomain = config('tenancy.central_domains')[2] ?? 'project_cms.test';
        $scheme = $request->getScheme();

        return redirect()->away("{$scheme}://{$domain}.{$centralDomain}/dashboard");
    }
}
