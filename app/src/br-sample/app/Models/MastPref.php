<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\Traits;

/**
 * 都道府県マスタ
 */
class MastPref extends CommonDBModel
{
    use Traits;

    protected $table = "mast_pref";

    // カラム
    public string $COL_PREF_ID = "pref_id";
    public string $COL_REGION_ID = "region_id";
    public string $COL_PREF_NM = "pref_nm";
    public string $COL_PREF_NS = "pref_ns";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_PREF_CD = "pref_cd";
    public string $COL_DELETE_YMD = "delete_ymd";

    /**
     * コンストラクタ
     */
    function __construct()
    {
        // カラム情報の設定
        $colPrefId = new ValidationColumn();
        $colPrefId->setColumnName($this->COL_PREF_ID, "都道府県ID")->require()->length(0, 2)->intOnly();
        $colRegionId = new ValidationColumn();
        $colRegionId->setColumnName($this->COL_REGION_ID, "地方ID")->require()->length(0, 2)->intOnly();
        $colPrefNm = new ValidationColumn();
        $colPrefNm->setColumnName($this->COL_PREF_NM, "都道府県名称")->require()->length(0, 5)->notHalfKana(); //TODO 独自チェック追加
        $colPrefNs = new ValidationColumn();
        $colPrefNs->setColumnName($this->COL_PREF_NS, "都道府県略称")->length(0, 3)->notHalfKana();
        $colOrderNo = new ValidationColumn();
        $colOrderNo->setColumnName($this->COL_ORDER_NO, "都道府県表示順序")->length(0, 2)->intOnly();
        $colPrefCd = new ValidationColumn();
        $colPrefCd->setColumnName($this->COL_PREF_CD, "都道府県コード")->length(0, 2);
        $colDeleteYmd = new ValidationColumn();
        $colDeleteYmd->setColumnName($this->COL_DELETE_YMD, "削除日")->correctDate();

        parent::setColumnDataArray([$colPrefId, $colRegionId, $colPrefNm, $colPrefNs, $colOrderNo, $colPrefCd, $colDeleteYmd]);
    }

    //TODO 独自のバリデーション 使用箇所未確認のため未実装
    public function pref_nm_validate()
    {
    }

    /**
     * 主キーで取得
     */
    public function selectByKey($prefId)
    {
        $data = $this->where($this->COL_PREF_ID, $prefId)->get();
        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_PREF_ID => $data[0]->pref_id,
                $this->COL_PREF_NM => $data[0]->pref_nm
            ];
        }
        return null;
    }

    // 都道府県マスタを取得
    //
    // aa_conditions
    //   region_id         地方ID
    //   area_id           エリアID
    //   pref_id           都道府県ID
    //   not_in_by_pref_id 取り除く都道府県IDを設定
    // as_order            ソートキー (pref_id, order_no)
    //
    // example
    //     get_mast_pref(['not_in_by_pref_id', ['1', '2']])
    public function getMastPrefs($aa_conditions = [], $as_order = 'pref_id')
    {
        //TODO 地方ID 引数
        $s_region_id = '';
        if (isset($aa_conditions['region_id']) && !$this->is_empty($aa_conditions['region_id'])) {
            $s_region_id = 'and mast_pref.region_id = :region_id';
        }

        //TODO エリアID oracle
        $s_area_id = '';
        if (isset($aa_conditions['area_id']) && !$this->is_empty($aa_conditions['area_id'])) {
            $s_area_id = <<<SQL
                and mast_pref.pref_id in (
                    select
                        nvl(nvl(mast_ward.pref_id, mast_city.pref_id), mast_area_match.pref_id) as pref_id
                    from
                        mast_area_match,
                        mast_city,
                        mast_ward,
                        (
                            select
                                area_id
                            from
                                mast_area
                            where
                                area_id = :area_id
                                or parent_area_id = :area_id
                        ) q1
                    where
                        mast_area_match.area_id = q1.area_id
                        and mast_area_match.city_id = mast_city.city_id(+)
                        and mast_area_match.ward_id = mast_ward.ward_id(+)
                    )
            SQL;
        }
        //TODO 都道府県
        $s_pref_id = '';
        if (isset($aa_conditions['pref_id']) && !$this->is_empty($aa_conditions['pref_id'])) {
            $s_pref_id = 'and mast_pref.pref_id = :pref_id';
        }

        //TODO 取り除く都道府県ID
        $s_not_in_by_pref_id = '';
        if (isset($aa_conditions['not_in_by_pref_id']) && !$this->is_empty($aa_conditions['not_in_by_pref_id'])) {
            $s_not_in_by_pref_id = 'and mast_pref.pref_id not in(';

            for ($n_cnt = 0; $n_cnt < count($aa_conditions['not_in_by_pref_id']); $n_cnt++) {
                $s_not_in_by_pref_id .= ':pref_id' . $n_cnt . ', ';
                $aa_conditions['pref_id' . $n_cnt] = $aa_conditions['not_in_by_pref_id'][$n_cnt];
            }

            $s_not_in_by_pref_id = substr($s_not_in_by_pref_id, 0, -2);
            $s_not_in_by_pref_id .= ')';
            unset($aa_conditions['not_in_by_pref_id']);
        }

        $s_sql = <<<SQL
            select
                mast_pref.pref_id,
                mast_pref.region_id,
                mast_pref.pref_nm,
                mast_pref.pref_ns,
                mast_pref.order_no,
                mast_pref.pref_cd,
                date_format(mast_pref.delete_ymd, '%Y-%m-%d') as delete_ymd
            from
                mast_pref
            where null is null
                {$s_region_id}
                {$s_area_id}
                {$s_pref_id}
                {$s_not_in_by_pref_id}
            order by
                mast_pref.{$as_order}
        SQL;

        $data = DB::select($s_sql);

        $result = [];
        if (!is_null($data) && count($data) > 0) { //TODO 複数件
            foreach ($data as $row) {

                $result[] = [
                    $this->COL_PREF_ID      => $row->pref_id,
                    $this->COL_REGION_ID    => $row->region_id,
                    $this->COL_PREF_NM      => $row->pref_nm,
                    $this->COL_PREF_NS      => $row->pref_ns,
                    $this->COL_ORDER_NO     => $row->order_no,
                    $this->COL_PREF_CD      => $row->pref_cd,
                    $this->COL_DELETE_YMD   => $row->delete_ymd
                ];
            }
        }
        return ['values' => $result];
    }
}
