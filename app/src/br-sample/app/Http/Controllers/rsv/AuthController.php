<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        return view('rsv.auth.login', [

            // TOOD: 暫定
            'type' => '',
            'banner' => '',
            'reconfirm' => '',
            'account_id' => '',
            'button_nm' => '',
            'next_url' => '',
        ]);
    }
}
