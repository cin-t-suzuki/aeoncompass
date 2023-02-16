<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Date;
use App\Models\System;
use App\Common\Traits;
use App\Models\HotelPerson;
use App\Models\Customer;
use App\Models\HotelControl;
use App\Models\ConfirmHotelPerson;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Util\Models_Cipher;
use App\Http\Requests\HtlTopRequest;

class HtlTopController extends _commonController
{
    use Traits;

    // 担当者確認ポップアップ表示間隔 =>三か月毎
    private $confirm_span = '-3 month';

    /**
     * インデックス
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(HTlTopRequest $request)
    {
        $target_cd = $request->input('target_cd');

        // Hotel モデルを生成
        $hotel = new Hotel();
        $hotel->setHotelCd($target_cd);

        // お知らせを取得
        $a_twitters = $hotel->getTwitters();

        // おしらせ説明部分表示
        $models_System = new System();
        $a_broadcast_messages = $models_System->getBroadcastMessages($target_cd);

        $hotel_control = new HotelControl();
        $a_hotel_control = $hotel_control->selectByKey(['hotel_cd' => $target_cd]);

        $is_open_adjournment_ctl = $hotel->isOpenAdjournmentCtl($target_cd);

        //-------------------------------
        // 担当者情報の更新確認
        //-------------------------------

        // リクエストパラメータの取得
        $a_hotel_person   = $request->input('Hotel_Person');
        $a_customer = $request->input('customer');

        $o_hotel_person  = new HotelPerson();
        $m_customer  = new Customer();

        // 施設担当者情報取得
        if ($this->is_empty($a_hotel_person)) {
            $a_hotel_person  = $o_hotel_person->selectByKey(['hotel_cd' => $target_cd]);

            // メールアドレス復号
            if (!is_null($a_hotel_person['person_email'] ?? null)) {
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $a_hotel_person['person_email'] = $cipher->decrypt($a_hotel_person['person_email']);
            }
        }
        //精算先担当者情報取得
        if ($this->is_empty($a_customer)) {
            $a_customer_hotel = $m_customer->getCustomer($target_cd);
            $a_customer = $m_customer->find(['customer_id' => $a_customer_hotel["values"][0]->customer_id ?? null]); //null追記でいいか

            // メールアドレス復号
            if (!is_null($a_customer['email'] ?? null)) {
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $a_customer['email'] = $cipher->decrypt($a_customer['email']);
            }
        }

        // 担当者確認ポップアップ表示制御
        if ($this->isSupportTime()) {
            //  営業時間 ポップアップ表示判定をする
            $a_confirm_hotel_person = $this->isConfirmHotelPerson($target_cd, $a_hotel_control['stock_type'] ?? null); //null追記でいいか
            $b_confirm_hotel_person_force
                = $this->isConfirmHotelPersonForce($a_hotel_person, $a_customer, $a_hotel_control['stock_type'] ?? null); //null追記でいいか


            $confirm_hotel_person_force = $b_confirm_hotel_person_force;// 変更強制フラグ
        } else {
            //  営業時間外はポップアップを抑止する
            $a_confirm_hotel_person = [
                'confirm_dtm_check'        => 0,
                'hotel_person_email_check' => 0,
                'customer_email_check'     => 0,
            ];

            $confirm_hotel_person_force = false;// 非強制
        }

        // ビューを表示
        return view('ctl.htlTop.index', [
            'is_open_adjournment_ctl' => $is_open_adjournment_ctl,
            'target_cd' => $target_cd,
            'twitters' => $a_twitters,// お知らせ
            'broadcast_messages' => $a_broadcast_messages,// おしらせ説明文
            'stock_type' => $a_hotel_control['stock_type'] ?? 0, //a_hotel_controlがnullだった場合、0でいいか？

            'hotel_person'  => $a_hotel_person,// 施設担当者情報
            'customer'      => $a_customer,// 精算先担当者情報

            'a_confirm_hotel_person' => $a_confirm_hotel_person, //確認用フラグ
            'confirm_hotel_person_force' => $confirm_hotel_person_force,
            'is_disp_rate_info' => false,
        ]);
    }

    /**
     * サポート営業時間を判定する
     *
     * @return bool
     * true  :営業時間
     * false :営業時間外
     *
     */
    private function isSupportTime()
    {

        $o_models_date = new DateUtil();

        if (
            $o_models_date->to_week('e') == 'Sat'
            || $o_models_date->to_week('e') == 'Sun'
            || $o_models_date->is_holiday()
        ) {
            return false;
        }

        if (
            strtotime('9:30:00') < strtotime($o_models_date->to_format('H:i:s'))
            && strtotime('18:30:00') > strtotime($o_models_date->to_format('H:i:s'))
        ) {
            // 営業時間
            return true;
        } else {
            //営業時間外
            return false;
        }
        return false;
    }

    /**
     * 施設担当者情報の更新チェック判定 空欄あり入力強制する場合
     *
     * @return bool
     * true  :ポップアップを表示する
     * false :ポップアップを表示する
     *
     */
    private function isConfirmHotelPersonForce($a_hotel_person, $a_customer, $stock_type)
    {
        // TODO ： スタッフかどうかの判定ができるようになったら実装
        // // 施設スタッフ以外（社内スタッフ）の場合は対象外
        // if ($this->box->user->operator->is_staff()) {
        //     return false;
        // }

        // 0:受託販売以外は対象外
        if ($stock_type  != 0) {
            return false;
        }

        // 名前、電話番号、メールアドレスいずれかに空欄がある場合は、変更画面を強制する。
        if (
            $this->is_empty($a_hotel_person['person_nm'] ?? null)// null追記
            || $this->is_empty($a_hotel_person['person_tel'] ?? null)
            || $this->is_empty($a_hotel_person['person_email'] ?? null)
            || $this->is_empty($a_customer['person_nm'] ?? null)
            || $this->is_empty($a_customer['tel'] ?? null)
            || $this->is_empty($a_customer['email'] ?? null)
        ) {
            return true;
        }
        return false;
    }

