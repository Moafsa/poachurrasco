<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Establishment;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's establishments
        $establishments = Establishment::where('user_id', $user->id)->get();
        
        // Get user's products
        $products = Product::whereIn('establishment_id', $establishments->pluck('id'))->get();
        
        // Calculate stats (with safe defaults)
        $stats = [
            'establishments_count' => $establishments->count(),
            'products_count' => $products->count(),
            'total_views' => $products->sum('view_count') ?? 0,
            'total_sales' => $products->sum('purchase_count') ?? 0,
        ];
        
        return view('dashboard.index', compact('stats', 'establishments', 'products'));
    }
    
    public function analytics()
    {
        return view('dashboard.analytics');
    }
    
    public function settings()
    {
        return view('dashboard.settings');
    }
    
    public function profile()
    {
        return view('dashboard.profile');
    }
}