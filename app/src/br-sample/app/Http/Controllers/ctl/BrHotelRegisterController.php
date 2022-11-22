<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// MEMO: 移植元では、 BrhotelController に一緒くたにされていた。
class BrHotelRegisterController extends Controller
{
    public function new(Request $request)
    {
        $a_hotel            = $request->input('Hotel');
        $a_hotel_control    = $request->input('Hotel_Control');
        $targetCd           = $request->input('target_cd');

        // TODO:
        $a_mast_prefs       = null;
        $a_mast_cities      = null;
        $a_mast_wards       = null;

        $guides = ['施設登録の際はウィザードに添ってSTEP 6/6 まで必ず完了してください。'];
        return view('ctl.brhotel.new', [
            'guides'        => $guides,

            'mast_prefs'    => $a_mast_prefs,
            'mast_cities'   => $a_mast_cities,
            'mast_wards'    => $a_mast_wards,

            'hotel'         => $a_hotel,
            'hotel_control' => $a_hotel_control,
            'target_cd'     => $targetCd,
        ]);
        return 'br hotel register controller';
    }
}
