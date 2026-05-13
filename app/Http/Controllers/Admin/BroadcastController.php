<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function index(Request $request)
    {
        $q = (string) $request->query('q', '');

        $broadcasts = Broadcast::query()
            ->when($q !== '', fn ($query) => $query->where('description', 'like', "%{$q}%"))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.broadcasts.index', [
            'broadcasts' => $broadcasts,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('admin.broadcasts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:4096'],
            'description' => ['required', 'string'],
        ]);

        $imagePath = $request->file('image')->store('broadcasts', 'public');

        $broadcast = Broadcast::create([
            'image_path' => $imagePath,
            'description' => $validated['description'],
        ]);

        ActivityLogger::log('created', 'Broadcast - '.$broadcast->id);

        return redirect()->route('admin.broadcasts.index')->with('status', 'Broadcast berhasil dibuat.');
    }

    public function edit(Broadcast $broadcast)
    {
        return view('admin.broadcasts.edit', [
            'broadcast' => $broadcast,
        ]);
    }

    public function update(Request $request, Broadcast $broadcast)
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'max:4096'],
            'description' => ['required', 'string'],
        ]);

        if ($request->hasFile('image')) {
            $broadcast->image_path = $request->file('image')->store('broadcasts', 'public');
        }

        $broadcast->description = $validated['description'];
        $broadcast->save();

        ActivityLogger::log('updated', 'Broadcast - '.$broadcast->id);

        return redirect()->route('admin.broadcasts.index')->with('status', 'Broadcast berhasil diupdate.');
    }

    public function destroy(Broadcast $broadcast)
    {
        $id = $broadcast->id;
        $broadcast->delete();

        ActivityLogger::log('deleted', 'Broadcast - '.$id);

        return redirect()->route('admin.broadcasts.index')->with('status', 'Broadcast berhasil dihapus.');
    }
}
