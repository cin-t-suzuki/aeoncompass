<?php

namespace App\Services;

use App\Models\HotelInsuranceWeather;
use App\Models\DenyList;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;

class BrHotelRegisterService
{

    /**
     * ホテルコードの取得
     * ※YYYYMM(年月) + 今月の４桁の連番を取得
     *
     * @return string
     */
    public function getHotelCd(): string
    {
        $sql = <<<SQL
            select
                ifnull(
                    max(hotel_cd) + 1
                    , concat(date_format(now(), '%Y%m'), '0001')
                ) as hotel_cd
            from
                hotel
            where
                hotel_cd LIKE concat(date_format(now(), '%Y%m'), '%')
        SQL;
        $result = DB::select($sql);
        return $result[0]['hotel_cd'];
    }
}
