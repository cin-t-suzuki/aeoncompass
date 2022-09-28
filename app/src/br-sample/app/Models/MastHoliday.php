<?php

namespace App\Models;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/**
 * 祝祭日マスタ
 */
class MastHoliday extends CommonDBModel
{
	protected $table = "mast_holiday";
	// カラム
	public string $COL_HOLIDAY = "holiday";
	public string $COL_HOLIDAY_NM = "holiday_nm";

	/**
	 * コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHoliday = new ValidationColumn();
		$colHoliday->setColumnName($this->COL_HOLIDAY, "祝祭日")->require()->correctDate();
		$colHolidayNm = new ValidationColumn();
		$colHolidayNm->setColumnName($this->COL_HOLIDAY_NM, "祝祭日名称")->notHalfKana()->length(0, 64);
		parent::setColumnDataArray([$colHoliday, $colHolidayNm]);
	}

	/* 祝日を判断します。
	 * $date = Y-m-d
	 * is_holiday
	 */
	public function isHoliday($arrayDate)
	{
		$date=$arrayDate['holiday'];
		$s_sql = <<<SQL
			SELECT  holiday, holiday_nm
			FROM    mast_holiday
			WHERE DATE_FORMAT (holiday , '%Y-%m-%d') = '{$date}'
			SQL;

		$data = DB::select($s_sql);

		if( empty($data) || count($data) <= 0 ){
			$data = array();
		}else{
			return $data[0];//data[0]->holiday_nm で取得
		}
	}


}
