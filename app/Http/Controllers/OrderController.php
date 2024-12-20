<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::query();
        $stock = request('stock');
        $search = request('search');
        if (request('search')) {
            $orders->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

      
        return view('order', [
            "orders" => $orders->paginate(4),
            "stock" => $stock,
            "search" => $search
        ]);
    }


    public function updateOrderStatus(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'order_status' => 'required|in:pending,in progress,completed',
        ]);

        // Find the order and update the status
        $order = Order::findOrFail($id);
        $order->order_status = $request->input('order_status');
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }


    //Create stripe payment intent API
    public function createPaymentIntent(Request $request)
    {
        // Set your secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));
    
        // Retrieve payment amount and customer details from the request
        $amount = $request->input('amount') * 100; // amount in cents
        $paymentToken = $request->input('token'); // Token for payment method
        $currency = 'gbp';
        $email = $request->input('email');
        $name = $request->input('name');
    
        if (!$paymentToken) {
            return response()->json(['error' => 'Payment token is required'], 400);
        }
    
        if (!$email) {
            return response()->json(['error' => 'Customer email is required'], 400);
        }
    
        try {
            // Step 1: Check if a customer already exists or create a new one
            $customer = Customer::create([
                'email' => $email,
                'name' => $name,
            ]);
    
            // Step 2: Create the PaymentIntent with the customer and email details
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'customer' => $customer->id, // Attach customer to the PaymentIntent
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $paymentToken,
                    ],
                ],
                'confirm' => true, // Automatically confirm the payment
                'description' => 'Product Purchase',
                'receipt_email' => $email, // Send receipt to the provided email
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);
    
            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    //payment intent API - second simple API
    public function createPaymentIntentAmount(Request $request)
    {
        $amount = $request->input('amount') * 100; // amount in cents
        $currency = 'gbp';

        // Set your Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $paymentIntent = PaymentIntent::create([
                'payment_method_types' => ['card'],
                'amount' => $amount,
                'currency' => $currency  
            ]);

            // Return the client secret, which will be used on the frontend
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ], 200);

        } catch (ApiErrorException $e) {
            // Handle Stripe API error
            return response()->json([
                'error' => $e->getMessage(),
            ], 400);
        }
    }


    // order storing into database API 
    public function store(Request $request) 
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string', 
            'email' => 'required',
            'phone' => 'required',
            'location' => 'required', 
            'products' => 'required|json', 
            'total_amount' => 'required|numeric',
            'order_status' => 'required|string', // E.g., 'paid', 'pending'
            'client_ip' => 'required'
        ]);

        // Create and store the order
        $order = Order::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'location'=> $validated['location'],
            'products' => $validated['products'],
            'total_amount' => $validated['total_amount'],
            'order_status' => $validated['order_status'],
            'client_ip' => $validated['client_ip']
        ]);

        // Return a response to the client
        return response()->json([
            'message' => 'Order successfully placed!',
            'order' => $order,
        ], 201);
    }


    public function getUserOrders($email)
    {
        $orders = Order::where('email', $email)->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for this user'], 404);
        }

        return response()->json($orders, 200);
    }


    public function getProducts(Request $request)
    {
        // Get the 'ids' parameter from the request, which can be a single ID or multiple IDs.
        $ids = $request->input('ids');

        if (is_array($ids)) {
            // If multiple IDs are provided, return all products with those IDs.
            $products = Product::whereIn('id', $ids)->get();
        } else {
            // If a single ID is provided, return the single product.
            $products = Product::find($ids);
        }

        if (!$products) {
            return response()->json(['error' => 'Product(s) not found'], 404);
        }

        return response()->json($products, 200);
    }
   
}
