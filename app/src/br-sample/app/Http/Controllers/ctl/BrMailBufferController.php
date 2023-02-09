<?php

namespace App\Http\Controllers\ctl;

use App\Models\System;
use App\Models\SendMailQueue;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Common\DateUtil;
use App\Common\Traits;

class BrMailBufferController extends _commonController
{
    use Traits;

    // MAIL_BUFFER一覧
    public function search(Request $request)
    {
        $a_search = $request->input('Search') ?? [];

        // System モデルを生成
        $systemModel = new System();

        // 送信電子メールキュー件名(タイトル)一覧取得
        $a_send_mail_queue_subjects = $systemModel->getSendMailQueueSubjects();

        $a_send_mail_queues = []; //初期化は配列でいいか？
        $errors = [];

        // 検索条件が存在すれば
        if (!$this->is_empty($a_search)) { //zap_is_empty→is_emptyでいいか？0の扱いが違うようだが、元ソース使用しないになっている
            // 検索条件の送信日の整形
            $o_after_date  = new DateUtil($a_search['send_dtm_after']); //Br_Models_Date→DateUtilでいいか？
            $o_before_date = new DateUtil($a_search['send_dtm_before']); //同様

            // 23:59:59の設定
            $o_before_date->add('h', 23);
            $o_before_date->add('i', 59);
            $o_before_date->add('s', 59);

            // 検索条件の設定
            $a_conditions['subject']            = $a_search['subject'];
            $a_conditions['to_mail']            = $a_search['to_mail'];
            $a_conditions['send_dtm']['before'] = $o_before_date->to_format('Y-m-d H:i:s');
            $a_conditions['send_dtm']['after']  = $o_after_date->to_format('Y-m-d H:i:s');

            // 送信日チェック
            if ($o_before_date->get() > $o_after_date->get()) {
                // 送信電子メールキュー一覧取得
                $a_send_mail_queues = $systemModel->getSendMailQueues($a_conditions);

                // データが存在しない場合
                if (count($a_send_mail_queues['values']) == 0) {
                    //改行はエスケープされるため、3つに分けて代入
                    $errors[] = 'データが見つかりません。';
                    $errors[] = '入力された条件に該当するデータが見つかりませんでした。';
                    $errors[] = '条件を見直して、再度、検索してください。';
                }
            } else {
                $errors[] = '送信日を正しく入力してください。';
            }
        }

        return view('ctl.brMailBuffer.search', [
            'send_mail_queues' => $a_send_mail_queues,
            'send_mail_queue_subjects' => $a_send_mail_queue_subjects,
            'search' => $a_search,

            'errors' => $errors
        ]);
    }

    // 送信内容(詳細)
    public function show(Request $request)
    {
        $sendMailQuereModel = new SendMailQueue();

        $a_send_mail_queue = $sendMailQuereModel->selectByWKey(
            $request->input('mail_cd'),
            date('Y-m-d H:i:s', strtotime($request->input('send_dtm'))),
        );

        // 表示用フラグ
        $b_disp = true;

        //ガイド＆エラーメッセージの初期化
        $errors = [];
        $guides = [];

        // データが存在しない場合
        if (count($a_send_mail_queue) == 0) {
            $errors[] = 'データが見つかりません。';
            // 非表示へ
            $b_disp = false;
        } elseif ($a_send_mail_queue['cipher'] == 1) {
            // 本文が暗号化されている場合は表示しない。　0:非暗号化 1:暗号化
            $errors[] = '個人保護法の為、表示できません。';
            // 非表示へ
            if (config('app.env') == 'product') {
                $b_disp = false;
            } else {
                $guides[] = '検証環境なので、表示します。';
                //↓書き替えあっているか？ $o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $s_contents = $cipher->decrypt($a_send_mail_queue['contents']);
                $a_send_mail_queue['contents'] = $s_contents;
            }
        }

        return view('ctl.brMailBuffer.show', [
            'send_mail_queue' => $a_send_mail_queue,
            'disp' => $b_disp,

            'guides' => $guides,
            'errors' => $errors
        ]);
    }
}
