<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 請求先・支払先関連施設
 * 
 */
class CustomerHotel extends CommonDBModel
{
	use Traits;

	protected $table = "customer_hotel";
	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_CUSTOMER_ID = "customer_id";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colCustomerId = (new ValidationColumn())->setColumnName($this->COL_CUSTOMER_ID, "請求先・支払先ID")->length(0, 10)->intOnly();

		parent::setColumnDataArray([$colHotelCd, $colCustomerId]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_CUSTOMER_ID => $data[0]->customer_id
			);
		}
		return null;
	}

		/** 請求先・支払先施設データ
		 * as_hotel_cd 請求先・支払先施設データの施設番号
		 *
		 * @param [type] $as_hotel_cd
		 * @return array
		 */
		public function getCustomer($as_hotel_cd){
				$s_sql =<<<SQL
					select	customer.customer_id,
							customer.customer_nm
					from	customer,
						(
							select	customer_id
							from	customer_hotel
							where	hotel_cd = :hotel_cd
						) q1
					where	customer.customer_id = q1.customer_id
SQL;

			$data = DB::select($s_sql, array('hotel_cd' => $as_hotel_cd));

			$result = [];
			if(!is_null($data) && count($data) > 0){ 
				foreach($data as $row){
					$result[] = array(
						$this->COL_CUSTOMER_ID => $row->customer_id,
						'customer_nm' => $row->customer_nm
					);
				}
			}
			return array('values' => $result );
		}

}
