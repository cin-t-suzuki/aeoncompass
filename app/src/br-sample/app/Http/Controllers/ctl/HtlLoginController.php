<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HtlLoginController extends Controller
{
    public function index()
    {
        return view('ctl.htl.login.index');
    }
    public function login(Request $request)
    {
        return redirect()->back()->withErrors([
            'test',
        ])->withInput();
    }
}
