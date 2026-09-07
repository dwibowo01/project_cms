<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * Used only for tenant-scoped resource routes (routes/tenant.php), which always
 * require a tenant and whose internal links don't all forward ?tenant=. Remembers
 * the last resolved tenant via cookie on bare IP/localhost deployments so those
 * links keep working. Central-only routes (routes/shared.php) intentionally use
 * the plain InitializeTenancyBySubDomain instead, so they aren't affected by a
 * tenant browsed earlier.
 */
class InitializeTenancyBySubDomainWithCookieFallback extends InitializeTenancyBySubDomain
{
    private const TENANT_COOKIE = 'active_tenant';

    public function handle($request, Closure $next): Response
    {
        $subdomain = $this->makeSubdomain($request->getHost());

        if (!is_object($subdomain) || !($subdomain instanceof Exception)) {
            return $this->initializeTenancy($request, $next, $subdomain);
        }

        $tenantId = $request->query('tenant') ?? $request->cookies->get(self::TENANT_COOKIE);
        $this->resolveTenant($tenantId);

        $response = $next($request);

        if ($tenantId && tenancy()->initialized) {
            $response->headers->setCookie(Cookie::create(self::TENANT_COOKIE, $tenantId, now()->addDay()));
        }

        return $response;
    }
}
