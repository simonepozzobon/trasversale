<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MailController extends Controller
{
    public function send_mail_form(Request $request)
    {
        return [
            'success' => true,
        ];
    }
}
