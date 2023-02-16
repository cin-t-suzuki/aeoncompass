<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Common\Traits;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdditionalZengin;
use App\Models\Hotel;
use App\Models\Customer;
use App\Models\CustomerHotel;
use App\Models\MastBank;
use App\Models\MastBankBranch;
use App\Models\Staff;
use App\Models\MastPref;
use Exception;

class BrAdditonalZenginController extends _commonController
{
    use Traits;

    // セレクトボックスの年の最小値
    private $reserve_select_year = '';

    // ループカウント
    private $year_loop_cnt = '11';

    /**
     * 一覧画面  表示処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        // セレクトボックスの初期値を設定
        $this->reserve_select_year = date('Y') - 5 . '-01-01';

        $additionalZenginModel = new AdditionalZengin();
        $a_additional_zengin = $additionalZenginModel->getPaymentSchedule();

        // ガイドメッセージの設定
        $guides = $request->session()->get('guides', []);

        // ビューを表示
        return view('ctl.brAdditionalZengin.list', [
            'reserve_select_year'         => $this->reserve_select_year,
            's_cnt'      => $this->year_loop_cnt,
            'direct_debit_ym_select' => $a_additional_zengin,

            'guides' => $guides
        ]);
    }

    /**
     * 一覧画面  検索リクエスト処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchList(Request $request)
    {
        // 必須項目のkeywordが無ければ検索しない。
        if ($this->is_empty($request->input('keywords')) && $request->input('unuse_check') == 2) {
            // エラーメッセージ
            $errors[] = "年月を指定もしくは、キーワードを入力してください。";
            // ビューを表示
            return view('ctl.brAdditionalZengin.searchHotel', [
                'errors' => $errors
            ]);
        }

        $a_conditions = [
            'keywords'  => $request->input('keywords'),
            'year'      => $request->input('year'),
            'month'     => $request->input('month'),
            'unuse_check'  => $request->input('unuse_check'),
            'ym'        => $request->input('ym'),
        ];

        // 引落追加額データの取得
        $additionalZenginModel = new AdditionalZengin();
        $a_additional_zengin['values'] = $additionalZenginModel->getAdditionalZengin($a_conditions);

        // ビューを表示
        return view('ctl.brAdditionalZengin.searchList', [
            'additional_zengin' => $a_additional_zengin
        ]);
    }

    /**
     * 詳細画面
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request)
    {
        // 別ページから値が渡されていればばそれを適用する
        // そうでなければrequestから取得する
        if ($request->session()->has('zengin_ym')) { // 判別はzengin_ymだけでいいか？
            $zengin_ym = $request->session()->pull('zengin_ym');
            $branch_id = $request->session()->pull('branch_id');
        } else {
            $zengin_ym = $request->input('zengin_ym');
            $branch_id = $request->input('branch_id');
        }

        // ガイドメッセージの設定
        $errors = $request->session()->get('errors', []);
        $guides = $request->session()->get('guides', []);

        $additionalZenginModel = new AdditionalZengin();
        $a_additional_zengin = $additionalZenginModel->selectByKey($zengin_ym, $branch_id);

        if (!$this->is_empty($a_additional_zengin)) { //if文で条件分岐つけていいか？nullだと取得エラーになる
            // 銀行名、支店名を取得する
            $this->getBankNm($a_additional_zengin);
            // スタッフ名を取得する
            $this->getStaffNm($a_additional_zengin);
        }

        // ビューを表示
        return view('ctl.brAdditionalZengin.detail', [
            'additional_zengin' => $a_additional_zengin,

            'errors' => $errors,
            'guides' => $guides
        ]);
    }


    /**
     * 更新処理
     *
     * @param Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $additionalZenginModel = new AdditionalZengin();
        $zengin_ym  = $request->input('zengin_ym');
        $branch_id  = $request->input('branch_id');
        $a_additional_zengin = $additionalZenginModel->selectByKey($zengin_ym, $branch_id);

        $a_additional_zengin['reason'] =  $request->input('reason'); //理由
        $a_additional_zengin['reason_internal'] =  $request->input('reason_internal'); //備考(内部のみ)
        $a_additional_zengin['additional_charge'] =  $request->input('additional_charge'); //追加金額

        // バリデーション
        //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
        $errorList = $additionalZenginModel->validation($a_additional_zengin);
        if (count($errorList) > 0) {
            $errorList[] = "口座振替の引落追加情報を更新できませんでした。 ";
            return redirect()->route('ctl.brAdditionalZengin.detail', [
                'zengin_ym' => $zengin_ym,
                'branch_id' => $branch_id
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 共通カラム値設定
        $additionalZenginModel->setUpdateCommonColumn($a_additional_zengin);

        // 更新件数
        $dbCount = 0;
        // コネクション
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $additionalZenginModel, $a_additional_zengin, &$dbCount) {
                // DB更新
                $dbCount = $additionalZenginModel->updateByKey($con, $a_additional_zengin);
                //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
            });
        } catch (Exception $e) {
            $errorList[] = '口座振替の引落追加情報更新でエラーが発生しました。';
        }
        // 更新エラー
        if (
            $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
        ) {
            $errorList[] = "口座振替の引落追加情報を更新できませんでした。 ";
            return redirect()->route('ctl.brAdditionalZengin.detail', [
                'zengin_ym' => $zengin_ym,
                'branch_id' => $branch_id
            ])->with([
                'errors' => $errorList
            ]);
        }

        $guides[] = '口座振替の引落追加情報の更新が完了しました。';

        // ビューを表示
        return redirect()->route('ctl.brAdditionalZengin.detail', [
            'zengin_ym' => $zengin_ym,
            'branch_id' => $branch_id
        ])->with([
            'guides' => $guides
        ]);
    }

    /**
     * 削除処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $additionalZenginModel = new AdditionalZengin();

        $zengin_ym  = $request->input('zengin_ym');
        $branch_id  = $request->input('branch_id');
        $a_additional_zengin = $additionalZenginModel->selectByKey($zengin_ym, $branch_id);

        $a_additional_zengin['notactive_flg'] = 1; // 削除フラグ(0:有効 1:無効)

        // バリデーション
        //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
        $errorList = $additionalZenginModel->validation($a_additional_zengin);
        if (count($errorList) > 0) {
            $errorList[] = "口座振替の追加金額情報を削除できませんでした。 ";
            return redirect()->route('ctl.brAdditionalZengin.detail', [
                'zengin_ym' => $zengin_ym,
                'branch_id' => $branch_id
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 共通カラム値設定
        $additionalZenginModel->setUpdateCommonColumn($a_additional_zengin);

        // 更新件数
        $dbCount = 0;
        // コネクション
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $additionalZenginModel, $a_additional_zengin, &$dbCount) {
                // DB更新
                $dbCount = $additionalZenginModel->updateByKey($con, $a_additional_zengin);
                //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
            });
        } catch (Exception $e) {
            $errorList[] = '口座振替の追加金額情報削除でエラーが発生しました。';
        }
        // 更新エラー
        if (
            $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
        ) {
            $errorList[] = "更新確認情報を削除できませんでした。 ";
            return redirect()->route('ctl.brAdditionalZengin.detail', [
                'zengin_ym' => $zengin_ym,
                'branch_id' => $branch_id
            ])->with([
                'errors' => $errorList
            ]);
        }

        $guides[] = '口座振替の追加金額情報を削除しました。';

        // ビューを表示
        return redirect()->route('ctl.brAdditionalZengin.detail', [
            'zengin_ym' => $zengin_ym,
            'branch_id' => $branch_id
        ])->with([
            'guides' => $guides
        ]);
    }

    /**
     * 施設選択画面 表示処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $mastPrefModel = new MastPref();
        $mastPrefsData = $mastPrefModel->getMastPrefs();

        // ビューを表示
        return view('ctl.brAdditionalZengin.search', [
            'mast_prefs' => $mastPrefsData
        ]);
    }

    /**
     * 施設選択画面 検索リクエスト処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchHotel(Request $request)
    {
        // 必須項目のkeywordが無ければ検索しない。
        if ($this->is_empty($request->input('keywords'))) {
            // エラーメッセージ
            $errors[] = "キーワードを入力してください。";
            // ビューを表示
            return view('ctl.brAdditionalZengin.searchHotel', [
                'errors' => $errors
            ]);
        }

        $hotelModel = new Hotel();

        $a_conditions = [
            'keywords'    => $request->input('keywords'),
            'pref_id'     => $request->input('pref_id'),
            //entry_state→entry_statusへ修正でいいか？公開中の制御をかけるには0（公開中）かどうかの判別が必要。以下に修正していいか？
            //（元ソース） 'entry_state' => ($this->_request->getParam('entry_state') == 'true') ? 1 : null,
            'entry_status' => ($request->input('entry_status') == '0') ? 0 : null,
            'stock_type' => null //追記、nullでいいか？
        ];

        // 検索条件に該当するホテル一覧の取得
        //TODO 検索数が膨大な場合、エラーになる可能性あり（キーワードで「ホテル」など）
        $errrorArr = []; //引数が必要なため追記でいいか？
        $a_hotel_list = $hotelModel->search($errrorArr, $a_conditions);
        $a_hotel_list = json_decode(json_encode($a_hotel_list), true); //stdclassを配列にしないと下でエラーになる,blade側(edit)も配列での参照へ変更

        // 各施設の精算先・口座情報を取得
        foreach ($a_hotel_list["values"] as $key => $hotel_list) {
            $a_hotel_list["values"][$key] = array_merge($hotel_list, $this->getFactoring($hotel_list["hotel_cd"]));
        }

        if (count($a_hotel_list['values']) == 0) {
            // エラーメッセージ
            $errors[] = "該当する施設が見つかりませんでした。";
        }

        // ビューを表示
        return view('ctl.brAdditionalZengin.searchHotel', [
            'hotel_list' => $a_hotel_list,

            //エラーメッセージがないときは空の配列を渡す
            'errors' => $errors ?? []
        ]);
    }

    /**
     * 登録画面 表示処理
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $hotel_cd = $request->input('hotel_cd');
        $reason = $request->input('reason');
        $reason_internal = $request->input('reason_internal');
        $additional_charge = $request->input('additional_charge');

        // エラーメッセージの設定
        $errors = $request->session()->get('errors', []);

        // 指定した施設CDの施設情報・精算先・口座情報を取得する。
        $a_hotel_list = $this->getHotelList($hotel_cd);

        // セレクトボックスの初期値を設定
        $this->reserve_select_year = date('Y') . '-01-01';

        $search['year'] = $request->input('year');
        $search['month'] = $request->input('month');

        // ビューを表示
        return view('ctl.brAdditionalZengin.edit', [
            'reserve_select_year' => $this->reserve_select_year,
            's_cnt' => 2,
            'hotel_list' => $a_hotel_list,
            'reason' => $reason,
            'reason_internal' => $reason_internal,
            'additional_charge' => $additional_charge,
            'search' => $search,

            'errors' => $errors
        ]);
    }

    /**
     * 登録画面 リクエスト処理
     *
     * @param Request $request
     * @return Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $additionalZenginModel = new AdditionalZengin();
        $o_date = new DateUtil($request->input('year') . '-' . $request->input('month') . '-23'); //Br_Models_DateはDateUtilでいいか？
        $hotel_cd = $request->input('hotel_cd');
        $reason = $request->input('reason');
        $reason_internal = $request->input('reason_internal');
        $additional_charge = $request->input('additional_charge');

        if ($o_date->diff('m', date('Y-m-d')) > 0) {
            $errorList[] = "過去引落日のデータは登録できません。 ";
            return redirect()->route('ctl.brAdditionalZengin.edit', [
                'hotel_cd' => $hotel_cd,
                'reason' => $reason,
                'reason_internal' => $reason_internal,
                'additional_charge' => $additional_charge
            ])->with([
                'errors' => $errorList
            ]);
        }

        // ->allの書き換えはselectBy3Keyを別途作成で問題ないか？
        $temp = $additionalZenginModel->selectBy3Key(
            $zengin_ym   = $o_date->to_format('Ym'),
            $hotel_cd,
            $notactive_flg   = '0'
        );

        if (!$this->is_empty($temp)) {
            $errorList[] = "同施設・同引落日のデータが既に登録されています。 ";
            return redirect()->route('ctl.brAdditionalZengin.edit', [
                'hotel_cd' => $hotel_cd,
                'reason' => $reason,
                'reason_internal' => $reason_internal,
                'additional_charge' => $additional_charge
            ])->with([
                'errors' => $errorList
            ]);
        }

        // 指定した施設CDの施設情報・精算先・口座情報を取得する。
        $a_hotel_list = $this->getHotelList($hotel_cd);

        // 連番の取得
        $branch_id = $additionalZenginModel->getBranchId($o_date->to_format('Ym'));

        $requestAdditionalZengin['zengin_ym'] = $o_date->to_format('Ym'); //処理年月コード
        $requestAdditionalZengin['branch_id'] = $branch_id; //連番
        $requestAdditionalZengin['billpay_ymd'] = $o_date->to_format('Y/m/d'); //請求支払処理年月
        $requestAdditionalZengin['hotel_cd'] = $hotel_cd; //施設コード
        $requestAdditionalZengin['hotel_nm'] = $a_hotel_list['values'][0]['hotel_nm']; //施設名
        $requestAdditionalZengin['customer_id'] = $a_hotel_list['values'][0]['customer_id']; //精算先ID
        $requestAdditionalZengin['customer_nm'] = $a_hotel_list['values'][0]['customer_nm']; //精算先名
        $requestAdditionalZengin['factoring_bank_cd'] = $a_hotel_list['values'][0]['factoring_bank_cd']; //引落銀行コード
        $requestAdditionalZengin['factoring_bank_branch_cd']
            = $a_hotel_list['values'][0]['factoring_bank_branch_cd']; //引落支店コード
        $requestAdditionalZengin['factoring_bank_account_type']
            = $a_hotel_list['values'][0]['factoring_bank_account_type']; //引落口座種別
        $requestAdditionalZengin['factoring_bank_account_no']
            = $a_hotel_list['values'][0]['factoring_bank_account_no']; //引落口座番号
        $requestAdditionalZengin['factoring_bank_account_kn']
            = $a_hotel_list['values'][0]['factoring_bank_account_kn']; //引落口座名義（カナ）
        $requestAdditionalZengin['factoring_cd'] = $a_hotel_list['values'][0]['factoring_cd']; //引落顧客コード
        $requestAdditionalZengin['reason'] = $reason; //理由
        $requestAdditionalZengin['reason_internal'] = $reason_internal; //備考(内部のみ)
        $requestAdditionalZengin['additional_charge'] = $additional_charge; //追加金額
        //TODO user情報未設定？のため一旦非表示、user設定後に要再表示
        // $requestAdditionalZengin['staff_id'] = $this->box->user->operator->staff_id; //スタッフID
        $requestAdditionalZengin['notactive_flg'] = '0'; //削除フラグ

        // バリデーション
        //カラムの書き方をconstにしてみたので、validationの書き方も変えましたが合っていますでしょうか？
        $errorList = $additionalZenginModel->validation($requestAdditionalZengin);
        if (count($errorList) > 0) {
            $errorList[] = "更新確認情報を更新できませんでした。 ";
            return redirect()->route('ctl.brAdditionalZengin.edit', [
                'hotel_cd' => $hotel_cd,
                'reason' => $reason,
                'reason_internal' => $reason_internal,
                'additional_charge' => $additional_charge
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 共通カラム値設定
        $additionalZenginModel->setInsertCommonColumn($requestAdditionalZengin);

        // コネクション
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $additionalZenginModel, $requestAdditionalZengin) {
                // DB更新
                $additionalZenginModel->insert($con, $requestAdditionalZengin);
                //insertでいいか？
            });
        } catch (Exception $e) {
            $errorList[] = '更新確認情報の登録処理でエラーが発生しました。';
        }
        // 更新エラー
        if (count($errorList) > 0 || !empty($dbErr)) {
            $errorList[] = "更新確認情報を更新できませんでした。 ";
            return redirect()->route('ctl.brAdditionalZengin.edit', [
                'hotel_cd' => $hotel_cd,
                'reason' => $reason,
                'reason_internal' => $reason_internal,
                'additional_charge' => $additional_charge
            ])->with([
                'errors' => $errorList
            ]);
        }
        return redirect()->route('ctl.brAdditionalZengin.list');
    }

    /**
     * 指定した施設CDの施設情報・精算先・口座情報を取得する。
     *
     * @param string $hotel_cd
     * @return array $a_hotel_list
     */
    private function getHotelList($hotel_cd)
    {
        $o_models_hotel = new Hotel();
        // 指定した施設CDの施設情報を取得する
        $errrorArr = []; //引数が必要なため追記でいいか？
        $aa_condition = ['entry_status' => null ,'pref_id' => null ,'stock_type' => null,'keywords' => $hotel_cd];
        //↑元々は'keywords' => $hotel_cdのみだが引数で必要そう？なので追記。問題ないか？
        $a_hotel_list = $o_models_hotel->search($errrorArr, $aa_condition);

        // 指定した施設CDの精算先・口座情報を取得する
        $a_getfactoring = $this->getFactoring($hotel_cd);
        $a_hotel_list = json_decode(json_encode($a_hotel_list), true); //stdclassを配列にしないと下でエラーになる,blade側(edit)も配列での参照へ変更
        $a_hotel_list['values'][0] = array_merge($a_hotel_list['values'][0], $a_getfactoring);
        return  $a_hotel_list;
    }

