<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 施設通知
 * 
 */
class HotelNotify extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_notify";

	// TODO カラム名大文字
	/**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'HOTEL_CD';

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_NOTIFY_DEVICE = "notify_device";
	public string $COL_NEPPAN_STATUS = "neppan_status";
	public string $COL_NOTIFY_STATUS = "notify_status";
	public string $COL_NOTIFY_NO = "notify_no";
	public string $COL_NOTIFY_EMAIL = "notify_email";
	public string $COL_NOTIFY_FAX = "notify_fax";
	public string $COL_FAXPR_STATUS = "faxpr_status";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana(); //TODO チェック
		$colNotifyDevice = (new ValidationColumn())->setColumnName($this->COL_notify_device, "通知媒体");//TODO チェック
		$colNeppanStatus = (new ValidationColumn())->setColumnName($this->COL_neppan_status, "ねっぱん通知ステータス");//TODO チェック
		$colNotifyStatus = (new ValidationColumn())->setColumnName($this->COL_notify_status, "通知ステータス");//TODO チェック
		$colNotifyNo = (new ValidationColumn())->setColumnName($this->COL_notify_no, "通知No");//TODO チェック
		$colNotifyEmail = (new ValidationColumn())->setColumnName($this->COL_notify_email, "通知電子メールアドレス");//TODO チェック
		$colNotifyFax = (new ValidationColumn())->setColumnName($this->COL_notify_fax, "通知ファックス番号");//TODO チェック
		$colFaxprStatus = (new ValidationColumn())->setColumnName($this->COL_faxpr_status, "FAXPR可否");//TODO チェック

		parent::setColumnDataArray([$colHotelCd, $colNotifyDevice, $colNeppanStatus, $colNotifyStatus, $colNotifyNo, $colNotifyEmail, $colNotifyFax, $colFaxprStatus]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_NOTIFY_DEVICE => $data[0]->notify_device,
				$this->COL_NEPPAN_STATUS => $data[0]->neppan_status,
				$this->COL_NOTIFY_STATUS => $data[0]->notify_status,
				$this->COL_NOTIFY_NO => $data[0]->notify_no,
				$this->COL_NOTIFY_EMAIL => $data[0]->notify_email,
				$this->COL_NOTIFY_FAX => $data[0]->notify_fax,
				$this->COL_FAXPR_STATUS => $data[0]->faxpr_status
			);
		}
		return null;
	}

}
