<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BrHotelAreaService
{
    public function getHotelInfo($hotelCd)
    {
        $sql = <<<SQL
            select
                q3.hotel_cd,
                case
                    when q3.hotel_category = 'a' then 'カプセルホテル'
                    when q3.hotel_category = 'b' then 'ビジネスホテル'
                    when q3.hotel_category = 'c' then 'シティホテル'
                    when q3.hotel_category = 'j' then '旅館'
                    else ''
                end as hotel_category,
                q3.hotel_nm,
                q3.postal_cd,
                q3.address,
                q3.tel,
                q3.fax,
                q3.pref_nm,
                q3.city_nm,
                mw.ward_id -- nm じゃなくて大丈夫？ MEMO:
            from
                mast_ward mw
                right outer join (
                    select
                        q2.hotel_cd,
                        q2.hotel_category,
                        q2.hotel_nm,
                        q2.postal_cd,
                        q2.ward_id,
                        q2.address,
                        q2.tel,
                        q2.fax,
                        q2.pref_nm,
                        mc.city_nm
                    from
                        mast_city mc
                        inner join (
                            select
                                q1.hotel_cd,
                                q1.hotel_category,
                                q1.hotel_nm,
                                q1.postal_cd,
                                q1.city_id,
                                q1.ward_id,
                                q1.address,
                                q1.tel,
                                q1.fax,
                                mp.pref_nm
                            from
                                mast_pref mp
                                inner join (
                                    select
                                        h.hotel_cd,
                                        h.hotel_category,
                                        h.hotel_nm,
                                        h.postal_cd,
                                        h.pref_id,
                                        h.city_id,
                                        h.ward_id,
                                        h.address,
                                        h.tel,
                                        h.fax
                                    from
                                        hotel h
                                    where
                                        h.hotel_cd = :hotel_cd
                                ) q1 on q1.pref_id = mp.pref_id
                        ) q2 on q2.city_id = mc.city_id
                ) q3 on q3.ward_id = mw.ward_id        
        SQL;
        /* こういう SQL では違う？
            $sql = <<<SQL
                select
                    hotel.hotel_cd,
                    case
                        when hotel.hotel_category = 'a' then 'カプセルホテル'
                        when hotel.hotel_category = 'b' then 'ビジネスホテル'
                        when hotel.hotel_category = 'c' then 'シティホテル'
                        when hotel.hotel_category = 'j' then '旅館'
                        else ''
                    end as hotel_category,
                    hotel.hotel_nm,
                    hotel.postal_cd,
                    hotel.address,
                    hotel.tel,
                    hotel.fax,
                    mast_pref.pref_nm,
                    mast_city.city_nm,
                    mast_ward.ward_id -- nm じゃなくて大丈夫？ MEMO:
                from
                    hotel
                    left outer join mast_pref on hotel.pref_id = mast_pref.pref_id
                    left outer join mast_city on hotel.city_id = mast_city.city_id
                    left outer join mast_ward on hotel.city_id = mast_ward.ward_id
                where
                    hotel.hotel_cd = :hotel_cd
            SQL;
        */

        $resultHotelInfo = DB::select($sql, ['hotel_cd' => $hotelCd]);
        if (count($resultHotelInfo) > 0) {
            return $resultHotelInfo[0];
        } else {
            // データがヒットしないときは、必要なプロパティを設定した空の stdClass を返す
            // MEMO: 設定しておかないと、 undefined array key で処理が止まる
            return (object)[
                'hotel_cd'  => null,
                'hotel_nm'  => null,
                'postal_cd' => null,
                'pref_nm'   => null,
                'address'   => null,
                'tel'       => null,
                'fax'       => null,
            ];
        }
    }
    
    // TODO: to be deleted
    public function dummyHotelArea($targetCd)
    {
        return (object)[
            'entry_no'      => 'entry_no_val' . Str::random(5),
            'area_nm_l'     => 'area_nm_l_val' . Str::random(5),
            'area_nm_m'     => 'area_nm_m_val' . Str::random(5),
            'area_nm_p'     => 'area_nm_p_val' . Str::random(5),
            'area_nm_s'     => 'area_nm_s_val' . Str::random(5),
            'hotel_cd'      => $targetCd,
        ];
    }
}
