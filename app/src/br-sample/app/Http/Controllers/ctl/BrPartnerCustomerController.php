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
        $customers = $model->getPartnerCustomers($params['keywords']);

        // TODO: これは何？（ビューで使われている）
        $search_params = [
            'key1' => 'item1',
            'key2' => 'item2',
            'customer_id' => 'non_output',
        ];
        $form_params = [
            'keywords' => $params['keywords'],
        ];

        return view('ctl.brPartnerCustomer.search', [
            'customers' => $customers,
            'form_params' => $form_params,
            'search_params' => $search_params,
        ]);
    }

    public function edit(Request $request)
    {
        return view('ctl.brPartnerCustomer.edit');
    }

}
