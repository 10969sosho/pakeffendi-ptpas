<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in as Admin (Web Guard)
        // PRIORITIZE THIS CHECK. If user is Admin, allow access regardless of Customer session.
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Check specific roles
            $role = strtolower((string) $user->role);
            $allowed = in_array($role, ['admin', 'super admin', 'super_admin', 'superadmin'], true);

            if ($allowed) {
                return $next($request);
            }

            // Sales trying to access Admin
            if ($role === 'sales') {
                 return redirect()->route('admin.login')->with('error', 'Akses ditolak. Sales tidak memiliki akses Admin.');
            }
            
            // Other roles?
             return redirect()->route('admin.login')->with('error', 'Akses ditolak. Peran Anda tidak diizinkan.');
        }

        // 2. If NOT logged in as Admin, but logged in as Customer
        // This means they are a Customer trying to access Admin area WITHOUT Admin session.
        if (Auth::guard('customer')->check()) {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Anda login sebagai Pelanggan.');
        }

        // 3. Not logged in at all
        return redirect()->route('admin.login')->with('error', 'Silakan login sebagai Admin terlebih dahulu.');
    }
}
