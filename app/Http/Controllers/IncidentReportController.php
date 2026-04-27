<?php

namespace App\Http\Controllers;

use App\Models\IncidentLog;
use App\Models\IncidentReport;
use App\Models\IncidentAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class IncidentReportController extends Controller
{
    public function index(Request $request)
    {
        $query = IncidentReport::with(['creator', 'assignee']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority != 'all') {
            $query->where('priority', $request->priority);
        }

        $reports = $query->latest()->paginate(10);

        return Inertia::render('Incidents/Index', [
            'reports' => $reports,
            'filters' => $request->only(['status', 'priority']),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'happened_at' => 'required|date',
            'location' => 'required|string|max:255',
            'work_unit' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,critical',
            'attachments.*' => 'nullable|file|max:10240', // 10MB
        ]);

        return DB::transaction(function () use ($request) {
            $report = IncidentReport::create([
                'title' => $request->title,
                'description' => $request->description,
                'happened_at' => $request->happened_at,
                'location' => $request->location,
                'work_unit' => $request->work_unit,
                'priority' => $request->priority,
                'status' => 'new',
                'created_by' => Auth::id(),
            ]);

            // Handle Attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('incidents/' . $report->id);
                    \App\Models\IncidentAttachment::create([
                        'incident_report_id' => $report->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            // Initial Log
            \App\Models\IncidentLog::create([
                'incident_report_id' => $report->id,
                'user_id' => Auth::id(),
                'action' => 'created',
                'to_status' => 'new',
                'comment' => 'Insiden dilaporkan.',
            ]);

            return redirect()->route('incidents.show', $report)->with('success', 'Insiden berhasil dilaporkan.');
        });
    }


    public function show(IncidentReport $incident)
    {
        $incident->load(['creator', 'assignee', 'attachments', 'logs.user']);
        return Inertia::render('Incidents/Show', [
            'report' => $incident,
            'available_users' => \App\Models\User::all(['id', 'name']),
        ]);
    }

    public function destroy(IncidentReport $report)
    {
        // Delete attachments from storage first
        foreach ($report->attachments as $attachment) {
            Storage::delete($attachment->file_path);
        }

        $report->delete();

        return redirect()->route('incidents.index')->with('success', 'Laporan insiden berhasil dihapus.');
    }

    public function updateStatus(Request $request, IncidentReport $report)
    {
        $request->validate([
            'status' => 'required|in:processing,waiting_follow_up,completed,closed',
            'comment' => 'nullable|string',
        ]);

        $oldStatus = $report->status;
        $report->update(['status' => $request->status]);

        IncidentLog::create([
            'incident_report_id' => $report->id,
            'user_id' => Auth::id(),
            'action' => 'status_updated',
            'from_status' => $oldStatus,
            'to_status' => $request->status,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function assign(Request $request, IncidentReport $report)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $report->update(['assigned_to' => $request->user_id]);

        IncidentLog::create([
            'incident_report_id' => $report->id,
            'user_id' => Auth::id(),
            'action' => 'assigned',
            'comment' => 'Insiden ditugaskan kepada ' . \App\Models\User::find($request->user_id)->name,
        ]);

        return back()->with('success', 'Insiden berhasil ditugaskan.');
    }

    public function export()
    {
        $reports = IncidentReport::with(['creator', 'assignee'])->latest()->get();
        $filename = "laporan_insiden_" . date('Ymd_His') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Judul', 'Deskripsi', 'Lokasi', 'Unit Kerja', 'Prioritas', 'Status', 'Dilaporkan Oleh', 'Ditugaskan Ke', 'Tanggal Kejadian'];

        $callback = function() use($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $report) {
                fputcsv($file, [
                    'INC-' . str_pad($report->id, 5, '0', STR_PAD_LEFT),
                    $report->title,
                    $report->description,
                    $report->location,
                    $report->work_unit,
                    ucfirst($report->priority),
                    ucfirst($report->status),
                    $report->creator->name,
                    $report->assignee->name ?? '-',
                    $report->happened_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
