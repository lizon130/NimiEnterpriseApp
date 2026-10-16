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

    public function index()
    {
        $category = Category::all();
        $brands = Brand::all();
        $partners = Company::all();
        $products = Product::all();
        return view('backend.pages.order.index', compact('category', 'brands', 'partners', 'products'));
    }

    /**
     * Merge duplicate order items (same product/part) into a single line:
     * quantities and subtotals are summed, so an invoice shows one row per product.
     */
    private function mergeOrderDetails($details)
    {
        return $details->groupBy(function ($item) {
            return $item->type . '|' . $item->product_id . '|' . $item->reference_id;
        })->map(function ($group) {
            if ($group->count() === 1) {
                return $group->first();
            }

            $base = $group->first();
            $merged = $base->replicate();
            $merged->quantity = $group->sum('quantity');
            $merged->subtotal = $group->sum('subtotal');
            $merged->setRelation('product', $base->product);
            $merged->setRelation('part', $base->part);
            return $merged;
        })->values();
    }

    public function view($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Merge all orders that share this invoice (same user + same business window)
        $orderIds = Order::where('invoice_no', $order->invoice_no)->pluck('id');
        $order_details = OrderDetail::whereIn('order_id', $orderIds)->with('product', 'part')->get();
        $order_details = $this->mergeOrderDetails($order_details);
        $group_total = Order::whereIn('id', $orderIds)->sum('total_price');
        $billing = json_decode($order->billing_information);

        return view('backend.pages.order.view', compact('order', 'order_details', 'billing', 'group_total'));
    }

    public function getList(Request $request)
    {

        $data = Order::query()->with(['details.product', 'details.part', 'partnerCompany'])
            ->orderByDesc('created_at');

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
            $data->where(function ($query) use ($request) {
                if ($request->status == 1) {
                    $status = 1;
                } else if ($request->status == 2) {
                    $status = 2;
                } else if ($request->status == 3) {
                    $status = 3;
                } else {
                    $status = 0;
                }
                $query->where('status', $status);
            });
        }

        // Combine all orders that share an invoice (same user + same business window) into one row
        $groups = $data->get()->groupBy('invoice_no');

        $rows = [];
        foreach ($groups as $group) {
            $latest = $group->first(); // newest order of this invoice (list is sorted desc)
            $company = $latest->partnerCompany;
            $ordersCount = $group->count();
            $total = $group->sum('total_price');
            $status = $group->min('status'); // conservative: invoice is as new as its newest order
            $details = $group->flatMap(function ($o) {
                return $o->details;
            })->values();

            // Merge duplicate items of the same product into one line (qty + subtotal summed)
            $details = $this->mergeOrderDetails($details);

            // Invoice column
            $invoiceHtml = '<strong>#' . $latest->invoice_no . '</strong>';
            if ($ordersCount > 1) {
                $invoiceHtml .= '<span class="badge bg-info ms-1" title="Orders combined in this invoice">' . $ordersCount . ' orders</span>';
            }

            // Company column
            if (!$company) {
                $companyHtml = '<span class="text-muted">-</span>';
            } else {
                $companyHtml = '<div class="order-partner">'
                    . '<span class="d-block fw-bold text-truncate" title="' . e($company->name ?? '') . '">' . e($company->name ?? '-') . '</span>'
                    . '<small class="text-muted text-nowrap"><i class="fa-solid fa-phone"></i> ' . e($company->phone_number ?? '-') . '</small>'
                    . '</div>';
            }

            // Products column (merged items of all orders in the invoice)
            if ($details->isEmpty()) {
                $productsHtml = '<span class="text-muted">-</span>';
            } else {
                $count = $details->count();
                $collapsed = $count > 2;

                $items = '';
                foreach ($details as $i => $item) {
                    $name = $item->product->name ?? $item->part->name ?? 'N/A';
                    $part = $item->part
                        ? '<small class="text-muted d-block text-truncate">Part: ' . e($item->part->name ?? 'N/A') . '</small>'
                        : '';
                    $extra = ($collapsed && $i >= 2) ? ' extra-item d-none' : '';

                    $items .= '<div class="order-item' . $extra . '">'
                        . '<div class="d-flex justify-content-between align-items-start gap-2">'
                        . '<div class="order-item-name"><span class="d-block text-truncate" title="' . e($name) . '">' . e($name) . '</span>' . $part . '</div>'
                        . '<span class="badge bg-secondary flex-shrink-0">× ' . (int) $item->quantity . '</span>'
                        . '</div></div>';
                }

                $productsHtml = '<div class="order-products">';
                $productsHtml .= '<span class="badge bg-light text-dark border mb-1"><i class="fa-solid fa-box-open"></i> ' . $count . ' item' . ($count > 1 ? 's' : '') . '</span>';
                $productsHtml .= $items;
                if ($collapsed) {
                    $productsHtml .= '<a href="javascript:void(0)" class="toggle-items small fw-bold d-block mt-1">+ ' . ($count - 2) . ' more</a>';
                }
                $productsHtml .= '</div>';
            }

            // Status column
            if ($status == 0) {
                $statusHtml = '<span class="badge bg-warning w-80">New</span>';
            } elseif ($status == 1) {
                $statusHtml = '<span class="badge bg-info w-80">Shipping</span>';
            } elseif ($status == 2) {
                $statusHtml = '<span class="badge bg-success w-80">Delivered</span>';
            } else {
                $statusHtml = '<span class="badge bg-danger w-80">Rejected</span>';
            }

            // Action column (latest order id represents the invoice)
            $rowId = $latest->id;
            if ($this->user->role === 4 || $this->user->role === 5) {
                $btn = '<a href="" data-id="' . $rowId . '" class="view_btn btn btn-sm btn-info text-light"><i class="fa-solid fa-eye"></i> View</a>';
            } else {
                $btn = '';
                $btn = $btn . '<a href="" data-id="' . $rowId . '" class="view_btn btn btn-sm btn-info text-light"><i class="fa-solid fa-eye"></i></a>';
                $btn = $btn . '<a href="' . route('admin.order.invoice', $rowId) . '" class="btn btn-sm btn-success text-light mx-1" target="_blank"><i class="fa-solid fa-file-invoice"></i></a>';

                if (Helper::hasRight('order.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $rowId . '" class="status_change_btn btn btn-sm btn-warning text-light mx-1"><i class="fa-solid fa-truck"></i></a>';
                    $btn = $btn . '<a href="" data-id="' . $rowId . '" class="edit_btn btn btn-sm btn-primary mx-1"><i class="fa-solid fa-pencil"></i></a>';
                }
                if (Helper::hasRight('order.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger" data-id="' . $rowId . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
            }

            $rows[] = [
                'invoice_no' => $invoiceHtml,
                'date' => date('d M Y', strtotime($latest->date)) . '<small class="text-muted d-block">' . $latest->created_at->timezone('Asia/Dhaka')->format('h:i A') . '</small>',
                'user_id' => $companyHtml,
                'products' => $productsHtml,
                'total_price' => '৳' . number_format($total, 2),
                'status' => $statusHtml,
                'action' => $btn,
            ];
        }

        return Datatables::of($rows)
            ->rawColumns(['invoice_no', 'date', 'user_id', 'products', 'total_price', 'status', 'action'])
            ->make(true);
    }

    public function row($number)
    {
        $products = Product::all();
        $number++;
        return view('backend.pages.order.row', compact('products', 'number'));
    }

    public function getCompany($user_id)
    {
        $company = Company::where('user_id', $user_id)->first();
        return json_encode($company);
    }

    public function getProduct(Request $request)
    {
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

    public function store(Request $request)
    {

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

            for ($i = 0; $i < count($request->product); $i++) {
                if (!empty($request->product[$i])) {
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
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function edit($order_id)
    {
        $order = Order::find($order_id);
        $products = Product::where('status', 1)->get();
        $partners = Company::all();
        $billing = json_decode($order->billing_information);
        return view('backend.pages.order.edit', compact('order', 'products', 'partners', 'billing'));
    }

    public function update(Request $request, $id)
    {
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

            for ($i = 0; $i < count($request->product); $i++) {
                if (!empty($request->product[$i])) {
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
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id)
    {
        $order = Order::find($id);
        if ($order) {
            // Delete the whole invoice group (all orders sharing this invoice)
            $orderIds = Order::where('invoice_no', $order->invoice_no)->pluck('id');
            OrderDetail::whereIn('order_id', $orderIds)->delete();
            Order::whereIn('id', $orderIds)->delete();
            return json_encode(['success' => 'Invoice deleted successfully.']);
        } else {
            return json_encode(['error' => 'Order not found.']);
        }
    }

    public function editStaus($order_id)
    {
        $order = Order::find($order_id);
        $products = Product::where('status', 1)->get();
        $partners = Company::all();
        $billing = json_decode($order->billing_information);
        return view('backend.pages.order.status', compact('order', 'products', 'partners', 'billing'));
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

        // The invoice groups several orders — update the status of all of them together
        $orders = Order::where('invoice_no', $order->invoice_no)->get();
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            foreach ($orders as $order) {
                $oldStatus = $order->status;

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
                } elseif ($oldStatus == 2 && $newStatus != 2) {
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

        // Merge all orders that share this invoice (same user + same business window)
        $orderIds = Order::where('invoice_no', $order->invoice_no)->pluck('id');
        $order_details = OrderDetail::whereIn('order_id', $orderIds)->with('product', 'part')->get();
        $order_details = $this->mergeOrderDetails($order_details);
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

        // Merge all orders that share this invoice (same user + same business window)
        $orderIds = Order::where('invoice_no', $order->invoice_no)->pluck('id');
        $order_details = OrderDetail::whereIn('order_id', $orderIds)->with('product', 'part')->get();
        $order_details = $this->mergeOrderDetails($order_details);
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

        // Merge all orders that share this invoice (same user + same business window)
        $orderIds = Order::where('invoice_no', $order->invoice_no)->pluck('id');
        $order_details = OrderDetail::whereIn('order_id', $orderIds)->with('product.category', 'part')->get();
        $order_details = $this->mergeOrderDetails($order_details);
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
