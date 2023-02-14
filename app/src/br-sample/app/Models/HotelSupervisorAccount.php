<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
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
		$colSupervisorCd = (new ValidationColumn())->setColumnName($this->COL_SUPERVISOR_CD, "施設統括コード")->require()->length(0, 10)->notHalfKana();
		$colAccountId = (new ValidationColumn())->setColumnName($this->COL_ACCOUNT_ID, "アカウントID")->require()->length(0, 10)->notHalfKana()->numberAndAlphabet();//半角英数のみかチェック
		$colPassword = (new ValidationColumn())->setColumnName($this->COL_PASSWORD, "パスワード")->require()->length(0, 10)->notHalfKana()->numberAndAlphabet();//半角英数のみかチェック
		$colAcceptStatus = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_STATUS, "ステータス")->require()->length(0, 1)->intOnly();

		parent::setColumnDataArray([$colSupervisorCd, $colAccountId,$colPassword,$colAcceptStatus]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($supervisorCd){

		//復号化処理
        $cipher = new Models_Cipher(config('settings.cipher_key'));
		//データ取得
		$data = $this->where($this->COL_SUPERVISOR_CD, $supervisorCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_SUPERVISOR_CD => $data[0]->supervisor_cd,
				$this->COL_ACCOUNT_ID => $data[0]->account_id,
				$this->COL_PASSWORD => $cipher->decrypt($data[0]->password),//復号して表示
				$this->COL_ACCEPT_STATUS => $data[0]->accept_status,
			);
		}else{
			return false;
		}
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