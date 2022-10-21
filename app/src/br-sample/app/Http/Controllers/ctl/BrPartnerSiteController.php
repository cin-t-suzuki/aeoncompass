<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Models\PartnerSite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use function PHPUnit\Framework\isNull;

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
        $errors = [];

        // MEMO: 検索条件の引き回し HACK: session で管理したい
        $form_params = $request->input();

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
                    // TODO: error (redirect to search?)
                    // 現行に合わせるのであれば、すべてが空のフォームを表示（site_cd 必須バリデーションに引っかかるので、戻らざるを得ない）
                    $errors[] = '対象となる精算サイトは見つかりませんでした。';
                    $partner_site = (object)[
                        'site_cd'       => '', //$model->_get_sequence_no(),
                        'site_nm'       => '',
                        'person_post'   => '',
                        'person_nm'     => '',
                        'email_decrypt' => '',
                        'mail_send'     => 0,
                        'partner_cd'    => '',
                        'partner_nm'    => '',
                        'affiliate_cd'  => '',
                        'affiliate_nm'  => '',
                        'sales_customer_id' => '',
                        'sales_customer_nm' => '',
                    ];
                } else {
                    $partner_site = $partner_sites[0];
                }
            } else {
                // サイトコードが未指定の場合は、新規登録とする
                $form_params['site_cd'] = null; // MEMO: HACK: 現行仕様に合わせているが未定義だと Warning が発生する。
                // HACK: オブジェクトをうまく初期化する方法がありそう。
                $partner_site = (object)[
                    'site_cd'       => $model->_get_sequence_no(),
                    'site_nm'       => '',
                    'person_post'   => '',
                    'person_nm'     => '',
                    'email_decrypt' => '',
                    'mail_send'     => 0,
                    'partner_cd'    => '',
                    'partner_nm'    => '',
                    'affiliate_cd'  => '',
                    'affiliate_nm'  => '',
                    'sales_customer_id' => '',
                    'sales_customer_nm' => '',
                ];
            }
        }

        // 手数料率設定
        $rates = $model->_get_rates(['site_cd' => $partner_site->site_cd]);

        // 精算先情報設定
        $partner_customer_site = [];
        $partner_customer_site['customer_id'] = $partner_site->sales_customer_id;
        $partner_customer_site['customer_nm'] = $partner_site->sales_customer_nm;
        // サイトコードが未指定で精算先が指定されてきていて、精算先が確定してない場合は、指定されてきたIDを初期値として設定
        if (
                is_null($partner_customer_site['customer_id']) // TODO: 要確認（現行では is_empty() で判定）
            && $request->has('customer_id')
            && $request->input('customer_id') != 1
            && !$request->has('site_cd')
        ) {
            $partner_customer_site['customer_id'] = $request->input('customer_id');
        }

        // 料率設定
        $partner_site_rate = [];
        if (count($rates) > 0) {
            $partner_site_rate['rate_type']         = $rates[0]->rate_type;
            $partner_site_rate['select_rate_index'] = $rates[0]->select_rate_index;
            $partner_site_rate['accept_s_ymd']      = $rates[0]->accept_s_ymd;
        } else {
            $partner_site_rate['rate_type']         = null;
            $partner_site_rate['select_rate_index'] = null;
            $partner_site_rate['accept_s_ymd']      = null;
        }

        // MEMO: 検索条件の引き回し HACK: session で管理したい
        $search_params = [];
        if ($request->has('customer_id')) {
            $search_params['customer_id'] = $request->input('customer_id');
        }
        if ($request->has('customer_off')) {
            $search_params['customer_off'] = $request->input('customer_off');
        }
        if ($request->has('keywords')) {
            $search_params['keywords'] = $request->input('keywords');
        }

        return view('ctl.brPartnerSite.edit', [
            'errors'                => $errors,
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
