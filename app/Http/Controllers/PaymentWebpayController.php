<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Helpers
use Transbank\Webpay\WebpayPlus\Transaction;
use Transbank\Webpay\WebpayPlus;

// Models
use App\Models\PaymentWebpay;
use App\Models\DetailPaymentWebpay;

class PaymentWebpayController extends Controller
{
    public function __construct() {
        //$this->middleware('auth');

        if (app()->environment('production')) {
            WebpayPlus::configureForProduction(config('services.transbank.webpay_plus_cc'), config('services.transbank.webpay_plus_api_key'));
        } else {
            WebpayPlus::configureForTesting();
        }
    }

    public function createTransaction(Request $request)
    {
        // Data transaction
            $reference = 'Ref-'.$request->userId;
            $sessionId = $request->userId.'-'.bin2hex(random_bytes(20));
            $ammount = $request->ammount;
            $returnUrl = \Request::root().'/payment-return';

        // Create transaction
            $resp = (new Transaction)->create($reference, $sessionId, $ammount, $returnUrl);

        dd($resp);
    }
}
