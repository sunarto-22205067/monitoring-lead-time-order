<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Inertia\Inertia;

class IncidentDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => IncidentReport::count(),
            'new' => IncidentReport::where('status', 'new')->count(),
            'processing' => IncidentReport::where('status', 'processing')->count(),
            'critical' => IncidentReport::where('priority', 'critical')->count(),
            
            // For Charts
            'by_status' => IncidentReport::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
            'by_priority' => IncidentReport::select('priority', DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->get(),
            'by_unit' => IncidentReport::select('work_unit', DB::raw('count(*) as count'))
                ->groupBy('work_unit')
                ->get(),
            
            'recent' => IncidentReport::with('creator')->latest()->limit(5)->get(),
        ];

        return Inertia::render('Incidents/Dashboard', [
            'stats' => $stats
        ]);
    }

    public function apiStats()
    {
        return response()->json([
            'total' => IncidentReport::count(),
            'new' => IncidentReport::where('status', 'new')->count(),
            'processing' => IncidentReport::where('status', 'processing')->count(),
            'critical' => IncidentReport::where('priority', 'critical')->count(),
        ]);
    }
}
