<?php

namespace App\Models;

use App\Common\Traits;
use App\Models\common\CommonDBModel;
use App\Models\common\ValidationColumn;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\DB;

class MemberSendingMail extends CommonDBModel
{
    use Traits;

    private $s_member_cd           = null; // 会員管理コード
    private $a_member_sending_mail = []; // お知らせメール配信拒否データ //nullだとカウントできない→[]へ変更していいか？

    protected $table = 'member_sending_mail';
    protected $primaryKey = ['member_cd', 'send_mail_type'];

    // カラム
    const COL_MEMBER_CD = 'member_cd';
    const COL_SEND_MAIL_TYPE = 'send_mail_type';


    public function __construct($as_member_cd)
    {
        $this->s_member_cd = $as_member_cd;
        $this->setMemberSendingMail(['member_cd' => $this->s_member_cd]);
        // カラム情報の設定
    }

    // お知らせメールの配信拒否データの保管
    // aa_condition
    //   member_cd       会員コード
    //   send_mail_type  メール送信タイプ
    public function setMemberSendingMail($aa_condition)
    {
        try {
            //初期化
            $s_member_cd = '';
            $s_send_mail_type = '';

            // 会員コード
            if (!$this->is_empty($aa_condition['member_cd'] ?? null)) { //null追記でいいか？
                $s_member_cd = '						and member_cd = :member_cd';
            }

            // メール送信タイプ
            if (!$this->is_empty($aa_condition['send_mail_type'] ?? null)) { //null追記でいいか？
                $s_send_mail_type = '						and send_mail_type = :send_mail_type';
            }

            $s_sql =
            <<< SQL
					select	member_cd,
							send_mail_type,
							null as `condition`
					from	member_sending_mail
					where	null is null
						{$s_member_cd}
						{$s_send_mail_type}
					order by	member_cd,
								send_mail_type
SQL;

            // データの取得
            $this->a_member_sending_mail = DB::select($s_sql, $aa_condition);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    //=====================================================================
    // メールマガジンの指定したジャンルを送信する状態に変更
    // this->_s_member_cd : 会員コード
    // as_magazine_type
    //=====================================================================
    public function acceptMagazineType($as_magazine_type)
    {
        // 送信しない状態の会員情報を取得
        for ($n_cnt = 0; $n_cnt < count($this->a_member_sending_mail); $n_cnt++) {
            if (($this->a_member_sending_mail[$n_cnt]->send_mail_type == $as_magazine_type) && ($this->a_member_sending_mail[$n_cnt]->member_cd == $this->s_member_cd)) {
                $this->a_member_sending_mail[$n_cnt]->condition = 'destroy';
            }
        }
    }

    //=====================================================================
    /// メールマガジンの指定したジャンルを送信しない状態に変更
    // this->_s_member_cd : 会員コード
    // as_magazine_type
    //=====================================================================
    public function rejectMagazineType($as_magazine_type)
    {
        $a_member_cd = [];
        for ($n_cnt = 0; $n_cnt < count($this->a_member_sending_mail); $n_cnt++) {
            if (($this->a_member_sending_mail[$n_cnt]->send_mail_type == $as_magazine_type) && ($this->a_member_sending_mail[$n_cnt]->member_cd == $this->s_member_cd)) {
                // 配信する状態の場合
                if ($this->a_member_sending_mail[$n_cnt]->condition == 'destroy') {
                    // 配信しない状態へ更新
                    $this->a_member_sending_mail[$n_cnt]->condition = 'save';
                } else {
                    // 配信しない状態確保
                    $a_member_cd[] = $this->a_member_sending_mail[$n_cnt]->member_cd;
                }
            }
        }

        // 配信しない状態になってない場合、配信しない状態のレコード追加
        if (!in_array($this->s_member_cd, $a_member_cd)) {
            $this->a_member_sending_mail[] = (object)[ //DB取得時の状態に合わせるためにオブジェクト化
                'member_cd'      => $this->s_member_cd,
                'send_mail_type' => $as_magazine_type,
                'condition'      => 'save'
            ];
        }
    }

    // お知らせメールのＤＢ更新
    public function execute()
    {
        for ($n_cnt = 0; $n_cnt < count($this->a_member_sending_mail); $n_cnt++) {
            //member_cd,send_mail_typeはコントローラーから当モデルに設定した値が上記までの処理で設定されているが、以下非表示部分は削除でいいか？
            //バリデーション別途実装要？？

            // // バリデータ
            // if (!$this->is_empty($this->a_member_sending_mail[$n_cnt]['condition'])) {

            //     // バリデート項目の設定
            //     $validations->set_validate(array('Member_Sending_Mail' => 'member_cd'));
            //     $validations->set_validate(array('Member_Sending_Mail' => 'send_mail_type'));

            // 値をセット
            $a_attributes = [
                'member_cd' => $this->a_member_sending_mail[$n_cnt]->member_cd,
                'send_mail_type' => $this->a_member_sending_mail[$n_cnt]->send_mail_type
            ];

            //     // 情報のセット
            //     $memberSendingMailModel->attributes($a_attributes);

            //     // バリデートを行う
            //     $validations->valid('Member_Sending_Mail');
            //     if (!$validations->is_valid()) {
            //         return false;
            //     }
            // }

            // // バリデートのみ行うモードのときはここで処理を中断
            // if ($this->_b_valid_only_mode) {
            //     return true;
            // }

            // 配信する：削除
            if ($this->a_member_sending_mail[$n_cnt]->condition == 'destroy') {
                // $o_member_sending_mail->destroy($a_attributes);
                // if ($this->box->item->error->has()){
                //     return false;
                // }
                //書き替えは以下でいいか？複合主キーを当ファイル上部で設定して、destroyをdeleteへ変更、結果を返す？
                $result = MemberSendingMail::where($a_attributes)->delete();
                return $result;

                // 配信しない：追加
            } elseif ($this->a_member_sending_mail[$n_cnt]->condition == 'save') {
                // 共通カラム値設定
                $this->setInsertCommonColumn($a_attributes);

                // コネクション
                $errorList = []; //初期化
                try {
                    $con = DB::connection('mysql');
                    $dbErr = $con->transaction(function () use ($con, $a_attributes) {
                        // DB更新
                        $this->insert($con, $a_attributes);
                        //insertでいいか？特有のsaveメソッドがありそう？？
                    });
                } catch (Exception $e) {
                    $errorList[] = '??の登録処理でエラーが発生しました。';
                }
                // 更新エラー
                if (
                    count($errorList) > 0 || !empty($dbErr)
                ) {
                    return false;
                }
            }
        }

        // 強制停止を解除
        $member_forced_stop_mail = new MemberForcedStopMail();
        if ($member_forced_stop_mail->selectByKey($this->s_member_cd)) {
             //destroyの戻り値はレコード数なので、削除対象がないとfalseになってしまう→上記ifを追加したが問題ないか？
            if (!$member_forced_stop_mail->destroy(['member_cd' => $this->s_member_cd])) {
                return false;
            }
        }

        return true;
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

    // メールマガジンを送信しない状態に変更
    // this->_s_member_cd : 会員コード
    public function unsendMailMagazine()
    {
        $this->rejectMagazineType('mailmagazine-v2');
    }

    // メールマガジンを送信しない状態に変更
    // this->_s_member_cd : 会員コード
    public function unsendMailMagazineWeek()
    {
        $this->rejectMagazineType('mailmagazine-v2-week');
    }

    // サンキューメールを送信しない状態に変更
    // this->_s_member_cd : 会員コード
    public function unsendMailThankyou()
    {

        $a_member_cd = [];
        for ($n_cnt = 0; $n_cnt < count($this->a_member_sending_mail); $n_cnt++) {
            if (
                ($this->a_member_sending_mail[$n_cnt]->send_mail_type == 'thankyou')
                && ($this->a_member_sending_mail[$n_cnt]->member_cd == $this->s_member_cd)
            ) {
                // 配信する状態の場合
                if ($this->a_member_sending_mail[$n_cnt]->condition == 'destroy') {
                    // 配信しない状態へ更新
                    $this->a_member_sending_mail[$n_cnt]->condition = 'save';
                    // 配信しない状態確保
                } else {
                    $a_member_cd[] = $this->a_member_sending_mail[$n_cnt]->member_cd;
                }
            }
        }

        // 配信しない状態になってない場合、配信しない状態のレコード追加
        if (!in_array($this->s_member_cd, $a_member_cd)) {
            $this->a_member_sending_mail[] = (object)[ //DB取得時の状態に合わせるためにオブジェクト化
                'member_cd'      => $this->s_member_cd,
                'send_mail_type' => 'thankyou',
                'condition'      => 'save'
            ];
        }
    }

    //  宿泊確認メールを送信しない状態に変更
    // this->_s_member_cd : 会員コード
    public function unsendMailStayconfirm()
    {

        $a_member_cd = [];
        for ($n_cnt = 0; $n_cnt < count($this->a_member_sending_mail); $n_cnt++) {
            if (
                ($this->a_member_sending_mail[$n_cnt]->send_mail_type == 'stayconfirm')
                && ($this->a_member_sending_mail[$n_cnt]->member_cd == $this->s_member_cd)
            ) {
                // 配信する状態の場合
                if ($this->a_member_sending_mail[$n_cnt]->condition == 'destroy') {
                    // 配信しない状態へ更新
                    $this->a_member_sending_mail[$n_cnt]->condition = 'save';
                    // 配信しない状態確保
                } else {
                    $a_member_cd[] = $this->a_member_sending_mail[$n_cnt]->member_cd;
                }
            }
        }

        // 配信しない状態になってない場合、配信しない状態のレコード追加
        if (!in_array($this->s_member_cd, $a_member_cd)) {
            $this->a_member_sending_mail[] = (object)[ //DB取得時の状態に合わせるためにオブジェクト化
                'member_cd'      => $this->s_member_cd,
                'send_mail_type' => 'stayconfirm',
                'condition'      => 'save'
            ];
        }
    }
}
