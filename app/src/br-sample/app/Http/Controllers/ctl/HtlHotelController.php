<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HtlHotelController extends Controller
{
    //
    public function show(Request $request)
    {
        $targetCd = $request->input('target_cd');
        return view('ctl.htlHotel.show', [
            'target_cd' => $targetCd,
        ]);
        return 'HtlHotelController show method';
    }
}
