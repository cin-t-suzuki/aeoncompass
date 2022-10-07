<?php

namespace App\Http\Controllers\ctl;

use App\Models\PartnerCustomer;
use Illuminate\Http\Request;

class BrPartnerCustomerController extends _commonController
{
    public function index(Request $request)
    {
        $model = new PartnerCustomer();
        $customers = $model->getPartnerCustomers();
        return view('ctl.brPartnerCustomer.search', [
            'customers' => $customers,
            'form_params' => [],
            'search_params' => [],
        ]);
    }

    public function search(Request $request)
    {
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

    public function edit(Request $request)
    {
        $search_params = [
        ];
        $form_params = [
            'customer_id' => '1',
        ];

        return view('ctl.brPartnerCustomer.edit', [
            'partner_customer' => $this->dummyPartnerCustomer(),
            'mast_pref' => $this->dummyMastPref(),
            'search_params' => $search_params,
            'form_params' => $form_params,
        ]);
    }

    public function modify(Request $request)
    {
        return view('ctl.brPartnerCustomer.modify');
    }

    private function dummyPartnerCustomer()
    {
        $partner_customer = [
            'customer_id'               => '0000000001',
            'customer_nm'               => '顧客名',
            'person_post'               => '顧客役職',
            'person_nm'                 => '担当者名',
            'postal_cd'                 => '郵便番号',
            'pref_id'                   => '5',
            'address'                   => '住所',
            'tel'                       => '電話番号',
            'fax'                       => 'ファックス番号',
            'email_decrypt'             => 'メールアドレス（平文）',
            'mail_send'                 => '1',
            'cancel_status'             => '0',
            'detail_status'             => '1',
            'billpay_day'               => '15',
            'billpay_required_month'    => '101100100101',
            'billpay_charge_min'        => 50234,
            'custmer_id'                => 1,
        ];
        return $partner_customer;
    }

    private function dummyMastPref()
    {
        $mast_pref = [
            'values' => [
                ['pref_id' => 1, 'pref_nm' => '北海道'],
                ['pref_id' => 2, 'pref_nm' => '青森'],
                ['pref_id' => 3, 'pref_nm' => '岩手'],
                ['pref_id' => 4, 'pref_nm' => '秋田'],
                ['pref_id' => 5, 'pref_nm' => '宮城'],
                ['pref_id' => 6, 'pref_nm' => '山形'],
            ],
        ];
        return $mast_pref;
    }
}
