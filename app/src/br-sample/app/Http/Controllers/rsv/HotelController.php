<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\rsv\_commonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Log;

/**
 * 施設コントローラ
 */
class HotelController extends _commonController
{
    /**
     * 施設詳細
     */
    public function info($hotelCd)
    {

        // User情報は？
        // Hotel情報を取得


        // ビューを表示
        return view("rsv.hotel.index", $this->getViewData());
    }
}
