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

}
