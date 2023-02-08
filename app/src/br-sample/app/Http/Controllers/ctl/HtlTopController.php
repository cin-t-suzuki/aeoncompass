<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// TODO: pull request #20 (メインメニュー施設情報) が merge されたら修正する
class HtlTopController extends Controller
{
    public function index()
    {
        return view('ctl.htl.top.index');
    }
}
