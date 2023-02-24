<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Charge extends CommonDBModel
{
    use Traits;

    public function __construct()
    {
        // カラム情報の設定
    }

    // キャッシュ用変数
    private $n_tax_rate = 0;    // 消費税

    /**
     * 消費税を取得します。
     *
     * @param string $accept_s_ymd
     * 開始日 YYYY-MM-DD
     * @return array
    */
    public function getTaxRate($as_accept_s_ymd = null)
    {
        try {
            if (!($this->is_empty($this->_n_tax_rate)) && $this->n_tax_rate != 0 && $this->is_empty($as_accept_s_ymd)) {
                return [
                    'values'     => $this->n_tax_rate,
                ];
            }


            // 開始日
            if (!$this->is_empty($as_accept_s_ymd)) {
                $s_accept_s_ymd = "	and	accept_s_ymd <= to_date(:accept_s_ymd, 'YYYY-MM-DD')";
                $a_conditions['accept_s_ymd'] = $as_accept_s_ymd;
            } else {
                $s_accept_s_ymd = "	and	accept_s_ymd <= trunc(sysdate, 'DD')";
                $a_conditions = array();
            }

            $s_sql =
            <<<SQL
					select	tax
					from	mast_tax
					where	accept_s_ymd = (
												select	max(accept_s_ymd)
												from	mast_tax
												where	null is null
													{$s_accept_s_ymd}
											)
SQL;

            // データの取得
            $a_row = DB::select($s_sql, $a_conditions);

            $this->n_tax_rate = ($a_row[0]['tax'] ?? 0);

            return [
                'values'     => $this->_n_tax_rate,
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
