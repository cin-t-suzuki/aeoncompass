<?php

namespace App\Http\Controllers\ctl;

use App\Models\MastPref;
use App\Models\PartnerCustomer;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrPartnerCustomerController extends _commonController
{
    public function index(Request $request)
    {
        $request->session()->forget('keywords');
        return redirect()->route('brpartnercustomer.search');
    }

    public function search(Request $request)
    {
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

    public function create(Request $request)
    {
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

    public function register(Request $request)
    {
        $partner_customer = $request->input('partner_customer');

        // 精算月のパラメータ調整
        // HACK: 共通化？
        $partner_customer['billpay_required_month'] = null;
        for($m = 1; $m <= 12; $m++) {
            $field_nm = 'billpay_month' . sprintf("%02d", $m);
            $partner_customer['billpay_required_month'] .= $partner_customer[$field_nm] ?? '0';
            unset($partner_customer[$field_nm]);
        }

        // validation
        $model = new PartnerCustomer();
        $partner_customer['customer_id'] = $model->_get_sequence_no();
        $error_list = $model->validation($partner_customer);

        if (count($error_list) > 0) {
            $error_list[] = '登録できませんでした。';
            // HACK: もっといい方法ありそう？ withInput, withErrors
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
            // HACK: もっといい方法ありそう？ withInput, withErrors
            $partner_customer['mail'] = $cipher->decrypt($partner_customer['email']); // HACK: mail <=> mail_decrypt の扱いを整理したい。
            $request->session()->put('partner_customer', $partner_customer);
            $request->session()->put('errors', $error_list);
            return redirect()->route('brpartnercustomer.create');
        }

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        // 登録完了と更新完了は共通 HACK: 共通であることがわかる命名
        return view('ctl.brPartnerCustomer.modify', [
            'partner_customer' => $model->getPartnerCustomersById($partner_customer['customer_id'])[0],
            'mast_pref'        => $mastPrefsData,
            'guides'           => ['完了いたしました'],
            'errors'           => $error_list, // unreachable!
        ]);

    }

    public function edit(Request $request, $customer_id)
    {
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

    public function modify(Request $request)
    {
        $partner_customer = $request->input('partner_customer');

        // 精算月のパラメータ調整
        $partner_customer['billpay_required_month'] = null;
        for($m = 1; $m <= 12; $m++) {
            $field_nm = 'billpay_month' . sprintf("%02d", $m);
            $partner_customer['billpay_required_month'] .= $partner_customer[$field_nm] ?? '0';
            unset($partner_customer[$field_nm]);
        }

        // validation
        $model = new PartnerCustomer();
        $error_list = $model->validation($partner_customer);

        if (count($error_list) > 0) {
            $error_list[] = '更新できませんでした。';
            // HACK: もっといい方法ありそう？
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
            // HACK: もっといい方法ありそう？
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

}
