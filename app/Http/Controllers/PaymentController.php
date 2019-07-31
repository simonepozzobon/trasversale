<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;

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

    public function save_order(Request $request)
    {
        $order = new Order();
        $order->code = uniqid();
        $order->name = $request->name;
        $order->surname = $request->surname;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->province = $request->province;
        $order->postal_code = $request->postal_code;
        $order->save();

        $items = json_decode($request->items);
        foreach ($items as $key => $product) {
            $order_item = new OrderItem();
            $order_item->product_id = $product->item->id;
            $order_item->order_id = $order->id;
            $order_item->quantity = $product->item->quantity;
            $order_item->price = $product->item->price;
            $order_item->save();

        }

        $order->refresh();

        return [
            'success' => true,
            'order' => $order
        ];
    }
}
