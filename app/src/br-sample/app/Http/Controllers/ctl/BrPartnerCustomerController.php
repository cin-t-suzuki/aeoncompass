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
        $model = new PartnerCustomer();
        $customers = $model->getPartnerCustomers();
        return view('ctl.brPartnerCustomer.search', [
            'customers' => $customers,
            'form_params' => [],
            'search_params' => ['hoge' => 'fuga'],
        ]);
    }

    public function search(Request $request)
    {
        // TODO: 確認 keywords 以外
        $params = $request->only(['keywords', 'partner_customer', 'customer_id', 'customer_off']);

        $model = new PartnerCustomer();
        // TODO: 暫定対処。 search_params を実装していないため、
        // 編集画面から戻った時に Undefined array key "keywords" エラーになる。
        $customers = $model->getPartnerCustomers($params['keywords'] ?? '');

        // TODO: これは何？（ビューで使われている）
        $search_params = [
            'key1' => 'item1',
            'key2' => 'item2',
            'customer_id' => 'non_output',
        ];
        $form_params = [
            'keywords' => $params['keywords'] ?? '', // TODO: 暫定対処 search_params 未実装
        ];

        return view('ctl.brPartnerCustomer.search', [
            'customers' => $customers,
            'form_params' => $form_params,
            'search_params' => $search_params,
        ]);
    }

    public function edit(Request $request, $customer_id)
    {
        // TODO: 何に使ってる？
        $search_params = [
        ];
        // TODO: 何に使ってる？
        $form_params = [
            'customer_id' => '1',
        ];

        // 編集対象を、 $customer_id をもとに取得
        $model = new PartnerCustomer();
        $partner_customer = $model->getPartnerCustomerById($customer_id);

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        return view('ctl.brPartnerCustomer.edit', [
            'partner_customer' => $partner_customer,
            'mast_pref' => $mastPrefsData,
            'search_params' => $search_params,
            'form_params' => $form_params,
        ]);
    }

    public function modify(Request $request)
    {
        $partner_customer = $request->input('partner_customer');
        // TODO: 更新処理

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
            // TODO: エラー処理、ここでうまいことやって、入力を保持して編集入力画面に返す。
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
            // TODO: エラー処理、同上（入力を保持して編集入力画面に返す。）
            return redirect()->route('brpartnercustomer.edit', ['customer_id' => $partner_customer['customer_id']]);
        }

        $search_params = [
        ];

        $mastPref = new MastPref();
        $mastPrefsData = $mastPref->getMastPrefs();

        return view('ctl.brPartnerCustomer.modify', [
            'partner_customer' => $model->getPartnerCustomerById($partner_customer['customer_id']),
            'mast_pref' => $mastPrefsData,
            'search_params' => $search_params,
            'guides' => ['更新が完了いたしました。'],
        ]);
    }

}
