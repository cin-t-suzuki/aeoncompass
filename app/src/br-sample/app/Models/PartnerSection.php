<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Util\Models_Cipher;
use Exception;
use App\Common\Traits;

/**
 * パートナーセクションマスタ
 */
class PartnerSection extends CommonDBModel
{
    use Traits;

    protected $table = "partner_section";

    // カラム
    public string $COL_PARTNER_CD = "partner_cd";
    public string $COL_SECTION_ID = "section_id";
    public string $COL_SECTION_NM = "section_nm";
    public string $COL_ORDER_NO = "order_no";
    public string $COL_ENTRY_CD = "entry_cd";
    public string $COL_ENTRY_TS = "entry_ts";
    public string $COL_MODIFY_CD = "modify_cd";
    public string $COL_MODIFY_TS = "modify_ts";




    /**
     * コンストラクタ　書き換え前
     */
    public function __construct() //function→public functionでいいか？（phpcs赤エラー）
    {
        // // カラム情報の設定
        $colPartnerCd = (new ValidationColumn())->setColumnName($this->COL_PARTNER_CD, "提携先コード")
            ->require()->length(0, 10)->notHalfKana();
        $colSectionId = (new ValidationColumn())->setColumnName($this->COL_SECTION_ID, "所属団体ID")
            ->require()->length(0, 2)->intOnly();
        $colSectionNm = (new ValidationColumn())->setColumnName($this->COL_SECTION_NM, "所属団体名称")
            ->require()->length(0, 32)->notHalfKana();
        $colOrderNo = (new ValidationColumn())->setColumnName($this->COL_ORDER_NO, "表示順序")
            ->length(0, 10)->intOnly();
        parent::setColumnDataArray([$colPartnerCd, $colSectionId, $colSectionNm, $colOrderNo]);
    }

    //======================================================================
    // 所属団体一覧の取得
    //======================================================================
    public function getSectionList($request_params) //trycatch一旦削除したがOK？引数追加
    {
        // 初期化
        $s_sql        = '';
        $a_conditions = [];
        $a_result     = [];

        $s_sql =
            <<< SQL
				select	partner_cd,
						section_id,
						section_nm,
						order_no
				from	partner_section
				where	partner_cd = :partner_cd
				order by	order_no
SQL;
        $a_conditions['partner_cd'] = $request_params['partner_cd'];
        $a_result = DB::select($s_sql, $a_conditions);

        return $a_result;
    }

    //======================================================================
    //
    //======================================================================
    public function searchParams($request_params)
    {
        // 検索用パラメータ設定（パートナー管理に戻った時用）
        $search_params = $this->setSearchParams($request_params);
        return $search_params;
    }

    //======================================================================
    // 検索用パラメータ設定（パートナー管理に戻った時用）
    //   ※リクエストから検索に使用するパラメータだけを抽出して保持する
    //======================================================================
    private function setSearchParams($request_params)
    {
        // 初期化
        $_a_search_params = [];
        $_s_search_params = '';

        // 提携先コード
        if (!$this->is_empty($request_params['search_partner_cd'] ?? null)) { //??null追加でいいか（下記同様）
            $_a_search_params['search_partner_cd'] = $request_params['search_partner_cd'];
        }

        // 提携先名称
        if (!$this->is_empty($request_params['search_partner_nm'] ?? null)) {
            $_a_search_params['search_partner_nm'] = $request_params['search_partner_nm'];
        }

        // 接続形態
        if (!$this->is_empty($request_params['search_connect_cls'] ?? null)) {
            $_a_search_params['search_connect_cls'] = $request_params['search_connect_cls'];
        }

        // 接続形態詳細
        if (!$this->is_empty($request_params['search_connect_type'] ?? null)) {
            $_a_search_params['search_connect_type'] = $request_params['search_connect_type'];
        }

        // 表示項目
        for ($ii = 1; $ii <= 5; $ii++) {
            if (!$this->is_empty($request_params['search_partner_disply_' . $ii] ?? null)) {
                $_a_search_params['search_partner_disply_' . $ii] = $request_params['search_partner_disply_' . $ii];
            }
        }

        // 検索用パラメータの指定があればURI形式も作成
        foreach ($_a_search_params as $key => $value) {
            $_s_search_params .= '/' . $key . '/' . $value;
        }

        return $_a_search_params; //TODO sの方渡せていない
    }

