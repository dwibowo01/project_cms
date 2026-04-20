<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        if (! tenant()) {
            return view('dashboard.central');
        }

        $tenantId = tenant('id');
        $viewName = 'dashboard.' . $tenantId;

        if (view()->exists($viewName)) {
            return view($viewName);
        }

        return view('dashboard.tenant');
    }
}
