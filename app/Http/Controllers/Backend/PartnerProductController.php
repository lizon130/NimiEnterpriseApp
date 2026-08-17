<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Auth;
use Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Company;
use App\Models\User;
use App\Models\PartnerProduct;
use App\Models\ProductStock; // <-- Make sure to import this

class PartnerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            if (!$this->user || Helper::hasRight('partnerproduct.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $category = Category::all();
        $brands = Brand::all();
        $products = Product::where('status', 1)->get();
        $partners = Company::where('status', 1)->get();
        return view('backend.pages.partner-product.index', compact('category','brands', 'products', 'partners'));
    }

    public function getList(Request $request){
        $data = PartnerProduct::query();
        if (!empty($request->company)) {
            $data->where('company_id', $request->company);
        }

        if ($request->product) {
            $data->where('product_id', $request->product);
        }

        if ($request->status) {
            $data->where(function($query) use ($request){
                if ($request->status == 1) {
                    $status = 1;
                }else if($request->status == 2){
                    $status = 2;
                }else{
                    $status = 0;
                }
                $query->where('status', $status);
            });
        }

        return Datatables::of($data)

        ->editColumn('company_id', function ($row) {
            return ($row->company)->name ?? '-';
        })

        ->editColumn('category_id', function ($row) {
            return ($row->category)->title ?? '-';
        })

        ->editColumn('sub_category_id', function ($row) {
            return ($row->sub_category)->title ?? '-';
        })

        ->editColumn('product_id', function ($row) {
            return ($row->product)->name ?? '-';
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-100">Approved</span>';
            }else if($row->status == 2){
                return '<span class="badge bg-danger w-100">Rejected</span>';
            }else{
                return '<span class="badge bg-warning w-100">Pending</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('partnerproduct.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('partnerproduct.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['company_id','category_id','sub_category_id','product_id','status','action'])->make(true);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'company' => 'required',
            'partner' => 'required',
            'category.*' => 'required',
            'product.*' => 'required',
            'quantity.*' => 'required|numeric|min:0',
            'price.*' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        for ($i = 0; $i < count($request->product); $i++) {
            $partnerproduct = new PartnerProduct();
            $partnerproduct->company_id = $request->company;
            $partnerproduct->partner = $request->partner;
            $partnerproduct->category_id = $request->category[$i];
            $partnerproduct->subcategory_id = $request->subcategory[$i] ?? null;
            $partnerproduct->product_id = $request->product[$i];
            $partnerproduct->quantity = $request->quantity[$i];
            $partnerproduct->price = $request->price[$i];
            $partnerproduct->discount_type = $request->discount_type[$i] ?? null;
            $partnerproduct->discount_price = $request->discount[$i] ?? null;
            $partnerproduct->status = 0;
            $partnerproduct->save();

            // --- ADD TO STOCK ---
            if($request->quantity[$i] > 0){
                $stock = ProductStock::firstOrCreate(
                    ['product_id' => $request->product[$i]],
                    ['quantity' => 0] // Default quantity if creating for the first time
                );
                $stock->addStock($request->quantity[$i], 'partner_assignment', $partnerproduct->id, 'Added from Partner Product Assignment');
            }
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Product assigned successfully and stock updated.',
        ]);
    }

    public function edit($id){
        $category = Category::all();
        $brands = Brand::all();
        $products = Product::where('status', 1)->get();
        $partners = Company::where('status', 1)->get();
        $partner_product = PartnerProduct::find($id);
        $subcategory = Category::where('parent_category', $partner_product->category_id)->get();
        return view('backend.pages.partner-product.edit', compact('category','brands','products','partners','partner_product', 'subcategory'));
    }

    public function row($number){
        $category = Category::all();
        $number++;
        return view('backend.pages.partner-product.row', compact('category','number'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
            'company' => 'required',
            'partner' => 'required',
            'category' => 'required',
            'product' => 'required',
            'quantity' => 'required',
        ]);

        $partnerproduct = PartnerProduct::find($id);

        // --- STOCK ADJUSTMENT LOGIC ON UPDATE ---
        $oldQuantity = $partnerproduct->quantity;
        $newQuantity = $request->quantity[0]; // Since it's an update, it's a single item array
        $difference = $newQuantity - $oldQuantity;

        // Update Partner Product
        $partnerproduct->company_id = $request->company;
        $partnerproduct->partner = $request->partner;
        $partnerproduct->status = $request->status;
        $partnerproduct->category_id = $request->category[0];
        $partnerproduct->subcategory_id = $request->subcategory[0] ?? '';
        $partnerproduct->product_id = $request->product[0];
        $partnerproduct->quantity = $newQuantity;
        $partnerproduct->price = $request->price[0];
        $partnerproduct->discount_type = $request->discount_type[0];
        $partnerproduct->discount_price = $request->discount[0];
        $partnerproduct->save();

        // Apply stock difference
        if ($difference != 0) {
            $stock = ProductStock::firstOrCreate(
                ['product_id' => $request->product[0]],
                ['quantity' => 0]
            );

            if ($difference > 0) {
                // Quantity increased, add to stock
                $stock->addStock($difference, 'partner_update', $partnerproduct->id, 'Partner product quantity increased');
            } else {
                // Quantity decreased, remove from stock
                $absDifference = abs($difference);
                if ($stock->hasStock($absDifference)) {
                    $stock->removeStock($absDifference, 'partner_update', $partnerproduct->id, 'Partner product quantity decreased');
                } else {
                    // If for some reason stock goes negative, just set to 0 and remove what we can
                    $stock->removeStock($stock->quantity, 'partner_update', $partnerproduct->id, 'Partner product quantity decreased (capped at 0)');
                }
            }
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Record updated successfully and stock adjusted.',
        ]);
    }

    public function delete($id){
        $partnerproduct = PartnerProduct::find($id);
        if($partnerproduct){

            // --- REMOVE FROM STOCK ---
            $stock = ProductStock::where('product_id', $partnerproduct->product_id)->first();
            if($stock && $partnerproduct->quantity > 0){
                if ($stock->hasStock($partnerproduct->quantity)) {
                    $stock->removeStock($partnerproduct->quantity, 'partner_removal', $partnerproduct->id, 'Partner product assignment deleted');
                } else {
                    // If available stock is less than what was assigned, just remove whatever is left
                    if($stock->quantity > 0){
                        $stock->removeStock($stock->quantity, 'partner_removal', $partnerproduct->id, 'Partner product assignment deleted (capped at 0)');
                    }
                }
            }

            $partnerproduct->delete();
            return json_encode(['success' => 'Record deleted successfully and stock adjusted.']);
        }else{
            return json_encode(['error' => 'Record not found.']);
        }
    }

    public function getPartner($id){
        $company = Company::find($id);
        return json_encode($company->contact_name);
    }

    public function getSubcategory($id){
        $subcategory = Category::where('parent_category', $id)->get();
        $html = '';
        foreach($subcategory as $row){
            $html .= '<option value="'.$row->id.'">'.$row->title.'</option>';
        }
        return $html;
    }

    public function getProduct(Request $request){
        $products = Product::where('status', 1)->where('category_id', $request->category_id);
        if($request->subcategory_id !=''){
            $products->where('sub_category_id', $request->subcategory_id);
        }
        $html = '';
        foreach($products->get() as $row){
            $html .= '<option value="'.$row->id.'">'.$row->code.' # '.$row->name.'</option>';
        }
        return $html;
    }
}
