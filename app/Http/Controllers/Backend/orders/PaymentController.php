<?php

namespace App\Http\Controllers\Backend\orders;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderInformation;
use App\Models\Payment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use Exception;


class PaymentController extends Controller
    {
    private $client;

    public function __construct()
    {
        $environment = new SandboxEnvironment(
            env('PAYPAL_CLIENT_ID'),
            env('PAYPAL_SECRET')
        );
        $this->client = new PayPalHttpClient($environment);
    }

    /**
     * Create a PayPal Order (get approval link)
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:order_informations,id',
        ]);

        $order = OrderInformation::find($request->order_id);

        $paypalOrder = new OrdersCreateRequest();
        $paypalOrder->prefer('return=representation');
        $paypalOrder->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => $order->id,
                'amount' => [
                    'currency_code' => env('PAYPAL_CURRENCY', 'USD'),
                    'value' => number_format($order->totalPrice, 2, '.', '')
                ]
            ]],
            'application_context' => [
                'cancel_url' => url('/api/payment/cancel?order_id=' . $order->id),
                'return_url' => url('/api/payment/success?order_id=' . $order->id),
            ]
        ];

        try {
            $response = $this->client->execute($paypalOrder);

            // Save record
            $payment = Payment::create([
            'order_information_id' => $order->id,
            'amount' => $order->totalPrice,
            'currency' => env('PAYPAL_CURRENCY', 'USD'),
            'paypal_payment_id' => $response->result->id,
            'status' => 'created',
        ]);


            // Return approval link
            return response()->json([
                'approval_url' => collect($response->result->links)
                    ->where('rel', 'approve')->first()->href,
                'payment_id' => $payment->id,
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Capture PayPal Payment after user approves
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        $token = $request->query('token');

        $order = OrderInformation::find($orderId);
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        $captureRequest = new OrdersCaptureRequest($token);
        $captureRequest->prefer('return=representation');

        try {
            $response = $this->client->execute($captureRequest);

            // Update payment + order
            $payment = Payment::where('paypal_payment_id', $token)->first();
            $payment->update(['status' => 'approved']);
            $order->update(['payment_status' => 'done', 'status' => 'accepted']);

            return response()->json([
                'message' => 'Payment captured successfully',
                'order_information_id' => $order->id,
                'status' => 'paid'
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function paymentCancel(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = OrderInformation::find($orderId);

        if ($order) {
            $order->update(['payment_status' => 'nondone', 'status' => 'cancel']);
        }

        return response()->json(['message' => 'Payment cancelled']);
    }
}
