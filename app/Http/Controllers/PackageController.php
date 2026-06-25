<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = \App\Models\Package::orderBy('created_at', 'desc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:packages|max:255',
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
        ]);

        $features = $request->features ? array_map('trim', explode("\n", $request->features)) : [];

        \App\Models\Package::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'features' => $features,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(\App\Models\Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, \App\Models\Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug,' . $package->id,
            'price' => 'required|integer|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
        ]);

        $features = $request->features ? array_map('trim', explode("\n", $request->features)) : [];

        $package->update([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'features' => $features,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(\App\Models\Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus.');
    }
}
