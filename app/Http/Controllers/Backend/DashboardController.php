<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Helper;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockHistory;
use App\Models\Service;
use App\Models\ServiceOrder;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('dashboard.view') != true) {
                Auth::logout();
                $request->session()->invalidate();
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }

            return $next($request);
        });
    }

    public function index()
    {
        if (empty(Session::get('admin_language'))) {
            Session::put('admin_language', 'en');
        }

        $user = Auth::user();

        $total_reseller = User::where('status', 1)->where('role', 4)->count();
        $total_distributor = User::where('status', 1)->where('role', 5)->count();

        $total_product = Product::where('status', 1)->count();
        $total_service = Service::where('status', 1)->count();

        $totalStock = ProductStock::sum('quantity');
        $outOfStock = ProductStock::where('quantity', '<=', 0)->count();
        $lowStock = ProductStock::where('quantity', '>', 0)->where('quantity', '<=', 5)->count();

        $todayStockIn = StockHistory::where('type', 'in')
            ->whereDate('created_at', Carbon::today())
            ->sum('quantity');

        $todayStockOut = StockHistory::where('type', 'out')
            ->whereDate('created_at', Carbon::today())
            ->sum('quantity');

        if ($user->role == 2 || $user->role == 4 || $user->role == 5) {
            $total_order = Order::where('user_id', $user->id)
                ->where('status', '!=', 3)
                ->count();

            $pending_order = Order::where('user_id', $user->id)
                ->where('status', 0)
                ->count();

            $completed_order = Order::where('user_id', $user->id)
                ->where('status', 2)
                ->count();

            $inquiry_request = Inquiry::where('user_id', $user->id)
                ->where('status', '!=', 2)
                ->count();

            $service_order = ServiceOrder::where('user_id', $user->id)
                ->where('status', '!=', 2)
                ->count();

            $total_sale = Order::where('user_id', $user->id)
                ->where('status', '!=', 3)
                ->sum('total_price');

            $today_sale = Order::where('user_id', $user->id)
                ->where('status', '!=', 3)
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price');

            $recentOrders = Order::with('company')
                ->where('user_id', $user->id)
                ->latest()
                ->limit(8)
                ->get();
        } else {
            $total_order = Order::where('status', '!=', 3)->count();

            $pending_order = Order::where('status', 0)->count();

            $completed_order = Order::where('status', 2)->count();

            $inquiry_request = Inquiry::where('status', '!=', 2)->count();

            $service_order = ServiceOrder::where('status', '!=', 2)->count();

            $total_sale = Order::where('status', '!=', 3)->sum('total_price');

            $today_sale = Order::where('status', '!=', 3)
                ->whereDate('created_at', Carbon::today())
                ->sum('total_price');

            $recentOrders = Order::with('company')
                ->latest()
                ->limit(8)
                ->get();
        }

        $lowStockProducts = Product::with('stock')
            ->where('status', 1)
            ->whereHas('stock', function ($query) {
                $query->where('quantity', '<=', 5);
            })
            ->limit(8)
            ->get();

        $recentStockHistory = StockHistory::with(['product', 'creator'])
            ->latest()
            ->limit(8)
            ->get();

        return view('backend.pages.dashboard', compact(
            'total_reseller',
            'total_distributor',
            'total_product',
            'total_service',
            'total_order',
            'pending_order',
            'completed_order',
            'inquiry_request',
            'service_order',
            'total_sale',
            'today_sale',
            'totalStock',
            'outOfStock',
            'lowStock',
            'todayStockIn',
            'todayStockOut',
            'lowStockProducts',
            'recentStockHistory',
            'recentOrders'
        ));
    }
}