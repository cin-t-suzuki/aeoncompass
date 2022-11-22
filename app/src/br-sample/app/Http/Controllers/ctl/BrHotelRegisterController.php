<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Models\MastPref;
use Illuminate\Http\Request;

// MEMO: 移植元では、 BrhotelController に一緒くたにされていた。
class BrHotelRegisterController extends Controller
{
    public function new(Request $request)
    {
        $a_hotel            = $request->input('Hotel');
        if (is_null($a_hotel)) {
            $a_hotel = [
                'address'           => null,
                'check_in_end'      => null,
                'check_in_info'     => null,
                'check_in'          => null,
                'check_out'         => null,
                'fax'               => null,
                'hotel_category'    => null,
                'hotel_cd'          => null,
                'hotel_kn'          => null,
                'hotel_nm'          => null,
                'hotel_old_nm'      => null,
                'midnight_status'   => null,
                'postal_cd'         => null,
                'pref_id'           => null,
                'room_count'        => null,
                'tel'               => null,
            ];
        }
        $a_hotel_control    = $request->input('Hotel_Control');
        $targetCd           = $request->input('target_cd');

        // TODO:
        $a_mast_prefs       = null;
        //都道府県
        $mastPref = new MastPref();
        $mastPrefList = $mastPref->getMastPrefs();

        $a_mast_cities      = ['values' => []];
        $a_mast_wards       = null;

        $guides = ['施設登録の際はウィザードに添ってSTEP 6/6 まで必ず完了してください。'];
        return view('ctl.brhotel.new', [
            'guides'        => $guides,

            'mast_prefs'    => $mastPrefList,
            'mast_cities'   => $a_mast_cities,
            'mast_wards'    => $a_mast_wards,

            'hotel'         => $a_hotel,
            'hotel_control' => $a_hotel_control,
            'target_cd'     => $targetCd,
        ]);
    }

    public function create()
    {
        return 'TODO: br hotel register controller create';
    }
}
