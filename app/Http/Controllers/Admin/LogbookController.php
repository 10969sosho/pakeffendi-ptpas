<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $logs = ActivityLog::query()
            ->with('actor')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query
                        ->where('description', 'like', "%{$q}%")
                        ->orWhere('data', 'like', "%{$q}%");
                })->orWhereHas('actor', fn ($q2) => $q2->where('name', 'like', "%{$q}%"));
            })
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        return view('admin.logs.index', [
            'logs' => $logs,
            'q' => $q,
        ]);
    }
}
