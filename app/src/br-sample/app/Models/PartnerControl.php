<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Util\Models_Cipher;
use App\Common\Traits;

/**
 * パートナーマスタ
 */
class PartnerControl extends CommonDBModel
{
	use Traits;

	protected $table = "partner_control";

	// カラム
	public string $COL_PARTNER_CD = "partner_cd";
	public string $COL_CONNECT_CLS = "connect_cls";
	public string $COL_CONNECT_TYPE = "connect_type";
	public string $COL_ENTRY_STATUS = "entry_status"; 
	public string $COL_PW_ADMIN = "pw_admin";
	public string $COL_PW_OPERATOR = "pw_operator";
	public string $COL_PW_USER = "pw_user";
	public string $COL_CHARSET = "charset";
	public string $COL_VOICE_STATUS = "voice_status";
	public string $COL_PAGE_TIMELIMIT = "page_timelimit"; 
	public string $COL_EXTENSION_STATE = "extension_state"; 
	public string $COL_STOCK_TYPE = "stock_type"; 
	public string $COL_PAYMENT_WAY = "payment_way"; 
	public string $COL_ENTRY_CD = "entry_cd"; 
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd"; 
	public string $COL_MODIFY_TS = "modify_ts";
	public string $COL_SALES_TYPE = "sales_type"; 
	public string $COL_AUTH_TYPE = "auth_type";
	public string $COL_RATE = "rate";
	public string $COL_PARTNER_POINT_STATUS = "partner_point_status"; 
	public string $COL_LATER_PAYMENT = "later_payment";
	public string $COL_VERSION = "version"; 
	public string $COL_RESULT_EMAIL = "result_email";
	public string $COL_STAYCONFIRM_STATUS = "stayconfirm_status"; 
	public string $COL_EMAIL_FROM_NM = "email_from_nm";
	public string $COL_RESULT_RPC_STATUS = "result_rpc_status"; 
	public string $COL_RESULT_RPC_URL = "result_rpc_url"; 



	/**
	 * コンストラクタ
	 */
	function __construct(){
		// // カラム情報の設定
		$colPartnerCd= (new ValidationColumn())->setColumnName($this->COL_PARTNER_CD, "提携先コード")->require()->length(0,10)->notHalfKana();
		$colConnectCls= (new ValidationColumn())->setColumnName($this->COL_CONNECT_CLS, "接続形態");// TODO パターンチェック必要？
		$colConnectType= (new ValidationColumn())->setColumnName($this->COL_CONNECT_TYPE, "接続形態（詳細）"); // TODO パターンチェック必要？
		$colEntryStatus= (new ValidationColumn())->setColumnName($this->COL_ENTRY_STATUS, "提携先登録状態"); // TODO パターンチェック必要？
		$colPwAdmin= (new ValidationColumn())->setColumnName($this->COL_PW_ADMIN, "管理パスワード")->require()->length(0,64)->notHalfKana();
		$colPwOperator= (new ValidationColumn())->setColumnName($this->COL_PW_OPERATOR, "運用パスワード")->require()->length(0,64)->notHalfKana();
		$colPwUser= (new ValidationColumn())->setColumnName($this->COL_PW_USER, "接続パスワード")->length(0,12)->notHalfKana(); //パスワード系、ひらがなとかはＯＫでいいの？
		$colCharset= (new ValidationColumn())->setColumnName($this->COL_CHARSET, "キャラクタセット")->length(0,16)->notHalfKana(); // TODO パターンチェック必要？カラムの説明
		$colVoiceStatus= (new ValidationColumn())->setColumnName($this->COL_VOICE_STATUS, "掲示板表示設定"); // TODO パターンチェック必要？
		$colPageTimelimit= (new ValidationColumn())->setColumnName($this->COL_PAGE_TIMELIMIT, "ページ有効時間")->length(0,4)->intOnly();
		$colExtensionState= (new ValidationColumn())->setColumnName($this->COL_EXTENSION_STATE, "付随情報表示状態"); // TODO パターンチェック必要？
		$colSalesType= (new ValidationColumn())->setColumnName($this->COL_SALES_TYPE, "販売可能タイプ")->require()->intOnly(); 
		$colAuthType= (new ValidationColumn())->setColumnName($this->COL_AUTH_TYPE, "予約照会認証タイプ")->require(); // TODO パターンチェック必要？カラムの説明
		$colRate= (new ValidationColumn())->setColumnName($this->COL_RATE, "料率")->length(0,3)->intOnly();
		$colResultEmail= (new ValidationColumn())->setColumnName($this->COL_RESULT_EMAIL, "実績レポート配信メールアドレス")->length(0,128)->notHalfKana()->emails(); 
		$colFromNm= (new ValidationColumn())->setColumnName($this->COL_EMAIL_FROM_NM, "お客様向けメール送信者名称")->length(0,15)->notHalfKana();
		$colRpcStatus= (new ValidationColumn())->setColumnName($this->COL_RESULT_RPC_STATUS, "予約時実績報告レポート配信")->require()->intOnly();  // TODO パターンチェック必要？カラムの説明
		$colRpcUrl= (new ValidationColumn())->setColumnName($this->COL_RESULT_RPC_URL, "予約時実績報告レポート配信先URL")->length(0,256)->url();
		//
		parent::setColumnDataArray([$colPartnerCd,$colConnectCls,$colConnectType,$colEntryStatus,$colPwAdmin,$colPwOperator,$colPwUser,$colCharset,$colVoiceStatus,$colPageTimelimit,$colExtensionState,$colSalesType,$colAuthType,$colRate,$colResultEmail,$colFromNm,$colRpcStatus,$colRpcUrl]);
	}


