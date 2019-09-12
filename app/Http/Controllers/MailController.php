<?php

namespace App\Http\Controllers;

use App\Mail\CartInfo;
use App\Mail\SimpleForm;
use App\Mail\SimpleFormAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send_mail_form(Request $request)
    {
        $admin_address = env('ADMIN_EMAIL');

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
        $admin_address = env('ADMIN_EMAIL');

        $data = [
            'sender' => $request->email,
            'name' => $request->name,
            'surname' => $request->surname,
            'message' => $request->message,
        ];

        $client_mail = Mail::to($request->email)
            ->send(new CartInfo($data));

        // $admin_mail = Mail::to($admin_address)
        //     ->send(new SimpleFormAdmin($data));

        return [
            'success' => true,
        ];
    }
}
