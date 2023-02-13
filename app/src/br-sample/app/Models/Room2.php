<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Room2 extends CommonDBModel
{

    // テーブル名称
    protected $table = "room2";

    // カラム
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_ROOM_ID = "room_id";
    public string $COL_ROOM_NM = "room_nm";
    public string $COL_ROOM_TYPE = "room_type";
    public string $COL_FLOORAGE_MIN = "floorage_min";
    public string $COL_FLOORAGE_MAX = "floorage_max";
    public string $COL_FLOOR_UNIT = "floor_unit";
    public string $COL_ACTIVE_STATUS = "active_status";
    public string $COL_DISPLAY_STATUS = "display_status";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_LABEL_CD = "label_cd";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";
    public string $COL_CAPACITY_MIN = "capacity_min";
    public string $COL_CAPACITY_MAX = "capacity_max";
    public string $COL_ACCEPT_STATUS = "accept_status";
    public string $COL_ROOM_NL = "room_nl";
    public string $COL_USER_SIDE_ORDER_NO = "user_side_order_no";


    // 正規部屋名称を利用するモジュール名 旧ソースのまま
    private $_a_room_nl = array('inq', 'pol');

    private $_s_room_id = null;

    public function get_room_id()
    {
        return $this->_s_room_id;
    }

    protected $_a_attributes = []; // 対象となるデータ

    function __construct()
    {

        parent::__construct();

        // 施設コード
        $colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

        // 部屋ID
        $colRoomId = (new ValidationColumn())->setColumnName($this->COL_ROOM_ID, "部屋ID")->require()->length(0, 10)->notHalfKana()->numberAndUpperAlphabet();

        // 部屋名称
        $colRoomNl = (new ValidationColumn())->setColumnName($this->COL_ROOM_NL, "部屋名称")->require()->length(0, 40)->notHalfKana();

        // 部屋名称（短縮）
        $colRoomNm = (new ValidationColumn())->setColumnName($this->COL_ROOM_NM, "部屋名称（短縮）")->require()->length(0, 15)->notHalfKana();

        // 部屋タイプ
        $colRoomType = (new ValidationColumn())->setColumnName($this->COL_ROOM_TYPE, "部屋タイプ")->require();

        // 最小床面積
        $colFloorageMin = (new ValidationColumn())->setColumnName($this->COL_FLOORAGE_MIN, "最小床面積")->require()->length(0, 3)->currencyOnly();

        // 最大床面積
        $colFloorageMax = (new ValidationColumn())->setColumnName($this->COL_FLOORAGE_MAX, "最大床面積")->require()->length(0, 3)->currencyOnly();


        // 広さ単位
        $colFloorUnit = (new ValidationColumn())->setColumnName($this->COL_FLOOR_UNIT, "最大床面積")->require();

        // 最小定員
        $colCapacityMin = (new ValidationColumn())->setColumnName($this->COL_CAPACITY_MIN, "最小定員")->require()->length(0, 2)->currencyOnly(); //TODO 独自チェック capacity_min_validate

        // 最大定員
        $colCapacityMax = (new ValidationColumn())->setColumnName($this->COL_CAPACITY_MAX, "最大定員")->require()->length(0, 2)->currencyOnly(); //TODO 独自チェック capacity_max_validate

        // システム取扱状態
        $colActiveStatus = (new ValidationColumn())->setColumnName($this->COL_ACTIVE_STATUS, "システム取扱状態")->require();

        // 表示ステータス
        $colDisplayStatus = (new ValidationColumn())->setColumnName($this->COL_DISPLAY_STATUS, "表示ステータス")->require();

        // 予約受付状態
        $colAcceptStatus = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_STATUS, "予約受付状態")->require();

        // 表示順序
        $colOrderNo = (new ValidationColumn())->setColumnName($this->COL_ORDER_NO, "表示順序")->intOnly()->length(0, 10);


        // 管理画面表示順序
        $colUserSideOrderNo = (new ValidationColumn())->setColumnName($this->COL_USER_SIDE_ORDER_NO, "管理画面表示順序")->intOnly()->length(0, 10);

        // 部屋ラベル
        $colLabelCd = (new ValidationColumn())->setColumnName($this->COL_LABEL_CD, "部屋ラベル")->notHalfKana()->length(0, 10)->numberAndUpperAlphabet(); // TODO 独自チェック label_cd_validate

        parent::setColumnDataArray([
            $colHotelCd,
            $colRoomNl,
            $colRoomId,
            $colRoomNm,
            $colRoomType,
            $colFloorageMin,
            $colFloorageMax,
            $colFloorUnit,
            $colCapacityMin,
            $colCapacityMax,
            $colActiveStatus,
            $colDisplayStatus,
            $colAcceptStatus,
            $colOrderNo,
            $colUserSideOrderNo,
            $colLabelCd
        ]);
    }

}
