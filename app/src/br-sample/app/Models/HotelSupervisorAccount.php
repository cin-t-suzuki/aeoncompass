<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

class HotelSupervisorAccount extends CommonDBModel
{
	use Traits;

	// 統括一覧を取得
	protected $table = "hotel_supervisor_account";
	
	// カラム
	public string $COL_SUPERVISOR_CD = "supervisor_cd";
	public string $COL_ACCOUNT_ID = "account_id";
	public string $COL_PASSWORD = "password";
	public string $COL_ACCEPT_STATUS = "accept_status";
	public string $COL_MODIFY_CD = "modify_cd";
	public string $COL_MODIFY_TS = "modify_ts";

	public string $METHOD_SAVE = "save";
	public string $METHOD_UPDATE = "update";


	/**
	 * コンストラクタ
	 */
	function __construct(){
		// TODOカラム情報の設定
		$colSupervisorCd = (new ValidationColumn())->setColumnName($this->COL_SUPERVISOR_CD, "supervisor_cd")->require()->length(0, 10)->notHalfKana();
		$colAccountId = (new ValidationColumn())->setColumnName($this->COL_ACCOUNT_ID, "account_id")->require()->length(0, 10)->notHalfKana();//TODO 半角英数チェック
		$colPassword = (new ValidationColumn())->setColumnName($this->COL_PASSWORD, "password")->require()->length(0, 10)->notHalfKana();//TODO 半角英数チェック
		$colAcceptStatus = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_STATUS, "accept_status")->require()->length(0, 1)->intOnly();//TODO パターンチェック必要？カラム説明	0 => '利用不可' 1 => '利用可'

		parent::setColumnDataArray([$colSupervisorCd, $colAccountId,$colPassword,$colAcceptStatus]);

	}

	/** 主キーで取得
	 */
	public function selectByKey($supervisorCd){
		$data = $this->where($this->COL_SUPERVISOR_CD, $supervisorCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_SUPERVISOR_CD => $data[0]->supervisor_cd,
				$this->COL_ACCOUNT_ID => $data[0]->account_id,
				$this->COL_PASSWORD => $data[0]->password,
				$this->COL_ACCEPT_STATUS => $data[0]->accept_status,
			);
		}
		return [];
	}


	/**  キーで更新
	 *
	 */ 

	public function updateByKey($con, $data){
		$result = $con->table($this->table)
					  ->where(array($this->COL_SUPERVISOR_CD=>$data[$this->COL_SUPERVISOR_CD]))
					  ->update($data);
		if($result){
			return $result;
		}elseif(!$result){
			return false;
		}
	}
	/** 新規登録(1件)
	 */
	public function singleInsert($con, $data){

		$result = $con->table($this->table)->insert($data);
		if(!$result){
			return "登録に失敗しました";
		}
		return "";
	}



}
?>