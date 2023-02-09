<?php

namespace App\Models;

use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Util\Models_Cipher;
use App\Common\Traits;

/**
 *
 */
class System extends CommonDBModel
{
    use Traits;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        // // カラム情報の設定
    }

    // 送信電子メールキュー件名一覧取得
    //
    // aa_conditions
    //
    public function getSendMailQueueSubjects($aa_conditions = [])
    {
        try {
            $s_sql =
            <<< SQL
					select	distinct
							subject
					from	send_mail_queue
					order by subject
SQL;

            return [
                'values'     => DB::select($s_sql, [])
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 送信電子メールキュー一覧取得
    //
    // aa_conditions
    //   subject     件名
    //   to_mail     宛先アドレス
    //   send_dtm    送信完了日時
    //     after  =    日付 YYYY-MM-DD HH24:MI:SS
    //     before =    日付 YYYY-MM-DD HH24:MI:SS
    public function getSendMailQueues($aa_conditions = [])
    {
        try {
            //変数の初期化
            $s_subject = '';
            $s_to_mail = '';
            $s_after_send_dtm = '';
            $s_before_send_dtm = '';

            // 件名を設定
            if (!$this->is_empty($aa_conditions['subject'])) {
                $a_conditions['subject'] = $aa_conditions['subject'];
                $s_subject = '	and	send_mail_queue.subject = :subject';
            }

            // 宛先アドレスを設定
            if (!$this->is_empty($aa_conditions['to_mail'])) {
                // $o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
                $cipher = new Models_Cipher(config('settings.cipher_key'));

                $a_conditions['to_mail'] = $cipher->encrypt($aa_conditions['to_mail']);
                $s_to_mail = '	and	send_mail_queue.to_mail = :to_mail';
            }

            // 送信完了日時を設定
            if (!$this->is_empty($aa_conditions['send_dtm']['after'])) {
                $s_after_send_dtm = "	and	send_mail_queue.send_dtm >= :after_send_dtm"; //to_date→date_formatでいいか？
                $a_conditions['after_send_dtm'] = $aa_conditions['send_dtm']['after'];
            }

            if (!$this->is_empty($aa_conditions['send_dtm']['before'])) {
                $s_before_send_dtm = "	and	send_mail_queue.send_dtm <= :before_send_dtm"; // 上記同様
                $a_conditions['before_send_dtm'] = $aa_conditions['send_dtm']['before'];
            }



            // 送信電子メールキュー
            $s_sql =
            <<< SQL
				select	mail_cd,
						subject,
						-- ↓書き替えあっているか？ to_number(to_date(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(send_dtm, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'YYYY-MM-DD'), 'YYYY-MM-DD') - to_date('1970-01-01', 'YYYY-MM-DD')) * 24 * 60 * 60 + to_number(to_char(cast(SYS_EXTRACT_UTC(to_timestamp(to_char(send_dtm, 'YYYY-MM-DD HH24:MI:SS'), 'YYYY-MM-DD HH24:MI:SS')) as date), 'SSSSS')) as send_dtm,
                        send_dtm as send_dtm,
						to_mail
				from	send_mail_queue
				where	null is null
					{$s_subject}
					{$s_to_mail}
					{$s_after_send_dtm}
					{$s_before_send_dtm}
				order by send_dtm
SQL;


            return [
                'values'     => DB::select($s_sql, $a_conditions)
            ];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }
}
