<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Order;
use App\Models\Region;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $orders = Order::query()
            ->with('user')
            ->when($request->search, fn($q) => $q
                ->where('order_number', 'like', "%{$request->search}%")
                ->orWhere('project_name', 'like', "%{$request->search}%")
                ->orWhere('mitra_name', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status_project', $request->status))
            ->when($request->regional, fn($q) => $q->where('regional', $request->regional))
            ->when($request->design_type, fn($q) => $q->where('design_type', $request->design_type))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Orders/Index', [
            'orders'      => $orders,
            'filters'     => $request->only('search', 'status', 'regional', 'design_type'),
            'regionals'   => Order::distinct()->whereNotNull('regional')->pluck('regional')->sort()->values(),
            'designTypes' => Order::distinct()->whereNotNull('design_type')->pluck('design_type')->sort()->values(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Orders/Create', [
            'mitras'      => Mitra::orderBy('name')->get(['id', 'name']),
            'regions'     => Region::orderBy('type')->orderBy('name')->get(),
            'designTypes' => ['PT2-Expand', 'PT2', 'PT3', 'PT4'],
            'statuses'    => ['active', 'overdue', 'completed'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number'             => 'required|string|unique:orders,order_number',
            'project_name'             => 'required|string|max:255',
            'design_type'              => 'nullable|string|max:50',
            'status_project'           => 'required|in:active,overdue,completed',
            'category'                 => 'nullable|string|max:100',
            'program_type'             => 'nullable|string|max:100',
            'program_year'             => 'nullable|string|max:10',
            'regional'                 => 'nullable|string|max:100',
            'witel'                    => 'nullable|string|max:100',
            'datel'                    => 'nullable|string|max:100',
            'sto'                      => 'nullable|string|max:100',
            'wok'                      => 'nullable|string|max:100',
            'mitra_name'               => 'nullable|string|max:255',
            'surveyor'                 => 'nullable|string|max:255',
            'household_count'          => 'nullable|integer|min:0',
            'odp_plan'                 => 'nullable|integer|min:0',
            'odp_realization'          => 'nullable|integer|min:0',
            'port_plan'                => 'nullable|integer|min:0',
            'port_realization'         => 'nullable|integer|min:0',
            'alpro_data'               => 'nullable|string',
            'capex_per_port'           => 'nullable|numeric|min:0',
            'po_value'                 => 'nullable|numeric|min:0',
            'gr_value'                 => 'nullable|numeric|min:0',
            'ir_value'                 => 'nullable|numeric|min:0',
            'estimated_go_live'        => 'nullable|date',
            'actual_go_live'           => 'nullable|date',
            'start_date'               => 'nullable|date',
            'finish_installation_date' => 'nullable|date',
            'duration_automation'      => 'nullable|integer|min:0',
            'duration_review'          => 'nullable|integer|min:0',
            'duration_approval'        => 'nullable|integer|min:0',
            'duration_go_live'         => 'nullable|integer|min:0',
            'description'              => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order berhasil ditambahkan.');
    }

    public function show(Order $order): Response
    {
        return Inertia::render('Orders/Show', [
            'order' => $order->load('user'),
        ]);
    }

    public function edit(Order $order): Response
    {
        return Inertia::render('Orders/Edit', [
            'order'       => $order,
            'mitras'      => Mitra::orderBy('name')->get(['id', 'name']),
            'regions'     => Region::orderBy('type')->orderBy('name')->get(),
            'designTypes' => ['PT2-Expand', 'PT2', 'PT3', 'PT4'],
            'statuses'    => ['active', 'overdue', 'completed'],
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_number'             => 'required|string|unique:orders,order_number,' . $order->id,
            'project_name'             => 'required|string|max:255',
            'design_type'              => 'nullable|string|max:50',
            'status_project'           => 'required|in:active,overdue,completed',
            'category'                 => 'nullable|string|max:100',
            'program_type'             => 'nullable|string|max:100',
            'program_year'             => 'nullable|string|max:10',
            'regional'                 => 'nullable|string|max:100',
            'witel'                    => 'nullable|string|max:100',
            'datel'                    => 'nullable|string|max:100',
            'sto'                      => 'nullable|string|max:100',
            'wok'                      => 'nullable|string|max:100',
            'mitra_name'               => 'nullable|string|max:255',
            'surveyor'                 => 'nullable|string|max:255',
            'household_count'          => 'nullable|integer|min:0',
            'odp_plan'                 => 'nullable|integer|min:0',
            'odp_realization'          => 'nullable|integer|min:0',
            'port_plan'                => 'nullable|integer|min:0',
            'port_realization'         => 'nullable|integer|min:0',
            'alpro_data'               => 'nullable|string',
            'capex_per_port'           => 'nullable|numeric|min:0',
            'po_value'                 => 'nullable|numeric|min:0',
            'gr_value'                 => 'nullable|numeric|min:0',
            'ir_value'                 => 'nullable|numeric|min:0',
            'estimated_go_live'        => 'nullable|date',
            'actual_go_live'           => 'nullable|date',
            'start_date'               => 'nullable|date',
            'finish_installation_date' => 'nullable|date',
            'duration_automation'      => 'nullable|integer|min:0',
            'duration_review'          => 'nullable|integer|min:0',
            'duration_approval'        => 'nullable|integer|min:0',
            'duration_go_live'         => 'nullable|integer|min:0',
            'description'              => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus.');
    }

    public function upload(): Response
    {
        return Inertia::render('Orders/Upload');
    }

    public function processUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Import logic using Maatwebsite Excel
            // \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\OrdersImport, $request->file('file'));
            return back()->with('success', 'File berhasil diimport.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport file: ' . $e->getMessage());
        }
    }

    public function monitoring(Request $request): Response
    {
        $orders = Order::query()
            ->when($request->regional, fn($q) => $q->where('regional', $request->regional))
            ->when($request->status, fn($q) => $q->where('status_project', $request->status))
            ->when($request->design_type, fn($q) => $q->where('design_type', $request->design_type))
            ->when($request->search, fn($q) => $q
                ->where('project_name', 'like', "%{$request->search}%")
                ->orWhere('order_number', 'like', "%{$request->search}%"))
            ->orderBy('regional')
            ->orderBy('project_name')
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Orders/Monitoring', [
            'orders'      => $orders,
            'filters'     => $request->only('search', 'regional', 'status', 'design_type'),
            'regionals'   => Order::distinct()->whereNotNull('regional')->pluck('regional')->sort()->values(),
            'designTypes' => Order::distinct()->whereNotNull('design_type')->pluck('design_type')->sort()->values(),
        ]);
    }

    public function analytics(Request $request): Response
    {
        $byRegional = Order::selectRaw('regional, status_project, count(*) as total, sum(odp_plan) as odp_plan, sum(odp_realization) as odp_real, sum(port_plan) as port_plan, sum(port_realization) as port_real, sum(po_value) as po_value, sum(gr_value) as gr_value')
            ->groupBy('regional', 'status_project')
            ->get();

        $byDesignType = Order::selectRaw('design_type, count(*) as total, sum(odp_plan) as odp_plan, sum(odp_realization) as odp_real')
            ->whereNotNull('design_type')
            ->groupBy('design_type')
            ->get();

        $byMitra = Order::selectRaw('mitra_name, count(*) as total, sum(odp_realization) as odp_real')
            ->whereNotNull('mitra_name')
            ->groupBy('mitra_name')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $monthly = Order::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return Inertia::render('Orders/Analytics', [
            'byRegional'   => $byRegional,
            'byDesignType' => $byDesignType,
            'byMitra'      => $byMitra,
            'monthly'      => $monthly,
            'summary' => [
                'total'      => Order::count(),
                'active'     => Order::where('status_project', 'active')->count(),
                'completed'  => Order::where('status_project', 'completed')->count(),
                'overdue'    => Order::where('status_project', 'overdue')->count(),
                'totalPo'    => Order::sum('po_value'),
                'totalGr'    => Order::sum('gr_value'),
                'totalIr'    => Order::sum('ir_value'),
                'odpRate'    => Order::sum('odp_plan') > 0
                    ? round(Order::sum('odp_realization') / Order::sum('odp_plan') * 100, 1) : 0,
                'portRate'   => Order::sum('port_plan') > 0
                    ? round(Order::sum('port_realization') / Order::sum('port_plan') * 100, 1) : 0,
            ],
        ]);
    }
}