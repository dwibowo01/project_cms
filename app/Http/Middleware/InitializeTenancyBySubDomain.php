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

        // No subdomain (e.g. bare IP) — fall back to ?tenant= query param
        $tenantId = $request->query('tenant');

        if ($tenantId) {
            /** @var Tenancy $tenancy */
            $tenancy = app(Tenancy::class);
            $tenant = $tenancy->find($tenantId);

            if ($tenant) {
                $tenancy->initialize($tenant);
            }
        }

        // Continue regardless — central routes work without tenant context
        return $next($request);
    }
}
