<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use App\Util\Models_Cipher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Demand extends CommonDBModel
{
    use Traits;

    // カラム

    public function __construct()
    {
        // カラム情報の設定
    }

    // ある月の締め日を取得します。
    //
    // as_ym YYYY-MM
    public function getDeadlineYmd($as_ym)
    {
        try {
            $s_sql =
            <<< SQL
					select	date_ymd as date_ymd -- 書き替えあっているか？
					from	money_schedule
					where	money_schedule_id = 1
                        and	date_format(ym, '%Y-%m') = :ym
					order by date_ymd
SQL;

            //データ取得
            $a_row = DB::select($s_sql, ['ym' => $as_ym]);

            return $a_row[0]->date_ymd ?? []; //データがない場合は空配列を返すでいいか？

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // ある月の送客日を取得します。
    //
    // as_ym YYYY-MM
    public function getSendCustomersYmd($as_ym)
    {
        try {
            $s_sql =
            <<< SQL
					select	date_ymd as date_ymd -- 書き替えあっているか？
					from	money_schedule
					where	money_schedule_id = 2
                        and	date_format(ym, '%Y-%m') = :ym
					order by date_ymd
SQL;

            //データ取得
            $a_row = DB::select($s_sql, ['ym' => $as_ym]);

            return $a_row[0]->date_ymd ?? []; //データがない場合は空配列を返すでいいか？

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


}
