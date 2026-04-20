<?php

namespace App\Actions\Fortify;

use Closure;

class PrepareTenantsForAuth
{
    public function handle($request, Closure $next)
    {
        $selectedTenant = $request->input('selected_tenant');

        // Initialize tenancy so CentralAppScope allows tenant users to be found
        // by the auth guard's credential check (AttemptToAuthenticate).
        if ($selectedTenant && ! tenancy()->initialized) {
            tenancy()->initialize($selectedTenant);
        }

        $response = $next($request);

        // End tenancy after authentication so the rest of the request
        // (session preparation, LoginResponse) runs on the central context.
        if (tenancy()->initialized) {
            tenancy()->end();
        }

        return $response;
    }
}
