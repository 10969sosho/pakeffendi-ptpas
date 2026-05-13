<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        $role = strtolower((string) (Auth::user()?->role ?? ''));
        $allowed = in_array($role, ['admin', 'super admin', 'super_admin', 'superadmin'], true);

        if (!$allowed) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun ini tidak punya akses admin.',
            ])->onlyInput('email');
        }

        ActivityLogger::log('login', 'Admin login');

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        ActivityLogger::log('logout', 'Admin logout');

        Auth::guard('web')->logout();

        // If no other guard is logged in, we can invalidate session
        if (!Auth::guard('customer')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil logout.');
    }
}
