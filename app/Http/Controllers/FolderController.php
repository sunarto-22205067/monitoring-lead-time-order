<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FolderController extends Controller
{
    public function index()
    {
        return Inertia::render('Folders/Index', [
            'folders' => Folder::with('category')->withCount('documents')->get(),
            'categories' => \App\Models\Category::all(['id', 'name'])
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        Folder::create($request->only(['name', 'category_id', 'description']));

        return redirect()->back()->with('success', 'Folder berhasil dibuat.');
    }

    public function update(Request $request, Folder $folder)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string'
        ]);

        $folder->update($request->only(['name', 'category_id', 'description']));

        return redirect()->back()->with('success', 'Folder berhasil diperbarui.');
    }

    public function destroy(Folder $folder)
    {
        if ($folder->documents()->exists()) {
            return redirect()->back()->with('error', 'Folder tidak dapat dihapus karena berisi dokumen.');
        }

        $folder->delete();
        return redirect()->back()->with('success', 'Folder berhasil dihapus.');
    }
}
