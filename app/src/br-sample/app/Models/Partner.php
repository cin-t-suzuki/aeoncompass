<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
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
	 * コンストラクタ  いじっていない
	 */
	function __construct(){
		// // カラム情報の設定
		// $colPrefId = new ValidationColumn();
		// $colPrefId->setColumnName($this->COL_PREF_ID, "都道府県ID")->require()->length(0,2)->intOnly();
		// $colRegionId = new ValidationColumn();
		// $colRegionId->setColumnName($this->COL_REGION_ID, "地方ID")->require()->length(0,2)->intOnly();
		// $colPrefNm = new ValidationColumn();
		// $colPrefNm->setColumnName($this->COL_PREF_NM, "都道府県名称")->require()->length(0,5)->notHalfKana();//TODO 独自チェック追加
		// $colPrefNs = new ValidationColumn();
		// $colPrefNs->setColumnName($this->COL_PREF_NS, "都道府県略称")->length(0,3)->notHalfKana();
		// $colOrderNo = new ValidationColumn();
		// $colOrderNo->setColumnName($this->COL_ORDER_NO, "都道府県表示順序")->length(0,2)->intOnly();
		// $colPrefCd = new ValidationColumn();
		// $colPrefCd->setColumnName($this->COL_PREF_CD, "都道府県コード")->length(0,2);
		// $colDeleteYmd = new ValidationColumn();
		// $colDeleteYmd->setColumnName($this->COL_DELETE_YMD, "削除日")->correctDate();

		// parent::setColumnDataArray([$colPrefId, $colRegionId, $colPrefNm, $colPrefNs, $colOrderNo, $colPrefCd, $colDeleteYmd]);
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


// 提携先グループ一覧の取得
//
//  aa_conditions
//    partner_group_nm 提携先グループ名称 like
//    partner_group_id 提携先グループid
//    hotel_cd         施設コード
// →一旦割愛


}