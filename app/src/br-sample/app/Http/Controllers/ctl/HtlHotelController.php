<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\Controller;
use App\Models\HotelAmenity;
use App\Models\HotelBathTax;
use App\Models\HotelCancelPolicy;
use App\Models\HotelCancelRate;
use App\Models\HotelCard;
use App\Models\HotelFacility;
use App\Models\HotelFacilityRoom;
use App\Models\HotelInfo;
use App\Models\HotelLink;
use App\Models\HotelNearby;
use App\Models\HotelReceipt;
use App\Models\HotelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HtlHotelController extends Controller
{
    //
    public function show(Request $request)
    {
        $targetCd = $request->input('target_cd');

        // TODO:
        $a_hotel_info = HotelInfo::find($targetCd);

        // $a_hotelrate             = $models_hotel->get_hotel_rates();              // 料率の一覧データを配列で取得
        // $a_hotel_links           = $models_hotel->get_hotel_links();              // 施設リンクの取得
        // $a_hotel_inform_cancel   = $models_hotel->get_hotel_inform_cancel();      // 施設注意事項情報の取得
        // $a_hotel_inform_free     = $models_hotel->get_hotel_inform_free();        // 施設その他記入欄情報の取得

        // TODO: mast_card と join して card_nm を取得
        $a_hotel_card               = DB::table('hotel_card')->where('hotel_cd', $targetCd)->get(); // 利用可能カードの取得
        // $a_hotel_card            = $models_hotel->get_hotel_cards();              // 利用可能カードの取得
        // $a_hotel_amenities       = $models_hotel->get_hotel_amenities();          // アメニティの取得
        // $a_hotel_services        = $models_hotel->get_hotel_services();           // サービス の取得
        // $a_hotel_nearbies        = $models_hotel->get_hotel_nearbies();           // 周辺情報の取得
        // $a_hotel_facilities      = $models_hotel->get_hotel_facilities();         // 設備 の取得
        // $a_hotel_facility_rooms  = $models_hotel->get_hotel_facility_rooms();     // 部屋設備 の取得
        // $a_hotel_cancel_rates    = $models_hotel->get_hotel_cancel_rates();


        return view('ctl.htlHotel.show', [
            'target_cd' => $targetCd,

            'hotels'                    => null, // 移植元で、 controller から渡されているが、使われていなさそう
            'a_hotel_info'              => $a_hotel_info,
            'a_hotel_links'             => [],
            'a_hotel_card'              => $a_hotel_card,
            'a_hotel_inform_cancel'     => [],

            'a_hotel_inform_free'       => [],
            'a_hotel_amenities'         => [],
            'a_hotel_services'          => [],
            'a_hotel_nearbies'          => [],
            'a_hotel_facilities'        => [],

            'a_hotel_facility_rooms'    => [],
            'a_amenity'                 => [],
            'a_service'                 => [],
            'a_nearby'                  => [],
            'a_facility'                => [],

            'a_facility_room'           => [],
            'a_hotel_station'           => [],
            'a_hotel_control'           => [],
            'a_hotel_cancel_policy'     => [],
            'a_hotel_cancel_rates'      => [],

            'a_hotel_receipt'           => [],
            'a_hotel_bath_tax'          => [],
            'a_hotel_bath_tax_flg'      => [],

            'a_hotel_link'              => [], // 移植元で、 controller から渡されていないが、 view で使っている
        ]);
        return 'HtlHotelController show method';
    }
}
