<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $stockAvailable = Product::when($search, function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        })->get();

        // Estatísticas dos tipos de membros
        $numberOfBoardMembers = \App\Models\User::where('type', 'board')->count();
        $numberOfEmployees = \App\Models\User::where('type', 'employee')->count();
        $numberOfMembers = \App\Models\User::where('type', 'member')->count();

        // Outros dados
        $shippingCosts = \App\Models\Order::sum('shipping_cost') ?? 0;

        // Estatísticas de pedidos mensais
        $monthlyOrderStats = $this->getMonthlyOrderStats()->values();

        // Estatísticas de produtos vendidos por mês
        $monthlyProductSales = $this->getMonthlyProductSales();
        $allMonthsProductSales = collect(range(1, 12))->map(function ($month) use ($monthlyProductSales) {
            return $monthlyProductSales->get($month)->total_items_sold ?? 0;
        });

        $currentYear = date('Y');

        return view('admin.dashboard', compact(
            'stockAvailable',
            'search',
            'numberOfBoardMembers',
            'numberOfEmployees',
            'numberOfMembers',
            'shippingCosts',
            'monthlyOrderStats',
            'allMonthsProductSales',
            'currentYear'
        ));
    }

    private function getMonthlyOrderStats()
    {
        $currentYear = date('Y');
        $monthlyOrders = \App\Models\Order::selectRaw('
            MONTH(created_at) as month,
            COUNT(*) as total_orders,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = "canceled" THEN 1 ELSE 0 END) as canceled
        ')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $allMonths = collect(range(1, 12))->map(function ($month) use ($monthlyOrders) {
            $data = $monthlyOrders->get($month);
            return [
                'month' => $month,
                'completed' => $data->completed ?? 0,
                'pending' => $data->pending ?? 0,
                'canceled' => $data->canceled ?? 0,
                'total_orders' => $data->total_orders ?? 0,
            ];
        });

        return $allMonths->values();
    }

    private function getMonthlyProductSales()
    {
        $currentYear = date('Y');

        return \App\Models\Order::selectRaw('
            MONTH(created_at) as month,
            SUM(total_items) as total_items_sold
        ')
        ->whereYear('created_at', $currentYear)
        ->where('status', 'completed')
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');
    }
}
