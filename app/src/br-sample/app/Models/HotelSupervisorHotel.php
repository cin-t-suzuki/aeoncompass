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


	protected $table = "hotel_supervisor_hotel";
	// カラム
	public string $COL_ID = "id";
	public string $COL_SUPERVISOR_CD = "supervisor_cd";
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ORDER_NUMBER = "order_number";
	
	public string $METHOD_SAVE = "save";
	public string $METHOD_UPDATE = "update";

	/**
	 * コンストラクタ カラム情報の設定
	 */
	function __construct(){	
		$colId = (new ValidationColumn())->setColumnName($this->COL_ID, "ID")->require()->length(0, 8)->intOnly();
		$colSupervisorCd = (new ValidationColumn())->setColumnName($this->COL_SUPERVISOR_CD, "施設統括コード")->require()->length(0, 10)->notHalfKana();
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); //独自チェックはhotelCdValidateで実施
		$colOrderNumber = (new ValidationColumn())->setColumnName($this->COL_ORDER_NUMBER, "並び順")->length(0, 5)->intOnly();

		parent::setColumnDataArray([$colId, $colSupervisorCd, $colHotelCd, $colOrderNumber]);
	}
		
	/** 独自のバリデーション
	* @param [type] $errorList
	* @param [type] $hotelSupervisorHotelData
	* @param [type] $method
	* @return List
	*/
	// 施設コード
	public function hotelCdValidate(&$errorList, $hotelSupervisorHotelData, $method){

		// 重複チェック
		$a_conditions = array();

		// インサート
	if ($method == $this->METHOD_SAVE){

		$s_sql =
<<<SQL
			select	hotel_cd
			from	hotel_supervisor_hotel
			where	null is null
				and	supervisor_cd  = :supervisor_cd
				and	hotel_cd  = :hotel_cd
SQL;
			$a_conditions['supervisor_cd'] = $hotelSupervisorHotelData['supervisor_cd'];
			$a_conditions['hotel_cd']      = $hotelSupervisorHotelData['hotel_cd'];

			// データの取得
			$data = DB::select($s_sql, $a_conditions);

			if (count($data) > 0){
				$errorList[] = '入力した施設コードは既に登録されています';
			return $errorList;
			}
		}
	}
	
	// 統括ホテル一覧を取得
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
						'hotel_nm' => $row->hotel_nm, //直接書いて取得
						'pref_nm' => $row->pref_nm //直接書いて取得

					);
				}
			}
			return array('values' => $result);

	}
	
	/** キーで取得
	 * 
	 * @return array
	 */
	public function selectByKey($hotelCd){
		$data = $this->where(array($this->COL_HOTEL_CD=>$hotelCd))->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
			);
		}
		return [];
	}

	/** キーで削除
	 * 
	 * @param [type] $con
	 * @param [type] $hotelCd
	 * @return void
	 */
	public function deleteByKey($con, $id){
		$result = $con->table($this->table)->where(array($this->COL_ID=>$id))->delete();
		return $result;
	}

	


	//id自動採番
	public function getSequence(){
		return $this->incrementSequence('id@hotel_supervisor_hotel');
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