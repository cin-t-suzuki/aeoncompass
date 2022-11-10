<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HtlHotelController extends Controller
{
    //
    public function show()
    {
        return view('ctl.htlHotel.show');
        return 'HtlHotelController show method';
    }
}
