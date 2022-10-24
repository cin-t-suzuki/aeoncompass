<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Http\Controllers\ctl\_commonController;
use App\Models\PartnerSite;
use App\Models\Partner;
use App\Models\PartnerCustomer;
use App\Models\AffiliateProgram;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use function PHPUnit\Framework\isNull;

class BrPartnerSiteController extends _commonController
{
    use Traits;

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
        $keywords = $request->input('keywords');

        $customer_id  = $request->input('customer_id');
        $customer_off = $request->input('customer_off');
        $site_cd      = $request->input('site_cd');

        $model = new PartnerSite();
        $sites = $model->getPartnerSiteByKeywords($keywords, $customer_id, $customer_off, $site_cd);

        $form_params = $request->input();

        // MEMO: 検索条件の引き回し HACK: session で管理したい？
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

        // 精算先名称を検索内容用に設定
        $customer = [];
        if ($request->has('customer_id')) {
            // HACK: DB から取得しているデータに含まれていればそれを使い、なければ DB から取得
            if ($request->input('customer_id') == $sites[0]->stock_customer_id) {
                $customer['customer_nm'] = $sites[0]->stock_customer_nm;
            } else if ($request->input('customer_id') == $sites[0]->sales_customer_id) {
                $customer['customer_nm'] = $sites[0]->sales_customer_nm;
            } else {
                $partnerCustomer = PartnerCustomer::findOrFail($request->input('customer_id'));
                $customer['customer_nm'] = $partnerCustomer->customer_nm;
            }
        }

        $request->session()->put('keywords', $keywords);
        return view('ctl.brPartnerSite.search', [
            'sites'         => $sites,
            'keywords'      => $keywords,
            'form_params'   => $form_params,
            'search_params' => $search_params,
            'customer'      => $customer,
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

        // HACK: validation の責務は、コントローラが負うほうが適切に思われる（要調査）。

        $model = new PartnerSite();

        // 精算サイト登録情報設定
        $a_site          = $request->input('partner_site');
        $a_site_rate     = $request->input('partner_site_rate');
        $a_customer_site = $request->input('partner_customer_site');
        $a_rate = $model->_get_rates(['site_cd' => $a_site['site_cd']]);

        // 料率タイプがNTA向けの場合は、販売向けの精算先の登録なし。
        //10(GBTNTA)は販売のみなので、↓の処理を通らないようにする。
        // HACK: Magic Number
        if ($a_site_rate['rate_type'] >= 6 && $a_site_rate['rate_type'] != 10) {
            $a_customer_site['customer_id'] = null;
        }

        // // 精算サイト登録
        // // TODO: implement _insert_site
        // if ($this->_insert_site($a_site)) {
        //     // 精算先・サイト関連の登録
        //     // TODO: implement _insert_customer_site
        //     $this->_insert_customer_site($a_site['site_cd'], $a_customer_site['customer_id']);

        //     // 精算サイトの情報取得
        //     $a_sites = $this->_get_sites(array('site_cd' => $a_site['site_cd']));
        //     $a_site  = $a_sites[0];
        //     $a_customer_site['customer_nm'] = $a_site['sales_customer_nm'];

        // // 登録失敗した場合、表示ように変更予定のパートナーとアフィリエイトの名称を設定する。
        // } 
        // // TODO: implement
        // else {
        //     // パートナー
        //     if (!$this->is_empty($a_site['parner_cd'])) {
        //         $o_partner      = Partner::getInstance();
        //         $a_partner = $o_partner->find(array('partner_cd' => $a_site['parner_cd']));
        //         $a_site['partner_nm'] = $a_partner['system_nm'];
        //     }
        //     // アフィリエイト
        //     if (!$this->is_empty($a_site['affiliate_cd'])) {
        //         $o_affiliate = AffiliateProgram::getInstance();
        //         $a_affiliate = $o_affiliate->find(array('affiliate_cd' => $a_site['affiliate_cd']));
        //         $a_site['affiliate_nm'] = $a_affiliate['program_nm'];
        //     }
        //     // 精算先
        //     if (!$this->is_empty($a_customer_site['customer_id'])) {
        //         $o_customer = PartnerCustomer::getInstance();
        //         $a_customer = $o_customer->find(array('customer_id' => $a_customer_site['customer_id']));
        //         $a_customer_site['customer_nm'] = $a_customer['customer_nm'];
        //     }
        // }

        // TODO: 本実装
        $partner_site = $request->input('partner_site');
        $partner_site['email_decrypt'] = $partner_site['email'];
        $partner_site['partner_nm'] = '';
        $partner_site['affiliate_nm'] = '';

        // TODO: 本実装
        $partner_site_rate = $request->input('partner_site_rate');
        $partner_customer_site = $request->input('partner_customer_site');

        // TODO: 本実装
        $partner_customer_site['customer_nm'] = '';


        // TODO: 本実装
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


        return view('ctl.brPartnerSite.modify', [
            'partner_site'          => (object)$partner_site,
            'partner_site_rate'     => (object)$partner_site_rate,
            'partner_customer_site' => (object)$partner_customer_site,
            'search_params'         => $search_params,


        ]);
    }
}
