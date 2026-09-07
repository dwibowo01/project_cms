<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Deliberately does not extend Stancl's middleware of the same name: that
 * class is hardcoded into tenancy's middleware priority list to always run
 * before tenant identification, which would defeat the ?tenant= fallback
 * this override exists for (see InitializeTenancyBySubDomain).
 */
class PreventAccessFromCentralDomains
{
    public function handle(Request $request, Closure $next): Response
    {
        // Allow through on bare IP/localhost deployments where tenancy was
        // already resolved via the ?tenant= query param fallback.
        if (tenancy()->initialized) {
            return $next($request);
        }

        if (in_array($request->getHost(), config('tenancy.central_domains'))) {
            abort(404);
        }

        return $next($request);
    }
}
