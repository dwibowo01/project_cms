<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain as Father;
use Stancl\Tenancy\Tenancy;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyBySubDomain extends Father
{
    public function handle($request, Closure $next): Response
    {
        $subdomain = $this->makeSubdomain($request->getHost());

        // Subdomain detected — use normal subdomain-based tenancy
        if (!is_object($subdomain) || !($subdomain instanceof Exception)) {
            return $this->initializeTenancy($request, $next, $subdomain);
        }

        // No subdomain (e.g. bare IP) — fall back to ?tenant= query param.
        // Central-only routes (dashboard, /, roles, tenants) rely on this
        // reflecting only the CURRENT request, so a missing ?tenant= here
        // correctly means "central", even if a tenant was browsed earlier.
        $this->resolveTenant($request->query('tenant'));

        // Continue regardless — central routes work without tenant context
        return $next($request);
    }

    protected function resolveTenant(?string $tenantId): void
    {
        if (! $tenantId) {
            return;
        }

        /** @var Tenancy $tenancy */
        $tenancy = app(Tenancy::class);
        $tenant = $tenancy->find($tenantId);

        if ($tenant) {
            $tenancy->initialize($tenant);
        }
    }
}
