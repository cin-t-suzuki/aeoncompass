<?php

namespace App\Http\Controllers\ctl;

use App\Models\MastPref;
use App\Models\PartnerCustomer;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrPartnerCustomerController extends _commonController
{
    /**
     * 初期表示
     */
    public function index(Request $request)
    {
        $request->session()->forget('keywords');
        return redirect()->route('brpartnercustomer.search');
    }

    /**
     * 検索一覧画面表示
     */
    public function search(Request $request)
    {
        // 検索ワードが request に含まれればそれを適用する
        // そうでなければ session を見て、あればそれを適用する
        if ($request->has('keywords')) {
            $keywords = $request->input('keywords');
        } else {
            $keywords = $request->session()->pull('keywords', '');
        }

        $model = new PartnerCustomer();
        $customers = $model->getPartnerCustomers($keywords);

        $request->session()->put('keywords', $keywords);
        return view('ctl.brPartnerCustomer.search', [
            'customers' => $customers,
            'keywords' => $keywords,
        ]);
    }

    /**
     * 新規登録入力画面表示
     */
    public function create(Request $request)
    {
        // session に情報が含まれる場合は、入力を保持して表示
        // そうでなければ初期化して表示
        if ($request->session()->has('partner_customer')) {
            $partner_customer = (object)$request->session()->pull('partner_customer');
            $partner_customer->customer_id = '－';
        } else {
            $partner_customer = (object)[
                'customer_id'            => '－',
                'customer_nm'            => '',
                'postal_cd'              => '',
                'pref_id'                => '',
                'address'                => '',
                'tel'                    => '',
                'fax'                    => '',
                'email'                  => '',
                'person_post'            => '',
                'person_nm'              => '',
                'mail_send'              => '0',
                'cancel_status'          => '0',
                'tax_unit'               => '1',
                'detail_status'          => '0',
                'billpay_day'            => '8',
                'billpay_required_month' => '000000000000',
                'billpay_charge_min'     => '',
            ];
        }

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        return view('ctl.brPartnerCustomer.create', [
            'partner_customer' => $partner_customer,
            'mast_pref'        => $mastPrefsData,
            'errors'           => $request->session()->pull('errors', []),
        ]);
    }

    /**
     * 新規登録処理、完了画面表示
     */
    public function register(Request $request)
    {
        $partner_customer = $request->input('partner_customer');

        $this->convertBillpayRequiredMonth($partner_customer);

        // validation
        $model = new PartnerCustomer();
        $partner_customer['customer_id'] = $model->_get_sequence_no();
        $error_list = $model->validation($partner_customer);

        if (count($error_list) > 0) {
            $error_list[] = '登録できませんでした。';
            // HACK: もっと laravel ぽい書き方がありそう…？ withInput, withErrors
            $request->session()->put('partner_customer', $partner_customer);
            $request->session()->put('errors', $error_list);
            return redirect()->route('brpartnercustomer.create');
        }

        // メールアドレス暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $partner_customer['email'] = $cipher->encrypt($partner_customer['email']);

        // 共通カラム値設定
        $model->setInsertCommonColumn($partner_customer, 'CtlPartnerCustomer/create.');

        // コネクション
        try {
            $con = DB::connection('mysql');
            $db_error = $con->transaction(function() use($con, $model, $partner_customer)
            {
                $model->singleInsert($con, $partner_customer);
            });
        } catch (\Exception $e) {
            $error_list[] = '登録できませんでした。';
        }

        if (count($error_list) > 0 || !empty($db_error)) {
            // HACK: もっと laravel ぽい書き方がありそう…？ withInput, withErrors
            $partner_customer['mail'] = $cipher->decrypt($partner_customer['email']); // HACK: mail <=> mail_decrypt の扱いを整理したい。
            $request->session()->put('partner_customer', $partner_customer);
            $request->session()->put('errors', $error_list);
            return redirect()->route('brpartnercustomer.create');
        }

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        // 登録完了と更新完了のビューは共通で使える HACK: 共通であることがわかる命名
        return view('ctl.brPartnerCustomer.modify', [
            'partner_customer' => $model->getPartnerCustomersById($partner_customer['customer_id'])[0],
            'mast_pref'        => $mastPrefsData,
            'guides'           => ['完了いたしました'],
            'errors'           => $error_list, // unreachable!
        ]);

    }

    /**
     * 編集入力画面表示
     */
    public function edit(Request $request, $customer_id)
    {
        // session に情報が含まれる場合は、入力を保持して表示
        // そうでなければ DB から取得して表示
        if ($request->session()->has('partner_customer')) {
            $partner_customer = (object)$request->session()->pull('partner_customer');
        } else {
            // 編集対象を、 $customer_id をもとに取得
            $model = new PartnerCustomer();
            $partner_customers = $model->getPartnerCustomersById($customer_id);
            if (count($partner_customers) > 0) {
                $partner_customer = $partner_customers[0];
            } else {
                $request->session()->put('errors', ['対象となる精算先は見つかりませんでした']);
                return redirect()->route('brpartnercustomer.create');
            }
        }

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        return view('ctl.brPartnerCustomer.edit', [
            'partner_customer' => $partner_customer,
            'mast_pref' => $mastPrefsData,
            'customer_id' => $customer_id,
            'errors' =>  $request->session()->pull('errors', []),
        ]);
    }

    /**
     * 編集処理、完了画面表示
     */
    public function modify(Request $request)
    {
        $partner_customer = $request->input('partner_customer');

        $this->convertBillpayRequiredMonth($partner_customer);

        // validation
        $model = new PartnerCustomer();
        $error_list = $model->validation($partner_customer);

        if (count($error_list) > 0) {
            $error_list[] = '更新できませんでした。';
            // HACK: もっと laravel ぽい書き方がありそう…？
            $request->session()->put('partner_customer', $partner_customer);
            $request->session()->put('errors', $error_list);
            return redirect()->route('brpartnercustomer.edit', ['customer_id' => $partner_customer['customer_id']]);
        }

        // メールアドレス暗号化
        $cipher = new Models_Cipher(config('settings.cipher_key'));
        $partner_customer['email'] = $cipher->encrypt($partner_customer['email']);

        // 共通カラム値設定
        $model->setUpdateCommonColumn($partner_customer, 'CtlPartnerCustomer/update.');

        // コネクション
        try {
            $con = DB::connection('mysql');
            $db_error = $con->transaction(function() use($con, $model, $partner_customer)
            {
                $model->updateByKey($con, $partner_customer);
            });
        } catch (\Exception $e) {
            $error_list[] = '更新できませんでした。';
        }

        if (count($error_list) > 0 || !empty($db_error)) {
            // HACK: もっと laravel ぽい書き方がありそう…？
            $partner_customer['mail'] = $cipher->decrypt($partner_customer['email']); // HACK: mail <=> mail_decrypt の扱いを整理したい。
            $request->session()->put('partner_customer', $partner_customer);
            $request->session()->put('errors', $error_list);
            return redirect()->route('brpartnercustomer.edit', ['customer_id' => $partner_customer['customer_id']]);
        }

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        return view('ctl.brPartnerCustomer.modify', [
            'partner_customer'  => $model->getPartnerCustomersById($partner_customer['customer_id'])[0],
            'mast_pref'         => $mastPrefsData,
            'guides'            => ['完了いたしました'],
            'errors'            => $error_list, // unreachable!
        ]);
    }

    /**
     * 精算必須月のパラメータ調整（参照渡し）
     *
     * billpay_month01 ~ 12 までを、'0'と'1'からなる長さ12の文字列に変換
     * i 月が必須月として指定されていたら、 i 番目の文字が '1' になる
     *
     * billpay_month01 ~ billpay_month12 を配列からクリア
     * billpay_required_month を配列にセット
     *
     * @param array &$partner_customer
     */
    private function convertBillpayRequiredMonth(&$partner_customer)
    {
        $billpay_required_month = '';
        for($m = 1; $m <= 12; $m++) {
            $field_nm = 'billpay_month' . sprintf("%02d", $m);
            $billpay_required_month .= $partner_customer[$field_nm] ?? '0';
            unset($partner_customer[$field_nm]);
        }
        $partner_customer['billpay_required_month'] = $billpay_required_month;
    }
}
