<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 施設管理サイト担当者
 * 
 */
class HotelPerson extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_person";
	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_PERSON_POST = "person_post";
	public string $COL_PERSON_NM = "person_nm";
	public string $COL_PERSON_TEL = "person_tel";
	public string $COL_PERSON_FAX = "person_fax";
	public string $COL_PERSON_EMAIL = "person_email";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); //TODO 独自チェック
		$colPersonPost = (new ValidationColumn())->setColumnName($this->COL_PERSON_POST, "担当者役職");//TODO チェック
		$colPersonNm = (new ValidationColumn())->setColumnName($this->COL_PERSON_NM, "担当者名称"); //TODO チェック
		$colPersonTel = (new ValidationColumn())->setColumnName($this->COL_person_tel, "担当者電話番号"); //TODO チェック
		$colPersonFax = (new ValidationColumn())->setColumnName($this->COL_person_fax, "担当者ファックス番号"); //TODO チェック
		$colPersonEmail = (new ValidationColumn())->setColumnName($this->COL_person_email, "担当者電子メールアドレス"); //TODO チェック

		parent::setColumnDataArray([$colHotelCd, $colPersonPost, $colPersonNm, $colPersonTel, $colPersonFax, $colPersonEmail]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_PERSON_POST => $data[0]->person_post,
				$this->COL_PERSON_NM => $data[0]->person_nm,
				$this->COL_PERSON_TEL => $data[0]->person_tel,
				$this->COL_PERSON_FAX => $data[0]->person_fax,
				$this->COL_PERSON_EMAIL => $data[0]->person_email
			);
		}
		return null;
	}

}
