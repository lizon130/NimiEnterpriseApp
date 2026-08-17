<?php
// app/Http/Controllers/Backend/StockController.php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockHistory;
use Yajra\DataTables\DataTables;
use Helper;
use Auth;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!$this->user || Helper::hasRight('stock.view') == false) {
                session()->flash('error', 'You can not access!');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('backend.pages.stock.index');
    }

    public function getList(Request $request)
    {
        try {
            \Log::info('Stock getList called');

            $data = Product::query();

            \Log::info('Product query created');

            return DataTables::of($data)
                ->addColumn('thumbnail', function ($row) {
                    try {
                        return '<img src="' . asset('uploads/product-images/' . $row->thumbnail) . '" width="50">';
                    } catch (\Exception $e) {
                        \Log::error('Thumbnail error: ' . $e->getMessage());
                        return '<img src="' . asset('assets/img/no-img.jpg') . '" width="50">';
                    }
                })
                ->addColumn('current_stock', function ($row) {
                    try {
                        $stock = ProductStock::where('product_id', $row->id)->first();
                        $quantity = $stock ? $stock->quantity : 0;
                        $badge = $quantity <= 0 ? '<span class="badge bg-danger">Out of Stock</span>' : ($quantity <= 5 ? '<span class="badge bg-warning">Low Stock</span>' :
                                '<span class="badge bg-success">In Stock</span>');
                        return "<strong>{$quantity}</strong> <br> {$badge}";
                    } catch (\Exception $e) {
                        \Log::error('Stock error for product ' . $row->id . ': ' . $e->getMessage());
                        return "0 <br> <span class='badge bg-danger'>Error</span>";
                    }
                })
                ->addColumn('brand_title', function ($row) {
                    try {
                        return $row->brand ? $row->brand->title : '-';
                    } catch (\Exception $e) {
                        return '-';
                    }
                })
                ->addColumn('action', function ($row) {
                    $stockQty = ProductStock::where('product_id', $row->id)->first()->quantity ?? 0;
                    return '<button class="btn btn-sm btn-primary adjust_stock" data-id="' . $row->id . '" data-name="' . e($row->name) . '" data-stock="' . $stockQty . '">
                            <i class="fa-solid fa-arrows-spin"></i> Adjust
                        </button>
                        <button class="btn btn-sm btn-info history_btn" data-id="' . $row->id . '">
                            <i class="fa-solid fa-clock-rotate-left"></i> History
                        </button>';
                })
                ->rawColumns(['thumbnail', 'current_stock', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Stock DataTable Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function initialize(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'quantity' => 'required|integer|min:0'
        ]);

        // Check if stock already exists
        $existingStock = ProductStock::where('product_id', $request->product_id)->first();

        if ($existingStock) {
            return response()->json([
                'success' => false,
                'error' => 'Stock already initialized for this product!'
            ], 400);
        }

        // Create stock with the exact quantity (DON'T call addStock)
        $stock = ProductStock::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        // Only create history record for initial setup (DON'T add stock again)
        if ($request->quantity > 0) {
            StockHistory::create([
                'product_id' => $request->product_id,
                'type' => 'in',
                'quantity' => $request->quantity,
                'reason' => 'initial',
                'notes' => 'Initial stock setup',
                'created_by' => auth()->id()
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Stock initialized successfully']);
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'type' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string'
        ]);

        $stock = ProductStock::where('product_id', $request->product_id)->first();

        if (!$stock) {
            return response()->json(['error' => 'Stock not initialized for this product'], 400);
        }

        try {
            if ($request->type == 'add') {
                $stock->addStock($request->quantity, $request->reason, null, $request->notes);
                $message = "Added {$request->quantity} stock";
            } else {
                $stock->removeStock($request->quantity, $request->reason, null, $request->notes);
                $message = "Removed {$request->quantity} stock";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_quantity' => $stock->quantity
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function history($productId)
    {
        $history = StockHistory::where('product_id', $productId)
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['history' => $history]);
    }
}