<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the inventory summary dashboard
     */
    public function index()
    {
        // Aggregate statistics (Pertemuan 4 Slide 29)
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // Total inventory value: SUM(stock * price)
        $totalInventoryValue = Product::selectRaw('SUM(stock * price) as total_val')->value('total_val') ?? 0;

        // Total physical items in warehouse
        $totalStockUnits = Product::sum('stock') ?? 0;

        // Low stock count: stock <= minimum_stock
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();

        // Total stock IN and OUT quantities
        $totalStockIn = StockMovement::where('type', 'IN')->sum('quantity') ?? 0;
        $totalStockOut = StockMovement::where('type', 'OUT')->sum('quantity') ?? 0;

        // Recent 6 transactions with eager loaded product, category, and user
        $recentMovements = StockMovement::with(['product.category', 'user'])
            ->latest()
            ->take(6)
            ->get();

        // Low stock products warning list
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Category summary with product count and total stock (Pertemuan 4 Slide 14)
        $categorySummaries = Category::withCount('products')
            ->withSum('products', 'stock')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalInventoryValue',
            'totalStockUnits',
            'lowStockCount',
            'totalStockIn',
            'totalStockOut',
            'recentMovements',
            'lowStockProducts',
            'categorySummaries'
        ));
    }
}
