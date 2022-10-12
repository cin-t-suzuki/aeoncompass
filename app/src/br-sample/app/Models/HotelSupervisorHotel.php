<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

class HotelSupervisorHotel extends CommonDBModel
{
	use Traits;


	/**
	 * コンストラクタ TODOカラム情報の設定
	 */
	function __construct(){
		// TODOカラム情報の設定
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
		
	
	// 統括ホテル一覧を取得
	protected $table = "hotel_supervisor_hotel";
	
	// カラム
	public string $COL_ID = "id";
	public string $COL_SUPERVISOR_CD = "supervisor_cd";
	public string $COL_HOTEL_CD = "hotel_cd";

	public function getHotelSupervisorHotel($aa_conditions = array()){
		
			$a_conditions = array();

			$s_supervisor_cd = "";
			if (isset($aa_conditions['supervisor_cd']) && !$this->is_empty($aa_conditions['supervisor_cd'])){
				$s_supervisor_cd = "and	hotel_supervisor_hotel.supervisor_cd = :supervisor_cd";
				$a_conditions['supervisor_cd'] = $aa_conditions['supervisor_cd'];
			}

			$s_sql =	
			<<<SQL
				select	q2.hotel_cd,
						q2.hotel_nm,
						q2.id,
						q2.supervisor_cd,
						mast_pref.pref_nm
				from	mast_pref,
					(
						select	hotel.hotel_cd,
								hotel.hotel_nm,
								hotel.pref_id,
								q1.id,
								q1.supervisor_cd
						from	hotel,
							(
								select	id,
										supervisor_cd,
										hotel_cd
								from	hotel_supervisor_hotel
								where	null is null
									{$s_supervisor_cd}
							) q1
						where	hotel.hotel_cd = q1.hotel_cd
					) q2
				where	mast_pref.pref_id = q2.pref_id
				order by	q2.pref_id, q2.hotel_cd
			SQL;
			
			$data = DB::select($s_sql,$a_conditions);

			$result = [];
			if(!is_null($data) && count($data) > 0){ 
				foreach($data as $row){
					$result[] = array(
						$this->COL_ID => $row->id,
						$this->COL_SUPERVISOR_CD => $row->supervisor_cd,
						$this->COL_HOTEL_CD => $row->hotel_cd,
						'hotel_nm' => $row->hotel_nm //hotel_nm はthisに入ってこないので直接書いて取得
					);
				}
			}
			return array('values' => $result);

	}
}






?>