    /**
     * 施設担当者情報の更新チェック判定
     *
     * @param string $target_cd
     * @param string $stock_type
     * @return array
     */
    private function isConfirmHotelPerson($target_cd, $stock_type)
    {

        $result_confirm = [
            'confirm_dtm_check'        => 0,
            'hotel_person_email_check' => 0,
            'customer_email_check'     => 0,
        ];

        // TODO ： スタッフかどうかの判定ができるようになったら実装
        // 施設スタッフ以外（社内スタッフ）の場合は対象外
        // if ($this->box->user->operator->is_staff()) {
        //     return $result_confirm;
        // }

        // 0:受託販売以外は対象外
        if ($stock_type  != 0) {
            return $result_confirm;
        }

        $confirmHotelPersonModel = new ConfirmHotelPerson();
        $a_confirm_hotel_person = $confirmHotelPersonModel->selectByKey(['hotel_cd' => $target_cd]);

        // 初回の為、対象外
        if (!$a_confirm_hotel_person) {
            $requestConfirmHotelPerson['hotel_cd'] = $target_cd;
            // 'confirm_dtm'  => 'sysdate',
            $requestConfirmHotelPerson['confirm_dtm'] = now();
            $requestConfirmHotelPerson['hotel_person_email_check'] = 0;
            $requestConfirmHotelPerson['customer_email_check'] = 0;

            //FormObjectでのバリデーションが問題なければ削除
            // // バリデーション
            // //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
            // $errorList = $confirmHotelPersonModel->validation($requestConfirmHotelPerson);
            // if (count($errorList) > 0) {
            //     $errorList[] = "更新確認情報を更新できませんでした。 ";
            //     //TODO バリデーションエラー時の処理は？この時点でreturnしたほうがいい？
            // }

            // 共通カラム値設定
            $confirmHotelPersonModel->setInsertCommonColumn($requestConfirmHotelPerson);

            // コネクション
            try {
                $con = DB::connection('mysql');
                $dbErr = $con->transaction(function () use ($con, $confirmHotelPersonModel, $requestConfirmHotelPerson) {
                    // DB更新
                    $confirmHotelPersonModel->insert($con, $requestConfirmHotelPerson);
                });
            } catch (Exception $e) {
                $errorList[] = '更新確認情報の登録処理でエラーが発生しました。';
            }
            // 更新エラー
            if (count($errorList) > 0 || !empty($dbErr)) {
                $errorList[] = "更新確認情報を更新できませんでした。 ";
                //TODO エラーリスト出力するところがblade側にはないが、追加すべき？
            }

            // ポップアップ表示用のフラグを立てる
            $result_confirm["confirm_dtm_check"] = 1;
            return $result_confirm;

            //確認対象
            //  指定期間(3か月)を過ぎたため
            //  施設担当者のメール送信エラー
            //  精算担当者のメール送信エラー
        } elseif (
            strtotime($a_confirm_hotel_person['confirm_dtm']) < strtotime($this->confirm_span) //confirm_dtmもtimestamp化
            || $a_confirm_hotel_person['hotel_person_email_check']
            || $a_confirm_hotel_person['customer_email_check']
        ) {
            // ポップアップ表示用のフラグを立てる
            if (strtotime($a_confirm_hotel_person['confirm_dtm']) < strtotime($this->confirm_span)) { //confirm_dtmもtimestamp化
                $result_confirm["confirm_dtm_check"] = 1;
            }
            if ($a_confirm_hotel_person['hotel_person_email_check']) {
                $result_confirm["hotel_person_email_check"] = 1;
            }
            if ($a_confirm_hotel_person['customer_email_check']) {
                $result_confirm["customer_email_check"] = 1;
            }

            $requestConfirmHotelPerson['hotel_cd'] = $a_confirm_hotel_person['hotel_cd'];
            $requestConfirmHotelPerson['confirm_dtm'] = now();
            //'hotel_person_email_check'  => 0,
            //'customer_email_check'      => 0,

            //FormObjectでのバリデーションが問題なければ削除
            // // バリデーション
            // //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
            // $errorList = $confirmHotelPersonModel->validation($requestConfirmHotelPerson);
            // if (count($errorList) > 0) {
            //     $errorList[] = "更新確認情報を更新できませんでした。 ";
            //     //TODO バリデーションエラー時の処理は？この時点でreturnしたほうがいい？
            // }

            // 共通カラム値設定
            $confirmHotelPersonModel->setUpdateCommonColumn($requestConfirmHotelPerson);

            // 更新件数
            $dbCount = 0;
            // コネクション
            try {
                $con = DB::connection('mysql');
                $dbErr = $con->transaction(function () use ($con, $confirmHotelPersonModel, $requestConfirmHotelPerson, &$dbCount) {
                    // DB更新
                    $dbCount = $confirmHotelPersonModel->updateByKey($con, $requestConfirmHotelPerson);
                    //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
                });
            } catch (Exception $e) {
                $errorList[] = '更新確認情報の更新処理でエラーが発生しました。';
            }
            // 更新エラー
            if (
                $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
            ) {
                $errorList[] = "更新確認情報を更新できませんでした。 ";
                //TODO エラーリスト出力するところがblade側にはないが、追加すべき？
            }

            return $result_confirm;
        }
        return $result_confirm;
    }
}
