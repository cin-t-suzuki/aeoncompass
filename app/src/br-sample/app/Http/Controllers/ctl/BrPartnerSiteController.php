<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Models\PartnerSite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class BrPartnerSiteController extends _commonController
{
    /**
     * TODO: phpdoc
     */
    public function index(Request $request)
    {
        return redirect()->route('ctl.brPartnerSite.search');
    }

    /**
     * TODO: phpdoc
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

        $customer_id = $request->input('customer_id', '');
        $customer_off = $request->input('customer_off', '');

        $model = new PartnerSite();
        $sites = $model->getPartnerSiteByKeywords($keywords, $customer_id, $customer_off);

        $request->session()->put('keywords', $keywords);
        return view('ctl.brPartnerSite.search', [
            'sites' => $sites,
            'keywords' => $keywords,
            'form_params' => [
                'customer_id' => $customer_id,
                'customer_off' => $customer_off,
            ],
        ]);
    }

    /**
     * TODO: phpdoc
     */
    public function edit(Request $request)
    {
		// 精算先登録情報設定
        $model = new PartnerSite();
        if ($request->has('partner_site')) {
            // リクエストから指定されている場合は、それを利用
            $partner_site = (object)$request->input('partner_site');
        } else {
            if ($request->has('site_cd')) {
                // リクエストから指定されていない場合は DB から取得
                $partner_sites = $model->_get_sites(['site_cd' => $request->input('site_cd')]);

                if (count($partner_sites) < 1) {
                    // TODO: error
                } else {
                    $partner_site = $partner_sites[0];
                }
            } else {
                // サイトコードが未指定の場合は、新規登録とする
                $partner_site = (object)[
                    'site_cd' => $model->_get_sequence_no(),
                    'site_nm' => '',
                    'person_post' => '',
                    'person_nm' => '',
                    'email_decrypt' => '',
                    'mail_send' => 0,
                    'partner_cd' => '',
                    'partner_nm' => '',
                    'affiliate_cd' => '',
                    'affiliate_nm' => '',
                ];
            }
        }


        // TODO:
        $form_params = [];
        $form_params['site_cd'] = $request->input('site_cd', '');

        // TODO:
        $search_params = [];


        // TODO:
        $partner_site_rate = (object)[
            'select_rate_index' => 0,
            'accept_s_ymd' => '',
        ];

        // TODO:
        $partner_customer_site = (object)[
            'customer_id' => '',
            'customer_nm' => '',
        ];

        // TODO:
        $rates = [
            (object)[
                'accept_s_ymd' => '',
            ],
            (object)[
                'accept_s_ymd' => '',
            ],
        ];

        return view('ctl.brPartnerSite.edit', [
            'errors' => [
                'dummy error',
            ],
            'form_params'           => $form_params,
            'search_params'         => $search_params,
            'partner_site'          => $partner_site,
            'partner_site_rate'     => $partner_site_rate,
            'partner_customer_site' => $partner_customer_site,
            'rates'                 => $rates,

        ]);
    }

    /**
     * TODO: phpdoc
     */
    public function modify(Request $request)
    {
        return 'TODO: modify controller';
    }
}
