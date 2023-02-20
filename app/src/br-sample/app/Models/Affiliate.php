<?php

namespace App\Models;
namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * アフィリエイトマスタ
 */
class Affiliate extends CommonDBModel
{
    use Traits;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // これ自体は残さないとエラーになる
    }

    /**
     * アフィリエイト一覧の取得
     *
     * @param array{
     *  affiliater_cd?: string アフィリエイトコード
     *  program_nm?: string    プログラム名称 like
     * } $aa_conditions
     * @return array
     */
    public function getAffiliaterLists($aa_conditions = [])
    {
        try {
            $a_conditions = [];
            $s_affiliater_cd = '';
            $s_program_nm = '';

            // アフィリエイトコードを指定
            if (!($this->is_empty($aa_conditions['affiliater_cd'] ?? null))) { //??null追記でいいか？
                $s_affiliater_cd = 'and	affiliater_cd = :affiliater_cd';
                $a_conditions['affiliater_cd'] = $aa_conditions['affiliater_cd'];
            }

            // プログラム名称を指定
            if (!($this->is_empty($aa_conditions['program_nm'] ?? null))) { //??null追記でいいか？
                $s_program_nm = 'and	program_nm like :program_nm';
                $a_conditions['program_nm'] = '%' . $aa_conditions['program_nm'] . '%';
            }

            $s_sql =
            <<<SQL
					select	affiliater.affiliater_cd,
							q1.affiliate_cd,
							affiliater.affiliater_nm,
							q1.reserve_system,
							q1.program_nm,
                            q1.accept_s_dtm as accept_s_dtm,
							-- to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(q1.accept_s_dtm, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(q1.accept_s_dtm, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as accept_s_dtm,
							q1.accept_e_dtm as accept_e_dtm,
                            -- to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(q1.accept_e_dtm, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(q1.accept_e_dtm, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as accept_e_dtm,
							q1.limit_cookie,
							q1.overwrite_status,
							q1.redirect,
							q1.tag
					from	affiliater
                    left outer join
						(
							select	affiliater_cd,
									affiliate_cd,
									reserve_system,
									program_nm,
									accept_s_dtm,
									accept_e_dtm,
									limit_cookie,
									overwrite_status,
									redirect,
									tag
							from	affiliate_program
							where	null is null
								{$s_affiliater_cd}
								{$s_program_nm}
						) q1
					on	(affiliater.affiliater_cd = q1.affiliater_cd)
					order by affiliater.affiliater_cd,q1.affiliate_cd
SQL;
            // データの取得
            return [
                'values'     => DB::select($s_sql, $a_conditions)
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
