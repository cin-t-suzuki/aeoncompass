<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;
use Exception;

class HotelSupervisor extends CommonDBModel
{
	use Traits;


	/**
	 * コンストラクタ
	 */
	function __construct(){
		// TODOカラム情報の設定
	}
		
	
	// 統括一覧を取得
	protected $table = "hotel_supervisor";
	
	// カラム
	public string $COL_SUPERVISOR_CD = "supervisor_cd";
	public string $COL_SUPERVISOR_NM = "supervisor_nm";

	public function getHotelSupervisor($aa_conditions = array()){
		
		$a_conditions = array();

		$s_supervisor_cd = "";
		if (isset($aa_conditions['supervisor_cd']) && !$this->is_empty($aa_conditions['supervisor_cd'])){
			$s_supervisor_cd = "and	hotel_supervisor.supervisor_cd = :supervisor_cd";
			$a_conditions['supervisor_cd'] = $aa_conditions['supervisor_cd'];
		}

		$s_sql =	
<<<SQL
		select	q1.supervisor_cd,
				q1.supervisor_nm,
				hotel_supervisor_account.account_id,
				hotel_supervisor_account.password,
				hotel_supervisor_account.accept_status
		from	hotel_supervisor_account,
			(
				select	hotel_supervisor.supervisor_cd,
						hotel_supervisor.supervisor_nm
				from	hotel_supervisor
				where	null is null
					{$s_supervisor_cd}
			) q1
		where	hotel_supervisor_account.supervisor_cd = q1.supervisor_cd
		order by hotel_supervisor_account.accept_status desc,q1.supervisor_cd  	
SQL;

		$data = DB::select($s_sql,$a_conditions);

		$result = [];
		if(!is_null($data) && count($data) > 0){ 
			foreach($data as $row){
				$result[] = array(
					$this->COL_SUPERVISOR_CD => $row->supervisor_cd,
					$this->COL_SUPERVISOR_NM => $row->supervisor_nm,				
				);
			}
		}
		return array('values' => $result);

	}
}
?>