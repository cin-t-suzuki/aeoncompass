<?php

namespace App\Http\Controllers\ctl;

use App\Models\MastPref;
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
        // TODO: 更新処理
        $search_params = [
        ];

		$mastPref = new MastPref();
		$mastPrefsData = $mastPref->getMastPrefs();

        return view('ctl.brPartnerCustomer.modify', [
            'partner_customer' => (new PartnerCustomer())->getPartnerCustomerById($request->get('customer_id')),
            'mast_pref' => $mastPrefsData,
            'search_params' => $search_params,

        ]);
    }

}