	//パートナーCDでの提携先管理情報取得
	public function selectByKey($partnerCd){
		$data = $this->where(array($this->COL_PARTNER_CD=>$partnerCd))->get();

		// パスワードを復号 DB取得方法が違うので下記のように書き換えたが問題ないか、元々登録のものはデコードすると文字化けし空白になる
		$cipher = new Models_Cipher(config('settings.cipher_key'));
			if (!empty($data[0]->pw_user)) {
				$data[0]->pw_user_decrypt = $cipher->decrypt($data[0]->pw_user);
			} else {
				$data[0]->pw_user_decrypt = '';
			}


		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_PARTNER_CD => $data[0]->partner_cd,
				$this->COL_CONNECT_CLS => $data[0]->connect_cls,
				$this->COL_CONNECT_TYPE => $data[0]->connect_type,
				$this->COL_ENTRY_STATUS => $data[0]->entry_status, 
				$this->COL_PW_ADMIN => $data[0]->pw_admin,
				$this->COL_PW_OPERATOR => $data[0]->pw_operator,
				$this->COL_PW_USER => $data[0]->pw_user_decrypt, //上で復号化したものを渡す
				$this->COL_CHARSET => $data[0]->charset,
				$this->COL_VOICE_STATUS => $data[0]->voice_status,
				$this->COL_PAGE_TIMELIMIT => $data[0]->page_timelimit, 
				$this->COL_EXTENSION_STATE => $data[0]->extension_state, 
				$this->COL_STOCK_TYPE => $data[0]->stock_type, 
				$this->COL_PAYMENT_WAY => $data[0]->payment_way, 
				$this->COL_ENTRY_CD => $data[0]->entry_cd, 
				$this->COL_ENTRY_TS => $data[0]->entry_ts,
				$this->COL_MODIFY_CD => $data[0]->modify_cd, 
				$this->COL_MODIFY_TS => $data[0]->modify_ts,
				$this->COL_SALES_TYPE => $data[0]->sales_type, 
				$this->COL_AUTH_TYPE => $data[0]->auth_type,
				$this->COL_RATE => $data[0]->rate,
				$this->COL_PARTNER_POINT_STATUS => $data[0]->partner_point_status, 
				$this->COL_LATER_PAYMENT => $data[0]->later_payment,
				$this->COL_VERSION => $data[0]->version, 
				$this->COL_RESULT_EMAIL => $data[0]->result_email,
				$this->COL_STAYCONFIRM_STATUS => $data[0]->stayconfirm_status, 
				$this->COL_EMAIL_FROM_NM => $data[0]->email_from_nm,
				$this->COL_RESULT_RPC_STATUS => $data[0]->result_rpc_status, 
				$this->COL_RESULT_RPC_URL => $data[0]->result_rpc_url
			);
		}
		return [];
	
	}

	
	/**  キーで更新
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return エラーメッセージ
	 */
	public function updateByKey($con, $data){
		$result = $con->table($this->table)->where($this->COL_PARTNER_CD, $data[$this->COL_PARTNER_CD])->update($data);
		return  $result;
	}
}

