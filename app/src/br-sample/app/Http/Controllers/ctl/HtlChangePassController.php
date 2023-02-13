<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\HotelAccount;
use App\Util\Models_Cipher;
use App\Http\Requests\HtlChangePassRequest;
use Exception;

class HtlChangePassController extends _commonController
{
    /**
     * インデックス
     * 
     * @return array
     */
    public function index(Request $request)
    {

        $target_cd = $request->input('target_cd');
        $request_id = $request->input('id');
        $request_pass = $request->input('pass');
        $request_both = $request->input('both');
        $id_1 = $request->input('id1');
        $id_2 = $request->input('id2');
        $pass_1 = $request->input('pass1');
        $pass_2 = $request->input('pass2');

        // バリデーションエラー時はエラーメッセージ取得
        $errors = $request->session()->get('errors', []);

        // ガイドエラーメッセージ取得
        $guides = $request->session()->get('guides', []);

        return view('ctl.htlchangepass.index', [
            'target_cd' => $target_cd,
            'id'        => $request_id,
            'pass'      => $request_pass,
            'both'      => $request_both,
            'id1'       => $id_1,
            'id2'       => $id_2,
            'pass1'     => $pass_1,
            'pass2'     => $pass_2,
            'errors'    => $errors,
            'guides'    => $guides
        ]);
    }

    /**
     * 更新処理
     * 
     * IDPW更新、IDのみ更新、PWのみ更新の3パターン
     * @return array
     */

    public function update(HtlChangePassRequest $request)
    {
        $target_cd = $request->input('target_cd');
        $request_id = $request->input('id');
        $request_pass = $request->input('pass');
        $request_both = $request->input('both');
        $id_1 = $request->input('id1');
        $pass_1 = $request->input('pass1');
        $actionCd = $this->getActionCd();

        // TODO スタッフの場合は更新できない処理
        // MEMO(2023/2/9)；ログイン機能実装前のため未実装
        // $s_msg = $this->staffCheck();
        // if(!is_null($s_msg)){
        //     DB::rollback();
        //     return redirect()
        //     ->route('ctl.htl_change_pass.index', [
        //         'target_cd' => $target_cd,
        //     ])
        //     ->with(['errors' => [$s_msg]]);
        // }

        // トランザクション開始
        DB::beginTransaction();

        try {
            $hotel_account = new HotelAccount();

            // IDPWどちらも更新を書ける必要があるとき
            if (!is_null($request_both) && is_null($request_id) && is_null($request_pass)) {
                // account_id、account_id_begin、PW（暗号化）の更新
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $pass_1 = $cipher->encrypt($pass_1);

                $update_id_pass = $hotel_account->where([
                    'hotel_cd' => $target_cd
                ])->update([
                    'account_id'        => strtoupper($id_1),
                    'account_id_begin'  => $id_1,
                    'password'          => $pass_1,
                    'modify_cd'         => $actionCd,
                    'modify_ts'         => now()
                ]);

                if ($update_id_pass != 1) {
                    DB::rollback();
                    return redirect()
                        ->route('ctl.htl_change_pass.index', [
                            'target_cd' => $target_cd,
                        ])
                        ->with(['errors' => ['更新エラー トップページよりやり直してください。']]);
                }
                $guides = '新しい ＩＤとパスワードに 変更しました。次回からは新しい ログインＩＤ と パスワード でログインしてください。';

            // idのみ更新
            } elseif (is_null($request_both) && !is_null($request_id) && is_null($request_pass))
            {
                // account_id、account_id_beginの更新
                $update_id = $hotel_account->where([
                    'hotel_cd' => $target_cd
                ])->update([
                    'account_id'        => strtoupper($id_1),
                    'account_id_begin'  => $id_1,
                    'modify_cd'         => $actionCd,
                    'modify_ts'         => now(),
                ]);

                if ($update_id != 1) {
                    DB::rollback();
                    return redirect()
                        ->route('ctl.htl_change_pass.index', [
                            'target_cd' => $target_cd,
                        ])
                        ->with(['errors' => ['更新エラー トップページよりやり直してください。']]);
                }

                $guides = '新しい ログインＩＤ に 変更しました。次回からは新しい ログインＩＤ でログインしてください。';

            // PWのみ更新
            } elseif (is_null($request_both) && is_null($request_id) && !is_null($request_pass)) {
                // 暗号化
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $pass_1 = $cipher->encrypt($pass_1);

                $update_pass = $hotel_account->where([
                    'hotel_cd' => $target_cd
                ])->update([
                    'password'   => $pass_1,
                    'modify_cd'  => $actionCd,
                    'modify_ts'  => now(),
                ]);

                if ($update_pass != 1) {
                    DB::rollback();
                    return redirect()
                        ->route('ctl.htl_change_pass.index', [
                            'target_cd' => $target_cd,
                        ])
                        ->with(['errors' => ['更新エラー トップページよりやり直してください。']]);
                }

                $guides = '新しいパスワードに 変更しました。次回からは新しい パスワード でログインしてください。';
            }

            // コミット
            DB::commit();

            // TODO ログイン状態で尚且つホテルの場合　再ログイン処理
            //     $this->loginHotelForce();

            return redirect()
                ->route('ctl.htl_change_pass.index', [
                    'target_cd' => $target_cd
                ])
                ->with(['guides' => [$guides]]);
        } catch (Exception) {
            DB::rollback();
            return redirect()
                ->route('ctl.htl_change_pass.index', [
                    'target_cd' => $target_cd,
                ])
                ->with(['errors' => ['更新エラー トップページよりやり直してください。']]);
        }
    }

    /**
     * TODO スタッフの場合は更新できない処理
     * 
     * MEMO(2023/2/9)；ログイン機能実装前のため後で要書き変え。
     * @return string
     */
    // private function staffCheck()
    // {
    //     $is_staff = true;
    //     if ($is_staff == true) {
    //         // ロールバック
    //         // DB::rollback();
    //             $s_msg = 'あなたの ＩＤ では  ログインＩＤ と パスワード を 変更できません。';
    //     }
    //     return $s_msg;
    // }

    /**
     * TODO ログイン状態で尚且つホテルの場合　再ログイン処理
     * 
     * MEMO(2023/2/9)；ログイン機能実装前のため後で要書き変え。
     * @return void
     */
    // private function loginHotelForce(){
    // if (
    //     $this->box->user->operator->is_login() == true
    //     &&    $this->box->user->operator->is_hotel() == true
    // ) {
    //     // 強制的に再ログインを行う
    //     $models_hotel = new models_Hotel();
    //     $models_hotel->login_hotel_force($this->_request->getParam('target_cd'));
    // }
    // }

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
