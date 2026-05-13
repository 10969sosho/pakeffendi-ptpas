<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    public function showLogin(Request $request)
    {
        if ($request->filled('redirect')) {
            $request->session()->put('url.intended', $request->string('redirect')->toString());
        }

        return view('guest.auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:190'],
            'password' => ['required', 'string', 'max:190'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $login = trim((string) $validated['login']);
        $credentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $validated['password']]
            : ['phone' => $login, 'password' => $validated['password']];

        $remember = (bool) ($validated['remember'] ?? false);

        // 1. Try Customer Login
        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $customer = Auth::guard('customer')->user();

            if ($customer->status !== 'active') {
                Auth::guard('customer')->logout();

                return back()->withErrors(['login' => 'Akun Anda belum aktif atau ditolak. Silakan hubungi Admin.']);
            }

            $request->session()->regenerate();
            $resolved = $this->cartService->resolve($request, $customer);

            return redirect()->intended('/profile')
                ->withCookie($resolved['cookie'])
                ->with('success', 'Login berhasil! Selamat datang kembali, '.$customer->full_name);
        }

        // 2. Try Sales/Admin Login (User guard)
        // Note: Users usually login via email, not phone. But we'll try with email.
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('web')->attempt(['email' => $login, 'password' => $validated['password']], $remember)) {
                $user = Auth::guard('web')->user();

                // Check Role
                if ($user->role === 'sales') {
                    $request->session()->regenerate();

                    return redirect()->intended('/profile')
                        ->with('success', 'Login berhasil sebagai Sales! Selamat datang kembali, '.$user->name);
                }

                // Admin is not allowed to login here
                Auth::guard('web')->logout();
            }
        }

        return back()
            ->withErrors(['login' => 'Email/HP atau kata sandi salah.'])
            ->onlyInput('login');
    }

    public function showRegister(Request $request)
    {
        if ($request->filled('redirect')) {
            $request->session()->put('url.intended', $request->string('redirect')->toString());
        }

        return view('guest.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:customers,email'],
            'phone' => ['required', 'string', 'max:30', 'unique:customers,phone'],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
            $mailBody = implode(PHP_EOL, [
                'New Customer Registration:',
                '',
                'Name: '.$customer->full_name,
                'Email: '.$customer->email,
                'Phone: '.$customer->phone,
                '',
                'Please review in Admin Dashboard.',
            ]);

            Mail::raw($mailBody, function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                    ->subject('New Customer Registration Request');
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration Mail Failed: '.$e->getMessage());
        }

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();
        $resolved = $this->cartService->resolve($request, $customer);

        return redirect()->intended('/profile')
            ->withCookie($resolved['cookie'])
            ->with('success', 'Pendaftaran berhasil! Selamat datang, '.$customer->full_name);
    }

    public function logout(Request $request)
    {
        // 1. Logout Customer if logged in
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
        }

        // 2. Logout Sales (Web Guard) if logged in
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            if ($user->role === 'sales') {
                Auth::guard('web')->logout();
            }
        }

        // 3. Invalidate session ONLY if no one is logged in anymore
        // This preserves the Admin session if they were logged in while a Customer logged out
        if (! Auth::guard('customer')->check() && ! Auth::guard('web')->check()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->to('/')->with('success', 'Anda telah berhasil logout.');
    }

    private function generateCustomerCode(): string
    {
        return 'W'.now()->format('ym').str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function generateKtpNumber(): string
    {
        return (string) random_int(1000000000000000, 9999999999999999);
    }
}
