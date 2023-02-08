<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HotelPerson;
use App\Models\Customer;
use App\Models\HotelNotify;
use App\Models\ExtendSetting;
use App\Models\MastPref;
use App\Models\LogHotelPerson;
use App\Models\LogCustomer;
use App\Models\ConfirmHotelPerson;
use App\Util\Models_Cipher;
use App\Http\Requests\HtlMailListRequest;
use Exception;

class HtlMailListController extends _commonController
{
    //一覧
    public function list(Request $request)
    {
        // リクエストパラメータの取得
        $a_hotel_person = $request->input('Hotel_Person');
        $a_customer = $request->input('customer');
        $a_extend_setting = $request->input('extend_setting');
        $a_hotel_notify = $request->input('Hotel_Notify');
        $a_request_alert_mail_hotel = $request->input('AlertMailHotel');

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        try {
            // インスタンスの取得
            $o_hotel_person  = new HotelPerson();
            $o_customer      = new Customer();
            $o_hotel_notify  = new HotelNotify();
            $o_extend_setting = new ExtendSetting();
            $o_mast_pref = new MastPref();

            // 施設担当者情報取得
            if (empty($a_hotel_person)) {
                $a_hotel_person  = $o_hotel_person->where(['hotel_cd' => $target_cd])->first();
            }

            // 精算先担当者情報取得
            if (empty($a_customer)) {
                $a_customer_hotel = $this->getCustomer($target_cd);
                $a_customer = $o_customer->where(['customer_id' => $a_customer_hotel["values"][0]->customer_id])->first();
            }
            $a_customer_pref = $o_mast_pref->where(['pref_id' => $a_customer['pref_id']])->first();

            if (empty($a_hotel_notify)) {
                // 予約通知メールアドレス取得(設定有の場合)
                $a_hotel_notify  = $o_hotel_notify->where(['hotel_cd' => $target_cd])->first();
                // ２進数を展開し一致するビットに変換
                $a_notify_device = $this->toShift($a_hotel_notify['notify_device'], true);
            }

            // 自動延長確認メールアドレス取得
            // MEMO bladeでfirstをcountするとエラーになるのでgetも準備
            if (empty($a_extend_setting)) {
                $a_extend_setting_count = $o_extend_setting->where(['hotel_cd' => $target_cd])->get();
                $a_extend_setting = $o_extend_setting->where(['hotel_cd' => $target_cd])->first();
            }

            // 満室通知メール情報取得
            $a_alert_mail_hotel = $this->getAlertMailHotels('vacant', $target_cd);

            // 宿泊体験通知メール情報取得
            $a_alert_mail_voice = $this->getAlertMailHotels('voice', $target_cd);

            // 表示させるメールアドレスを復号
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            $a_hotel_person['person_email'] = $cipher->decrypt($a_hotel_person['person_email']);
            $a_customer['email'] = $cipher->decrypt($a_customer['email']);

            $a_extend_setting['email'] = $cipher->decrypt($a_extend_setting['email']);

            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            // ガイドエラーメッセージ取得
            $guides = $request->session()->get('guides', []);

            return view('ctl.htlmaillist.list', [
                'target_cd' => $target_cd,
                'hotel_person' => $a_hotel_person,                      // 施設担当者情報
                'customer' => $a_customer,                              // 精算先担当者情報
                'hotel_notify' => $a_hotel_notify,                      // 予約通知メールアドレス
                'notify_device' => $a_notify_device,                    // 予約通知設定
                'extend_setting' => $a_extend_setting,                  // 自動延長確認メールアドレス
                'extend_setting_count' => $a_extend_setting_count,      // 自動延長確認メールアドレス
                'alert_mail_hotel' => $a_request_alert_mail_hotel,      // 満室通知メール情報
                'a_alert_mail_hotel' => $a_alert_mail_hotel,            // 満室通知メール リスト用
                // 'alert_mail_voice' => $a_request_alert_mail_voice,   // 宿泊体験通知メール情報 　MEMO(2023/2/6):旧ソースでもコメントアウト
                'a_alert_mail_voice' => $a_alert_mail_voice,            // 宿泊体験通知メール リスト用
                'a_customer_pref' => $a_customer_pref,                  // 請求先都道府県
                'errors'        => $errors,
                'guides'        => $guides
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 施設担当者・精算先担当者・自動延長メールの更新
    public function edit(HtlMailListRequest $request)
    {
        $target_cd = $request->input('target_cd');
        $actionCd = $this->getActionCd();

        try {
            // トランザクション開始
            DB::beginTransaction();

            // 施設担当者の更新
            $result_person = $this->updatePerson($request, $actionCd);

            // 精算先担当者の更新
            $result_customer = $this->updateCustomer($request, $actionCd);

            //自動延長メールの更新
            $result_extend_setting = $this->updateExtendSetting($request, $actionCd);

            //担当者情報更新確認のフラグをOFFにする
            $result_confirm_hotel_person = $this->updateConfirmHotelPerson($request, $actionCd);

            if (
                $result_person === true &&
                $result_customer === true &&
                $result_extend_setting === true &&
                $result_confirm_hotel_person === true
            ) {
                // コミット
                DB::commit();
            } else {
                DB::rollback();
                return redirect()
                    ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
                    ->with(['errors' => ['更新に失敗しました。もう一度お試しください。']]);
            }
            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            // ロールバック
            DB::rollback();
        }
        return redirect()
            ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
            ->with(['guides' => ['担当者情報の更新が完了しました。']]);
    }


    // 施設担当者の更新
    private function updatePerson(Request $request, $actionCd)
    {

        // ターゲットコード
        $target_cd = $request->input('target_cd');

        $a_hotel_person = $request->input('Hotel_Person');

        //--------------------------
        // 施設担当者情報
        //--------------------------
        // 変更前の情報取得
        $o_hotel_person  = new HotelPerson();
        $e_hotel_person = $o_hotel_person->where(['hotel_cd' => $target_cd])->first();

        // 変更箇所が無い場合
        if (
            strcmp($e_hotel_person['person_post'], $a_hotel_person['person_post'])   == 0 &&
            strcmp($e_hotel_person['person_nm'], $a_hotel_person['person_nm'])       == 0 &&
            strcmp($e_hotel_person['person_tel'], $a_hotel_person['person_tel'])     == 0 &&
            strcmp($e_hotel_person['person_fax'], $a_hotel_person['person_fax'])     == 0 &&
            strcmp($e_hotel_person['person_email'], $a_hotel_person['person_email']) == 0
        ) {
            return true;
        }

        // メールアドレス暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $a_hotel_person['person_email'] = $cipher->encrypt($a_hotel_person['person_email']);


        // 更新
        $hotel_person_update = $o_hotel_person->where([
            'hotel_cd' => $target_cd
        ])->update([
            'person_post' => $a_hotel_person['person_post'],
            'person_nm' => $a_hotel_person['person_nm'],
            'person_tel' => $a_hotel_person['person_tel'],
            'person_fax' => $a_hotel_person['person_fax'],
            'person_email' => $a_hotel_person['person_email'],
            'modify_cd'     => $actionCd
        ]);

        if ($hotel_person_update == 0) {
            DB::rollback();
            return redirect()
                ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
                ->with(['errors' => ['施設担当者情報を更新できませんでした。']]);
        };

        //--------------------------
        // 施設担当者情報履歴
        //--------------------------
        $o_log_hotel_person  = new LogHotelPerson();
        $a_log_hotel_person = $o_log_hotel_person->where(['hotel_cd' => $target_cd])->get();
        $branch_no = count($a_log_hotel_person) + 1;

        $log_hotel_person_insert = $o_log_hotel_person->insert([
            'hotel_cd'     => $target_cd,
            'branch_no'    => $branch_no,
            'person_post'  => $a_hotel_person['person_post'],
            'person_nm'    => $a_hotel_person['person_nm'],
            'person_tel'   => $a_hotel_person['person_tel'],
            'person_fax'   => $a_hotel_person['person_fax'],
            'person_email' => $a_hotel_person['person_email'],
            'entry_cd'     => $actionCd,
            'entry_ts'     => now(),
            'modify_cd'    => $actionCd,
            'modify_ts'     => now(),
        ]);

        // 更新失敗した場合アクションへ
        if (!$log_hotel_person_insert) {
            // エラーメッセージ
            DB::rollback();
            return redirect()
                ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
                ->with(['errors' => ['施設担当者情報履歴を更新できませんでした。']]);
        };

        return true;
    }

    // 精算先担当者の更新
    private function updateCustomer(Request $request, $actionCd)
    {

        $a_customer = $request->input('customer');

        //--------------------------
        // 精算担当者情報
        //--------------------------
        $o_customer = new Customer();
        $e_customer = $o_customer->where(['customer_id' => $a_customer['customer_id']])->first();

        // 変更箇所が無い場合
        if (
            strcmp($e_customer['section_nm'], $a_customer['section_nm'])    == 0 &&
            strcmp($e_customer['person_post'], $a_customer['person_post'])  == 0 &&
            strcmp($e_customer['person_nm'], $a_customer['person_nm'])      == 0 &&
            strcmp($e_customer['tel'], $a_customer['tel'])                  == 0 &&
            strcmp($e_customer['fax'], $a_customer['fax'])                  == 0 &&
            strcmp($e_customer['email'], $a_customer['email'])              == 0
        ) {
            return true;
        }

        // メールアドレス暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $a_customer['email'] = $cipher->encrypt($a_customer['email']);

        $customer_update = $o_customer->where([
            'customer_id' => $a_customer['customer_id']
        ])->update([
            'section_nm' => $a_customer['section_nm'],
            'person_post' => $a_customer['person_post'],
            'person_nm' => $a_customer['person_nm'],
            'tel' => $a_customer['tel'],
            'fax' => $a_customer['fax'],
            'email' => $a_customer['email'],
            'modify_cd' => $actionCd

        ]);
        if ($customer_update == 0) {
            DB::rollback();
            return redirect()
                ->route('ctl.htl_mail_list.list', ['target_cd' => $request->target_cd])
                ->with(['errors' => ['精算先情報を更新できませんでした。']]);
        }

        //--------------------------
        // 精算担当者情報履歴
        //--------------------------
        $o_log_customer  = new LogCustomer();
        $a_log_customer = $o_log_customer->where(['customer_id' => $a_customer['customer_id']])->get();
        $branch_no = count($a_log_customer) + 1;

        $log_customer_insert = $o_log_customer->insert([
            'customer_id'  => $a_customer['customer_id'],
            'branch_no'    => $branch_no,
            'section_nm'   => $a_customer['section_nm'],
            'person_post'  => $a_customer['person_post'],
            'person_nm'    => $a_customer['person_nm'],
            'tel'          => $a_customer['tel'],
            'fax'          => $a_customer['fax'],
            'email'        => $a_customer['email'],
            'entry_cd'     => $actionCd,
            'entry_ts'     => now(),
            'modify_cd'    => $actionCd,
            'modify_ts'     => now()
        ]);

        // 更新失敗した場合アクションへ
        if (!$log_customer_insert) {
            // エラーメッセージ
            DB::rollback();
            return redirect()
                ->route('ctl.htl_mail_list.list', ['target_cd' => $request->target_cd])
                ->with(['errors' => ['精算先情報履歴を更新できませんでした。']]);
        };

        return true;
    }

    // 自動延長確認の更新
    private function updateExtendSetting(Request $request, $actionCd)
    {

        // ターゲットコード
        $target_cd = $request->input('target_cd');
        $a_extend_setting = $request->input('extend_setting');
        //--------------------------
        // 自動延長確認情報
        //--------------------------
        $o_extend_setting = new ExtendSetting();
        $e_extend_setting = $o_extend_setting->where(['hotel_cd' => $target_cd])->first();

        // 自動延長がない場合は何もしない。
        if (empty($e_extend_setting) || $e_extend_setting['email_notify'] == 0) {
            return true;
        }
        // 変更箇所が無い場合
        if (
            strcmp($e_extend_setting['email'], $a_extend_setting['email']) == 0 &&
            strcmp($e_extend_setting['email_type'], $a_extend_setting['email_type'])  == 0
        ) {
            return  true;
        }

        // メールアドレス暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $a_extend_setting['email'] = $cipher->encrypt($a_extend_setting['email']);

        // 更新
        $extend_setting_update = $o_extend_setting->where([
            'hotel_cd' => $target_cd
        ])->update([
            'email'       => $a_extend_setting['email'],
            'email_type'  => $a_extend_setting['email_type'],
            'modify_cd'   => $actionCd
        ]);

        if ($extend_setting_update == 0) {
            DB::rollback();
            return redirect()
                ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
                ->with(['errors' => ['自動延長確認情報を更新できませんでした。']]);
        }

        return true;
    }

    //担当者情報更新確認のフラグをOFFにする
    private function updateConfirmHotelPerson(Request $request, $actionCd)
    {

        // ターゲットコード
        $target_cd = $request->input('target_cd');
        $a_customer = $request->input('customer');

        //--------------------------
        // 自動延長確認情報
        //--------------------------
        $o_confirm_hotel_person  = new ConfirmHotelPerson();
        $a_confirm_hotel_person = $o_confirm_hotel_person->where(['hotel_cd' => $target_cd])->first();

        if (empty($a_confirm_hotel_person)) {
            // 追加処理
            $confirm_hotel_person_insert = $o_confirm_hotel_person->insert([
                'hotel_cd'     => $target_cd,
                'confirm_dtm'  => now(),
                'hotel_person_email_check'  => 0,
                'customer_email_check'      => 0,
                'entry_cd'     => $actionCd,
                'modify_cd'    => $actionCd
            ]);
            if (!$confirm_hotel_person_insert) {
                // エラーメッセージ
                DB::rollback();
                return redirect()
                    ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
                    ->with(['errors' => ['担当者情報更新確認情報を更新できませんでした。']]);
            }
        } else {
            // 更新処理
            $confirm_hotel_person_update = $o_confirm_hotel_person->where([
                'hotel_cd'     => $target_cd
            ])->update([
                'confirm_dtm'  => now(),
                'hotel_person_email_check'  => 0,
                'customer_email_check'      => 0,
                'modify_cd'    => $actionCd
            ]);
        }
        // 登録されなかった場合
        if ($confirm_hotel_person_update == 0) {
            // エラーメッセージ
            DB::rollback();
            return redirect()
                ->route('ctl.htl_mail_list.list', ['target_cd' => $target_cd])
                ->with(['errors' => ['担当者情報更新確認情報を更新できませんでした。']]);
        }

        // MEMO(2023/2/6): 旧ソースでも$e_confirm_hotel_personが未定義エラーのため、未実装
        // 請求先確認フラグがONの場合、いずれかの施設で更新したら他施設では表示しないようにする。（精算担当者の確認フラグのみOFFにする）
        // if ($e_confirm_hotel_person['customer_email_check']) {

        //     $o_customer_hotel  = Customer_Hotel::getInstance();
        //     $a_customer_hotel = $o_customer_hotel->all(array('customer_id' => $a_customer['customer_id']));

        //     foreach ($a_customer_hotel as $customer_hotel) {
        //         if ($customer_hotel['hotel_cd'] != $target_cd) {
        //             $a_confirm_hotel_person = $o_confirm_hotel_person->find(array('hotel_cd' => $customer_hotel['hotel_cd']));
        //             $o_confirm_hotel_person->attributes($a_confirm_hotel_person);
        //             $o_confirm_hotel_person->attributes(array(
        //                 //'confirm_dtm'  => 'sysdate',
        //                 //'hotel_person_email_check'  => 0,
        //                 'customer_email_check'      => 0,
        //                 'modify_cd'    => $this->box->info->env->action_cd,
        //                 'modify_ts'    => 'sysdate',
        //             ));

        //             $o_confirm_hotel_person->update();
        //             // 登録されなかった場合
        //             if ($o_confirm_hotel_person->row_count() == 0) {
        //                 // エラーメッセージ
        //                 $this->box->item->error->add("担当者情報更新確認情報を更新できませんでした。");
        //                 return false;
        //             }
        //         }
        //     }
        // }

        return true;
    }
    // 請求先・支払先施設データ
    //   as_hotel_cd 請求先・支払先施設データの施設番号
    public function getCustomer($as_hotel_cd)
    {
        try {
            $s_sql =
                <<<SQL
					select	customer.customer_id,
							customer.customer_nm
					from	customer,
						(
							select	customer_id
							from	customer_hotel
							where	hotel_cd = :hotel_cd
						) q1
					where	customer.customer_id = q1.customer_id
SQL;

            // データの取得
            $a_row = DB::select($s_sql, ['hotel_cd' => $as_hotel_cd]);
            return ['values' => $a_row];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // ２進数を展開し一致するビットもしくは位に変換します。
    //
    //  as_value 数字を設定
    //  ab_bits  true ビットで返却 false 位で返却
    //
    //  example
    //    bits = true
    //      > 30
    //        >> array(2, 4, 8, 16)
    //    bits = false
    //      > 30
    //        >> array(1, 2, 3, 4)
    public function toShift($as_value, $ab_bits = true)
    {
        try {
            if ($as_value <= 0) {
                return null;
            }

            $buf_value = 1;

            $n_cnt = 0;
            while ($buf_value <= $as_value) {
                $buf_value <<= 1;
                $bits[] = array($buf_value / 2, $n_cnt);    // ビットと位を保持
                $n_cnt++;
            }

            // ビットで逆順に並び替え
            rsort($bits);

            // 一致するビットと位を取得
            for ($n_cnt = 0; $n_cnt < count($bits); $n_cnt++) {
                if ($bits[$n_cnt][0] <= $as_value) {
                    $a_bits[] = $bits[$n_cnt][0];
                    $a_position[] = $bits[$n_cnt][1];
                    $as_value = $as_value - $bits[$n_cnt][0];
                }
            }

            // ビットを返却
            if ($ab_bits) {
                return $a_bits;

                // 位を返却
            } else {
                return $a_position;
            }


            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // アラートメール施設一覧を取得
    public function getAlertMailHotels($alert_system_cd, $target_cd)
    {
        try {
            // 施設コードとシステムコードを設定
            $a_conditions = [
                'hotel_cd'   => $target_cd,
                'alert_system_cd'   => $alert_system_cd
            ];

            $s_sql =
                <<< SQL
					select	hotel_cd,
							branch_no,
							alert_system_cd,
							email,
							email_type,
							email_notify,
							note
					from	alert_mail_hotel
					where	hotel_cd = :hotel_cd
						and	alert_system_cd = :alert_system_cd
SQL;

            $a_alert_mail_hotel = DB::select($s_sql, $a_conditions);

            // メールアドレス復号
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            for ($n_cnt = 0; $n_cnt < count($a_alert_mail_hotel); $n_cnt++) {
                $a_alert_mail_hotel[$n_cnt]->email = $cipher->decrypt($a_alert_mail_hotel[$n_cnt]->email);
            }
            return ['values' => $a_alert_mail_hotel];

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * コントローラ名とアクション名を取得して、ユーザーIDと連結
     * ユーザーID取得は暫定の為、書き換え替えが必要です。
     *
     * MEMO: app/Models/common/CommonDBModel.php から移植したもの
     * HACK: 適切に共通化したいか。
     * @return string
     */
    private function getActionCd()
    {
        $path = explode("@", \Illuminate\Support\Facades\Route::currentRouteAction());
        $pathList = explode('\\', $path[0]);
        $controllerName = str_replace("Controller", "", end($pathList)); // コントローラ名
        $actionName = $path[1]; // アクション名
        $userId = \Illuminate\Support\Facades\Session::get("user_id"); // TODO: ユーザー情報取得のキーは仮です
        $actionCd = $controllerName . "/" . $actionName . "." . $userId;

        return $actionCd;
    }
}
