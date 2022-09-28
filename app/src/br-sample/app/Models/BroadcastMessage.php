<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * 管理画面お知らせ
 */
class BroadcastMessage extends CommonDBModel
{
	protected $table = "broadcast_message";
	// カラム
	public string $COL_BROADCAST_MESSAGE_ID       ="id";
	public string $COL_TITLE                      ="title";
	public string $COL_DESCRIPTION                ="description";
	public string $COL_ACCEPT_S_DTM               ="accept_s_dtm";
	public string $COL_ACCEPT_E_DTM               ="accept_e_dtm";
	public string $COL_ENTRY_CD                   ="entry_cd";
	public string $COL_ENTRY_TS                   ="entry_ts";
	public string $COL_MODIFY_CD                  ="modify_cd";
	public string $COL_MODIFY_TS                  ="modify_ts";
	public string $COL_HEADER_MESSAGE             ="header_message";
	public string $COL_ACCEPT_HEADER_S_DTM        ="accept_header_s_dtm";
	public string $COL_ACCEPT_HEADER_E_DTM        ="accept_header_e_dtm";

	/**
	 * コンストラクタ
	 */
	function __construct()
	{
		// カラム情報の設定
		$colBroadcastMessageId = new ValidationColumn();
		$colBroadcastMessageId->setColumnName($this->COL_BROADCAST_MESSAGE_ID, "")->require()->length(0, 8)->currencyOnly();

		$colTitle = new ValidationColumn();
		$colTitle->setColumnName($this->COL_TITLE, "お知らせ欄タイトル")->require()->length(0, 80)->notHalfKana();

		$colDescription = new ValidationColumn();
		$colDescription ->setColumnName($this->COL_DESCRIPTION, "お知らせ詳細")->require()->length(0, 1333)->notHalfKana();

		$colAcceptSDtm = new ValidationColumn();
		$colAcceptSDtm ->setColumnName($this->COL_ACCEPT_S_DTM, "お知らせ欄表示期間(開始日時)")->require()->correctDate();
		$colAcceptEDtm = new ValidationColumn();
		$colAcceptEDtm ->setColumnName($this->COL_ACCEPT_E_DTM, "お知らせ欄表示期間(終了日時)")->require()->correctDate();

		$colHeaderMessage = new ValidationColumn();
		$colHeaderMessage->setColumnName($this->COL_HEADER_MESSAGE, "ページ上部表示文言")->length(0, 600)->notHalfKana();

		$colAcceptHeaderSDtm = new ValidationColumn();
		$colAcceptHeaderSDtm->setColumnName($this->COL_ACCEPT_HEADER_S_DTM, "ページ上部表示期間(開始日時)")->correctDate();
		$colAcceptHeaderEDtm = new ValidationColumn();
		$colAcceptHeaderEDtm->setColumnName($this->COL_ACCEPT_HEADER_E_DTM, "ページ上部表示期間(終了日時)")->correctDate();

		parent::setColumnDataArray([$colBroadcastMessageId, $colTitle, $colDescription, $colAcceptSDtm, $colAcceptEDtm, 
									$colHeaderMessage, $colAcceptHeaderSDtm, $colAcceptHeaderEDtm ]);
	}



	/**
	 * 主キーで取得
	 */
	public function selectByKey($brbroadcast_id)
	{
		$data = $this->where("id", $brbroadcast_id)->get();
		if(!is_null($data) && count($data) > 0){
			return $data;
		}
		return null;
	}

