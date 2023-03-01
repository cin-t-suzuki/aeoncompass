<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function subscribe(Request $request)
    {
        return view('rsv.mail.subscribe', [
        ]);
    }
}
