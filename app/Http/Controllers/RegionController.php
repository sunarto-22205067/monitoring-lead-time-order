<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegionController extends Controller
{
    public function index(Request $request): Response
    {
        $regions = Region::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('parent_name', 'like', "%{$request->search}%"))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->orderBy('type')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Regions/Index', [
            'regions' => $regions,
            'filters' => $request->only('search', 'type'),
            'types'   => Region::$types,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'        => 'required|in:Witel,Datel,STO',
            'name'        => 'required|string|max:255',
            'parent_name' => 'nullable|string|max:255',
        ]);

        Region::create($validated);

        return back()->with('success', 'Region berhasil ditambahkan.');
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'type'        => 'required|in:Witel,Datel,STO',
            'name'        => 'required|string|max:255',
            'parent_name' => 'nullable|string|max:255',
        ]);

        $region->update($validated);

        return back()->with('success', 'Region berhasil diperbarui.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        return back()->with('success', 'Region berhasil dihapus.');
    }
}