    /**
     * 指定した施設の精算先・口座情報を取得する
     *
     * @param string $hotel_cd
     * @return array $a_factoring
     */
    private function getFactoring($hotel_cd)
    {
        //精算先、精算先関連施設 の インスタンスを取得
        $customer = new Customer();
        $customer_hotel = new CustomerHotel();

        $a_factoring = [
            'customer_id' => '',
            'customer_nm' => '',
            'factoring_flg' => '',
        ];

        $a_customer_hotel = $customer_hotel->selectByKey(['hotel_cd' => $hotel_cd]); //find→selectByKeyに変更でいいか？
        if ($a_customer_hotel) {
            $a_customer = $customer->selectByKey(['customer_id' => $a_customer_hotel['customer_id']]); //find→selectByKeyに変更でいいか？
            $f_factoring = false;
            if (
                ($a_customer['bill_way'] ?? null) == '1' && //null追記でいいか
                isset($a_customer['factoring_bank_cd']) && isset($a_customer['factoring_bank_branch_cd']) &&
                isset($a_customer['factoring_bank_account_type']) && isset($a_customer['factoring_bank_account_no']) &&
                isset($a_customer['factoring_bank_account_kn']) && isset($a_customer['factoring_cd'])
            ) {
                $f_factoring = true;
            }

            if ($f_factoring) {
                // 銀行名、支店名を取得する
                $this->getBankNm($a_customer);
            }

            $a_factoring = [
                'customer_id'        =>    $a_customer['customer_id'] ?? null, //null追記でいいか？
                'customer_nm'        =>    $a_customer['customer_nm'] ?? null, //null追記でいいか？
                'factoring_bank_cd'    =>    $a_customer['factoring_bank_cd'] ?? null, //null追記でいいか？
                'factoring_bank_nm'    =>    $a_customer['factoring_bank_nm'] ?? null, //null追記でいいか？
                'factoring_bank_branch_cd'        =>    $a_customer['factoring_bank_branch_cd'] ?? null, //null追記でいいか？
                'factoring_bank_branch_nm'        =>    $a_customer['factoring_bank_branch_nm'] ?? null, //null追記でいいか？
                'factoring_bank_account_type'    =>    $a_customer['factoring_bank_account_type'] ?? null, //null追記でいいか？
                'factoring_bank_account_no'        =>    $a_customer['factoring_bank_account_no'] ?? null, //null追記でいいか？
                'factoring_bank_account_kn'        =>    $a_customer['factoring_bank_account_kn'] ?? null, //null追記でいいか？
                'factoring_cd'        =>    $a_customer['factoring_cd'] ?? null, //null追記でいいか？
                'factoring_flg'        =>    $f_factoring,
                'bill_way'        =>    $a_customer['bill_way'] ?? null, //null追記でいいか？
            ];
        }
        return $a_factoring;
    }

