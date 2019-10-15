<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\OrderItem;
use App\Subscriber;

use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    public function get_subscribers($id)
    {
        $product = Product::where('id', $id)->with('order_items.order.subscriber', 'order_items.product')->first();

        return [
            'success' => true,
            'product' => $product,
        ];
    }

    public function save_subscriber(Request $request)
    {
        if ($request->is_new == 1) {
            $subscriber = new Subscriber();
            $order = new Order();
            $order->code = uniqid();
            $order->subscriber_id = 0;

        } else {
            $subscriber = Subscriber::find($request->subscriber_id);
            $order = Order::find($request->order_id);
        }

        $subscriber->name = $request->name;
        $subscriber->surname = $request->surname;
        $subscriber->email = $request->email;
        $subscriber->payment_type_id = $request->payment_type_id;
        $subscriber->payment_status_id = $request->payment_status_id;
        $subscriber->save();


        if ($request->is_new == 1) {
            $order->subscriber_id = $subscriber->id;
        }

        $order->payment_status_id = $request->payment_status_id;
        $order->name = $request->name;
        $order->surname = $request->surname;
        $order->address = $request->address;
        $order->email = $request->email;
        $order->city = $request->city;
        $order->province = $request->province;
        $order->postal_code = $request->postal_code;
        $order->ragione_sociale = $request->ragione_sociale;
        $order->cf = $request->cf;
        $order->codice_destinatario = $request->codice_destinatario;
        $order->language = $request->language;
        $order->pec = $request->pec;
        $order->phone = $request->phone;
        $order->type = $request->type;
        $order->vat = $request->vat;
        $order->save();

        $product = Product::where('id', $request->product_id)->with('order_items.order.subscriber')->first();

        if ($request->is_new == 1) {
            $order_item = new OrderItem();
            $order_item->product_id = $product->id;
            $order_item->order_id = $order->id;
            $order_item->quantity = 1;
            $order_item->price = $product->price;
            $order_item->save();
        }

        $product->load('order_items.order.subscriber');
        $product = $this->set_available($product);
        $product->save();

        return [
            'success' => true,
            'product' => $product,
            'debug' => $request->all(),
        ];
    }

    public function cancel_subrscriber_order(Request $request)
    {
        $subscriber = Subscriber::find($request->subscriber_id);
        $subscriber->payment_status_id = $request->payment_status_id;
        $subscriber->save();

        $order = Order::find($request->order_id);
        $order->payment_status_id = 5;
        $order->save();

        $product = Product::where('id', $request->product_id)->with('order_items.order.subscriber')->first();
        $product = $this->set_available($product);
        $product->save();

        return [
            'success' => true,
            'product' => $product,
        ];
    }

    public function set_available($product)
    {
        $total = $product->guests_total;
        $items = $product->order_items;

        $confirmed = 0;

        foreach ($items as $key => $item) {
            $order = $item->order;
            if ($order->payment_status_id == 1) {
                $confirmed = $confirmed + $item->quantity;
            }
        }

        $product->guests_available = $total - $confirmed;
        $product->guests_confirmed = $confirmed;
        return $product;
    }
}
