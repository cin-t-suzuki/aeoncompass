<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Models\MastCity;
use App\Models\MastPref;
use App\Models\MastWard;
use Illuminate\Http\Request;

// MEMO: 移植元では、 BrhotelController に一緒くたにされていた。
class BrHotelRegisterController extends Controller
{
    public function new(Request $request)
    {
        $guides = ['施設登録の際はウィザードに添ってSTEP 6/6 まで必ず完了してください。'];

        if ($request->session()->has('Hotel')) {
            // session にデータがある場合、登録処理からの戻り
            $a_hotel = $request->session()->get('Hotel');
        } else {
            // session にデータがない場合、初期表示
            $a_hotel = [
                'hotel_cd'          => null,
                'hotel_category'    => null,
                'hotel_nm'          => null,
                'hotel_kn'          => null,
                'hotel_old_nm'      => null,
                'postal_cd'         => null,
                'pref_id'           => null,
                'city_id'           => null,
                'ward_id'           => null,
                'address'           => null,
                'tel'               => null,
                'fax'               => null,
                'room_count'        => null,
                'check_in'          => null,
                'check_in_end'      => null,
                'check_in_info'     => null,
                'check_out'         => null,
                'midnight_status'   => null,
            ];
        }

        if ($request->session()->has('Hotel_Control')) {
            // session にデータがある場合、登録処理からの戻り
            $a_hotel_control = $request->session()->get('Hotel_Control');
        } else {
            // session にデータがない場合、初期表示
            $a_hotel_control = [
                'stock_type' => null,
            ];
        }

        //都道府県
        $a_mast_prefs = (new MastPref())->getMastPrefs();
        //市
        $a_mast_cities = ['values' => []];
        if (!is_null($a_hotel['pref_id'])) {
            $a_mast_cities = (new MastCity())->getMastCities($a_hotel['pref_id']);
        }
        //区
        $a_mast_wards = null;
        if (!is_null($a_hotel['city_id'])) {
            $a_mast_wards = (new MastWard())->getMastWards($a_hotel['city_id']);
        }

        return view('ctl.brhotel.new', [
            'action'        => 'new',
            'guides'        => $guides,

            'mast_prefs'    => $a_mast_prefs,
            'mast_cities'   => $a_mast_cities,
            'mast_wards'    => $a_mast_wards,

            'hotel'         => $a_hotel,
            'hotel_control' => $a_hotel_control,
            'target_cd'     => $request->input('target_cd'),
        ]);
    }

    public function create()
    {
        return 'TODO: br hotel register controller create';
    }
}
