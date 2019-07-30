<?php

namespace App\Http\Controllers;

use Braintree\ClientToken;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function generate_token()
    {
        $token = ClientToken::generate();
        return [
            'token' => $token
        ];
    }
}
