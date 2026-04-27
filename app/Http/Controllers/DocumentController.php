<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Inertia\Inertia;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $query = Document::with(['category', 'team', 'user', 'currentVersion']);

        if (!$user->hasRole('Admin')) {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereIn('team_id', $user->teams->pluck('id'));
            });
        }

        $documents = $query->latest()->paginate(10);
        
        $categories = \App\Models\Category::all();
        $teams = \App\Models\Team::whereHas('members', function($q) {
            $q->where('user_id', Auth::id());
        })->orWhere('owner_id', Auth::id())->get();

        return Inertia::render('Documents/Index', [
            'documents' => $documents,
            'categories' => $categories,
            'teams' => $teams
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB max
            'category_id' => 'nullable|exists:categories,id',
            'team_id' => 'nullable|exists:teams,id',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('file');
        
        // Use a private storage path
        $path = $file->store('documents/v1', 'local');

        $document = Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'team_id' => $request->team_id,
            'user_id' => Auth::id(),
            'status' => 'published',
        ]);

        $version = DocumentVersion::create([
            'document_id' => $document->id,
            'file_path' => $path,
            'version_number' => 1,
            'file_size' => $file->getSize(),
            'file_type' => $file->getClientMimeType(),
            'uploaded_by' => Auth::id(),
        ]);

        $document->update(['current_version_id' => $version->id]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'upload',
            'target_type' => 'Document',
            'target_id' => $document->id,
            'details' => "Mengunggah versi awal dari {$document->title}",
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $this->authorize('view', $document);

        $document->load(['category', 'team', 'user', 'currentVersion', 'versions.uploader']);
        
        return Inertia::render('Documents/Show', [
            'document' => $document
        ]);
    }

    /**
     * Download the specified document version.
     */
    public function download(Document $document, DocumentVersion $version = null)
    {
        $this->authorize('download', $document);

        $version = $version ?: $document->currentVersion;

        if (!$version || $version->document_id !== $document->id) {
            abort(404);
        }

        // TODO: Add permission checks (Team/User/Admin)
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'download',
            'target_type' => 'Document',
            'target_id' => $document->id,
            'details' => "Mengunduh versi {$version->version_number} dari {$document->title}",
        ]);

        return Storage::disk('local')->download($version->file_path, "{$document->title}_v{$version->version_number}." . pathinfo($version->file_path, PATHINFO_EXTENSION));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('update', $document);

        // If file is provided, upload new version
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|file|max:10240',
                'comment' => 'nullable|string',
            ]);

            $file = $request->file('file');
            $nextVersionNumber = $document->versions()->max('version_number') + 1;
            $path = $file->store("documents/v{$nextVersionNumber}", 'local');

            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'file_path' => $path,
                'version_number' => $nextVersionNumber,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'uploaded_by' => Auth::id(),
            ]);

            $document->update(['current_version_id' => $version->id]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'version_update',
                'target_type' => 'Document',
                'target_id' => $document->id,
                'details' => "Mengunggah versi {$nextVersionNumber}: " . ($request->comment ?: "Tanpa catatan"),
            ]);

            return redirect()->route('documents.show', $document)->with('success', 'Versi baru berhasil diunggah.');
        }

        // Otherwise, update metadata only
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $document->update($request->only(['title', 'description', 'category_id', 'team_id']));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'metadata_update',
            'target_type' => 'Document',
            'target_id' => $document->id,
            'details' => "Memperbarui metadata dokumen: {$document->title}",
        ]);

        return redirect()->route('documents.show', $document)->with('success', 'Metadata dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        $this->authorize('delete', $document);

        foreach ($document->versions as $version) {
            Storage::disk('local')->delete($version->file_path);
        }

        $document->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'target_type' => 'Document',
            'target_id' => 0,
            'details' => "Menghapus dokumen dan semua versinya.",
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen dan seluruh riwayatnya berhasil dihapus.');
    }
}
