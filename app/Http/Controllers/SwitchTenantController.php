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

        // On bare IP deployments, subdomains are not possible.
        // Fall back to APP_URL + ?tenant= query param instead.
        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
        if (filter_var($appHost, FILTER_VALIDATE_IP)) {
            return redirect(config('app.url') . '/dashboard?tenant=' . $tenant->id);
        }

        $centralDomain = collect(config('tenancy.central_domains'))
            ->first(fn ($d) => str_contains($d, '.'));
        $scheme = $request->getScheme();

        return redirect()->away("{$scheme}://{$domain}.{$centralDomain}/dashboard");
    }
}
