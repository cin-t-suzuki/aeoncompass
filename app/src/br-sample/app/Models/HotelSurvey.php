<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;


/** 
 * 施設測地
 * 東京測地系と正解測地系の緯度経度。
 * 景サイン式に使用する場合は、度表記を使用。
 */
class HotelSurvey extends CommonDBModel
{
	use Traits;

	protected $table = "hotel_survey";

    /**
     * テーブルに関連付ける主キー
     *
     * @var string
     *
     * (site_cd, accept_s_ymd, fee_type, stock_class) で PK になっているが、
     * Laravel では複合キーに対応していない
     */
    protected $primaryKey = 'hotel_cd';

    /**
     * モデルのIDを自動増分するか
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * モデルにタイムスタンプを付けるか
     *
     * MEMO: 独自実装でタイムスタンプを設定しているため、Laravel 側では設定しない。
     * HACK: (工数次第) Laravel の機能を使ったほうがよい気もする。
     *
     * @var bool
     */
    public $timestamps = false;
    const CREATED_AT = 'entry_ts';
    const UPDATED_AT = 'modify_ts';


	// カラム
	public string $COL_HOTEL_CD = "hotel_cd";
	public string $COL_WGS_LAT = "wgs_lat";
	public string $COL_WGS_LNG = "wgs_lng";
	public string $COL_WGS_LAT_D = "wgs_lat_d";
	public string $COL_WGS_LNG_D = "wgs_lng_d";
	public string $COL_TD_LAT = "td_lat";
	public string $COL_TD_LNG = "td_lng";
	public string $COL_TD_LAT_D = "td_lat_d";
	public string $COL_TD_LNG_D = "td_lng_d";

	/** コンストラクタ
	 */
	function __construct(){
		// カラム情報の設定
		$colHotelCd = new ValidationColumn();
		$colHotelCd->setColumnName($this->COL_HOTEL_CD, "施設コード")->require()->length(0, 10)->notHalfKana();

		//TODO 登録機能を作るときに項目のバリデーション作成

		parent::setColumnDataArray([$colHotelCd]);
	}

	/** 主キーで取得
	 */
	public function selectByKey($hotelCd){
		$data = $this->where($this->COL_HOTEL_CD, $hotelCd)->get();

		if(!is_null($data) && count($data) > 0){
			return array(
				$this->COL_HOTEL_CD => $data[0]->hotel_cd,
				$this->COL_WGS_LAT => $data[0]->wgs_lat,
				$this->COL_WGS_LNG => $data[0]->wgs_lng,
				$this->COL_WGS_LAT_D => $data[0]->wgs_lat_d,
				$this->COL_WGS_LNG_D => $data[0]->wgs_lng_d,
				$this->COL_TD_LAT => $data[0]->td_lat,
				$this->COL_TD_LAT_D => $data[0]->td_lat_d,
				$this->COL_TD_LNG_D => $data[0]->td_lng_d
			);
		}
		return null;

	}

}
