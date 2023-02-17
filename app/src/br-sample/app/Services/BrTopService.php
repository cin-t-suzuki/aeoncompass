<?php

namespace App\Services;

use App\Models\SecureLicense;
use Illuminate\Support\Facades\DB;

class BrTopService
{
    /**
     * スケジュールを取得
     *
     *  aa_conditions
     * date_ym 取得する処理月 YYYY-MM
     *
     * MEMO: 移植元 public\app\ctl\models\Schedule.php get_schedules()
     *
     * @param string $ym: 取得する処理月 YYYY-MM
     * @return array
     */
    public function getSchedules($ym): array
    {
        // oracle に合わせるため、ymの文字書式で比較するよう変更

        $s_sql = <<<SQL
            select
                date_format(money_schedule.ym, '%Y-%m-%d') as ym,
                date_format(money_schedule.date_ymd, '%Y-%m-%d') as date_ymd,
                /* 
                -- MEMO: 移植元のソースを残している。
                --（移植元では、 Unix timestamp を生成しているらしい）
                CAST(
                    str_to_date(
                        date_format(
                            cast(
                                CONVERT_TZ(
                                    STR_TO_DATE(
                                        date_format(
                                            money_schedule.ym,
                                            '%Y-%m-%d %H:%i:%s'
                                        ),
                                        'YYYY-MM-DD HH24:MI:SS'
                                    ),
                                    'Asia/Tokyo',
                                    'UTC'
                                ) as datetime
                            ),
                            '%Y-%m-%d'
                        ),
                        '%Y-%m-%d'
                    )
                    - str_to_date(
                        '1970-01-01',
                        '%Y-%m-%d'
                    ) AS SIGNED
                ) * 24 * 60 * 60 -- (日) -> (秒)
                + CAST(
                    date_format(
                        cast(
                            CONVERT_TZ(
                                STR_TO_DATE(
                                    date_format(
                                        money_schedule.ym,
                                        '%Y-%m-%d %H:%i:%s'
                                    ),
                                    'YYYY-MM-DD HH24:MI:SS'
                                ),
                                'Asia/Tokyo',
                                'UTC'
                            ) as datetime
                        ),
                        '%s%sS'
                    ) AS SIGNED
                ) as ym,
                CAST(
                    str_to_date(
                        date_format(
                            cast(
                                CONVERT_TZ(
                                    STR_TO_DATE(
                                        date_format(
                                            money_schedule.date_ymd,
                                            '%Y-%m-%d %H:%i:%s'
                                        ),
                                        'YYYY-MM-DD HH24:MI:SS'
                                    ),
                                    'Asia/Tokyo',
                                    'UTC'
                                ) as datetime
                            ),
                            '%Y-%m-%d'
                        ),
                        '%Y-%m-%d'
                    )
                    - str_to_date(
                        '1970-01-01',
                        '%Y-%m-%d'
                    ) AS SIGNED
                ) * 24 * 60 * 60
                + CAST(
                    date_format(
                        cast(
                            CONVERT_TZ(
                                STR_TO_DATE(
                                    date_format(
                                        money_schedule.date_ymd,
                                        '%Y-%m-%d %H:%i:%s'
                                    ),
                                    'YYYY-MM-DD HH24:MI:SS'
                                ),
                                'Asia/Tokyo',
                                'UTC'
                            ) as datetime
                        ),
                        '%s%sS'
                    ) AS SIGNED
                ) as date_ymd,
                */
                q1.id as money_schedule_id,
                q1.schedule_nm
            from
                money_schedule
                right outer join (
                    select
                        id,
                        schedule_nm
                    from
                        mast_money_schedule
                ) q1
                    on money_schedule.money_schedule_id = q1.id
                        and date_format(money_schedule.ym, '%Y-%m') = :ym
            order by
                money_schedule.date_ymd
        SQL;

        $results = DB::select($s_sql, ['ym' => $ym]);
        return $results;
    }

    /**
     * 申請者に許可されているライセンス一覧の取得
     *
     * MEMO: 移植元 public\app\ctl\models\License.php get_applicant_license()
     *
     * @param string $applicantStaffId
     * @return array
     */
    public function getApplicantLicense($applicantStaffId): array
    {
        $sql = <<<SQL
            select
                license_token
            from
                secure_license
            where
                license_status = :license_status
                and applicant_staff_id = :applicant_staff_id
                and secure_license.license_limit_dtm > now()
        SQL;

        $licenses = DB::select($sql, [
            'license_status' => SecureLicense::LICENSE_STATUS_VALID,
            'applicant_staff_id' => $applicantStaffId,
        ]);

        // MEMO: 復号が必要であればコメントイン
        // $cipher = new \App\Util\Models_Cipher(config('settings.cipher_key'));
        // foreach ($licenses as $key => $value) {
        //     $licenses[$key]->license_token = $cipher->decrypt($value->license_token);
        // }

        return $licenses;
    }
}
