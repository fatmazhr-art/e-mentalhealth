<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuthenticated
{
    /**
     * Redirect admin yang sudah login agar tidak mengakses halaman login lagi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
