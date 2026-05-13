<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSales
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
             return redirect()->route('guest.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'sales') {
             // If user is Admin, maybe redirect to admin dashboard?
             if ($user->isAdmin()) {
                 return redirect()->route('admin.dashboard')->with('error', 'Halaman ini khusus untuk Sales.');
             }
             
             // If user is Customer (shouldn't happen if this middleware is after auth:web), 
             // but if auth:customer,web is used, $request->user() might be Customer.
             if ($user instanceof \App\Models\Customer) {
                  return redirect()->route('guest.login')->with('error', 'Akses khusus Sales.');
             }

            abort(403, 'Unauthorized. Sales role required.');
        }

        return $next($request);
    }
}
