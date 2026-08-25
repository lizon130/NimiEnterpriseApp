<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductStock;
use App\Models\User;
use Auth;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('order.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $category = Category::all();
        $brands = Brand::all();
        $partners = Company::all();
        $products = Product::all();
        return view('backend.pages.order.index', compact('category','brands','partners', 'products'));
    }

    public function view($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order_details = OrderDetail::where('order_id', $id)->with('product', 'part')->get();
        $billing = json_decode($order->billing_information);

        return view('backend.pages.order.view', compact('order', 'order_details', 'billing'));
    }

    public function getList(Request $request){

        $data = Order::query();

        if ($this->user->role == 2 || $this->user->role == 4 || $this->user->role == 5) {
            $data->where('user_id', $this->user->id);
        }

        if (!empty($request->date)) {
            $data->where('date', $request->date);
        }

        if ($request->user_id) {
            $data->where('user_id', $request->user_id);
        }

        if (!empty($request->status)) {
            $data->where(function($query) use ($request){
                if ($request->status == 1) {
                    $status = 1;
                }else if($request->status == 2){
                    $status = 2;
                }else if($request->status == 3){
                    $status = 3;
                }else{
                    $status = 0;
                }
                $query->where('status', $status);
            });
        }

        return Datatables::of($data)
        ->editColumn('invoice_no', function ($row) {
            return '<strong>#' . $row->invoice_no . '</strong>';
        })
        ->editColumn('user_id', function ($row) {
            return optional($row->company)->first_name ?? '-' .' '. optional($row->company)->last_name ?? '-';
        })
        ->editColumn('date', function ($row) {
            return date('d M Y', strtotime($row->date));
        })
        ->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return '<span class="badge bg-warning w-80">New</span>';
            }elseif ($row->status == 1) {
                return '<span class="badge bg-info w-80">Shipping</span>';
            }elseif ($row->status == 2) {
                return '<span class="badge bg-success w-80">Delivered</span>';
            }else{
                return '<span class="badge bg-danger w-80">Rejected</span>';
            }
        })
        ->addColumn('action', function ($row) {
            if ($this->user->role === 4 || $this->user->role === 5) {
                // For roles 4 and 5, show only view button
                return '<a href="" data-id="'.$row->id.'" class="view_btn btn btn-sm btn-info text-light"><i class="fa-solid fa-eye"></i> View</a>';
            }

            $btn = '';
            $btn = $btn . '<a href="" data-id="'.$row->id.'" class="view_btn btn btn-sm btn-info text-light"><i class="fa-solid fa-eye"></i></a>';

            // Add Invoice Button
            $btn = $btn . '<a href="'.route('admin.order.invoice', $row->id).'" class="btn btn-sm btn-success text-light mx-1" target="_blank"><i class="fa-solid fa-file-invoice"></i></a>';

            if (Helper::hasRight('order.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="status_change_btn btn btn-sm btn-warning text-light mx-1"><i class="fa-solid fa-truck"></i></a>';
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary mx-1"><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('order.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['invoice_no','user_id','status','action'])->make(true);
    }

    public function row($number){
        $products = Product::all();
        $number++;
        return view('backend.pages.order.row', compact('products','number'));
    }

    public function getCompany($user_id){
        $company = Company::where('user_id', $user_id)->first();
        return json_encode($company);
    }

    public function getProduct(Request $request){
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // price = MRP (original). Send the applicable offer separately so the form
        // can fill MRP + Discount and let subtotal become the net amount.
        $offer = Helper::productOffer($product->id, $request->user_id);

        $product->discount = $offer['discount'];
        $product->discount_type = $offer['type'];

        return json_encode($product);
    }

    public function store(Request $request){

        $validator = $request->validate([
            'user_id' => 'required',
            'company' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'product' => 'required',
        ]);

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->date = date('Y-m-d');
        $order->total_price = $request->total_price;
        $order->payment_status = $request->payment_status;
        $order->payment_method = $request->payment_method;

        $billing_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $order->billing_information = json_encode($billing_information);

        if ($order->save()) {
            $order->refresh();

            for ($i=0; $i < count($request->product); $i++) {
                if(!empty($request->product[$i])){
                    $order_detail = new OrderDetail();
                    $order_detail->order_id  = $order->id;
                    $order_detail->product_id = $request->product[$i];
                    $order_detail->quantity = $request->qty[$i];
                    $order_detail->unit_price = $request->price[$i];
                    $order_detail->discount_type = $request->discount_type[$i];
                    $order_detail->discount = $request->discount[$i];
                    $order_detail->subtotal = $request->subtotal[$i];
                    $order_detail->save();
                }
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Order created successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function edit($order_id){
        $order = Order::find($order_id);
        $products = Product::where('status', 1)->get();
        $partners = Company::all();
        $billing = json_decode($order->billing_information);
        return view('backend.pages.order.edit', compact('order','products','partners','billing'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
            'user_id' => 'required',
            'company' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'product' => 'required',
        ]);

        $order = Order::find($id);
        $order->user_id = $request->user_id;
        $order->date = date('Y-m-d');
        $order->total_price = $request->total_price;
        $order->payment_status = $request->payment_status;
        $order->payment_method = $request->payment_method;
        $order->status = $request->status;

        $billing_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $order->billing_information = json_encode($billing_information);

        if ($order->save()) {
            OrderDetail::where('order_id', $id)->delete();

            for ($i=0; $i < count($request->product); $i++) {
                if(!empty($request->product[$i])){
                    $order_detail = new OrderDetail();
                    $order_detail->order_id  = $order->id;
                    $order_detail->product_id = $request->product[$i];
                    $order_detail->quantity = $request->qty[$i];
                    $order_detail->unit_price = $request->price[$i];
                    $order_detail->discount_type = $request->discount_type[$i];
                    $order_detail->discount = $request->discount[$i];
                    $order_detail->subtotal = $request->subtotal[$i];
                    $order_detail->save();
                }
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Order updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $order = Order::find($id);
        if($order->delete()){
            $details = OrderDetail::where('order_id', $id)->delete();
            return json_encode(['success' => 'Order deleted successfully.']);
        }else{
            return json_encode(['error' => 'Order not found.']);
        }
    }

    public function editStaus($order_id){
        $order = Order::find($order_id);
        $products = Product::where('status', 1)->get();
        $partners = Company::all();
        $billing = json_decode($order->billing_information);
        return view('backend.pages.order.status', compact('order','products','partners','billing'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2,3',
            'message' => 'nullable|string',
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'type' => 'error',
                'message' => 'Order not found.'
            ], 404);
        }

        $oldStatus = $order->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            $order->status = $newStatus;
            $order->save();

            if ($newStatus == 2 && $oldStatus != 2) {
                $orderDetails = OrderDetail::where('order_id', $order->id)->get();

                foreach ($orderDetails as $item) {
                    if ($item->type == 'product' && $item->quantity > 0) {
                        $stock = ProductStock::firstOrCreate(
                            ['product_id' => $item->product_id],
                            ['quantity' => 0]
                        );

                        if ($stock->hasStock($item->quantity)) {
                            $stock->removeStock(
                                $item->quantity,
                                'sale',
                                $order->id,
                                'Stock deducted for Delivered Order #' . $order->id
                            );
                        } else {
                            if ($stock->quantity > 0) {
                                $stock->removeStock(
                                    $stock->quantity,
                                    'sale',
                                    $order->id,
                                    'Partial deduction for Delivered Order #' . $order->id . ' (Capped at 0)'
                                );
                            }
                        }
                    }
                }
            }
            elseif ($oldStatus == 2 && $newStatus != 2) {
                $orderDetails = OrderDetail::where('order_id', $order->id)->get();

                foreach ($orderDetails as $item) {
                    if ($item->type == 'product' && $item->quantity > 0) {
                        $stock = ProductStock::firstOrCreate(
                            ['product_id' => $item->product_id],
                            ['quantity' => 0]
                        );

                        $stock->addStock(
                            $item->quantity,
                            'return',
                            $order->id,
                            'Stock restored, Order #' . $order->id . ' status changed from Delivered'
                        );
                    }
                }
            }

            DB::commit();

            if ($request->has('send_email') && $request->send_email == 'on') {
                // Your email sending logic here...
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Order status updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

      /**
     * Generate Invoice
     */
    public function invoice($id)
    {
        $order = Order::find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        $order_details = OrderDetail::where('order_id', $id)->with('product', 'part')->get();
        $billing = json_decode($order->billing_information);

        // total_price already includes item discounts (net payable) - no extra discount applied
        $logoBase64 = $this->getLogoBase64();

        return view('backend.pages.order.invoice', compact('order', 'order_details', 'billing', 'logoBase64'));
    }

    /**
    * Generate PDF Invoice
    */
    public function invoicePdf($id)
    {
        $order = Order::find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        $order_details = OrderDetail::where('order_id', $id)->with('product', 'part')->get();
        $billing = json_decode($order->billing_information);

        // total_price already includes item discounts (net payable) - no extra discount applied
        $logoBase64 = $this->getLogoBase64();

        $pdf = Pdf::loadView('backend.pages.order.invoice-pdf', compact('order', 'order_details', 'billing', 'logoBase64'));
        $pdf->setPaper('a4', 'portrait');

        $safeInvoiceNo = str_replace(['/', '\\'], '-', $order->invoice_no);

        return $pdf->download('invoice-' . $safeInvoiceNo . '.pdf');
    }

    /**
     * View Invoice in Browser
     */
    public function invoiceView($id)
    {
        $order = Order::find($id);
        if (!$order) {
            abort(404, 'Order not found');
        }

        $order_details = OrderDetail::where('order_id', $id)->with('product', 'part')->get();
        $billing = json_decode($order->billing_information);

        // total_price already includes item discounts (net payable) - no extra discount applied
        $logoBase64 = $this->getLogoBase64();

        return view('backend.pages.order.invoice-pdf', compact('order', 'order_details', 'billing', 'logoBase64'));
    }

   private function getLogoBase64()
{
    $possiblePaths = [
        // Your live server public root
        '/home/nimiente/public_html/assets/img/Logo.png',
        '/home/nimiente/public_html/assets/img/logo.png',
        '/home/nimiente/public_html/assets/img/LOGO.png',

        // If logo is inside uploaded/storage folder
        '/home/nimiente/public_html/storage/images/Logo.png',
        '/home/nimiente/public_html/storage/images/logo.png',

        // If project has public folder locally/server
        base_path('public/assets/img/Logo.png'),
        base_path('public/assets/img/logo.png'),

        // If public folder is one level outside Laravel app
        base_path('../assets/img/Logo.png'),
        base_path('../assets/img/logo.png'),

        // Laravel public_path fallback
        public_path('assets/img/Logo.png'),
        public_path('assets/img/logo.png'),
    ];

    foreach ($possiblePaths as $logoPath) {
        try {
            if (!empty($logoPath) && file_exists($logoPath) && is_readable($logoPath)) {
                $imageData = file_get_contents($logoPath);

                if ($imageData !== false) {
                    $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));

                    $mimeType = match ($extension) {
                        'jpg', 'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'gif' => 'image/gif',
                        'webp' => 'image/webp',
                        default => 'image/png',
                    };

                    return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                }
            }
        } catch (\Throwable $e) {
            continue;
        }
    }

    return null;
}
}
