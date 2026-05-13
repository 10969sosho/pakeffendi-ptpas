<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    private function getShopper()
    {
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user();
        }
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->isSales()) {
            return Auth::guard('web')->user();
        }

        return null;
    }

    public function index(Request $request)
    {
        $shopper = $this->getShopper();
        abort_unless($shopper, 401);

        $isSales = ($shopper instanceof User && $shopper->isSales());

        if (! $isSales) {
            $recentOrders = SalesOrder::query()
                ->where('customer_id', $shopper->id)
                ->withCount('items')
                ->latest('order_date')
                ->limit(5)
                ->get();

            return view('guest.profile.index', [
                'customer' => $shopper,
                'recentOrders' => $recentOrders,
                'is_sales' => false,
            ]);
        }

        foreach (['customer', 'date_from', 'date_to', 'q'] as $key) {
            if ($request->has($key) && $request->input($key) === '') {
                $request->merge([$key => null]);
            }
        }

        $validated = $request->validate([
            'customer' => ['nullable', 'string', 'max:120'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'q' => ['nullable', 'string', 'max:120'],
        ]);

        $dateFrom = trim((string) ($validated['date_from'] ?? ''));
        $dateTo = trim((string) ($validated['date_to'] ?? ''));

        if ($dateFrom !== '' && $dateTo !== '' && $dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $customerName = trim((string) ($validated['customer'] ?? ''));
        $q = trim((string) ($validated['q'] ?? ''));

        $ordersQuery = SalesOrder::query()
            ->where(function ($query) use ($shopper) {
                $query
                    ->where('sales_id', $shopper->id)
                    ->orWhere('sales_person_id', $shopper->id);
            })
            ->with('customer')
            ->when($dateFrom !== '', fn ($query) => $query->whereDate('order_date', '>=', $dateFrom))
            ->when($dateTo !== '', fn ($query) => $query->whereDate('order_date', '<=', $dateTo))
            ->when($customerName !== '', function ($query) use ($customerName) {
                $query->whereHas('customer', function ($customerQuery) use ($customerName) {
                    $customerQuery->where('full_name', 'like', "%{$customerName}%");
                });
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($where) use ($q) {
                    $where
                        ->where('order_no', 'like', "%{$q}%")
                        ->orWhereHas('items', function ($itemsQuery) use ($q) {
                            $itemsQuery->where(function ($itemWhere) use ($q) {
                                $itemWhere
                                    ->where('product_name', 'like', "%{$q}%")
                                    ->orWhereHas('product', function ($productQuery) use ($q) {
                                        $productQuery
                                            ->where('sku', 'like', "%{$q}%")
                                            ->orWhere('name', 'like', "%{$q}%");
                                    });
                            });
                        });
                });
            });

        $totalNominal = (float) (clone $ordersQuery)->sum('grand_total');
        $totalTransaksi = (int) (clone $ordersQuery)->count();

        $orders = $ordersQuery
            ->withCount('items')
            ->latest('order_date')
            ->paginate(10)
            ->withQueryString();

        return view('guest.profile.index', [
            'customer' => $shopper,
            'is_sales' => true,
            'orders' => $orders,
            'order_filters' => [
                'customer' => $customerName,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'q' => $q,
            ],
            'order_stats' => [
                'total_nominal' => $totalNominal,
                'total_transaksi' => $totalTransaksi,
            ],
        ]);
    }

    public function logs()
    {
        $shopper = $this->getShopper();
        abort_unless($shopper && $shopper instanceof User && $shopper->isSales(), 401);

        // Fetch logs related to this sales user
        $logs = \App\Models\ActivityLog::where('actor_id', $shopper->id)
            ->latest()
            ->paginate(20);

        return view('guest.profile.logs', [
            'customer' => $shopper,
            'logs' => $logs,
            'is_sales' => true,
        ]);
    }

    public function update(Request $request)
    {
        $shopper = $this->getShopper();
        abort_unless($shopper, 401);

        if ($shopper instanceof Customer) {
            $validated = $request->validate([
                'full_name' => ['required', 'string', 'max:120'],
                'phone' => ['required', 'string', 'max:30', Rule::unique('customers', 'phone')->ignore($shopper->id)],
            ]);
            $shopper->update($validated);
        } else {
            // Sales Update Profile
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($shopper->id)],
                // Sales might not have phone/address fields in User model yet, or they are different.
                // Assuming User model only has name/email for now based on standard Laravel.
                // If User model has phone/address, add validation here.
            ]);
            $shopper->update($validated);
        }

        return back()->with('status', 'Profil diperbarui.');
    }
}
