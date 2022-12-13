<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * @部屋マスタ
 */
class Room extends CommonDBModel
{
    use Traits;

    protected $table = "room";
    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_ROOM_CD = "room_cd";
    public string $COL_ROOM_NM = "room_nm";
    public string $COL_ROOM_TYPE = "room_type";
    public string $COL_FLOORAGE_MIN = "floorage_min";
    public string $COL_FLOORAGE_MAX = "floorage_max";
    public string $COL_FLOOR_UNIT = "floor_unit";
    public string $COL_ACTIVE_STATUS = "active_status";
    public string $COL_DISPLAY_STATUS = "display_status";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";
    public string $COL_ACCEPT_STATUS = "accept_status";
    public string $COL_ROOM_NL = "room_nl";
    public string $COL_USER_SIDE_ORDER_NO = "user_side_order_no";

    /**
     * コンストラクタ
     */
    public function __construct() //function→public fuctionでいいか,使っていない
    {
        // カラム情報の設定
    }

    /**
     * 復号主キーで取得
     */
    public function selectByWKey($hotelCd, $roomCd)
    {
        $data = $this->where(array($this->COL_HOTEL_CD => $hotelCd, $this->COL_ROOM_CD => $roomCd))->get();
        if (!is_null($data) && count($data) > 0) {
            return array(
                $this->COL_HOTEL_CD => $data[0]->hotel_cd,
                $this->COL_ROOM_CD => $data[0]->room_cd,
                $this->COL_ROOM_NM => $data[0]->room_nm,
                $this->COL_ROOM_TYPE => $data[0]->room_type,
                $this->COL_FLOORAGE_MIN => $data[0]->floorage_min,
                $this->COL_FLOORAGE_MAX => $data[0]->floorage_max,
                $this->COL_FLOOR_UNIT => $data[0]->floor_unit,
                $this->COL_ACTIVE_STATUS => $data[0]->active_status,
                $this->COL_DISPLAY_STATUS => $data[0]->display_status,
                $this->COL_ORDER_NO => $data[0]->order_no,
                $this->COL_ENTRY_CD => $data[0]->entry_cd,
                $this->COL_ENTRY_TS => $data[0]->entry_ts,
                $this->COL_MODIFY_CD => $data[0]->modify_cd,
                $this->COL_MODIFY_TS => $data[0]->modify_ts,
                $this->COL_ACCEPT_STATUS => $data[0]->accept_status,
                $this->COL_ROOM_NL => $data[0]->room_nl,
                $this->COL_USER_SIDE_ORDER_NO => $data[0]->user_side_order_no
            );
        }
        return null;
    }
}
