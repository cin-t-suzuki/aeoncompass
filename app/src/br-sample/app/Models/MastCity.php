<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * 市マスタ
 */
class MastCity extends CommonDBModel
{
    use Traits;

    protected $table = "mast_city";

    // カラム
    public string $COL_CITY_ID      = "city_id";
    public string $COL_PREF_ID      = "pref_id";
    public string $COL_CITY_NM      = "city_nm";
    public string $COL_PREF_CITY_NM = "pref_city_nm";
    public string $COL_ORDER_NO     = "order_no";
    public string $COL_CITY_CD      = "city_cd";
    public string $COL_DELETE_YMD   = "delete_ymd";

    // 宿泊税の特別処理のための定数
    public const CITY_ID_BEPPU = 44202;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
        $colCityId = new ValidationColumn();
        $colCityId->setColumnName($this->COL_CITY_ID, "市ID")->require()->length(0, 20)->intOnly();
        $colPrefId = new ValidationColumn();
        $colPrefId->setColumnName($this->COL_PREF_ID, "都道府県ID")->require()->length(0, 2)->intOnly();
        $colCityNm = new ValidationColumn();
        $colCityNm->setColumnName($this->COL_CITY_NM, "市名称")->require()->length(0, 20)->notHalfKana(); //TODO 独自チェック追加
        $colPrefCityNm = new ValidationColumn();
        $colPrefCityNm->setColumnName($this->COL_PREF_CITY_NM, "都道府県市名称")->require()->length(0, 25)->notHalfKana();
        $colOrderNo = new ValidationColumn();
        $colOrderNo->setColumnName($this->COL_ORDER_NO, "市表示順序")->length(0, 5)->intOnly();
        $colPrefCd = new ValidationColumn();
        $colPrefCd->setColumnName($this->COL_CITY_CD, "市コード")->length(0, 20)->notHalfKana();
        $colDeleteYmd = new ValidationColumn();
        $colDeleteYmd->setColumnName($this->COL_DELETE_YMD, "削除日")->correctDate();

        parent::setColumnDataArray([
            $colCityId, $colPrefId, $colCityNm, $colPrefCityNm, $colOrderNo,
            $colPrefCd, $colDeleteYmd
        ]);
    }

    //TODO  独自のバリデーション 使用箇所未確認のため未実装
    public function cityNmValidate()
    {
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($cityId)
    {
        $data = $this->where($this->COL_CITY_ID, $cityId)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_CITY_ID => $data[0]->city_id,
                $this->COL_CITY_NM => $data[0]->city_nm
            ];
        }
        return null;
    }

    // 市マスタを取得
    // aa_conditions
    //   pref_id 都道府県ID
    public function getMastCities($pref_id)
    {
        // 都道府県ID
        $s_pref_id = "";
        if (!$this->is_empty($pref_id)) {
            $s_pref_id .= <<<SQL
                and mast_city.pref_id = {$pref_id}
            SQL;
        }

        $s_sql = <<<SQL
            select *
            from
                mast_city
            where null is null
                {$s_pref_id}
            order by order_no
        SQL;

        $data = DB::select($s_sql);

        $result = [];
        if (!is_null($data) && count($data) > 0) {
            foreach ($data as $row) {
                $result[] = [
                    $this->COL_CITY_ID      => $row->city_id,
                    $this->COL_PREF_ID      => $row->pref_id,
                    $this->COL_CITY_NM      => $row->city_nm,
                    $this->COL_PREF_CITY_NM => $row->pref_city_nm,
                    $this->COL_ORDER_NO     => $row->order_no,
                    $this->COL_CITY_CD      => $row->city_cd,
                    $this->COL_DELETE_YMD   => $row->delete_ymd
                ];
            }
        }
        return ['values' => $result];
    }
}
