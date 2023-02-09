<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use App\Common\DateUtil;
use Exception;

/**
 *
 */
class Record extends CommonDBModel
{
    use Traits;

    private $o_date      = null;

    // カラム

    /** コンストラクタ
     */
    public function __construct()
    {
        // カラム情報の設定
    }


    // 実行日の設定
    public function setDateYmd($as_date_ymd = null)
    {
        // 実行年月の範囲設定
        $this->o_date = new DateUtil($as_date_ymd); //Br_Models_Date→DateUtilでいいか？
    }

    // 実行日を返す
    public function getDateYmd()
    {
        // 実行年月の範囲設定
        return $this->o_date->get();
    }

    // 指定月分の日別集計結果
    //  as_month  集計年 (string) yyyy-mm-01
    public function getDayRecords()
    {
        // 実行年月確認
        if ($this->is_empty($this->o_date)) {
            throw new Exception('実行年月を指定してください。(string) yyyy-mm-01 ');
        }
        $this->o_date->set($this->o_date->to_format('Y-m') . '-01');
        return $this->getDailyRecordsChild($this->o_date->to_format('Y-m-d'));
    }

    // 指定月分の日別集計結果
    //  as_month  集計年 (string) yyyy-mm
    public function getWeekRecords()
    {
        // 実行年確認
        if ($this->is_empty($this->o_date)) {
            throw new Exception('実行年月を指定してください。(string) yyyy-mm-01 ');
        }

        $this->o_date->set($this->o_date->to_format('Y-m') . '-01');

        return $this->getWeekRecordsChild($this->o_date->to_format('Y-m'), 3);
    }

    // 年度集計結果
    //  as_year  集計年 (string) yyyy
    public function getYearRecords()
    {
        // 実行年確認
        if ($this->is_empty($this->o_date)) {
            throw new Exception('実行年月を指定してください。(string) yyyy ');
        }

        $this->o_date->set($this->o_date->to_format('Y') . '-01-01');

        return $this->getMonthRecordsChild($this->o_date->to_format('Y-m'));
    }

    // 年度集計結果
    //  as_year  集計年 (string) yyyy
    public function getFiscalRecords()
    {
        // 実行年確認
        if ($this->is_empty($this->o_date)) {
            throw new Exception('実行年月を指定してください。(string) yyyy ');
        }

        $this->o_date->set($this->o_date->to_format('Y') . '-04-01');

        return $this->getMonthRecordsChild($this->o_date->to_format('Y-m'));
    }

