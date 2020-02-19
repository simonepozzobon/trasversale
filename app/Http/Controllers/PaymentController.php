<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Subscriber;
use App\Transaction;

use Braintree\ClientToken;
use Braintree\Gateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->gateway = new Gateway(
            [
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
            ]
        );
    }


    public function generate_token()
    {
        $token = $this->gateway->clientToken()->generate();
        return [
            'token' => $token
        ];
    }

    public function save_order(Request $request)
    {
        $order = new Order();
        $order->code = uniqid();
        $order->subscriber_id = 0;
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

        $items = json_decode($request->items);
        foreach ($items as $key => $product) {
            $order_item = new OrderItem();
            $order_item->product_id = $product->item->id;
            $order_item->order_id = $order->id;
            $order_item->quantity = $product->item->quantity;
            $order_item->price = $product->item->price;
            $order_item->save();
        }

        $subscriber = new Subscriber();
        $subscriber->name = $request->name;
        $subscriber->surname = $request->surname;
        $subscriber->email = $request->email;
        $subscriber->payment_type_id = 1;
        $subscriber->save();

        $order->subscriber_id = $subscriber->id;
        $order->save();

        $order = $order->refresh();

        return [
            'success' => true,
            'order' => $order,
            'subscriber' => $subscriber,
        ];
    }

    public function test()
    {
        $request = new Request();
        $request->replace(
            [
              'order_id' => 2,
            ]
        );

        $this->create_transaction($request);
    }

    public function create_transaction(Request $request)
    {
        $nonce = $request->nonce;

        $order = Order::find($request->order_id);
        $items = $order->products;

        $amount = 0;
        foreach ($items as $key => $item) {
            $product = $item->product;
            if ($product->vat_included == 1) {
                $subtotal = $item->price * $item->quantity;
            } else {
                $vat = ($product->price * $product->vat) / 100;
                $item_price = $product->price + $vat;
                $subtotal = $item_price * $item->quantity;
            }

            $amount = $amount + $subtotal;
        }

        if ($amount > 0) {
            try {
                $results = $this->gateway->transaction()->sale(
                    [
                      'amount' => $amount,
                      'paymentMethodNonce' => $nonce,
                      'options' => [
                          'submitForSettlement' => true
                      ]
                  ]
                );
            } catch (\Exception $e) {
                return [
                  'success' => false,
                  'errors' => $e
              ];
            }

            $transaction = new Transaction();
            $transaction->order_id = $request->order_id;
            $transaction->braintree_id = $results->transaction->id;
            $transaction->nonce = $nonce;
            $transaction->amount = $amount;
            $transaction->status = $results->success;
            $transaction->save();

            $order->payment_status_id = 1;
            $order->save();
        } else {
            $transaction = new Transaction();
            $transaction->order_id = $request->order_id;
            $transaction->braintree_id = uniqid();
            $transaction->nonce = $nonce;
            $transaction->amount = $amount;
            $transaction->status = true;
            $transaction->save();

            $order->payment_status_id = 1;
            $order->save();
        }

        foreach ($items as $key => $item) {
            $product = $item->product;
            if ($product->has_limited_guests == 1) {
                $product = $this->set_available($product);
                // $product->guests_available = $product->guests_available - $item->quantity;
                // $product->guests_confirmed = $product->guests_confirmed + $item->quantity;
                $product->save();
            }
        }

        return [
            'success' => true,
            'amount' => $amount,
            'results' => isset($results) ? $results : 'no-payment',
            'items' => $items,
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
