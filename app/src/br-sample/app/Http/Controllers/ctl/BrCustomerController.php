<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Models\MastPref;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\MastBank;
use App\Models\MastBankBranch;
use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Requests\{
    CustomerRequest,
};
use App\Services\BrCustomerService as Service;

class BrCustomerController extends _commonController
{
    use Traits;

    private $default_customer_limit = 30;

    /**
     * 検索一覧表示
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        // $this->paramsは$request->inputでいいか？(このアクション内全体的に変更済)

        if ($request->session()->has('customer')) {
            // 遷移元データがあれば、入力を保持して表示
            $a_customer = $request->session()->pull('customer');
        } else {
            // 遷移元データがなければ、初期表示用データを表示
            $a_customer = $request->input('customer');
        }

        // 銀行検索から帰ってきた場合の値セット
        if (!$this->is_empty($request->input('mast_bank_branch_cd'))) {
            $a_cd = explode(',', $request->input('mast_bank_branch_cd')); //sprit→explodeでいいか？preg_splitもあり
            if ($request->input('is_fact') == 1) {
                $a_customer['factoring_bank_cd']        = $a_cd[0];
                $a_customer['factoring_bank_branch_cd'] = $a_cd[1];
            } else {
                $a_customer['payment_bank_cd']        = $a_cd[0];
                $a_customer['payment_bank_branch_cd'] = $a_cd[1];
            }
        }

        // マスタ共通オブジェクト取得
        $mastPrefModel  = new MastPref();
        $customerModel  = new Customer();

        // 都道府県の一覧データを配列で取得
        $a_mast_prefs = $mastPrefModel->getMastPrefs();

        // keywordsが存在しない場合のデフォルト値設定
        $a_customer_list = [];
        $n_cnt = 0;

        // keywordsが存在すれば検索
        if (!$this->is_empty($request->input('keywords'))) {
            // 取得条件を設定
            $a_conditions = [
                'pref_id'  => $request->input('pref_id'),
                'keywords' => $request->input('keywords')
            ];

            // 請求先・支払先を検索
             // 支払先が登録されていないとリスト検索には引っかからないようだがその仕様であっている？？
            $a_customer_list = $customerModel->search($a_conditions);

            // リスト下部、検件数表示制御　※取得件数と表示の件を比較　表示件数より取得件数の方が少なければ取得件数をいれる。
            if (count($a_customer_list['values']) < $this->default_customer_limit) {
                $n_cnt = count($a_customer_list['values']);
            } else {
                $n_cnt = $this->default_customer_limit;
            }
        }

        // 初期表示の場合の設定
        if ($this->is_empty($a_customer)) {
            // 請求最低金額のデフォルト値の設定
            $a_customer['bill_charge_min']    = 10000;
            // 支払最低金額のデフォルト値の設定
            $a_customer['payment_charge_min'] = 1000;

            //銀行、銀行支店のデフォルト値の設定　// nullで追記したが問題ないか？
            $a_customer['payment_bank_cd'] = null;
            $a_customer['payment_bank_branch_cd'] = null;
            $a_customer['factoring_bank_cd'] = null;
            $a_customer['factoring_bank_branch_cd'] = null;

            // 請求必須月 支払必須月 をデフォルト全てONにする
            $a_customer['bill_month01'] = 1;
            $a_customer['bill_month02'] = 1;
            $a_customer['bill_month03'] = 1;
            $a_customer['bill_month04'] = 1;
            $a_customer['bill_month05'] = 1;
            $a_customer['bill_month06'] = 1;
            $a_customer['bill_month07'] = 1;
            $a_customer['bill_month08'] = 1;
            $a_customer['bill_month09'] = 1;
            $a_customer['bill_month10'] = 1;
            $a_customer['bill_month11'] = 1;
            $a_customer['bill_month12'] = 1;

            $a_customer['payment_month01'] = 1;
            $a_customer['payment_month02'] = 1;
            $a_customer['payment_month03'] = 1;
            $a_customer['payment_month04'] = 1;
            $a_customer['payment_month05'] = 1;
            $a_customer['payment_month06'] = 1;
            $a_customer['payment_month07'] = 1;
            $a_customer['payment_month08'] = 1;
            $a_customer['payment_month09'] = 1;
            $a_customer['payment_month10'] = 1;
            $a_customer['payment_month11'] = 1;
            $a_customer['payment_month12'] = 1;
        }

        // プライマリキーの値を発行
        $n_sequence = $customerModel->getSequenceNo();
        $a_customer['customer_id'] = $n_sequence;

        // 銀行の取得　// ?? null追記でいいか
        $mast_bank = new MastBank();
        $a_bank = $mast_bank->selectByKey($a_customer['payment_bank_cd'] ?? null); //元ソースではfindを使用しているが、selectByKeyでいいか？(16文字以降切り捨てがない)
        // 銀行支店の取得　// ?? null追記でいいか
        $mast_bank_branch = new MastBankBranch();
        $a_bank_branch = $mast_bank_branch->selectByKey($a_customer['payment_bank_cd'] ?? null, $a_customer['payment_bank_branch_cd'] ?? null); //元ソースではfindを使用しているが、selectByKeyでいいか？(16文字以降切り捨てがない)

        // 引落銀行の取得　// ?? null追記でいいか
        $a_factoring_bank        = $mast_bank->selectByKey($a_customer['factoring_bank_cd'] ?? null);
        $a_factoring_bank_branch = $mast_bank_branch->selectByKey($a_customer['factoring_bank_cd'] ?? null, $a_customer['factoring_bank_branch_cd'] ?? null);

        $errors = $request->session()->get('errors', []);
        // errorsの渡し方でエラーが出てたため、以下のreturnも含め書き方変更し、blade側も変更しています。
        // editとこちらしか以下returnの書き方は変更していないが、他のメソッドも合わせるべき？？（合わせる場合はblade側も要修正）

        return view('ctl.brCustomer.list', [
            'customer_list'      => $a_customer_list,
            'mast_pref'    => $a_mast_prefs,
            'keywords'   => $request->input('keywords'),
            'limit'    => $this->default_customer_limit,
            'cnt'    => $n_cnt,
            'customer'    => $a_customer,
            'bank'         => $a_bank,
            'bank_branch' => $a_bank_branch,
            'factoring_bank'     => $a_factoring_bank,
            'factoring_bank_branch'     => $a_factoring_bank_branch,
            // 'guides'        => $guides,
            'errors'        => $errors
        ]);
    }

    /**
     * 銀行検索
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function banksearch(Request $request)
    {
        // $this->paramsは$request->inputでいいか？(このアクション内全体的に変更済)
        $a_customer = $request->input('customer');

        // 検索から戻ってくる際のURLを生成
            $is_fact = $request->query('is_fact') ?? null; //is_fact部分を取得(渡されないときはnullで、URLに出力しないよう設定)
            $next = route('ctl.brCustomer.list', ['customer' => $a_customer, 'is_fact' => $is_fact]);
            return redirect()->route('ctl.brbank.query')->with([
                'next' => $next,
                'keyword' => ''
            ]);
    }

    /**
     * 登録
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerRequest $request, Service $service)
    {
        // $this->paramsは$request->inputでいいか？(このアクション内全体的に変更済)
        $a_customer = $request->input('customer');

        // 銀行の存在確認（支払）
        $mast_bank = new MastBank();
        $a_bank = $mast_bank->selectByKey($a_customer['payment_bank_cd']); //find→selectByKeyでいいか
        if ($this->is_empty($a_bank) && !$this->is_empty($a_customer['payment_bank_cd'])) {
            $errorList[] = '支払銀行：該当する銀行コードが存在しません。';
            // list アクションに転送します
            return redirect()->route('ctl.brCustomer.list', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 銀行支店の存在確認（支払）
        $mast_bank_branch = new MastBankBranch();
        // ゆうちょ銀行の場合支店の確認はしない。
        if ($a_customer['payment_bank_cd'] != '9900') {
            $a_bank_branch = $mast_bank_branch->selectByKey($a_customer['payment_bank_cd'], $a_customer['payment_bank_branch_cd']); //find→selectByKeyでいいか
            if ($this->is_empty($a_bank_branch) && !$this->is_empty($a_customer['payment_bank_branch_cd'])) {
                $errorList[] = '支払銀行：該当する銀行支店コードが存在しません。';
                // list アクションに転送します
                return redirect()->route('ctl.brCustomer.list', [
                    'customer' => $a_customer
                ])->with([
                    'errors' => $errorList
                ]);
            }
        }

        // 銀行の存在確認（引落）
        $a_factoring_bank = $mast_bank->selectByKey($a_customer['factoring_bank_cd']); //find→selectByKeyでいいか
        if ($this->is_empty($a_factoring_bank) && !$this->is_empty($a_customer['factoring_bank_cd'])) {
            $errorList[] = '引落銀行：該当する銀行コードが存在しません。';
            // list アクションに転送します
            return redirect()->route('ctl.brCustomer.list', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 銀行支店の存在確認（引落）
        // ゆうちょ銀行の場合支店の確認はしない。
        if ($a_customer['factoring_bank_cd'] != '9900') {
            $a_factoring_bank_branch = $mast_bank_branch->selectByKey($a_customer['factoring_bank_cd'], $a_customer['factoring_bank_branch_cd']); //find→selectByKeyでいいか
            if ($this->is_empty($a_factoring_bank_branch) && !$this->is_empty($a_customer['factoring_bank_branch_cd'])) {
                $errorList[] = '引落銀行：該当する銀行支店コードが存在しません。';
                // list アクションに転送します
                return redirect()->route('ctl.brCustomer.list', [
                    'customer' => $a_customer
                ])->with([
                    'errors' => $errorList
                ]);
            }
        }

        // 引落銀行情報が引落顧客番号以外、全て入力されているか、もしくは全てNULLでなければエラー
        $w_bank_empty_check = 0;
        if ($this->is_empty($a_customer['factoring_bank_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['factoring_bank_branch_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['factoring_bank_account_no'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['factoring_bank_account_kn'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if (($w_bank_empty_check < 4) && ($w_bank_empty_check > 0)) {
            $errorList[] = '引落銀行の情報は引落顧客番号以外全て入力するか、全て空欄である必要があります。';
            // list アクションに転送します
            return redirect()->route('ctl.brCustomer.list', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }

        // 支払銀行情報が引落顧客番号以外、全て入力されているか、もしくは全てNULLでなければエラー
        $w_bank_empty_check = 0;
        if ($this->is_empty($a_customer['payment_bank_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['payment_bank_branch_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['payment_bank_account_no'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['payment_bank_account_kn'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if (($w_bank_empty_check < 4) && ($w_bank_empty_check > 0)) {
            $errorList[] = '支払銀行の情報は全て入力するか、全て空欄である必要があります。';
            // list アクションに転送します
            return redirect()->route('ctl.brCustomer.list', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }

        // 登録用に請求月を整形
        $a_customer['bill_required_month'] = ''; //初期値追記でいいか
        for ($i = 1; $i <= 12; $i++) {
            // 1月から１２月分 の１２桁の01の文字列、1が立ってる桁が請求月になります。（例 ４月請求 = 000100000000)
            $a_customer['bill_required_month'] .= ($a_customer['bill_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] ?? '0');
        }

        // 登録用に支払月を整形
        $a_customer['payment_required_month'] = ''; //初期値追記でいいか
        for ($i = 1; $i <= 12; $i++) {
            // 1月から１２月分 の１２桁の01の文字列、1が立ってる桁が支払月になります。（例 ４月支払 = 000100000000)
            $a_customer['payment_required_month'] .= ($a_customer['payment_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] ?? '0');
        }

        // モデルの取得
        $customerModel  = new Customer();

        // プライマリキーの値を設定,customer_idへ再代入
        $n_sequence = $customerModel->getSequenceNo();
        $a_customer['customer_id'] = $n_sequence;

        /* データ整形 */
        $customerData = $service->makeCustomerData($a_customer);

