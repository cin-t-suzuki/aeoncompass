<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * @部屋プランマスタ
 */
class RoomPlan extends CommonDBModel
{
	use Traits;

	protected $table = "room_plan";
	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ROOM_CD = "room_cd";
	public string $COL_PLAN_CD = "plan_cd";
	public string $COL_PLAN_TYPE = "plan_type";
	public string $COL_PLAN_NM = "plan_nm";
	public string $COL_CHARGE_TYPE = "charge_type";
	public string $COL_CAPACITY = "capacity";
	public string $COL_PAYMENT_WAY = "payment_way";
	public string $COL_STAY_LIMIT = "stay_limit";
	public string $COL_ORDER_NO = "order_no";
	public string $COL_DISPLAY_STATUS = "display_status";
	public string $COL_ENTRY_CD = "entry_cd"; 
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd"; 
	public string $COL_MODIFY_TS = "modify_ts";
	public string $COL_USER_SIDE_ORDER_NO = "user_side_order_no";
	public string $COL_ACCEPT_STATUS = "accept_status";
	public string $COL_CHECK_IN = "check_in";
	public string $COL_CHECK_IN_END = "check_in_end";
	public string $COL_CHECK_OUT = "check_out";
	public string $COL_STAY_CAP = "stay_cap";


	/**
	 * コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colPrefId = new ValidationColumn();
		$colPrefId->setColumnName($this->COL_PREF_ID, "都道府県ID")->require()->length(0,2)->intOnly();

		parent::setColumnDataArray([$colPrefId]);
	}

	/** 
	 * 復号主キーで取得
	 */
	public function selectByTripleKey($hotelCd,$roomCd,$planCd){
		$data = $this->where(array($this->COL_HOTEL_CD=>$hotelCd,$this->COL_ROOM_CD=>$roomCd,$this->COL_PLAN_CD=>$planCd))->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_ROOM_CD => $data[0]->room_cd,
				$this->COL_PLAN_CD => $data[0]->plan_cd,
				$this->COL_PLAN_TYPE => $data[0]->plan_type,
				$this->COL_PLAN_NM => $data[0]->plan_nm,
				$this->COL_CHARGE_TYPE => $data[0]->charge_type,
				$this->COL_CAPACITY => $data[0]->capacity,
				$this->COL_PAYMENT_WAY => $data[0]->payment_way,
				$this->COL_STAY_LIMIT => $data[0]->stay_limit,
				$this->COL_ORDER_NO => $data[0]->order_no,
				$this->COL_DISPLAY_STATUS => $data[0]->display_status,
				$this->COL_ENTRY_CD => $data[0]->entry_cd,
				$this->COL_ENTRY_TS => $data[0]->entry_ts,
				$this->COL_MODIFY_CD => $data[0]->modify_cd,
				$this->COL_MODIFY_TS => $data[0]->modify_ts,
				$this->COL_USER_SIDE_ORDER_NO => $data[0]->user_side_order_no,
				$this->COL_ACCEPT_STATUS => $data[0]->accept_status,
				$this->COL_CHECK_IN => $data[0]->check_in,
				$this->COL_CHECK_IN_END => $data[0]->check_in_end,
				$this->COL_CHECK_OUT => $data[0]->check_out,
				$this->COL_STAY_CAP => $data[0]->stay_cap
			);
		}
		return null;
	}

	

}
