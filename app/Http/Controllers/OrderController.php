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
            'client_ip' => 'required'
        ]);

        // Create and store the order
        $order = Order::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
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
   
}