	/**
	 * 主キーで取得
	 * 一部項目名を変換
	 */
	public function getDetail($brbroadcast_id)
	{
		
		$data = $this->selectByKey($brbroadcast_id);
		//        Log::info("デバッグgetDetail".$data[0]->id);//TODO =145
		return array(
			"brbroadcast_id" => $data[0]->id,
			"header_message" => $data[0]->header_message,
			"title" => $data[0]->title,
			"description" => $data[0]->description,
		// 1項目を年月日時に分割 編集
			"accept_header_s_year"  => $this->getDateCheckNull('Y',   $data[0]->accept_header_s_dtm) , 
			"accept_header_s_month" => $this->getDateCheckNull('n',   $data[0]->accept_header_s_dtm) ,
			"accept_header_s_day"   => $this->getDateCheckNull('j',   $data[0]->accept_header_s_dtm) ,
			"accept_header_s_time"  => $this->getDateCheckNull('H:i', $data[0]->accept_header_s_dtm) ,

			"accept_header_e_year"  => $this->getDateCheckNull('Y',   $data[0]->accept_header_e_dtm) , 
			"accept_header_e_month" => $this->getDateCheckNull('n',   $data[0]->accept_header_e_dtm) , 
			"accept_header_e_day"   => $this->getDateCheckNull('j',   $data[0]->accept_header_e_dtm) , 
			"accept_header_e_time"  => $this->getDateCheckNull('H:i', $data[0]->accept_header_e_dtm) , 

			"accept_s_year"  => $this->getDateCheckNull('Y',   $data[0]->accept_s_dtm) , 
			"accept_s_month" => $this->getDateCheckNull('n',   $data[0]->accept_s_dtm) , 
			"accept_s_day"   => $this->getDateCheckNull('j',   $data[0]->accept_s_dtm) , 
			"accept_s_time"  => $this->getDateCheckNull('H:i', $data[0]->accept_s_dtm) , 

			"accept_e_year"  => $this->getDateCheckNull('Y',   $data[0]->accept_e_dtm) , 
			"accept_e_month" => $this->getDateCheckNull('n',   $data[0]->accept_e_dtm) , 
			"accept_e_day"   => $this->getDateCheckNull('j',   $data[0]->accept_e_dtm) , 
			"accept_e_time"  => $this->getDateCheckNull('H:i', $data[0]->accept_e_dtm) 
		);

	}
	
	/* 一覧画面情報を取得
	 */
	public function getList()
	{
		$s_sql = <<<SQL
			select id as brbroadcast_id
				, header_message
				, title
				, date_format(accept_header_s_dtm, '%Y-%m-%d %H:%i:%s') as accept_header_s_dtm
				, date_format(accept_header_e_dtm, '%Y-%m-%d %H:%i:%s') as accept_header_e_dtm
				, date_format(accept_s_dtm, '%Y-%m-%d %H:%i:%s') as accept_s_dtm
				, date_format(accept_e_dtm, '%Y-%m-%d %H:%i:%s') as accept_e_dtm 
				, ifnull(modify_ts,'0001/01/01 00:00:00') as modify_ts
			from broadcast_message
			order by modify_ts desc
		SQL;

		$data = DB::select($s_sql);

		if( empty($data) || count($data) <= 0 ){
			$data = array();
			$this->_s_error = 'NotFound';
		}

		return $data;
	}

	/* key max+1を取得
	 */
	public function getNextId()
	{
		$s_sql = <<<SQL
			select  IfNull(max(id),0) + 1 as next_id
				from    broadcast_message
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
		$cnt = $this->where($this->COL_BROADCAST_MESSAGE_ID, $data[$this->COL_BROADCAST_MESSAGE_ID])->count();
		if($cnt > 0){
			return "ご指定の管理画面お知らせコードは既に存在しています";
		}

		$result = $con->table($this->table)->insert($data);
		if(!$result){
			return "登録に失敗しました";
		}
		return "";
	}
	
	/**  更新(1件)
	 */
	public function singleUpdate($con, $data){

		$result = $con->table($this->table)->where($this->COL_BROADCAST_MESSAGE_ID, $data[$this->COL_BROADCAST_MESSAGE_ID])->update($data);
		if(!$result){
			return "更新に失敗しました";
		}
		return "";
	}

	/**
	 * 日付データがnullの場合''を返し、それ以外の場合は日付書式処理をする
	 */
	private static function getDateCheckNull( $format, $str) {
		if($str == null || $format == null){
			return '';
		}
		return date($format, strtotime($str));
	}

}
