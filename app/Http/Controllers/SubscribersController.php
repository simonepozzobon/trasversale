<?php

namespace App\Http\Controllers;

use App\Product;

use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    public function get_subscribers($id)
    {
        $product = Product::where('id', $id)->with('order_items.order.subscriber')->first();

        return [
            'success' => true,
            'product' => $product,
        ];
    }
}