    /**  新規登録
     *
     * @param [type] $con
     * @param [type] $data
     * @return
     */
    public function insert($con, $data)
    {
        $result = $con->table($this->table)->insert($data);
        return  $result;
    }

    /**  キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByKey($con, $data)
    {
        $result = $con->table($this->table)->where($this->COL_PARTNER_CD, $data[$this->COL_PARTNER_CD])->update($data);
        return  $result;
    }
    /**  複合主キーで更新
     *
     * @param [type] $con
     * @param [type] $data
     * @return エラーメッセージ
     */
    public function updateByWKey($con, $partnerSectionData)
    {
        $result = $con->table($this->table)->where([
                $this->COL_PARTNER_CD => $partnerSectionData['partner_cd'],
                $this->COL_SECTION_ID => $partnerSectionData['section_id']
            ])->update($partnerSectionData);
        return  $result;
    }

    /** キーで削除
     *
     * @param [type] $con
     * @param [type] $hotelCd
     * @param [type] $branchNo
     * @return void
     */
    public function deleteByKey($con, $a_partner_section_work)
    {
        $result = $con->table($this->table)->where([
                $this->COL_PARTNER_CD => $a_partner_section_work['partner_cd'],
                $this->COL_SECTION_ID => $a_partner_section_work['section_id']
            ])->delete();
        return $result;
    }

    //パートナーCDでの提携先所属団体情報取得
    public function selectByKey($partnerCd)
    {
        $data = $this->where([$this->COL_PARTNER_CD => $partnerCd])->get();

        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_PARTNER_CD => $data[0]->partner_cd,
                $this->COL_SECTION_ID => $data[0]->section_id,
                $this->COL_SECTION_NM => $data[0]->section_nm,
                $this->COL_ORDER_NO => $data[0]->order_no,
                $this->COL_ENTRY_CD => $data[0]->entry_cd,
                $this->COL_ENTRY_TS => $data[0]->entry_ts,
                $this->COL_MODIFY_CD => $data[0]->modify_cd,
                $this->COL_MODIFY_TS => $data[0]->modify_ts,
            ];
        }
        return [];
    }
    //パートナーCDとセクションIDでの提携先所属団体情報取得
    public function selectByWKey($a_partner_section_work)
    {
        $data = $this->where([
                $this->COL_PARTNER_CD => $a_partner_section_work['partner_cd'],
                $this->COL_SECTION_ID => $a_partner_section_work['section_id']
            ])->get();

        if (!is_null($data) && count($data) > 0) {
            return [
                $this->COL_PARTNER_CD => $data[0]->partner_cd,
                $this->COL_SECTION_ID => $data[0]->section_id,
                $this->COL_SECTION_NM => $data[0]->section_nm,
                $this->COL_ORDER_NO => $data[0]->order_no,
                $this->COL_ENTRY_CD => $data[0]->entry_cd,
                $this->COL_ENTRY_TS => $data[0]->entry_ts,
                $this->COL_MODIFY_CD => $data[0]->modify_cd,
                $this->COL_MODIFY_TS => $data[0]->modify_ts,
            ];
        }
        return [];
    }

    //======================================================================
    // 所属団体IDの取得(登録用)
    //======================================================================
    public function getSectionIdNext($_a_request_params)
    {
        try {
            // 初期化
            $s_sql        = '';
            $a_conditions = [];
            $a_result     = [];
            $n_section_id = null;

            $s_sql =
                <<< SQL
				select	max(section_id) as section_id
				from	partner_section
				where	partner_cd = :partner_cd
SQL;
            $a_conditions['partner_cd'] = $_a_request_params['partner_cd'];
            $a_result = DB::select($s_sql, $a_conditions);
            $n_section_id = (int)($a_result[0]->section_id ?? 0); //(int),nvlの書き換え合っている？
            $n_section_id++;

            return $n_section_id;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //======================================================================
    // 表示順の取得(登録用)
    //======================================================================
    public function getOrderNoNext($_a_request_params)
    {
        try {
            // 初期化
            $s_sql        = '';
            $a_conditions = [];
            $a_result     = [];
            $n_order_no   = null;

            $s_sql =
                <<< SQL
				select	max(order_no) as order_no
				from	partner_section
				where	partner_cd = :partner_cd
SQL;
            $a_conditions['partner_cd'] = $_a_request_params['partner_cd'];
            $a_result = DB::select($s_sql, $a_conditions);
            $n_order_no = (int)($a_result[0]->order_no ?? 0);
            $n_order_no++;

            return $n_order_no;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