    /**
     * 銀行名、支店名を取得する
     *
     * @param string $a_customer
     */
    private function getBankNm(&$a_customer)
    {
        // 銀行名、支店名 の インスタンスを取得
        $mast_bank = new MastBank();
        $mast_bank_branch = new MastBankBranch();

        $a_mast_bank = $mast_bank->selectByKey(['bank_cd' => $a_customer['factoring_bank_cd']]); //find→selectByKeyに変更でいいか？
        $a_mast_branch_bank = $mast_bank_branch->selectByKey(['bank_cd' => $a_customer['factoring_bank_cd']], ['bank_branch_cd' => $a_customer['factoring_bank_branch_cd']]); //find→selectByKeyに変更でいいか？

        $a_customer['factoring_bank_nm'] = $a_mast_bank['bank_nm'];
        $a_customer['factoring_bank_branch_nm'] = $a_mast_branch_bank['bank_branch_nm'];
    }

    /**
     * スタッフ名を取得する
     *
     * @param string $a_customer
     */
    private function getStaffNm(&$a_customer)
    {
        // スタッフ の インスタンスを取得
        $staff = new Staff();
        $a_staff = $staff->selectBykey(['staff_id' => $a_customer['staff_id']]);
        $a_customer['staff_nm'] = $a_staff['staff_nm'] ?? null; //null追記でいいか
    }
}