    // 日別集計結果
    //  as_date_ymd  集計年月日 (string) yyyy-mm-dd
    //  an_cnt    日数   未指定の時 末日
    private function getDailyRecordsChild($as_date_ymd, $an_cnt = null)
    {
        try {
            // 実行日確認
            if ($this->is_empty($as_date_ymd)) {
                throw new Exception('実行年月日を指定してください。(string) yyyy-mm-dd ');
            }

            // 実行年月の範囲設定
            $o_date = new DateUtil($as_date_ymd); //Br_Models_Date→DateUtilでいいか？
            $s_start_ymd = $o_date->to_format('Y-m-d');

            // 終了日
            if ($this->is_empty($an_cnt)) {
                // 翌月末日取得
                $o_date->set($o_date->to_format('Y-m-') . '01');
                $o_date->add('m', 1);
                $o_date->add('d', -1);
            } else {
                $o_date->add('d', $an_cnt);
            }
            $s_end_ymd = $o_date->to_format('Y-m-d');

            //Invalid parameter number対策で×5
            $s_where1 = 'and	date_ymd between date_format(:start_ymd1, \'%Y-%m-%d\') and date_format(:end_ymd1, \'%Y-%m-%d\')'; //to_date→date_formatでいいか,\'yyyy-mm-dd\'
            $s_where2 = 'and	date_ymd between date_format(:start_ymd2, \'%Y-%m-%d\') and date_format(:end_ymd2, \'%Y-%m-%d\')';
            $s_where3 = 'and	date_ymd between date_format(:start_ymd3, \'%Y-%m-%d\') and date_format(:end_ymd3, \'%Y-%m-%d\')';
            $s_where4 = 'and	date_ymd between date_format(:start_ymd4, \'%Y-%m-%d\') and date_format(:end_ymd4, \'%Y-%m-%d\')';
            $s_where5 = 'and	date_ymd between date_format(:start_ymd5, \'%Y-%m-%d\') and date_format(:end_ymd5, \'%Y-%m-%d\')';

            $s_sql = $this->getDailySql('date_ymd', $s_where1, $s_where2, $s_where3, $s_where4, $s_where5);

            //Invalid parameter number対策で×5
            $a_conditions['start_ymd1'] = $s_start_ymd;
            $a_conditions['end_ymd1'] = $s_end_ymd;
            $a_conditions['start_ymd2'] = $s_start_ymd;
            $a_conditions['end_ymd2'] = $s_end_ymd;
            $a_conditions['start_ymd3'] = $s_start_ymd;
            $a_conditions['end_ymd3'] = $s_end_ymd;
            $a_conditions['start_ymd4'] = $s_start_ymd;
            $a_conditions['end_ymd4'] = $s_end_ymd;
            $a_conditions['start_ymd5'] = $s_start_ymd;
            $a_conditions['end_ymd5'] = $s_end_ymd;
            // データの取得
            $a_rows = DB::select($s_sql, $a_conditions);

            return [
                'values'     => $a_rows
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 月別集計結果
    //  as_month  集計年 (string) yyyy-mm
    //  an_cnt    表示月数 デフォルト 12ヶ月分
    private function getMonthRecordsChild($as_month, $an_cnt = 12)
    {
        try {
            // 実行年月確認
            if ($this->is_empty($as_month)) {
                throw new Exception('実行年月を指定してください。(string) yyyy-mm ');
            }

            // 実行年月の範囲設定
            $o_date = new DateUtil($as_month . '-01'); //Br_Models_Date→DateUtilでいいか？
            $s_start_ymd = $o_date->to_format('Y-m-d');

            // 指定月後の末日取得
            $o_date->add('m', $an_cnt);
            $o_date->add('d', -1);
            $s_end_ymd = $o_date->to_format('Y-m-d');

            $s_where = 'and	date_ymd between date_format(:start_ymd, \'%Y-%m-%d\') and date_format(:end_ymd, \'%Y-%m-%d\')'; //to_date→date_formatでいいか,\'yyyy-mm-dd\'

            // 日別SQL
            $s_sql = $this->getDailySql('date_format(date_ymd, \'%Y-%m-01\')', $s_where); //trunc(date_ymd, \'MM\')'→左記へ変更でいいか？

            // データの取得
            $a_rows = DB::select($s_sql, ['start_ymd' => $s_start_ymd, 'end_ymd' => $s_end_ymd]);

            return [
                'values'     => $a_rows
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 年度集計結果
    //  as_month  集計年 (string) yyyy-mm
    //  an_cnt    表示月数 デフォルト 3か月分
    private function getWeekRecordsChild($as_month, $an_cnt = 3)
    {
        try {
            // 実行年月確認
            if ($this->is_empty($as_month)) {
                throw new Exception('実行年月を指定してください。(string) yyyy-mm ');
            }

            // 実行年月の範囲設定
            $o_date = new DateUtil($as_month . '-01'); //Br_Models_Date→DateUtilでいいか？

            // 開始日を含む週の月曜日の日を開始日にする
            // 日曜日
            if ($o_date->to_week('n') == 1) {
                $o_date->add('d', -6);
                // 月曜日
            } elseif ($o_date->to_week('n') == 2) {
                // 開始日の変更なし
                // その他の曜日
            } else {
                $o_date->add('d', 2 - $o_date->to_week('n'));
            }
            $s_start_ymd = $o_date->to_format('Y-m-d');


            // 終了日を含む週の日曜日の日を終了日にする
            $o_date->set($as_month . '-01');
            $o_date->add('m', $an_cnt);
            $o_date->add('d', -1);
            // 日曜日
            if ($o_date->to_week('n') == 1) {
                // 終了日の変更なし;

                // その他の曜日
            } else {
                $o_date->add('d', 8 - $o_date->to_week('n'));
            }
            $s_end_ymd = $o_date->to_format('Y-m-d');

            $s_where = 'and	date_ymd between date_format(:start_ymd, \'%Y-%m-%d\') and date_format(:end_ymd, \'%Y-%m-%d\')'; //to_date→date_formatでいいか,\'yyyy-mm-dd\'
            // 月別集計SQL
            // $s_sql = $this->getDailySql('date_ymd + decode(to_char(date_ymd, \'d\'), \'1\', -6, 2 - to_number(to_char(date_ymd, \'d\')))', $s_where);
            $s_sql = $this->getDailySql('(date_ymd + interval if(date_format(date_ymd, \'%w\') = \'0\', -6, 1 - date_format(date_ymd, \'%w\')) day)', $s_where);
            //書き替えあっているか？Oracleは1~日曜、MySQLは0~日曜

            // データの取得
            $a_rows = DB::select($s_sql, ['start_ymd' => $s_start_ymd, 'end_ymd' => $s_end_ymd]);
            return [
                'values'     => $a_rows
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }


    // 日別集計ＳＱＬ分を返す
    // $as_date_ymd 日付の集計日を設定する。
    // バインド変数
    // :start_ymd (string) yyyy-mm-dd
    // :end_ymd   (string) yyyy-mm-dd
    private function getDailySql($as_date_ymd, $as_where1 = null, $as_where2 = null, $as_where3 = null, $as_where4 = null, $as_where5 = null)
    {

        // 日別集計
        $s_sql =
        <<<SQL
					select	q0.date_ymd         as date_ymd, -- to_char削除'yyyy-mm-dd'でいいか？
							q1.submit_reserve_count       as reserve_submit_reserve_count,   -- リザーブ  ：予約操作泊数
							q1.submit_cancel_count        as reserve_submit_cancel_count,    -- リザーブ  ：キャンセル操作泊数
							q1.submit_immediate_count     as reserve_submit_immediate_count, -- リザーブ  ：即日キャンセル操作泊数
							q1.stay_reserve_count         as reserve_stay_reserve_count,     -- リザーブ  ：宿泊泊数
							q1.stay_cancel_count          as reserve_stay_cancel_count,      -- リザーブ  ：宿泊キャンセル泊数
							q2.submit_reserve_count       as stream_submit_reserve_count,    -- ストリーム：予約操作泊数
							q2.submit_cancel_count        as stream_submit_cancel_count,     -- ストリーム：キャンセル操作泊数
							q2.submit_immediate_count     as stream_submit_immediate_count,  -- ストリーム：即日キャンセル操作泊数
							q2.stay_reserve_count         as stream_stay_reserve_count,      -- ストリーム：宿泊泊数
							q2.stay_cancel_count          as stream_stay_cancel_count,       -- ストリーム：宿泊キャンセル泊数
							q3.member_rsv_entry_count     as member_rsv_entry_count,         -- 会員申込数
							q3.member_rsv_commit_count    as member_rsv_commit_count,        -- 会員確定数
							q3.member_rsv_withdraw_count  as member_rsv_withdraw_count,      -- 会員退会数
							q4.member_rsv_total           as member_rsv_total,               -- 会員登録累計数
							q3.member_dash_entry_count    as member_dash_entry_count,        -- 会員申込数
							q3.member_dash_commit_count   as member_dash_commit_count,       -- 会員確定数
							q3.member_dash_withdraw_count as member_dash_withdraw_count,     -- 会員退会数
							q4.member_dash_total          as member_dash_total,              -- 会員登録累計数
							q3.member_entry_count         as member_entry_count,             -- 会員申込数
							q3.member_commit_count        as member_commit_count,            -- 会員確定数
							q3.member_withdraw_count      as member_withdraw_count,          -- 会員退会数
							q4.member_total               as member_total,                   -- 会員登録累計数
							q4.hotel_total                as hotel_total,                    -- 施設登録累計数
							q3.first_visit_count_top      as first_visit_count_top,          -- top訪問数
							q3.first_visit_count_mypage   as first_visit_count_mypage,       -- mypage訪問数
							q3.first_visit_count          as first_visit_count               -- 訪問累計数
					from	(   -- カレンダー
								select	{$as_date_ymd} as date_ymd
								from	mast_calendar
								where	null is null
									{$as_where1}
								group by {$as_date_ymd}
							) q0
                            left outer join
							( -- リザーブ 泊数
								select	{$as_date_ymd}                    as date_ymd,                -- 日付
										sum(submit_reserve_count)   as submit_reserve_count,    -- 予約操作泊数
										sum(submit_cancel_count)    as submit_cancel_count,     -- キャンセル操作泊数
										sum(submit_immediate_count) as submit_immediate_count,  -- 即日キャンセル操作泊数
										sum(stay_reserve_count)     as stay_reserve_count,      -- 宿泊泊数
										sum(stay_cancel_count)      as stay_cancel_count        -- 宿泊キャンセル泊数
								from	record_reserve
								where	null is null
									{$as_where2}
									and	system_type = 0
								group by {$as_date_ymd}
							) q1
                            on q0.date_ymd = q1.date_ymd
                            left outer join
							( -- ストリーム 泊数
								select	{$as_date_ymd}                    as date_ymd,               -- 日付
										sum(submit_reserve_count)   as submit_reserve_count,   -- 予約操作泊数
										sum(submit_cancel_count)    as submit_cancel_count,    -- キャンセル操作泊数
										sum(submit_immediate_count) as submit_immediate_count, -- 即日キャンセル操作泊数
										sum(stay_reserve_count)     as stay_reserve_count,     -- 宿泊泊数
										sum(stay_cancel_count)      as stay_cancel_count       -- 宿泊キャンセル泊数
								from	record_reserve
								where	null is null
									{$as_where3}
									and	system_type = 1
								group by {$as_date_ymd}
							) q2
                            on q0.date_ymd = q2.date_ymd
                            left outer join
							( -- その他集計
								select	{$as_date_ymd}                                         as date_ymd,                  -- 日付
										sum(member_rsv_entry_count)                            as member_rsv_entry_count,        -- 会員申込数
										sum(member_rsv_commit_count)                           as member_rsv_commit_count,       -- 会員確定数
										sum(member_rsv_withdraw_count)                         as member_rsv_withdraw_count,     -- 会員退会数
										sum(member_dash_entry_count)                           as member_dash_entry_count,        -- 会員申込数
										sum(member_dash_commit_count)                          as member_dash_commit_count,       -- 会員確定数
										sum(member_dash_withdraw_count)                        as member_dash_withdraw_count,     -- 会員退会数
										sum(member_entry_count)                                as member_entry_count,        -- 会員申込数
										sum(member_commit_count)                               as member_commit_count,       -- 会員確定数
										sum(member_withdraw_count)                             as member_withdraw_count,     -- 会員退会数
										sum(first_visit_count) - sum(first_visit_count_mypage) as first_visit_count_top,     -- top訪問数
										sum(first_visit_count_mypage)                          as first_visit_count_mypage,  -- mypage訪問数
										sum(first_visit_count)                                 as first_visit_count          -- 訪問累計数

								from	record_various
								where	null is null
									{$as_where4}
								group by {$as_date_ymd}
							) q3
                            on q0.date_ymd = q3.date_ymd
                            left outer join
							( -- 累計取得
								select	q6.date_ymd     as date_ymd,
										q5.member_total as member_total, -- 会員登録累計数
										q5.member_rsv_total as member_rsv_total, -- 会員登録累計数
										q5.member_dash_total as member_dash_total, -- 会員登録累計数
										q5.hotel_total  as hotel_total   -- 施設登録累計数
								from	record_various q5,
										(
											select	{$as_date_ymd} as date_ymd,
													max(date_ymd)   as last_ymd
											from	record_various
											where	null is null
												{$as_where5}
												and	member_total is not null
												and	hotel_total  is not null
											group by	{$as_date_ymd}
										) q6
								where	q5.date_ymd = q6.last_ymd
							) q4
                            on q0.date_ymd = q4.date_ymd
                    -- joinへの書き換えあっているか？
					-- where	q0.date_ymd = q1.date_ymd(+)
					-- 	and	q0.date_ymd = q2.date_ymd(+)
					-- 	and	q0.date_ymd = q3.date_ymd(+)
					-- 	and	q0.date_ymd = q4.date_ymd(+)
					order by q0.date_ymd
SQL;
        return $s_sql;
    }

    // 年別集計結果
    //  as_year  集計年 (string) yyyy
    public function getAllRecords()
    {
        return $this->getAllRecordsChild();
    }

    // 年別集計結果
    private function getAllRecordsChild()
    {
        try {
            $s_where = 'and	date_format(date_ymd, \'%Y-01-01\') <= date_format(now(), \'%Y-01-01\')'; //trunc(now(), \'YY\')→date_format(now(),'%Y-01-01'), \'YYYY\'か\'YY\'
            // 年別SQL
            $s_sql = $this->getDailySql('date_format(date_ymd, \'%Y-01-01\')', $s_where); // 上記同様

            // データの取得
            $a_rows = DB::select($s_sql, []);

            return [
                'values'     => $a_rows
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
