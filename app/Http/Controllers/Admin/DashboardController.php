<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SalesOrder;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'newCustomersCount' => Customer::query()->whereDate('created_at', now()->toDateString())->count(),
            'totalSalesCount' => SalesOrder::query()->count(),
        ]);
    }
}
