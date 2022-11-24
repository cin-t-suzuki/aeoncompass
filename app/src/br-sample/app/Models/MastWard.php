<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * 都道府県マスタ
 */
class MastWard extends CommonDBModel
{
    use Traits;

    protected $table = "mast_ward";
    // カラム
    public string $COL_WARD_ID              = "ward_id";
    public string $COL_PREF_ID              = "pref_id";
    public string $COL_CITY_ID              = "city_id";
    public string $COL_WARD_CD              = "ward_cd";
    public string $COL_WARD_NM              = "ward_nm";
    public string $COL_CITY_WARD_NM         = "city_ward_nm";
    public string $COL_PREF_CITY_WARD_NM    = "pref_city_ward_nm";
    public string $COL_ORDER_NO             = "order_no";
    public string $COL_DELETE_YMD           = "delete_ymd";

    /**
     * コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colWardId = new ValidationColumn();
        $colWardId->setColumnName($this->COL_WARD_ID, "区ID")->require()->length(0, 20)->intOnly();
        $colPrefId = new ValidationColumn();
        $colPrefId->setColumnName($this->COL_PREF_ID, "都道府県ID")->require()->length(0, 2)->intOnly();
        $colCityId = new ValidationColumn();
        $colCityId->setColumnName($this->COL_CITY_ID, "市ID")->require()->length(0, 20)->intOnly();
        $colWardCd = new ValidationColumn();
        $colWardCd->setColumnName($this->COL_WARD_CD, "区コード")->length(0, 50)->notHalfKana();
        $colWardNm = new ValidationColumn();
        $colWardNm->setColumnName($this->COL_WARD_NM, "区名称")->require()->length(0, 20)->notHalfKana(); //TODO 独自チェック追加
        $colCityWardNm = new ValidationColumn();
        $colCityWardNm->setColumnName($this->COL_CITY_WARD_NM, "市区名称")->require()->length(0, 25)->notHalfKana();
        $colPrefCityWardNm = new ValidationColumn();
        $colPrefCityWardNm->setColumnName($this->COL_PREF_CITY_WARD_NM, "都道府県市区名称")->require()->length(0, 50)->notHalfKana();
        $colOrderNo = new ValidationColumn();
        $colOrderNo->setColumnName($this->COL_ORDER_NO, "区表示順序")->length(0, 5)->intOnly();
        $colDeleteYmd = new ValidationColumn();
        $colDeleteYmd->setColumnName($this->COL_DELETE_YMD, "削除日")->correctDate();

        parent::setColumnDataArray([$colWardId, $colPrefId, $colCityId, $colWardCd, $colWardNm, $colCityWardNm, $colPrefCityWardNm, $colOrderNo, $colDeleteYmd]);
    }

    //TODO 独自のバリデーション 使用箇所があれば実装
    public function pref_nm_validate()
    {
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($wardId)
    {
        $data = $this->where($this->COL_WARD_ID, $wardId)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_WARD_ID => $data[0]->ward_id,
                $this->COL_WARD_NM => $data[0]->ward_nm
            ];
        }
        return null;
    }

    // 区マスタを取得
    // aa_conditions
    //   city_id 市ID
    public function getMastWards($city_id)
    {
        // 市ID
        $s_city_id = "";
        if (!$this->is_empty($city_id)) {
            $s_city_id .= <<<SQL
                and mast_ward.city_id = {$city_id}
            SQL;
        }

        $s_sql = <<<SQL
            select *
            from
                mast_ward
            where null is null
                {$s_city_id}
            order by
                order_no
        SQL;

        $data = DB::select($s_sql);

        $result = [];
        if (!is_null($data) && count($data) > 0) {
            foreach ($data as $row) {

                $result[] = [
                    $this->COL_WARD_ID              => $row->ward_id,
                    $this->COL_PREF_ID              => $row->pref_id,
                    $this->COL_CITY_ID              => $row->city_id,
                    $this->COL_WARD_CD              => $row->ward_cd,
                    $this->COL_WARD_NM              => $row->ward_nm,
                    $this->COL_CITY_WARD_NM         => $row->city_ward_nm,
                    $this->COL_PREF_CITY_WARD_NM    => $row->pref_city_ward_nm,
                    $this->COL_ORDER_NO             => $row->order_no,
                    $this->COL_DELETE_YMD           => $row->delete_ymd
                ];
            }
        }
        return ['values' => $result];
    }
}
