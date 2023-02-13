<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * @部屋プランマスタ
 */
class RoomPlanPriority extends CommonDBModel
{
    use Traits;

    protected $table = "room_plan_priority";
    // カラム
    public string $COL_PREF_ID = "pref_id";
    public string $COL_SPAN = "span";
    public string $COL_WDAY = "wday";
    public string $COL_PRIORITY = "priority";
    public string $COL_HOTEL_CD = "hotel_cd";
    public string $COL_ROOM_CD = "room_cd";
    public string $COL_PLAN_CD = "plan_cd";
    public string $COL_DISPLAY_STATUS = "display_status";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";
    public string $COL_ROOM_ID = "room_id";
    public string $COL_PLAN_ID = "plan_id";

    /**
     * コンストラクタ
     */
    public function __construct() //function→public fuctionでいいか,使っていない
    {
        // カラム情報の設定
        $colPrefId = new ValidationColumn();
        $colPrefId->setColumnName($this->COL_PREF_ID, "都道府県ID")->require()->length(0, 2)->intOnly();
        $colSpan = new ValidationColumn();
        $colSpan->setColumnName($this->COL_SPAN, "宿泊対象期間")->require()->length(0, 1)->intOnly();
        $colWday = new ValidationColumn();
        $colWday->setColumnName($this->COL_WDAY, "曜日")->require(); //TODO パターンチェック、カラムの説明
        $colPriority = new ValidationColumn();
        $colPriority->setColumnName($this->COL_PRIORITY, "重点表示順位")->require()->length(0, 3)->intOnly(); //TODO 独自チェック
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->length(0, 10)->notHalfKana();
        $colRoomCd = new ValidationColumn();
        $colRoomCd->setColumnName($this->COL_ROOM_CD, "部屋コード")->length(0, 10)->notHalfKana(); // 半角大文字英数チェック
        $colPlanCd = new ValidationColumn();
        $colPlanCd->setColumnName($this->COL_PLAN_CD, "プランコード")->length(0, 10)->notHalfKana(); // 半角大文字英数チェック
        $colDisplayStatus = new ValidationColumn();
        $colDisplayStatus->setColumnName($this->COL_DISPLAY_STATUS, "表示ステータス")->require(); //TODO パターンチェック、カラムの説明

        parent::setColumnDataArray([$colPrefId, $colSpan, $colWday, $colPriority,
                                    $colHotelCd, $colRoomCd, $colPlanCd, $colDisplayStatus]);
    }

    /**
     * 復号主キーで取得
     */
    public function selectBy4Key($prefId, $span, $wday, $priority)
    {
        $data = $this->where(array(
            $this->COL_PREF_ID => $prefId,
            $this->COL_SPAN => $span,
            $this->COL_WDAY => $wday,
            $this->COL_PRIORITY => $priority
            ))->get();
        if (!is_null($data) && count($data) > 0) {
            return array(
                $this->COL_PREF_ID => $data[0]->pref_id,
                $this->COL_SPAN => $data[0]->span,
                $this->COL_WDAY => $data[0]->wday,
                $this->COL_PRIORITY => $data[0]->priority,
                $this->COL_HOTEL_CD => $data[0]->hotel_cd,
                $this->COL_ROOM_CD => $data[0]->room_cd,
                $this->COL_PLAN_CD => $data[0]->plan_cd,
                $this->COL_DISPLAY_STATUS => $data[0]->display_status,
                $this->COL_ENTRY_CD => $data[0]->entry_cd,
                $this->COL_ENTRY_TS => $data[0]->entry_ts,
                $this->COL_MODIFY_CD => $data[0]->modify_cd,
                $this->COL_MODIFY_TS => $data[0]->modify_ts,
                $this->COL_ROOM_ID => $data[0]->room_id,
                $this->COL_PLAN_ID => $data[0]->plan_id
            );
        }
        return null;
    }

    /**  複合主キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateBy4Key($con, $roomPlanPriorityData)
    {
        $result = $con->table($this->table)->where(array(
                $this->COL_PREF_ID => $roomPlanPriorityData['pref_id'],
                $this->COL_SPAN => $roomPlanPriorityData['span'],
                $this->COL_WDAY => $roomPlanPriorityData['wday'],
                $this->COL_PRIORITY => $roomPlanPriorityData['priority']
                ))->update($roomPlanPriorityData);
        return  $result;
    }

    /**  新規登録
     *
     * @param [type] $con
     * @param [type] $data
     * @return
     */
    public function insert($con, $data)
    {
        $result = $con->table($this->table)->insert($data);
        return  $result;
    }

    /** 復号主キーで削除
     *
     * @param [type] $con
     * @param [type] $hotelCd
     * @param [type] $branchNo
     * @return void
     */
    public function deleteBy4Key($prefId, $span, $wday, $priority)
    {
        $result = $this->where(array(
                $this->COL_PREF_ID => $prefId,
                $this->COL_SPAN => $span,
                $this->COL_WDAY => $wday,
                $this->COL_PRIORITY => $priority
                ))->delete();
        return $result;
    }
}
