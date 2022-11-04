<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * パートナーマスタ
 */
class Partner extends CommonDBModel
{
	use Traits;

	protected $table = "partner";

	// カラム
	public string $COL_PARTNER_CD = "partner_cd";
	public string $COL_PARTNER_NM = "partner_nm";
	public string $COL_SYSTEM_NM = "system_nm";
	public string $COL_PARTNER_NS = "partner_ns";
	public string $COL_URL = "url"; 
	public string $COL_POSTAL_CD = "postal_cd"; 
	public string $COL_ADDRESS = "address"; 
	public string $COL_TEL = "tel"; 
	public string $COL_FAX = "fax"; 
	public string $COL_PERSON_POST = "person_post";
	public string $COL_PERSON_NM = "person_nm";
	public string $COL_PERSON_KN = "person_kn"; 
	public string $COL_PERSON_EMAIL = "person_email";
	public string $COL_OPEN_YMD = "open_ymd"; 
	public string $COL_TIEUP_YMD = "tieup_ymd"; 
	public string $COL_ENTRY_CD = "entry_cd"; 
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd"; 
	public string $COL_MODIFY_TS = "modify_ts";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// // カラム情報の設定
		$colPartnerCd= (new ValidationColumn())->setColumnName($this->COL_PARTNER_CD, "提携先コード")->require()->length(0,10)->notHalfKana();
		$colPartnerNm= (new ValidationColumn())->setColumnName($this->COL_PARTNER_NM, "提携先名称")->require()->length(0,65)->notHalfKana();
		$colSystemNm= (new ValidationColumn())->setColumnName($this->COL_SYSTEM_NM, "システム名称")->require()->length(0,65)->notHalfKana();
		$colPartnerNs= (new ValidationColumn())->setColumnName($this->COL_PARTNER_NS, "提携先略称")->require()->length(0,20)->notHalfKana();
		$colUrl= (new ValidationColumn())->setColumnName($this->COL_URL, "ウェブサイトアドレス")->require()->length(0,128)->notHalfKana()->url();
		$colPostalCd= (new ValidationColumn())->setColumnName($this->COL_POSTAL_CD, "郵便番号")->require()->length(0,8)->notHalfKana()->postal(); 
		$colAddress= (new ValidationColumn())->setColumnName($this->COL_ADDRESS, "住所")->require()->length(0,100)->notHalfKana();
		$colTel= (new ValidationColumn())->setColumnName($this->COL_TEL, "電話番号")->require()->length(0,15)->notHalfKana()->phoneNumber();
		$colFax= (new ValidationColumn())->setColumnName($this->COL_FAX, "ファックス番号")->require()->length(0,15)->notHalfKana()->phoneNumber();
		$colPersonPost= (new ValidationColumn())->setColumnName($this->COL_PERSON_POST, "担当者役職")->require()->length(0,32)->notHalfKana();
		$colPersonNm= (new ValidationColumn())->setColumnName($this->COL_PERSON_NM, "担当者名称")->require()->length(0,32)->notHalfKana();
		$colPersonKn= (new ValidationColumn())->setColumnName($this->COL_PERSON_KN, "担当者かな名称")->require()->length(0,64)->notHalfKana()->HiraganaOnly();
		$colPersonEmail= (new ValidationColumn())->setColumnName($this->COL_PERSON_EMAIL, "担当者電子メールアドレス")->require()->length(0,128)->notHalfKana()->emails();
		$colOpenYmd= (new ValidationColumn())->setColumnName($this->COL_OPEN_YMD, "公開日")->require()->correctDate();
		parent::setColumnDataArray([$colPartnerCd,$colPartnerNm,$colSystemNm,$colPartnerNs,$colUrl,$colPostalCd,$colAddress,$colTel,$colFax,$colPersonPost,$colPersonNm,$colPersonKn,$colPersonEmail,$colOpenYmd]);
	}

	// 提携先一覧の取得
	//
	//  aa_conditions
	//    partner_cd   提携先コード
	//    partner_nm   提携先名称 like
	//    connect_cls  接続形態
	//    connect_type 接続形態（詳細）
	public function getPartners($aa_conditions = array()){

		$a_conditions = array();
		$s_partner_cd = '';
		$s_partner_nm = '';
		$s_connect_cls = '';
		$s_connect_type = '';

		// 提携先コードを指定
		if (!($this->is_empty($aa_conditions['partner_cd']))){
			$s_partner_cd = 'and	partner_cd = :partner_cd'; 
			$a_conditions['partner_cd'] = $aa_conditions['partner_cd'];
		}

		// 提携先名称を指定
		if (!($this->is_empty($aa_conditions['partner_nm']))){
			$s_partner_nm = 'and	partner_nm like :partner_nm';
			$a_conditions['partner_nm'] = '%' . $aa_conditions['partner_nm'] . '%';
		}

		// 接続形態を指定
		if (!($this->is_empty($aa_conditions['connect_cls']))){
			$s_connect_cls = 'and	connect_cls = :connect_cls';
			$a_conditions['connect_cls'] = $aa_conditions['connect_cls'];
		}

		// 接続形態（詳細）を指定
		if (!($this->is_empty($aa_conditions['connect_type']))){
			$s_connect_type = 'and	connect_type = :connect_type';
			$a_conditions['connect_type'] = $aa_conditions['connect_type'];
		}


		$s_sql =
<<< SQL
					select	q1.partner_cd,
							q1.partner_nm,
							q1.system_nm,
							q1.partner_ns, 							
							q1.tieup_ymd,
							partner_control.connect_cls,
							partner_control.connect_type,
							partner_control.entry_status,
							partner_control.version
					from	partner_control,
						(
							select	partner.partner_cd,
									partner.partner_nm,
									partner.system_nm,
									partner.partner_ns,
									partner.tieup_ymd
							from	partner
							where	null is null
								{$s_partner_cd}
								{$s_partner_nm}
						) q1
					where	partner_control.partner_cd = q1.partner_cd
						{$s_connect_cls}
						{$s_connect_type}
					order by partner_control.partner_cd
SQL;
		
		//データ取得
		$data = DB::select($s_sql,$a_conditions);

		//取得結果を返す
		$result = [];
		if(!is_null($data) && count($data) > 0){ 
			foreach($data as $row){
				$result[] = array(
					$this->COL_PARTNER_CD => $row->partner_cd,
					$this->COL_PARTNER_NM => $row->partner_nm,
					$this->COL_SYSTEM_NM => $row->system_nm,
					$this->COL_PARTNER_NS => $row->partner_ns,
					$this->COL_TIEUP_YMD => $row->tieup_ymd,
					"connect_cls" => $row->connect_cls,
					"connect_type" => $row->connect_type,
					"entry_status" => $row->entry_status,
					"version" => $row->version,
					);
			}
		}
		return array('values' => $result);
	}


	//パートナーCDでの提携先情報取得
	public function selectByKey($partnerCd){
		$data = $this->where(array($this->COL_PARTNER_CD=>$partnerCd))->get();
		
		// メールアドレスを復号 DB取得方法が違うので下記のように書き換えたが問題ないか、元々登録のものはデコードすると文字化けし空白になる
		$cipher = new Models_Cipher(config('settings.cipher_key'));
			if (!empty($data[0]->person_email)) {
				$data[0]->email_decrypt = $cipher->decrypt($data[0]->person_email);
			} else {
				$data[0]->email_decrypt = '';
			}

		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_PARTNER_CD => $data[0]->partner_cd,
				$this->COL_PARTNER_NM => $data[0]->partner_nm,
				$this->COL_SYSTEM_NM => $data[0]->system_nm,
				$this->COL_PARTNER_NS => $data[0]->partner_ns,
				$this->COL_TIEUP_YMD => $data[0]->tieup_ymd,
				$this->COL_URL => $data[0]->url, 
				$this->COL_POSTAL_CD => $data[0]->postal_cd, 
				$this->COL_ADDRESS => $data[0]->address, 
				$this->COL_TEL => $data[0]->tel, 
				$this->COL_FAX => $data[0]->fax, 
				$this->COL_PERSON_POST => $data[0]->person_post,
				$this->COL_PERSON_NM => $data[0]->person_nm,
				$this->COL_PERSON_KN => $data[0]->person_kn, 
				$this->COL_PERSON_EMAIL => $data[0]->email_decrypt, //上で復号化したものを渡す
				$this->COL_OPEN_YMD => $data[0]->open_ymd, 
				$this->COL_TIEUP_YMD => $data[0]->tieup_ymd, 
				$this->COL_ENTRY_CD => $data[0]->entry_cd, 
				$this->COL_ENTRY_TS => $data[0]->entry_ts,
				$this->COL_MODIFY_CD => $data[0]->modify_cd, 
				$this->COL_MODIFY_TS => $data[0]->modify_ts,
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

	//======================================================================
	// 接続形態一覧の取得
	//======================================================================
	public function get_connect_cls_list()
	{
		// 初期化
		$a_return = array();

		$s_sql =
<<< SQL
			select distinct
					connect_cls
			from	partner_control
			order by	connect_cls
SQL;
		$a_rows = DB::select($s_sql, array());

		// 整形
		foreach ( ($a_rows ?? array()) as $a_row ) {   //nvl($a_rows, array())空の書き換え合っている？
			// 接続形態がnullのものは弾く
			if ( !$this->is_empty($a_row->connect_cls) ) {
				$a_return[$a_row->connect_cls] = strtoupper($a_row->connect_cls);
			}
		}

		return $a_return;
	}

	//======================================================================
	// 接続形態（詳細）一覧の取得
	//======================================================================
	public function get_connect_type_list()
	{
		// 初期化
		$a_return = array();

		$s_sql =
<<< SQL
			select distinct
					connect_type
			from	partner_control
			order by	connect_type
SQL;
		$a_rows = DB::select($s_sql, array());

		// 整形
		foreach ( ($a_rows ?? array()) as $a_row ) {   //nvl($a_rows, array())空の書き換え合っている？
			// 接続形態がnullのものは弾く
			if ( !$this->is_empty($a_row->connect_type) ) {
				$a_return[$a_row->connect_type] = strtoupper($a_row->connect_type);
			}
		}

		return $a_return;
	}



}