        // 共通カラム値設定
        $customerModel->setInsertCommonColumn($customerData);

        // コネクション
        $errorList = []; //初期化
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $customerModel, $customerData) {
                // DB更新
                $customerModel->insert($con, $customerData);
                //insertでいいか？
            });
        } catch (Exception $e) {
            $errorList[] = '精算先情報の登録処理でエラーが発生しました。';
        }
        // 更新エラー
        if (count($errorList) > 0 || !empty($dbErr)) {
            $errorList[] = "精算先情報を更新できませんでした。 ";
            return redirect()->route('ctl.brCustomer.list', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }

        $a_find_customer = $customerModel->find(['customer_id' => $a_customer['customer_id']]);
        // 請求月
        for ($i = 1; $i <= strlen($a_find_customer['bill_required_month']); $i++) {
            $a_find_customer['bill_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] = substr($a_find_customer['bill_required_month'], ($i - 1), 1);
        }

        // 支払月
        for ($i = 1; $i <= strlen($a_find_customer['payment_required_month']); $i++) {
            $a_find_customer['payment_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] = substr($a_find_customer['payment_required_month'], ($i - 1), 1);
        }
        $a_customer = $a_find_customer;

        $o_mast_pref   = new MastPref();
        $a_pref = $o_mast_pref->selectByKey($a_customer['pref_id']); //find→selectByKeyでいいか

        // ビュー情報を設定
        $this->addViewData("pref_nm", $a_pref['pref_nm']);
        $this->addViewData("customer", $a_customer);
        $this->addViewData("customer_id", $n_sequence);
        $this->addViewData("keywords", $request->input('keywords'));
        $this->addViewData("bank", $a_bank);
        $this->addViewData("bank_branch", $a_bank_branch);

        // 引落銀行の取得　//元ソースにはないが必要そう　// ?? null追記でいいか
        $a_factoring_bank        = $mast_bank->selectByKey($a_customer['factoring_bank_cd']);
        $a_factoring_bank_branch = $mast_bank_branch->selectByKey($a_customer['factoring_bank_cd'], $a_customer['factoring_bank_branch_cd']);
        $this->addViewData("factoring_bank", $a_factoring_bank);
        $this->addViewData("factoring_bank_branch", $a_factoring_bank_branch);

        // ビューを表示
        return view("ctl.brCustomer.create", $this->getViewData());
    }

    /**
     * 編集
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        try {
            if ($request->session()->has('customer')) {
                // 遷移元データがあれば、入力を保持して表示
                $a_customer = $request->session()->pull('customer');
            } else {
                // 遷移元データがなければ、初期表示用データを表示
                $a_customer = $request->input('customer');
            }

            // マスタ共通オブジェクト取得
            $mastPrefModel  = new MastPref();
            // 都道府県の一覧データを配列で取得
            $a_mast_prefs = $mastPrefModel->getMastPrefs();

            // 銀行検索から帰ってきた場合の値セット
            if (!$this->is_empty($request->input('mast_bank_branch_cd'))) {
                $a_cd = explode(',', $request->input('mast_bank_branch_cd'));
                if ($request->input('is_fact') == 1) {
                    $a_customer['factoring_bank_cd']        = $a_cd[0];
                    $a_customer['factoring_bank_branch_cd'] = $a_cd[1];
                } else {
                    $a_customer['payment_bank_cd']        = $a_cd[0];
                    $a_customer['payment_bank_branch_cd'] = $a_cd[1];
                }
            }

            // Customerモデルを取得
            $o_customer      = new Customer();
            $check = $request->all();
            $a_find_customer = $o_customer->find(['customer_id' => $request->input('customer_id')]);

            if ($this->is_empty($a_customer)) {
                // 請求月
                // top用に請求月を整形
                //?? null追記でいいか？(バリデーションエラーで戻ってきたときにa_customerがない)
                for ($i = 1; $i <= strlen($a_find_customer['bill_required_month'] ?? null); $i++) {
                    $a_find_customer['bill_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] = substr($a_find_customer['bill_required_month'], ($i - 1), 1);
                }

                // 支払月
                // top用に支払月を整形
                //?? null追記でいいか？(バリデーションエラーで戻ってきたときにa_customerがない)
                for ($i = 1; $i <= strlen($a_find_customer['payment_required_month'] ?? null); $i++) {
                    $a_find_customer['payment_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] = substr($a_find_customer['payment_required_month'], ($i - 1), 1);
                }

                // パラメーターが無い場合、初期値へ代入
                $a_customer = $a_find_customer;
            }

            //以下4か所 ?? null追記でいいか？(バリデーションエラーで戻ってきたときにa_customerがない)
            // 銀行の取得（支払）
            $mast_bank = new MastBank();
            $a_bank = $mast_bank->selectByKey(['bank_cd' => $a_customer['payment_bank_cd'] ?? null]); //find→selectByKeyでいいか
            // 銀行支店の取得（引落）
            $mast_bank_branch = new MastBankBranch();
            $a_bank_branch = $mast_bank_branch->selectByKey($a_customer['payment_bank_cd'] ?? null, $a_customer['payment_bank_branch_cd'] ?? null); //find→selectByKeyでいいか

            // 銀行の取得（引落）
            $a_factoring_bank = $mast_bank->selectByKey($a_customer['factoring_bank_cd'] ?? null); //find→selectByKeyでいいか
            // 銀行支店の取得（引落）
            $a_factoring_bank_branch = $mast_bank_branch->selectByKey($a_customer['factoring_bank_cd'] ?? null, $a_customer['factoring_bank_branch_cd'] ?? null); //find→selectByKeyでいいか


            //施設担当者変更履歴
            $s_sql =
                <<< SQL
                select 	c.branch_no,
                        c.section_nm,
                        c.person_post,
                        c.person_nm,
                        c.tel,
                        c.fax,
                        c.email,
                        c.entry_ts as entry_ts, -- to_char(c.entry_ts,'yyyy/mm/dd hh24:mi:ss')を削除
                        c.modify_ts as modify_ts -- to_char(c.modify_ts,'yyyy/mm/dd hh24:mi:ss')を削除
                from 	log_customer c
                where c.customer_id = :customer_id
                order by c.modify_ts desc
SQL;
            $a_log_customer = DB::select($s_sql, ['customer_id' => $request->input('customer_id')]);

            $cipher = new Models_Cipher(config('settings.cipher_key'));
            for ($n_cnt = 0; $n_cnt < count($a_log_customer); $n_cnt++) {
                // メールアドレス暗号化解除
                if (!$this->is_empty($a_log_customer[$n_cnt]->email)) {
                    try {
                        $a_log_customer[$n_cnt]->email = $cipher->decrypt($a_log_customer[$n_cnt]->email);
                        // 各メソッドで Exception が投げられた場合
                    } catch (Exception $e) {
                        true;
                    }
                }
            }

            $errors = $request->session()->get('errors', []);

            return view('ctl.brCustomer.edit', [
                'customer'      => $a_customer,
                'mast_pref'    => $a_mast_prefs,
                'keywords'   => $request->input('keywords'),
                'customer_id'    => $request->input('customer_id'),
                'bank'         => $a_bank,
                'bank_branch' => $a_bank_branch,
                'factoring_bank'     => $a_factoring_bank,
                'factoring_bank_branch'     => $a_factoring_bank_branch,
                'log_customer'     => $a_log_customer,
                // 'guides'        => $guides,
                'errors'        => $errors
            ]);

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Service $service)
    {
        $a_customer = $request->input('customer');

        // 銀行の存在確認（支払）
        $mast_bank = new MastBank();
        $a_bank = $mast_bank->selectByKey($a_customer['payment_bank_cd']); //find→selectByKeyでいいか
        if ($this->is_empty($a_bank) && !$this->is_empty($a_customer['payment_bank_cd'])) {
            $errorList[] = "該当する銀行コードが存在しません。 ";
            // edit アクションに転送します
            return redirect()->route('ctl.brCustomer.edit', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 銀行支店の存在確認（支払）
        $mast_bank_branch = new MastBankBranch();
        // 銀行支店の存在確認（引落）
        if ($a_customer['payment_bank_cd'] != '9900') {
            $a_bank_branch = $mast_bank_branch->selectByKey($a_customer['payment_bank_cd'], $a_customer['payment_bank_branch_cd']); //find→selectByKeyでいいか
            if ($this->is_empty($a_bank_branch) && !$this->is_empty($a_customer['payment_bank_branch_cd'])) {
                $errorList[] = "該当する銀行支店コードが存在しません。 ";
                // edit アクションに転送します
                return redirect()->route('ctl.brCustomer.edit', [
                    'customer' => $a_customer
                ])->with([
                    'errors' => $errorList
                ]);
            }
        }

        // 銀行の存在確認（引落）
        $a_factoring_bank = $mast_bank->selectByKey($a_customer['factoring_bank_cd']); //find→selectByKeyでいいか
        if ($this->is_empty($a_factoring_bank) && !$this->is_empty($a_customer['factoring_bank_cd'])) {
            $errorList[] = "引落銀行：該当する銀行コードが存在しません。 ";
            // edit アクションに転送します
            return redirect()->route('ctl.brCustomer.edit', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }
        // 銀行支店の存在確認（引落）
        if ($a_customer['factoring_bank_cd'] != '9900') {
            $a_factoring_bank_branch = $mast_bank_branch->selectByKey($a_customer['factoring_bank_cd'], $a_customer['factoring_bank_branch_cd']); //find→selectByKeyでいいか
            if ($this->is_empty($a_factoring_bank_branch) && !$this->is_empty($a_customer['factoring_bank_branch_cd'])) {
                $errorList[] = "引落銀行：該当する銀行支店コードが存在しません。 ";
                // edit アクションに転送します
                return redirect()->route('ctl.brCustomer.edit', [
                    'customer' => $a_customer
                ])->with([
                    'errors' => $errorList
                ]);
            }
        }

        // 引落銀行情報が引落顧客番号以外、全て入力されているか、もしくは全てNULLでなければエラー
        $w_bank_empty_check = 0;
        if ($this->is_empty($a_customer['factoring_bank_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['factoring_bank_branch_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['factoring_bank_account_no'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['factoring_bank_account_kn'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if (($w_bank_empty_check < 4) && ($w_bank_empty_check > 0)) {
            $errorList[] = "引落銀行の情報は引落顧客番号以外全て入力するか、全て空欄である必要があります。 ";
            // edit アクションに転送します (元ソースはlistに転送だが、editの方がよさそうなので修正でいい？)
            return redirect()->route('ctl.brCustomer.edit', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }

        // 支払銀行情報が引落顧客番号以外、全て入力されているか、もしくは全てNULLでなければエラー
        $w_bank_empty_check = 0;
        if ($this->is_empty($a_customer['payment_bank_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['payment_bank_branch_cd'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['payment_bank_account_no'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if ($this->is_empty($a_customer['payment_bank_account_kn'])) {
            $w_bank_empty_check = $w_bank_empty_check + 1;
        }
        if (($w_bank_empty_check < 4) && ($w_bank_empty_check > 0)) {
            $errorList[] = "支払銀行の情報は全て入力するか、全て空欄である必要があります。 ";
            // edit アクションに転送します (元ソースはlistに転送だが、editの方がよさそうなので修正でいい？)
            return redirect()->route('ctl.brCustomer.edit', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }

        // 登録用に請求月を整形
        $a_customer['bill_required_month'] = ''; //初期値追記でいいか
        for ($i = 1; $i <= 12; $i++) {
            // 1月から１２月分 の１２桁の01の文字列、1が立ってる桁が請求月になります。（例 ４月請求 = 000100000000)
            $a_customer['bill_required_month'] .= ($a_customer['bill_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] ?? '0');
        }

        // 登録用に支払月を整形
        $a_customer['payment_required_month'] = ''; //初期値追記でいいか
        for ($i = 1; $i <= 12; $i++) {
            // 1月から１２月分 の１２桁の01の文字列、1が立ってる桁が支払月になります。（例 ４月支払 = 000100000000)
            $a_customer['payment_required_month'] .= ($a_customer['payment_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] ?? '0');
        }

        // モデルの取得
        $customerModel  = new Customer();
        $customerData = $customerModel->selectByKey(['customer_id' => $request->input('customer_id')]); //find→selectByKeyでいいか

        /* データ整形 */
        $customerData = $service->makeCustomerData($a_customer);

        // 共通カラム値設定
        $customerModel->setUpdateCommonColumn($customerData);

        // 更新件数
        $dbCount = 0;
        // コネクション
        $errorList = []; //初期化
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $customerModel, $customerData, &$dbCount) {
                // DB更新
                $dbCount = $customerModel->updateByKey($con, $customerData);
                //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
            });
        } catch (Exception $e) {
            $errorList[] = '精算先情報の更新処理でエラーが発生しました。';
        }
        // 更新エラー
        if (
            $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
        ) {
            //改行いらないのでは？ $errorList[] = "更新エラー<br>トップページよりやり直してください ";
            $errorList[] = "更新エラー：トップページよりやり直してください ";
            // edit アクションに転送します
            return redirect()->route('ctl.brCustomer.edit', [
                'customer' => $a_customer
            ])->with([
                'errors' => $errorList
            ]);
        }

        $a_find_customer = $customerModel->selectByKey(['customer_id' => $a_customer['customer_id']]); //find→selectByKeyでいいか

        // 請求月
        for ($i = 1; $i <= strlen($a_find_customer['bill_required_month']); $i++) {
            $a_find_customer['bill_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] = substr($a_find_customer['bill_required_month'], ($i - 1), 1);
        }

        // 支払月
        for ($i = 1; $i <= strlen($a_find_customer['payment_required_month']); $i++) {
            $a_find_customer['payment_month' . str_pad($i, 2, 0, STR_PAD_LEFT)] = substr($a_find_customer['payment_required_month'], ($i - 1), 1);
        }
        $a_customer = $a_find_customer;

        $o_mast_pref   = new MastPref();
        $a_pref = $o_mast_pref->selectByKey($a_customer['pref_id']);

        // ビュー情報を設定
        $this->addViewData("pref_nm", $a_pref['pref_nm']);
        $this->addViewData("customer", $a_customer);
        $this->addViewData("customer_id", $request->input('customer_id'));
        $this->addViewData("keywords", $request->input('keywords'));
        $this->addViewData("bank", $a_bank);
        $this->addViewData("bank_branch", $a_bank_branch);
        $this->addViewData("factoring_bank", $a_factoring_bank);
        $this->addViewData("factoring_bank_branch", $a_factoring_bank_branch);

        // ビューを表示
        return view("ctl.brCustomer.update", $this->getViewData());
    }

    /**
     * 上付サンプル
     *
     * @return \Illuminate\Http\Response
     */
    public function sendletter()
    {
        // ビューを表示
        return view("ctl.brCustomer.sendletter", $this->getViewData());
    }


    /**
     * 請求先全件のCSVダウンロード　brhotelから呼び出される
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function csv(Request $request)
    {
        $customerModel  = new Customer();

        //アサイン登録
        // $this->box->item->assign->customers = $customerModel->search();
        // $this->set_assign();
        $customers = $customerModel->search();
        $this->addViewData("customers", $customers); //ここちがそう

        //CSV出力方法を元ソースから全般的に変更しているが問題ないか？
        $header = $customerModel->setCsvHeader($customers); //renderでbladeからではなく、モデルからの取得に変更
        $data = $customerModel->setCsvData($customers);
        $csvList = array_merge([$header], $data);

        $response = new StreamedResponse(function () use ($request, $csvList) {
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            // CSVデータ
            foreach ($csvList as $key => $value) {
                fputcsv($stream, $value);
            }
            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="customers.csv"');

        // print $s_response;
        return $response;
        exit;
    }
}
