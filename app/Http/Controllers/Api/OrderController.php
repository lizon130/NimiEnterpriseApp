<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Decode JSON to an associative array
        $data = $request->json()->all();
        // Validate the request data
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'district' => 'required|string|max:255',
            'upazila' => 'required|string|max:255',
            'total_price' => 'required|numeric',
            'transaction_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Check if user exists, if not create a new user
            $user = User::where('email', $data['email'])->orWhere('phone', $data['phone'])->first();
            if (!$user) {
                $user = new User();
                $user->first_name = $data['name'];
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                $user->role = 2;
                $user->password = Hash::make($data['phone']); // default password
                $user->save();
            }

            // Create new order
            $order = new Order();
            $order->user_id = $user->id;
            $order->date = now()->format('Y-m-d');
            $order->total_price = $data['total_price'];
            $order->payment_status = 0;
            $order->payment_method = $data['payment_method']; // Add logic for payment method if needed
            $order->transaction_id = $data['transaction_id'];
            $order->note = $data['note'];

            $billing_information = [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'city' => $data['district'],
                'state' => $data['upazila'],
            ];

            $order->billing_information = json_encode($billing_information);

            $order->save();

            // Save the order details
            foreach ($data['products'] as $cart) {
                if (!empty($cart['id'])) {
                    $product = Product::where('slug', $cart['id'])->first();
                    $orderDetail = new OrderDetail();
                    $orderDetail->order_id = $order->id;
                    $orderDetail->product_id = $product->id;
                    $orderDetail->quantity = $cart['quantity'];
                    $orderDetail->unit_price = $cart['price'];
                    $orderDetail->subtotal = $cart['subtotal'];
                    $orderDetail->save();
                }
            }

            DB::commit();

            return response()->json([
                'type' => 'success',
                'message' => 'Order created successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function orderPaymentStatus(Request $request){
        $data = $request->json()->all();
        $order = Order::where('transaction_id', $data['transaction_id'])->first();
        $order->payment_status = $data['status'];
        $order->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Order created successfully.'
        ]);
    }

    public function getOrderDetails($transaction_id){
        $order = Order::where('transaction_id', $transaction_id)->with('company', 'details', 'details.product')->first();
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $order
        ]);
    }
}
