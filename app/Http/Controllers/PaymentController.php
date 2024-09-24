<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Square\SquareClient;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Square\Exceptions\ApiException;

class PaymentController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT', 'sandbox'),  // Change to 'production' when ready
        ]);
    }

    public function createPayment(Request $request)
    {
        $nonce = $request->input('nonce');
        $amount = 1000; // Example: 1000 cents = $10.00

        // Create the Money object
        $money = new Money();
        $money->setAmount($amount); 
        $money->setCurrency('USD');

        // Create a payment request
        $paymentRequest = new CreatePaymentRequest($nonce, uniqid(), $money);

        try {
            $response = $this->client->getPaymentsApi()->createPayment($paymentRequest);
            if ($response->isSuccess()) {
                return response()->json(['success' => true, 'payment' => $response->getResult()]);
            } else {
                return response()->json(['success' => false, 'errors' => $response->getErrors()]);
            }
        } catch (ApiException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
