<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\WholesaleCalculation;
use Session;

class WholesaleCalculationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            // Use 'brand.view' permission instead of 'wholesale-calculation.view'
            if (!$this->user || Helper::hasRight('brand.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Get totals for summary
        $totalPurchase = WholesaleCalculation::sum('purchase_amount');
        $totalSale = WholesaleCalculation::sum('sale_amount');

        return view('backend.pages.wholesale-calculation.index', compact('totalPurchase', 'totalSale'));
    }

    public function getList(Request $request)
    {
        $data = WholesaleCalculation::query()->orderBy('id', 'desc');

        return Datatables::of($data)
            ->editColumn('date', function ($row) {
                return date('d-m-Y', strtotime($row->date));
            })
            ->editColumn('purchase_amount', function ($row) {
                return '৳ ' . number_format($row->purchase_amount, 2);
            })
            ->editColumn('sale_amount', function ($row) {
                return '৳ ' . number_format($row->sale_amount, 2);
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                // Use existing brand permissions
                if (Helper::hasRight('brand.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary"><i class="fa-solid fa-pencil"></i></a>';
                }
                if (Helper::hasRight('brand.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->with('totalPurchase', WholesaleCalculation::sum('purchase_amount'))
            ->with('totalSale', WholesaleCalculation::sum('sale_amount'))
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'date' => 'required|date',
            'purchase_amount' => 'required|numeric|min:0',
            'sale_amount' => 'required|numeric|min:0',
        ]);

        $wholesale = new WholesaleCalculation();
        $wholesale->date = $request->date;
        $wholesale->purchase_amount = $request->purchase_amount;
        $wholesale->sale_amount = $request->sale_amount;

        if ($wholesale->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Wholesale calculation created successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function edit($id)
    {
        $wholesale = WholesaleCalculation::find($id);
        if (!$wholesale) {
            return response()->json([
                'type' => 'error',
                'message' => 'Record not found.'
            ], 404);
        }
        return view('backend.pages.wholesale-calculation.edit', compact('wholesale'));
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'date' => 'required|date',
            'purchase_amount' => 'required|numeric|min:0',
            'sale_amount' => 'required|numeric|min:0',
        ]);

        $wholesale = WholesaleCalculation::find($id);
        if (!$wholesale) {
            return response()->json([
                'type' => 'error',
                'message' => 'Record not found.'
            ], 404);
        }

        $wholesale->date = $request->date;
        $wholesale->purchase_amount = $request->purchase_amount;
        $wholesale->sale_amount = $request->sale_amount;

        if ($wholesale->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Wholesale calculation updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id)
    {
        $wholesale = WholesaleCalculation::find($id);
        if ($wholesale) {
            $wholesale->delete();
            return response()->json([
                'success' => 'Wholesale calculation deleted successfully.'
            ]);
        } else {
            return response()->json([
                'error' => 'Record not found.'
            ], 404);
        }
    }
}
