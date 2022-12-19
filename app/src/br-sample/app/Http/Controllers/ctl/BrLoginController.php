<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrLoginController extends Controller
{
    public function index()
    {
        return view('ctl.br.login.index', [
        ]);
    }
}
