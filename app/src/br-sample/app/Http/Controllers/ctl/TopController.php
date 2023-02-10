<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;

class TopController extends Controller
{
    // インデックス
    public function index()
    {
        // 本番環境の場合、表示しない
        if (config('app.env') == 'product') {
            return 'not found';
            // return $this->_forward('output', 'error', null, array('error_no' => '404'));
        }
        // ビューを表示
        return view("ctl.top.index");
    }
}
