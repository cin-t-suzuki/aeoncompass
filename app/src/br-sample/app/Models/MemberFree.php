<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberFree extends CommonDBModel
{
    use Traits;

    // フリーユーザ情報

    protected $table = "member_free";

    // カラム
    const COL_MEMBER_CD  = "member_cd";
    const COL_PARTNER_CD  = "partner_cd";
    const COL_EMAIL  = "email";
    const COL_CHECK_IN_YMD  = "check_in_ymd";
    const COL_STAY  = "stay";
    const COL_ROOMS  = "rooms";
    const COL_HOTEL_CD  = "hotel_cd";
    const COL_ROOM_CD  = "room_cd";
    const COL_PLAN_CD  = "plan_cd";
    const COL_PREF_ID  = "pref_id";
    const COL_ADDRESS  = "address";
    const COL_GENDER  = "gender";
    const COL_BIRTH_YMD  = "birth_ymd";
    const COL_IP_ADDRESS  = "ip_address";
    const COL_RESERVE_DTM  = "reserve_dtm";
    const COL_PAYMENT_WAY_SPECIFIED  = "payment_way_specified";
    const COL_DEFAULT_STATUS  = "default_status";
    const COL_ROOM_ID  = "room_id";
    const COL_PLAN_ID  = "plan_id";
    const COL_INSURANCE_WEATHER_STATUS  = "insurance_weather_status";
    const COL_ADULTS  = "adults";
    const COL_MALES  = "males";
    const COL_FEMALES  = "females";
    const COL_CHILD1S  = "child1s";
    const COL_CHILD2S  = "child2s";
    const COL_CHILD3S  = "child3s";
    const COL_CHILD4S  = "child4s";
    const COL_CHILD5S  = "child5s";
    const COL_PAYMENT_WAY  = "payment_way";
    const COL_MEMBER_LAST_NM  = "member_last_nm";
    const COL_MEMBER_FIRST_NM  = "member_first_nm";
    const COL_MEMBER_LAST_NM_KN  = "member_last_nm_kn";
    const COL_MEMBER_FIRST_NM_KN  = "member_first_nm_kn";
    const COL_MEMBER_TEL  = "member_tel";
    const COL_EXTSCD  = "extscd";


    public function __construct()
    {
        // カラム情報の設定
    }

    // メンバーCDとパートナーCDでの取得
    public function selectByWKey($member_cd, $partner_cd)
    {
        $data = $this->where([
                self::COL_MEMBER_CD => $member_cd,
                self::COL_PARTNER_CD => $partner_cd
            ])->get();

        if (!is_null($data) && count($data) > 0) {
            return [
                self::COL_MEMBER_CD  => $data[0]->member_cd,
                self::COL_PARTNER_CD  => $data[0]->partner_cd,
                self::COL_EMAIL  => $data[0]->email,
                self::COL_CHECK_IN_YMD  => $data[0]->check_in_ymd,
                self::COL_STAY  => $data[0]->stay,
                self::COL_ROOMS  => $data[0]->rooms,
                self::COL_HOTEL_CD  => $data[0]->hotel_cd,
                self::COL_ROOM_CD  => $data[0]->room_cd,
                self::COL_PLAN_CD  => $data[0]->plan_cd,
                self::COL_PREF_ID  => $data[0]->pref_id,
                self::COL_ADDRESS  => $data[0]->address,
                self::COL_GENDER  => $data[0]->gender,
                self::COL_BIRTH_YMD  => $data[0]->birth_ymd,
                self::COL_IP_ADDRESS  => $data[0]->ip_address,
                self::COL_RESERVE_DTM  => $data[0]->reserve_dtm,
                self::COL_PAYMENT_WAY_SPECIFIED  => $data[0]->payment_way_specified,
                self::COL_DEFAULT_STATUS  => $data[0]->default_status,
                self::COL_ROOM_ID  => $data[0]->room_id,
                self::COL_PLAN_ID  => $data[0]->plan_id,
                self::COL_INSURANCE_WEATHER_STATUS  => $data[0]->insurance_weather_status,
                self::COL_ADULTS  => $data[0]->adults,
                self::COL_MALES  => $data[0]->males,
                self::COL_FEMALES  => $data[0]->females,
                self::COL_CHILD1S  => $data[0]->child1s,
                self::COL_CHILD2S  => $data[0]->child2s,
                self::COL_CHILD3S  => $data[0]->child3s,
                self::COL_CHILD4S  => $data[0]->child4s,
                self::COL_CHILD5S  => $data[0]->child5s,
                self::COL_PAYMENT_WAY  => $data[0]->payment_way,
                self::COL_MEMBER_LAST_NM  => $data[0]->member_last_nm,
                self::COL_MEMBER_FIRST_NM  => $data[0]->member_first_nm,
                self::COL_MEMBER_LAST_NM_KN  => $data[0]->member_last_nm_kn,
                self::COL_MEMBER_FIRST_NM_KN  => $data[0]->member_first_nm_kn,
                self::COL_MEMBER_TEL  => $data[0]->member_tel,
                self::COL_EXTSCD  => $data[0]->extscd
            ];
        }
        return [];
    }
}
