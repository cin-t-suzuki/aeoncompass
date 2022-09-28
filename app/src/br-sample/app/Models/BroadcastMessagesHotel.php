<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * 管理画面お知らせ施設
 */
class BroadcastMessagesHotel extends CommonDBModel
{
	protected $table = "broadcast_messages_hotel";
	// カラム
	public string $COL_BROADCAST_MESSAGES_HOTEL_ID          ="broadcast_messages_hotel_id";
	public string $COL_BROADCAST_MESSAGES_ID          ="broadcast_messages_id";
	public string $COL_HOTEL_CD          ="hotel_cd";
	public string $COL_ORDER_NUMBER          ="order_number";
	
	/**
	 * コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colBroadcastMessagesHotelId = new ValidationColumn();
		$colBroadcastMessagesHotelId->setColumnName($this->COL_BROADCAST_MESSAGES_HOTEL_ID, "お知らせ施設ID")
			->require()->intOnly()->length(0, 8);

		$colBroadcastMessagesId = new ValidationColumn();
		$colBroadcastMessagesId->setColumnName($this->COL_BROADCAST_MESSAGES_ID, "お知らせID")->intOnly()->length(0, 8);

		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->intOnly()->length(0, 10);

		$colOrderNumber = new ValidationColumn();
		$colOrderNumber->setColumnName($this->COL_ORDER_NUMBER, "並び順")->intOnly()->length(0, 5);

		parent::setColumnDataArray([$colBroadcastMessagesHotelId, 
			$colBroadcastMessagesId, $colHotelCd, $colOrderNumber]);
	}

	/**
	 * 主キーで取得
	 */
	public function selectByKey($broadcast_messages_hotel_id){
		$data = $this->where($this->COL_BROADCAST_MESSAGES_HOTEL_ID, $broadcast_messages_hotel_id)->get();
		if(!is_null($data) && count($data) > 0){
			return $data;
		}
		return null;
	}

	/**
	 * 存在チェック
	 */
	public function selectByBroadcastMessagesId($broadcast_messages_id)
	{
		$s_sql = <<<SQL
			select 	broadcast_messages_hotel_id,
					hotel_cd
			from broadcast_messages_hotel
			where broadcast_messages_id = {$broadcast_messages_id}
			order by order_number asc
		SQL;

		$data = DB::select($s_sql);

		if( empty($data) || count($data) <= 0 ){
			$data = array();
			$this->_s_error = 'NotFound';
		}

		return $data;
	}

	/** key max+1を取得
	 */
	public function getNextId()
	{
		$s_sql = <<<SQL
			select  IfNull(max(broadcast_messages_hotel_id),0) + 1 as next_id
				from    broadcast_messages_hotel
		SQL;

		$data = DB::select($s_sql);

		if( empty($data) || count($data) <= 0 ){
			return 1;
		}

		return $data[0]->next_id;
	}
	
	/** 新規登録(1件)
	 */
	public function singleInsert($con, $data){
		// 重複チェック
		$cnt = $this->where($this->COL_BROADCAST_MESSAGES_HOTEL_ID, $data[$this->COL_BROADCAST_MESSAGES_HOTEL_ID])->count();
		if($cnt > 0){
			return "ご指定のお知らせ施設IDは既に存在しています";
		}

		$result = $con->table($this->table)->insert($data);
		if(!$result){
			return "登録に失敗しました";
		}
		return "";
	}

	/** 削除(1件)
	 */
	public function deleteByKey($con, $broadcast_messages_hotel_id){
		
		$result = $con->table($this->table)->where("broadcast_messages_hotel_id", $broadcast_messages_hotel_id)->delete();

		if(!$result){
			return "削除に失敗しました";
		}
		return "";

	}

}
