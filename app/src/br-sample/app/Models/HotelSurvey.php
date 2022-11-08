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
    const COL_HOTEL_CD  = "hotel_cd";
    const COL_WGS_LAT   = "wgs_lat";
    const COL_WGS_LNG   = "wgs_lng";
    const COL_WGS_LAT_D = "wgs_lat_d";
    const COL_WGS_LNG_D = "wgs_lng_d";
    const COL_TD_LAT    = "td_lat";
    const COL_TD_LNG    = "td_lng";
    const COL_TD_LAT_D  = "td_lat_d";
    const COL_TD_LNG_D  = "td_lng_d";

    /** コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colHotelCd = new ValidationColumn();
        $colHotelCd->setColumnName(self::COL_HOTEL_CD, '施設コード');
        $colWgsLat = new ValidationColumn();
        $colWgsLat->setColumnName(self::COL_WGS_LAT, '世界測地系-度分秒-緯度');
        $colWgsLng = new ValidationColumn();
        $colWgsLng->setColumnName(self::COL_WGS_LNG, '世界測地系-度分秒-経度');
        $colWgsLatD = new ValidationColumn();
        $colWgsLatD->setColumnName(self::COL_WGS_LAT_D, '世界測地系-度-緯度');
        $colWgsLngD = new ValidationColumn();
        $colWgsLngD->setColumnName(self::COL_WGS_LNG_D, '世界測地系-度-経度');
        $colTdLat = new ValidationColumn();
        $colTdLat->setColumnName(self::COL_TD_LAT, '東京測地系-度分秒-緯度');
        $colTdLng = new ValidationColumn();
        $colTdLng->setColumnName(self::COL_TD_LNG, '東京測地系-度分秒-経度');
        $colTdLatD = new ValidationColumn();
        $colTdLatD->setColumnName(self::COL_TD_LAT_D, '東京測地系-度-緯度');
        $colTdLngD = new ValidationColumn();
        $colTdLngD->setColumnName(self::COL_TD_LNG_D, '東京測地系-度-経度');

        // バリデーションルール
        // 施設コード
        $colHotelCd->require();      // 必須入力チェック
        $colHotelCd->notHalfKana();  // 半角カナチェック
        $colHotelCd->length(0, 10);  // 長さチェック

        // 世界測地系-度分秒-緯度
        $colWgsLat->notHalfKana();   // 半角カナチェック
        $colWgsLat->length(0, 16);   // 長さチェック
        $colWgsLat->require();       // 必須入力チェック

        // 世界測地系-度分秒-経度
        $colWgsLng->notHalfKana();   // 半角カナチェック
        $colWgsLng->length(0, 16);   // 長さチェック
        $colWgsLng->require();       // 必須入力チェック

        // 世界測地系-度-緯度
        $colWgsLatD->notHalfKana(); // 半角カナチェック
        $colWgsLatD->length(0, 16); // 長さチェック
        $colWgsLatD->require();     // 必須入力チェック

        // 世界測地系-度-経度
        $colWgsLngD->notHalfKana(); // 半角カナチェック
        $colWgsLngD->length(0, 16); // 長さチェック
        $colWgsLngD->require();     // 必須入力チェック

        // 東京測地系-度分秒-緯度
        $colTdLat->notHalfKana();    // 半角カナチェック
        $colTdLat->length(0, 16);    // 長さチェック
        $colTdLat->require();        // 必須入力チェック

        // 東京測地系-度分秒-経度
        $colTdLng->notHalfKana();    // 半角カナチェック
        $colTdLng->length(0, 16);    // 長さチェック
        $colTdLng->require();        // 必須入力チェック

        // 東京測地系-度-緯度
        $colTdLatD->notHalfKana();  // 半角カナチェック
        $colTdLatD->length(0, 16);  // 長さチェック
        $colTdLatD->require();      // 必須入力チェック

        // 東京測地系-度-経度
        $colTdLngD->notHalfKana();  // 半角カナチェック
        $colTdLngD->length(0, 16);  // 長さチェック
        $colTdLngD->require();      // 必須入力チェック

        parent::setColumnDataArray([
            $colHotelCd, $colWgsLat, $colWgsLng, $colWgsLatD, $colWgsLngD,
            $colTdLat  , $colTdLng , $colTdLatD, $colTdLngD ,
        ]);
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($hotelCd)
    {
        $data = $this->where(self::COL_HOTEL_CD, $hotelCd)->get();

        if (!is_null($data) && count($data) > 0) {
            return array(
                self::COL_HOTEL_CD => $data[0]->hotel_cd,
                self::COL_WGS_LAT => $data[0]->wgs_lat,
                self::COL_WGS_LNG => $data[0]->wgs_lng,
                self::COL_WGS_LAT_D => $data[0]->wgs_lat_d,
                self::COL_WGS_LNG_D => $data[0]->wgs_lng_d,
                self::COL_TD_LAT => $data[0]->td_lat,
                self::COL_TD_LAT_D => $data[0]->td_lat_d,
                self::COL_TD_LNG_D => $data[0]->td_lng_d
            );
        }
        return null;
    }

}
