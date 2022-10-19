<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 施設スタッフノート
 * 
 */
class HotelStaffNote extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_staff_note";
	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_STAFF_NOTE = "staff_note";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); 
		$colStaffNote = (new ValidationColumn())->setColumnName($this->COL_STAFF_NOTE, "スタッフノート")->notHalfKana()->length(0,1000);

		parent::setColumnDataArray([$colHotelCd, $colStaffNote]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_STAFF_NOTE => $data[0]->staff_note
			);
		}
		return null;
	}

}
