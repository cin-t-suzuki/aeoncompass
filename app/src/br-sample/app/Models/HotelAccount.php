<?php
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;

/** 施設認証
 * 
 */
class HotelAccount extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_account";
    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     */
    protected $primaryKey = 'hotel_cd';
    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_ACCOUNT_ID_BEGIN = "account_id_begin";
	public string $COL_ACCOUNT_ID = "account_id";
	public string $COL_PASSWORD = "password";
	public string $COL_ACCEPT_STATUS = "accept_status";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = (new ValidationColumn())->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();
		$colAccountIdBegin = (new ValidationColumn())->setColumnName($this->COL_ACCOUNT_ID_BEGIN, "入力アカウントID")->require()->length(0,10)->notHalfKana();//TODO 半角英数チェック
		$colAccountId = (new ValidationColumn())->setColumnName($this->COL_ACCOUNT_ID, "アカウントID")->require()->length(0,10)->notHalfKana(); //TODO 半角英数チェック、独自チェック
		$colPassword = (new ValidationColumn())->setColumnName($this->COL_PASSWORD, "パスワード")->require()->length(0,64)->notHalfKana();//TODO 独自チェック
		$colAcceptStatus = (new ValidationColumn())->setColumnName($this->COL_ACCEPT_STATUS, "ステータス")->require();// TODO パターンチェック必要？カラム説明
			//	0 => '利用不可',
			//	1 => '利用可'
		parent::setColumnDataArray([$colHotelCd, $colAccountIdBegin, $colAccountId, $colPassword, $colAcceptStatus]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();
		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_ACCOUNT_ID_BEGIN => $data[0]->account_id_begin,
				$this->COL_ACCOUNT_ID => $data[0]->account_id,
				$this->COL_PASSWORD => $data[0]->password,
				$this->COL_ACCEPT_STATUS => $data[0]->accept_status
			);
		}
		return null;
	}

}
