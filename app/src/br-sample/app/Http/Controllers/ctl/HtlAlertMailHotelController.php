<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel;
use App\Models\AlertMailHotel;
use App\Util\Models_Cipher;
use App\Http\Requests\HtlAlertMailHotelRequest;
use Exception;

class HtlAlertMailHotelController extends _commonController
{
    // 一覧
    public function list(Request $request)
    {
        try {
            // リクエストパラメータの取得
            $a_request_alert_mail_hotel = $request->input('AlertMailHotel');

            if (!isset($a_request_alert_mail_hotel['email'])) {
                $a_request_alert_mail_hotel['email'] = null;
            };

            if (!isset($a_request_alert_mail_hotel['email_type'])) {
                $a_request_alert_mail_hotel['email_type'] = null;
            };

            if (!isset($a_request_alert_mail_hotel['note'])) {
                $a_request_alert_mail_hotel['note'] = null;
            };

            // ターゲットコード
            $target_cd = $request->input('target_cd');

            // 満室通知メール情報取得
            $a_alert_mail_hotel = $this->getAlertMailHotels('vacant', $target_cd);

            // バリデーションエラー時はエラーメッセージ取得
            $errors = $request->session()->get('errors', []);

            // ガイドエラーメッセージ取得
            $guides = $request->session()->get('guides', []);

            return view('ctl.htlalertmailhotel.list', [
                'target_cd'          => $target_cd,
                'alert_mail_hotel'   => $a_request_alert_mail_hotel, // メール情報
                'a_alert_mail_hotel' => $a_alert_mail_hotel,         // リスト用
                'errors'             => $errors,
                'guides'             => $guides
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 新規登録処理
    public function create(HtlAlertMailHotelRequest $request)
    {

        // リクエストパラメータの取得
        $a_request_alert_mail_hotel = $request->input('AlertMailHotel');

        // ターゲットコード
        $target_cd = $request->input('target_cd');
        $actionCd = $this->getActionCd();

        try {
            // トランザクション開始
            DB::beginTransaction();

            //******枝番取得start******
            $models_hotel = new Hotel();

            //ブランチ番号取得
            $branch_no = $models_hotel->fill_counter('Alert_Mail_Hotel', 'branch_no', $target_cd);
            //******枝番取得end******

            // ホテルコードインスタンス生成
            $o_alert_mail_hotel = new AlertMailHotel();

            // メッセージ表示用に暗号前のメアドを退避
            $email = $a_request_alert_mail_hotel['email'];

            // メールアドレス暗号化
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            $a_request_alert_mail_hotel['email'] = $cipher->encrypt($a_request_alert_mail_hotel['email']);

            // メールアドレス重複チェック（target_cdのホテルの場合は重複NG、target_cd以外のホテルとは重複OK）
            $mail_check = $this->emailValidator($target_cd, $a_request_alert_mail_hotel);

            if ($mail_check == true) {
                $o_alert_mail_hotel->create([
                    'hotel_cd'        => $target_cd,                                // ホテルコード
                    'branch_no'       => $branch_no,                                // 枝番
                    'email'           => $a_request_alert_mail_hotel['email'],      // メールアドレス
                    'email_type'      => $a_request_alert_mail_hotel['email_type'], // メールアドレス
                    'note'            => $a_request_alert_mail_hotel['note'],       // 備考
                    'alert_system_cd' => 'vacant',                                  // アラートシステムコード
                    'email_notify'    => 1,                                         // アラートシステムコード
                    'entry_cd'        => $actionCd,
                    'entry_ts'        => now(),
                    'modify_cd'       => $actionCd,
                    'modify_ts'       => now(),
                ]);

                // コミット
                DB::commit();

                return redirect()
                    ->route('ctl.htl_alert_mail_hotel.list', ['target_cd' => $target_cd])
                    ->with(['guides' => [$email . 'を登録しました。']]);
            } else {
                return redirect()
                    ->route('ctl.htl_alert_mail_hotel.list', [
                        'target_cd' => $target_cd,
                        'AlertMailHotel[email]' => $email,
                        'AlertMailHotel[email_type]' => $a_request_alert_mail_hotel['email_type'],
                        'AlertMailHotel[note]' => $a_request_alert_mail_hotel['note']
                    ])
                    ->with(['errors' => [$email . 'は既に使用されています。']]);
            }
        } catch (Exception) {
            // ロールバック
            DB::rollback();
            // エラーメッセージ
            return redirect()
                ->route('ctl.htl_alert_mail_hotel.list', ['target_cd' => $target_cd])
                ->with(['errors' => ['ご希望のデータを登録できませんでした。']]);
        }
    }

    // 更新処理（通知/非通知の変更）
    public function update(Request $request)
    {

        // リクエストパラメータの取得
        $a_request_alert_mail_hotel = $request->input('AlertMailHotel');

        $target_cd = $request->input('target_cd');
        $actionCd = $this->getActionCd();

        try {
            // トランザクション開始
            DB::beginTransaction();

            $o_alert_mail_hotel = new AlertMailHotel();

            // 更新対象のテーブルがない場合、一覧画面へ戻る。
            $a_alert_mail_hotel_count = $o_alert_mail_hotel->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ]
            )->count();

            if ($a_alert_mail_hotel_count == 0) {
                // エラーメッセージ
                return redirect()
                    ->route('ctl.htl_alert_mail_hotel.list', [
                        'target_cd' => $target_cd,
                        'AlertMailHotel' => $a_request_alert_mail_hotel
                    ])
                    ->with(['errors' => ['ご希望の満室通知メールデータが見つかりませんでした。下記一覧から選んでください。']]);
            }

            // キーに関連付くデータを取得
            $a_alert_mail_hotel = $o_alert_mail_hotel->where(
                [
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ]
            )->first();

            // ガイドメッセージに表示させるメールアドレスを復号
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            $a_alert_mail_hotel['email'] = $cipher->decrypt($a_alert_mail_hotel['email']);

            // 更新
            if ($a_alert_mail_hotel['email_notify'] == 0) {
                // 非通知の場合は通知に変更
                $o_alert_mail_hotel->where([
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ])->update([
                    'email_notify'  => 1,
                    'modify_cd' => $actionCd,
                ]);

                $guides = $a_alert_mail_hotel['email'] . 'を通知に変更しました。';
            } else {
                // 通知の場合は非通知に変更
                $o_alert_mail_hotel->where([
                    'hotel_cd'  => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ])->update([
                    'email_notify'  => 0,
                    'modify_cd' => $actionCd,
                ]);

                $guides = $a_alert_mail_hotel['email'] . 'を非通知に変更しました。';
            }

            // コミット
            DB::commit();

            return redirect()
                ->route('ctl.htl_alert_mail_hotel.list', [
                    'target_cd' => $target_cd,
                    'AlertMailHotel' => $a_request_alert_mail_hotel
                ])
                ->with(['guides' => [$guides]]);
        } catch (Exception) {
            DB::rollback();
            return redirect()
                ->route('ctl.htl_alert_mail_hotel.list', [
                    'target_cd' => $target_cd,
                    'AlertMailHotel' => $a_request_alert_mail_hotel
                ])
                ->with(['errors' => ['ご希望の満室通知メールデータを更新できませんでした。']]);
        }
    }

    // 削除処理
    public function delete(Request $request)
    {
        // リクエストパラメータの取得
        $a_request_alert_mail_hotel = $request->input('AlertMailHotel');
        $target_cd = $request->input('target_cd');

        try {
            // トランザクション開始
            DB::beginTransaction();

            $o_alert_mail_hotel = new AlertMailHotel();

            // キーに関連付くデータを取得
            $a_alert_mail_hotel_count = $o_alert_mail_hotel->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ]
            )->count();

            $a_alert_mail_hotel_email = $o_alert_mail_hotel->where(
                [
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ]
            )->value('email');

            // ガイドメッセージに表示させるメールアドレスを復号
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            $a_alert_mail_hotel_email = $cipher->decrypt($a_alert_mail_hotel_email);

            if ($a_alert_mail_hotel_count > 0) {
                // ホテルコードと枝番に絡むデータを削除
                $o_alert_mail_hotel->where([
                    'hotel_cd' => $target_cd,
                    'branch_no' => $a_request_alert_mail_hotel['branch_no']
                ])->delete();
                // コミット
                DB::commit();
                return redirect()
                    ->route('ctl.htl_alert_mail_hotel.list', [
                        'target_cd' => $target_cd,
                        'AlertMailHotel' => $a_request_alert_mail_hotel
                    ])
                    ->with(['guides' => [$a_alert_mail_hotel_email . 'を削除しました。']]);
            }
        } catch (Exception) {
            DB::rollback();
            return redirect()
                ->route('ctl.htl_alert_mail_hotel.list', [
                    'target_cd' => $target_cd,
                    'AlertMailHotel' => $a_request_alert_mail_hotel
                ])
                ->with(['errors' => ['ご希望の満室通知メールデータを削除できませんでした。']]);
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

    // 電子メールアドレス重複チェック
    public function emailValidator($target_cd, $a_attributes)
    {
        //重複チェック
        $a_conditions = [];

        // 施設コード
        $a_conditions['hotel_cd'] = $target_cd;

        // アラートシステムコード
        $a_conditions['alert_system_cd'] = 'vacant';

        // メールアドレス
        $a_conditions['email'] = $a_attributes['email'];

        $s_sql =
            <<<SQL
				select	hotel_cd
				from	alert_mail_hotel
				where	null is null
					and	hotel_cd        = :hotel_cd
                    and	alert_system_cd = :alert_system_cd
					and	email           = :email
SQL;

        // データの取得
        $a_row = collect(DB::select($s_sql, $a_conditions));
        if (count($a_row) > 0) {
            return false;
        } else {
            return true;
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
        $userId = \Illuminate\Support\Facades\Session::get("user_id");   // TODO: ユーザー情報取得のキーは仮です
        $actionCd = $controllerName . "/" . $actionName . "." . $userId;

        return $actionCd;
    }
}
