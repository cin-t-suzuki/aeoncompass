<?php

namespace App\Models;

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/**
 * パートナーマスタ
 */
class Advert extends CommonDBModel
{
    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'hotel_advert_2009000400';

	// カラム
	public string $COL_RECORD_ID = "record_id";
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ADVERT_S_YMD = "advert_s_ymd";
	public string $COL_ADVERT_E_YMD = "advert_e_ymd";
	public string $COL_ADVERT_ORDER = "advert_order"; 
	public string $COL_ADVERT_CHARGE = "advert_charge"; 
	public string $COL_ADVERT_STATUS = "advert_status";  
	public string $COL_ENTRY_CD = "entry_cd"; 
	public string $COL_ENTRY_TS = "entry_ts";
	public string $COL_MODIFY_CD = "modify_cd"; 
	public string $COL_MODIFY_TS = "modify_ts";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// // カラム情報の設定
		$colRecordId= (new ValidationColumn())->setColumnName($this->COL_RECORD_ID, "広告掲載ID")->require()->length(0,8)->intOnly();
		$colHotelCd= (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0,10)->notHalfKana(); //TODO 独自チェック
		$colAdvertSYmd= (new ValidationColumn())->setColumnName($this->COL_ADVERT_S_YMD, "掲載開始年月日")->require()->hyphenOrSlashDate();
		$colAdvertEYmd= (new ValidationColumn())->setColumnName($this->COL_ADVERT_E_YMD, "掲載最終年月日")->require()->hyphenOrSlashDate();
		$colAdvertOrder= (new ValidationColumn())->setColumnName($this->COL_ADVERT_ORDER, "掲載順序")->require()->length(0,8)->intOnly();
		$colAdvertCharge= (new ValidationColumn())->setColumnName($this->COL_ADVERT_CHARGE, "掲載金額")->require()->length(0,7)->intOnly();
		$colAdvertStatus= (new ValidationColumn())->setColumnName($this->COL_ADVERT_STATUS, "掲載状態")->length(0,1)->intOnly(); //TODO パターンチェック、カラムの説明
		parent::setColumnDataArray([$colRecordId,$colHotelCd,$colAdvertSYmd,$colAdvertEYmd,$colAdvertOrder,$colAdvertCharge,$colAdvertStatus]);
	}

	// 広告掲載施設の取得
	//
	//  aa_conditions
	public function get_hotel_advert_2009000400s($aa_conditions = array()){

		$a_conditions = array();

		$s_sql =
<<<SQL
			select	q2.record_id,
					q2.hotel_cd,
					q2.hotel_nm,
					q2.advert_s_ymd,
					q2.advert_e_ymd,
					q2.advert_order,
					q2.advert_charge,
					q2.advert_status,
					mast_pref.pref_nm
			from	mast_pref,
				(
					select	q1.record_id,
							q1.hotel_cd,
							hotel.hotel_nm,
							q1.advert_s_ymd,
							q1.advert_e_ymd,
							q1.advert_order,
							q1.advert_charge,
							q1.advert_status,
							hotel.pref_id
					from	hotel,
						(
							select	record_id,
									hotel_cd,
									advert_s_ymd,
									advert_e_ymd,
									advert_order,
									advert_charge,
									case
										when advert_e_ymd < date_format(now(), 'DD') then
											0
										else
											advert_status
									end as advert_status
							from	hotel_advert_2009000400
							where	null is null
								and	date_add(now(), INTERVAL -3 MONTH) <= advert_e_ymd
						) q1
					where	hotel.hotel_cd = q1.hotel_cd
				) q2
			where	mast_pref.pref_id = q2.pref_id
			order by mast_pref.pref_id,
					q2.advert_s_ymd,
					q2.advert_e_ymd,
					q2.hotel_cd
SQL;

	//データ取得
	$data = DB::select($s_sql,$a_conditions);

	//取得結果を返す
	$result = [];
	if(!is_null($data) && count($data) > 0){ 
		foreach($data as $row){
			$result[] = array(
				$this->COL_RECORD_ID => $row->record_id,
				$this->COL_HOTEL_CD => $row->hotel_cd,
				$this->COL_ADVERT_S_YMD => $row->advert_s_ymd,
				$this->COL_ADVERT_E_YMD => $row->advert_e_ymd,
				$this->COL_ADVERT_ORDER => $row->advert_order,
				$this->COL_ADVERT_CHARGE => $row->advert_charge,
				$this->COL_ADVERT_STATUS => $row->advert_status,
				"hotel_nm" => $row->hotel_nm,
				"pref_nm" => $row->pref_nm
				);
		}
	}
	return array('values' => $result);

	}

	/** 
	 * 広告掲載施設の取得
	 * 
	 * as_table_name  テーブル名称
	 */
	public function get_record_id($table_name){
		$s_sql =
<<<SQL
			select	ifNull(substr(max(record_id), 5), 0) + 1 as record_id
			from	{$table_name}
			where	record_id like concat(date_format(now(), '%Y') , '%')
SQL;
	//データ取得
	$data = DB::select($s_sql,array());
	//取得結果を返す
	$result = [];
	if(!is_null($data) && count($data) > 0){ 
		foreach($data as $row){
			$result[] = array(
				$this->COL_RECORD_ID => $row->record_id,
				);
		}
	}
	
	return $result[0]['record_id'];

	}

	/** 
	 * 主キーで取得
	 */
	public function selectByKey($record_id){
		$data = $this->where($this->COL_RECORD_ID, $record_id)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_RECORD_ID => $data[0]->record_id,
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_ADVERT_S_YMD => $data[0]->advert_s_ymd,
				$this->COL_ADVERT_E_YMD => $data[0]->advert_e_ymd,
				$this->COL_ADVERT_ORDER => $data[0]->advert_order,
				$this->COL_ADVERT_CHARGE => $data[0]->advert_charge,
				$this->COL_ADVERT_STATUS => $data[0]->advert_status,
				$this->COL_ENTRY_CD => $data[0]->entry_cd,
				$this->COL_ENTRY_TS => $data[0]->entry_ts,
				$this->COL_MODIFY_CD => $data[0]->modify_cd,
				$this->COL_MODIFY_TS => $data[0]->modify_ts
			);
		}
		return null;
	}

	/**  キーで更新
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return エラーメッセージ
	 */
	public function updateByKey($con, $data){
		$result = $con->table($this->table)->where($this->COL_RECORD_ID, $data[$this->COL_RECORD_ID])->update($data);
		return  $result;
	}

	/**  新規登録
	 *
	 * @param [type] $con
	 * @param [type] $data
	 * @return 
	 */
	public function insert($con, $data){
		$result = $con->table($this->table)->insert($data);
		return  $result;
		
	}

}
