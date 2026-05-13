<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        $page = AboutPage::query()->first();

        if (!$page) {
            $page = AboutPage::create([
                'content' => null,
            ]);
        }

        return view('admin.about.edit', [
            'page' => $page,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'content' => ['nullable', 'string'],
        ]);

        $page = AboutPage::query()->first();

        if (!$page) {
            $page = AboutPage::create([
                'content' => $validated['content'] ?? null,
            ]);
        } else {
            $page->content = $validated['content'] ?? null;
            $page->save();
        }

        ActivityLogger::log('updated', 'AboutUs');

        return redirect()->route('admin.about.edit')->with('status', 'Konten berhasil disimpan.');
    }
}
