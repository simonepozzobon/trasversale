<?php

namespace App\Http\Controllers;

use App\Product;

use App\Mail\CartInfo;
use App\Mail\CartInfoAdmin;
use App\Mail\SimpleForm;
use App\Mail\SimpleFormAdmin;

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
        }

        $data = [
            'sender' => $request->email,
            'name' => $request->name,
            'surname' => $request->surname,
            'message' => $request->message,
            'item' => $product
        ];

        $client_mail = Mail::to($request->email)
            ->send(new CartInfo($data, $product));

        $admin_mail = Mail::to($admin_address)
            ->send(new CartInfoAdmin($data, $product));

        return [
            'success' => true,
        ];
    }
}
