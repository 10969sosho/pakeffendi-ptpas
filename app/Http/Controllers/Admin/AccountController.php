<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $accounts = User::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('role', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accounts.index', [
            'accounts' => $accounts,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('admin.accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'super admin', 'sales'])],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', 'public');
        }

        $account = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'photo_path' => $photoPath,
        ]);

        ActivityLogger::log('created', 'Account - '.$account->email);

        return redirect()->route('admin.accounts.index')->with('status', 'Account berhasil dibuat.');
    }

    public function edit(User $account)
    {
        return view('admin.accounts.edit', [
            'account' => $account,
        ]);
    }

    public function update(Request $request, User $account)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($account->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', Rule::in(['admin', 'super admin', 'sales'])],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            $account->photo_path = $request->file('photo')->store('users', 'public');
        }

        $account->name = $validated['name'];
        $account->email = $validated['email'];
        $account->role = $validated['role'];

        if (!empty($validated['password'])) {
            $account->password = Hash::make($validated['password']);
        }

        $account->save();

        ActivityLogger::log('updated', 'Account - '.$account->email);

        return redirect()->route('admin.accounts.index')->with('status', 'Account berhasil diupdate.');
    }

    public function destroy(User $account)
    {
        if (auth()->id() === $account->id) {
            return back()->withErrors(['Tidak bisa menghapus akun sendiri.']);
        }

        $email = $account->email;
        $account->delete();

        ActivityLogger::log('deleted', 'Account - '.$email);

        return redirect()->route('admin.accounts.index')->with('status', 'Account berhasil dihapus.');
    }
}
