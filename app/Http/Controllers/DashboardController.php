<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Mitra;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $totalOrders      = Order::count();
        $activeOrders     = Order::where('status_project', 'active')->count();
        $completedOrders  = Order::where('status_project', 'completed')->count();
        $overdueOrders    = Order::where('status_project', 'overdue')->count();

        $totalOdpPlan         = Order::sum('odp_plan');
        $totalOdpRealization  = Order::sum('odp_realization');
        $totalPortPlan        = Order::sum('port_plan');
        $totalPortRealization = Order::sum('port_realization');

        $totalPoValue = Order::sum('po_value');
        $totalGrValue = Order::sum('gr_value');
        $totalIrValue = Order::sum('ir_value');

        // By Regional
        $byRegional = Order::selectRaw('regional, count(*) as total, sum(odp_realization) as odp_real, sum(odp_plan) as odp_plan_total')
            ->groupBy('regional')
            ->orderByDesc('total')
            ->get();

        // By Design Type
        $byDesignType = Order::selectRaw('design_type, count(*) as total')
            ->whereNotNull('design_type')
            ->groupBy('design_type')
            ->orderByDesc('total')
            ->get();

        // By Status
        $byStatus = Order::selectRaw('status_project, count(*) as total')
            ->groupBy('status_project')
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalOrders'         => $totalOrders,
                'activeOrders'        => $activeOrders,
                'completedOrders'     => $completedOrders,
                'overdueOrders'       => $overdueOrders,
                'totalOdpPlan'        => $totalOdpPlan,
                'totalOdpRealization' => $totalOdpRealization,
                'totalPortPlan'       => $totalPortPlan,
                'totalPortRealization'=> $totalPortRealization,
                'totalPoValue'        => $totalPoValue,
                'totalGrValue'        => $totalGrValue,
                'totalIrValue'        => $totalIrValue,
                'odpProgress'         => $totalOdpPlan > 0 ? round(($totalOdpRealization / $totalOdpPlan) * 100, 1) : 0,
                'portProgress'        => $totalPortPlan > 0 ? round(($totalPortRealization / $totalPortPlan) * 100, 1) : 0,
            ],
            'byRegional'   => $byRegional,
            'byDesignType' => $byDesignType,
            'byStatus'     => $byStatus,
            'recentOrders' => $recentOrders,
            'totalMitras'  => Mitra::count(),
            'totalUsers'   => User::count(),
        ]);
    }
}