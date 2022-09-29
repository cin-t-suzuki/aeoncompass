<?php

namespace App\Http\Controllers\ctl;

class BrPartnerCustomerController extends _commonController
{
    public function search()
    {
        // オブジェクトの取得

        return view('ctl.brPartnerCustomer.search');
    }
}