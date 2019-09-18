<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\OrderItem;
use App\Subscriber;

use App\Mail\CartInfo;
use App\Mail\CartInfoAdmin;
use App\Mail\SimpleForm;
use App\Mail\SimpleFormAdmin;
use App\Mail\PayTeacherCard;
use App\Mail\PayTeacherCardAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send_mail_form(Request $request)
    {
        $admin_address = env('ADMIN_EMAIL', 'demo@example.comdsgsdfg');

        $data = [
            'sender' => $request->email,
            'name' => $request->name,
            'message' => $request->message,
        ];

        $client_mail = Mail::to($request->email)
            ->send(new SimpleForm($data));

        $admin_mail = Mail::to($admin_address)
            ->send(new SimpleFormAdmin($data));

        return [
            'success' => true,
        ];
    }

    public function send_cart_info(Request $request)
    {
        $admin_address = env('ADMIN_EMAIL', 'demo@example.com');

        $product = new Product();
        if (isset($request->item_id) && $request->item_id) {
            $product = Product::find($request->item_id);

            $order = new Order();
            $order->code = uniqid();
            $order->subscriber_id = 0;
            $order->name = $request->name;
            $order->surname = $request->surname;
            $order->address = 'non fornito';
            $order->email = $request->email;
            $order->city = 'non fornito';
            $order->province = 'non fornito';
            $order->postal_code = 'non fornito';
            $order->payment_status_id = 3;
            $order->save();

            $order_item = new OrderItem();
            $order_item->product_id = $product->id;
            $order_item->order_id = $order->id;
            $order_item->quantity = 1;
            $order_item->price = $product->price;
            $order_item->save();

            $subscriber = new Subscriber();
            $subscriber->name = $request->name;
            $subscriber->surname = $request->surname;
            $subscriber->email = $request->email;
            $subscriber->payment_type_id = 4;
            $subscriber->save();

            $order->subscriber_id = $subscriber->id;
            $order->save();
        }

        $data = [
            'sender' => $request->email,
            'name' => $request->name,
            'surname' => $request->surname,
            'message' => $request->message,
        ];

        $client_mail = Mail::to($request->email)
            ->send(new CartInfo($data, $product));

        $admin_mail = Mail::to($admin_address)
            ->send(new CartInfoAdmin($data, $product));

        return [
            'success' => true,
        ];
    }

    public function teacher_card(Request $request)
    {
        $admin_address = env('ADMIN_EMAIL', 'demo@example.com');

        $product = new Product();
        if (isset($request->item_id) && $request->item_id) {
            $product = Product::find($request->item_id);

            $order = new Order();
            $order->code = uniqid();
            $order->subscriber_id = 0;
            $order->name = $request->name;
            $order->surname = $request->surname;
            $order->address = 'non fornito';
            $order->email = $request->email;
            $order->city = 'non fornito';
            $order->province = 'non fornito';
            $order->postal_code = 'non fornito';
            $order->payment_status_id = 2;
            $order->save();

            $order_item = new OrderItem();
            $order_item->product_id = $product->id;
            $order_item->order_id = $order->id;
            $order_item->quantity = 1;
            $order_item->price = $product->price;
            $order_item->save();

            $subscriber = new Subscriber();
            $subscriber->name = $request->name;
            $subscriber->surname = $request->surname;
            $subscriber->email = $request->email;
            $subscriber->payment_type_id = 3;
            $subscriber->save();

            $order->subscriber_id = $subscriber->id;
            $order->save();
        }

        $data = [
            'sender' => $request->email,
            'name' => $request->name,
            'surname' => $request->surname,
            'code' => $request->code,
        ];

        $client_mail = Mail::to($request->email)
            ->send(new PayTeacherCard($data, $product));

        $admin_mail = Mail::to($admin_address)
            ->send(new PayTeacherCardAdmin($data, $product));

        return [
            'success' => true,
        ];
    }
}
