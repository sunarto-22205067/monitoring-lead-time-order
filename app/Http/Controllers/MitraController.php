<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MitraController extends Controller
{
    public function index(Request $request): Response
    {
        $mitras = Mitra::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('region', 'like', "%{$request->search}%"))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Mitras/Index', [
            'mitras'  => $mitras,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'region'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Mitra::create($validated);

        return back()->with('success', 'Mitra berhasil ditambahkan.');
    }

    public function update(Request $request, Mitra $mitra)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'region'      => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $mitra->update($validated);

        return back()->with('success', 'Mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra)
    {
        $mitra->delete();
        return back()->with('success', 'Mitra berhasil dihapus.');
    }
}
