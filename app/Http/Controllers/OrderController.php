<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class OrderController extends Controller
{

    //Create stripe payment intent API
    public function createPaymentIntent(Request $request)
    {
        // Set your secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Retrieve payment amount from the request
        $amount = $request->input('amount') * 100; // amount in cents
        $paymentToken = $request->input('token');
        $currency = 'gbp';

        if (!$paymentToken) {
            return response()->json(['error' => 'Payment token is required'], 400);
        }

        try {
            // Create the PaymentIntent

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount, 
                'currency' => $currency,
                'payment_method_data' => [
                    'type' => 'card',
                    'card' => [
                        'token' => $paymentToken, 
                    ],
                ],
                'confirm' => true, 
                'description' => 'Product Purchase',
            ]);

            return response()->json([
                'success' => true,
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // order storing into database API 
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string', 
            'email' => 'required', 
            'products' => 'required|json', 
            'total_amount' => 'required|numeric',
            'order_status' => 'required|string', // E.g., 'paid', 'pending'
        ]);

        // Create and store the order
        $order = Order::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'products' => $validated['products'],
            'total_amount' => $validated['total_amount'],
            'order_status' => $validated['order_status'],
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

        foreach ($orders as $order) {
            $productIds = json_decode($order->products, true); 
            $order->products = Product::whereIn('id', $productIds)->get();
        }

        return response()->json($orders, 200);
    }
   
}
