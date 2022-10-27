<?php

namespace App\Http\Controllers\ctl;

use App\Common\Traits;
use App\Http\Controllers\ctl\_commonController;
use App\Models\AffiliateProgram;
use App\Models\PartnerSite;
use App\Models\Partner;
use App\Models\PartnerCustomer;
use App\Models\PartnerCustomerSite;
use App\Models\PartnerSiteRate;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class BrPartnerSiteController extends _commonController
{
    use Traits;

    // TODO: 定数定義箇所、要検討
    const RATE_PATTERN_UNSPECIFIED                 = 0;  // 0:指定なし
    const RATE_PATTERN_SPECIAL_ALLIANCE_0_PERCENT  = 1;  // 1:特別提携    0% ベストリザーブオリジナルサイト・光通信等
    const RATE_PATTERN_NORMAL_ALLIANCE_1_PERCENT   = 2;  // 2:通常提携    1%
    const RATE_PATTERN_SPECIAL_ALLIANCE_2_PERCENT  = 3;  // 3:特別提携    2% アークスリー等
    const RATE_PATTERN_NTA_BTM                     = 4;  // 4:日本旅行ビジネストラベルマネージメント（BTM）
    const RATE_PATTERN_YAHOO_TRAVEL                = 5;  // 5:Yahoo!トラベル
    const RATE_PATTERN_NTA_2_PERCENT               = 6;  // 6:日本旅行    2%
    const RATE_PATTERN_NTA_3_PERCENT               = 7;  // 7:日本旅行    3% MSD等
    const RATE_PATTERN_NTA_4_PERCENT               = 8;  // 8:日本旅行    4% JRおでかけネット
    const RATE_PATTERN_NTA_RELO_CLUB               = 9;  // 9:日本旅行    リロクラブ
    const RATE_PATTERN_GBTNTA                      = 10; // 10:GBTNTA 1%(在庫手数料0%)

    const FEE_TYPE_SALE  = 1;
    const FEE_TYPE_STOCK = 2;

    const STOCK_CLASS_GENERAL_ONLINE_STOCK = 1; // 一般ネット在庫
    const STOCK_CLASS_LINKED_STOCK_NORMAL  = 2; // 連動在庫（通常）
    const STOCK_CLASS_LINKED_STOCK_VISUAL  = 3; // 連動在庫（ヴィジュアル）
    const STOCK_CLASS_LINKED_STOCK_PREMIUM = 4; // 連動在庫（プレミアム）
    const STOCK_CLASS_TOYOKO_INN_STOCK     = 5; // 東横イン在庫

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
     * TODO: Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function modify(Request $request)
    {
        // HACK: validation の責務は、コントローラが負うほうが適切に思われる（要調査）。

        $modelPartnerSite = new PartnerSite();

        // 精算サイト登録情報設定
        $partnerSite     = $request->input('partner_site');
        $a_site_rate     = $request->input('partner_site_rate');
        $a_customer_site = $request->input('partner_customer_site');

        // キーの初期値を設定
        $a_customer_site['customer_nm'] = null;

        // 料率タイプがNTA向けの場合は、販売向けの精算先の登録なし。
        // ただし、10(GBTNTA)は販売のみなので例外
        // HACK: hardcoding business logic
        // MEMO: rate_type (0 ~ 10) に応じて、 partner_site_rate の fee_type, stock_class, rate のパターンが決まる
        $stockOnly = [
            self::RATE_PATTERN_NTA_2_PERCENT,
            self::RATE_PATTERN_NTA_3_PERCENT,
            self::RATE_PATTERN_NTA_4_PERCENT,
            self::RATE_PATTERN_NTA_RELO_CLUB,
        ];
        if (in_array($a_site_rate['rate_type'], $stockOnly)) {
            $a_customer_site['customer_id'] = null;
        }

        try {
            DB::beginTransaction();

            // 精算サイト登録
            $errorList = $this->_insert_partner_site($partnerSite);
            if (count($errorList) === 0) {
                // 精算先・サイト関連の登録
                $this->_insert_partner_customer_site($partnerSite['site_cd'], $a_customer_site['customer_id']);

                // 精算サイトの情報取得
                $a_sites = $modelPartnerSite->_get_sites(['site_cd' => $partnerSite['site_cd']]);
                $partnerSite = $a_sites[0];
                $a_customer_site['customer_nm'] = $partnerSite['sales_customer_nm'];
            } else {
                // キーの初期値を設定
                $partnerSite['partner_nm'] = null;
                $partnerSite['affiliate_nm'] = null;

                // 登録失敗した場合、画面表示用に変更予定のパートナーとアフィリエイトの名称を設定
                if (!is_null($partnerSite['partner_cd'])) {
                    // TODO: Partner モデルの取り込み待ち
                    // $partnerSite['partner_nm'] = Partner::find($partnerSite['partner_cd'])->system_nm;
                }
                if (!is_null($partnerSite['affiliate_cd'])) {
                    $affiliateProgram = AffiliateProgram::where('affiliate_cd', $partnerSite['affiliate_cd'])->first();
                    if (!is_null($affiliateProgram)) {
                        $partnerSite['affiliate_nm'] = $affiliateProgram->program_nm;
                    }
                }
                if (!is_null($a_customer_site['customer_id'])) {
                    $partnerCustomer = PartnerCustomer::find($a_customer_site['customer_id']);
                    if (!is_null($partnerCustomer)) {
                        $a_customer_site['customer_nm'] = $partnerCustomer->customer_nm;
                    }
                }
            }

            // 料率登録 手数料率タイプ・開始年月日が変更されたときに登録する
            $a_rate = $modelPartnerSite->_get_rates(['site_cd' => $partnerSite['site_cd']]);
            // TODO: 取得結果が0件の場合、 undefined array key exception になる。
            // 0件の場合は登録？ input の値が null の場合は？
            if (count($a_rate) === 0 || $a_rate[0]->select_rate_index != $a_site_rate['rate_type'] || $a_rate[0]->accept_s_ymd != $a_site_rate['accept_s_ymd']) {
                $partnerSiteRateErrorList = $this->_insert_partner_site_rate($partnerSite['site_cd'], $a_site_rate['rate_type'], $a_site_rate['accept_s_ymd']);
                if (count($partnerSiteRateErrorList) === 0) {
                    $a_rate = $modelPartnerSite->_get_rates(['site_cd' => $partnerSite['site_cd']]);
                } else {
                    $errorList = array_merge($errorList, $partnerSiteRateErrorList);
                }
            }

            // パートナー存在確認
            if (!$this->is_empty($partnerSite['partner_cd'])) {
                if ((!array_key_exists('partner_nm', $partnerSite) || $this->is_empty($partnerSite['partner_nm']))) {
                    $errorList[] = 'TODO: InputError';
                }
            }
            // アフィリエイト存在確認
            if (!$this->is_empty($partnerSite['affiliate_cd'])) {
                if ((!array_key_exists('affiliate_nm', $partnerSite) || $this->is_empty($partnerSite['affiliate_nm']))) {
                    $errorList[] = 'TODO: InputError';
                }
            }

            // 精算先存在確認
            if (!$this->is_empty($a_customer_site['customer_id'])) {
                if (!array_key_exists('customer_nm', $a_customer_site) || $this->is_empty($a_customer_site['customer_nm'])) {
                    $errorList[] = 'TODO: InputError';
                }
            }

            // パートナー・アフィリエイトの在庫料率重複登録確認
            if (!$this->is_empty($partnerSite['partner_cd']) || !$this->is_empty($partnerSite['affiliate_cd'])) {
                if ($this->_exists_rate($partnerSite)) {
                    $errorList[] = 'TODO: RateInputError';
                    $errorList[] = '指定されたパートナーまたはアフィリエイトは、すでに他のサイトで使用されているため[2:在庫]の手数料率の登録ができません、パートナーまたはアフィリエイトをご確認ください。';
                }
            }

            if (!is_null($partnerSite['email'])) {
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $partnerSite['email_decrypt'] = $cipher->decrypt($partnerSite['email']);
            } else {
                $partnerSite['email_decrypt'] = null;
            }

            if (count($errorList) > 0) {
                DB::rollBack();
                // TODO: redirect to edit
                $request->session()->put('partner_site', $partnerSite);
                $request->session()->put('errors', $errorList);
                return redirect()->route('ctl.brPartnerSite.edit');
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
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

        return view('ctl.brPartnerSite.modify', [
            'partner_site'          => (object)$partnerSite,
            'partner_site_rate'     => (object)$a_site_rate,
            'partner_customer_site' => (object)$a_customer_site,
            'search_params'         => $search_params,
        ]);
    }

    /**
     * partner_site (提携サイト) 更新／登録
     *
     * @param array $partnerSite
     * @return string[]
     */
    private function _insert_partner_site($partnerSite)
    {
        $modelPartnerSite = new PartnerSite();

        // バリデーション
        $errorList = $modelPartnerSite->validation($partnerSite);
        if (count($errorList) > 0) {
            return $errorList;
        }

        // メール暗号化
        if (!is_null($partnerSite['email'])) {
            $cipher = new Models_Cipher(config('settings.cipher_key'));
            $partnerSite['email'] = $cipher->encrypt($partnerSite['email']);
        }

        // 挿入か更新かをチェック
        $alreadyExists = !is_null(PartnerSite::find($partnerSite['site_cd']));

        // 共通カラム設定
        if ($alreadyExists) {
            // 更新
            $modelPartnerSite->setUpdateCommonColumn($partnerSite, 'PartnerSite/update');
        } else {
            // 挿入
            $modelPartnerSite->setInsertCommonColumn($partnerSite, 'PartnerSite/create');
        }

        $result = PartnerSite::updateOrCreate(
            ['site_cd' => $partnerSite['site_cd']],
            $partnerSite
        );
        if ($alreadyExists) {
            if (!$result->wasChanged) {
                $errorList[] = '更新に失敗しました。';
            }
        } else {
            if (!$result->wasRecentlyCreated) {
                $errorList[] = '登録に失敗しました。';
            }
        }

        return $errorList;
    }

    /**
     * partner_customer_site (精算先関連サイト（提携先）) 更新／登録
     *
     *
     * @param string $inputSiteCd
     * @param string? $inputCustomerId
     * @return string[]
     */
    private function _insert_partner_customer_site($inputSiteCd, $inputCustomerId)
    {
        // 料率パターン
        // MEMO: 1 は日本旅行の customer_id を表している。
        // TODO: 要確認：「販売」「在庫」は両方必要？
        $a_row = [
            self::FEE_TYPE_SALE  => $inputCustomerId,
            self::FEE_TYPE_STOCK => 1,
        ];

        foreach ($a_row as $feeType => $customerId) {
            $existingPartnerCustomerSite = PartnerCustomerSite::where('site_cd', $inputSiteCd)
                ->where('fee_type', $feeType)->first();

            $alreadyExists = !is_null($existingPartnerCustomerSite);

            if (is_null($customerId)) {
                // 登録がなくなったら（精算先ID が指定されなかったら）破棄
                if ($alreadyExists) {
                    $existingPartnerCustomerSite->delete();
                }
                continue;
            }

            // 提携先の変更がない場合は更新しない。
            if ($alreadyExists && $existingPartnerCustomerSite->customer_id == $customerId) {
                continue;
            }

            // 登録・更新
            // バリデーション
            $partnerCustomerSite = [
                'customer_id' => $customerId,
                'site_cd' => $inputSiteCd,
                'fee_type' => $feeType
            ];
            $modelPartnerCustomerSite = new PartnerCustomerSite();
            $errorList = $modelPartnerCustomerSite->validation($partnerCustomerSite);
            if (count($errorList) > 0) {
                return $errorList;
            }

            // 共通カラム設定
            if ($alreadyExists) {
                // 更新
                $modelPartnerCustomerSite->setUpdateCommonColumn($partnerCustomerSite, 'PartnerSite/update');
            } else {
                // 登録
                $modelPartnerCustomerSite->setInsertCommonColumn($partnerCustomerSite, 'PartnerSite/create');
            }

            $result = PartnerCustomerSite::updateOrCreate(
                [
                    'site_cd' => $partnerCustomerSite['site_cd'],
                    'fee_type' => $partnerCustomerSite['fee_type']
                ],
                $partnerCustomerSite
            );
            if ($alreadyExists) {
                if (!$result->wasChanged) {
                    $errorList[] = '更新に失敗しました。';
                }
            } else {
                if (!$result->wasRecentlyCreated) {
                    $errorList[] = '登録に失敗しました。';
                }
            }

            if (count($errorList) > 0) {
                return $errorList;
            }
        }

        return $errorList;
    }

    /**
     * partner_site_rate (提携先サイト手数料率) 更新／登録
     *
     * HACK: 多分にハードコーディングを含んでいる
     * TODO: AC 用にカスタマイズ必要か。
     * fee_type は 1:販売 のみでよい？
     * stock_class は 1:一般ネット在庫 のみでよい？
     *
     * @param string $inputSiteCd
     * @param string? $inputRatePattern
     * @param string? $inputAcceptSYmd
     * @return string[]
     */
    private function _insert_partner_site_rate($inputSiteCd, $inputRatePattern, $inputAcceptSYmd)
    {
        $errorList = [];

        // 料率タイプが対象範囲外の場合は正常終了する。
        if ($inputRatePattern < 1 || 10 < $inputRatePattern) {
            return $errorList;
        }

        // 開始年月日チェック
        if (is_null($inputAcceptSYmd)) {
            $errorList[] = '料率タイプを設定している場合は、必ず開始年月日を入力してください。';
            return $errorList;
        }
        // TODO: 開始日が入力されて料率パターンが入力されないケースはある？
        // セレクトリストだから大丈夫そう？

        // 料率パターン
        // 1:BR 0%
        $ratePattern = [
            self::RATE_PATTERN_SPECIAL_ALLIANCE_0_PERCENT => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 0,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
                ],
            ],
            self::RATE_PATTERN_NORMAL_ALLIANCE_1_PERCENT => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 1,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 1,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 1,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
                ],
            ],
            self::RATE_PATTERN_SPECIAL_ALLIANCE_2_PERCENT => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 2,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 2,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
                ],
            ],
            self::RATE_PATTERN_NTA_BTM => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 2,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 2,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 1,
                ],
            ],
            self::RATE_PATTERN_YAHOO_TRAVEL => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 0,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0.3,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 1.3,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 1.8,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 2,
                ],
            ],
            self::RATE_PATTERN_NTA_2_PERCENT => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 2,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 2,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
                ],
            ],
            self::RATE_PATTERN_NTA_3_PERCENT => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 3,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 3,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 3,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
                ],
            ],
            self::RATE_PATTERN_NTA_4_PERCENT => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 4,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 3,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 4,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 4,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 3,
                ],
            ],
            self::RATE_PATTERN_NTA_RELO_CLUB => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => null,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => null,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => null,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 2,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 3,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 5,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => null,
                ],
            ],
            self::RATE_PATTERN_GBTNTA => [
                self::FEE_TYPE_SALE  => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 1,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 1,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 1,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 1,
                ],
                self::FEE_TYPE_STOCK => [
                    self::STOCK_CLASS_GENERAL_ONLINE_STOCK => 0,
                    self::STOCK_CLASS_LINKED_STOCK_NORMAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_VISUAL  => 0,
                    self::STOCK_CLASS_LINKED_STOCK_PREMIUM => 0,
                    self::STOCK_CLASS_TOYOKO_INN_STOCK     => 0,
                ],
            ],
        ];

        // 当該データ登録
        foreach ($ratePattern[$inputRatePattern] as $feeType => $stockRatePattern) {
            foreach ($stockRatePattern as $stockClass => $rate) {
                $existingPartnerSiteRate = PartnerSiteRate::where('site_cd', $inputSiteCd)->where('accept_s_ymd', $inputAcceptSYmd)->where('fee_type', $feeType)->where('stock_class', $stockClass)->first();
                $alreadyExists = !is_null($existingPartnerSiteRate);

                // 販売手数料で料率がヌル値の場合は精算対象ではないので登録しない。
                if ($feeType == self::FEE_TYPE_SALE && is_null($rate)) {
                    if ($alreadyExists) {
                        $existingPartnerSiteRate->delete();
                    }
                    continue;
                }

                $partnerSiteRate = [
                    'site_cd'      => $inputSiteCd,
                    'accept_s_ymd' => $inputAcceptSYmd,
                    'fee_type'     => $feeType,
                    'stock_class'  => $stockClass,
                    'rate'         => $rate,
                ];

                $modelPartnerSiteRate = new PartnerSiteRate();
                $errorList = $modelPartnerSiteRate->validation($partnerSiteRate);

                if (count($errorList) > 0) {
                    return $errorList;
                }

                // 共通カラム設定
                if ($alreadyExists) {
                    // 更新
                    $modelPartnerSiteRate->setUpdateCommonColumn($partnerSiteRate, 'PartnerSite/update');
                } else {
                    // 挿入
                    $modelPartnerSiteRate->setInsertCommonColumn($partnerSiteRate, 'PartnerSite/create');
                }

                $result = PartnerSiteRate::updateOrCreate(
                    ['site_cd' => $partnerSiteRate['site_cd']],
                    $partnerSiteRate
                );
                if ($alreadyExists) {
                    if (!$result->wasChanged) {
                        $errorList[] = '更新に失敗しました。';
                    }
                } else {
                    if (!$result->wasRecentlyCreated) {
                        $errorList[] = '登録に失敗しました。';
                    }
                }

                if (count($errorList) > 0) {
                    return $errorList;
                }
            }
        }
        return $errorList;
    }

    /**
     * パートナー精算サイト手数料率重複確認
     *
     * @param array $partnerSite
     * @return bool
     */
    private function _exists_rate($partnerSite)
    {
        // バインドパラメータ設定
        $whereSql = '';
        $parameters = [];
        $parameters['site_cd'] = $partnerSite['site_cd'];
        if (array_key_exists('partner_cd', $partnerSite) && !is_null($partnerSite['partner_cd']) && strlen($partnerSite['partner_cd']) > 0) {
            $parameters['partner_cd'] = $partnerSite['partner_cd'];
            $whereSql .= ' and partner_site.partner_cd = :partner_cd';
        }
        if (array_key_exists('partner_cd', $partnerSite) && !is_null($partnerSite['partner_cd']) && strlen($partnerSite['affiliate_cd']) > 0) {
            $parameters['affiliate_cd'] = $partnerSite['affiliate_cd'];
            $whereSql .= ' and partner_site.affiliate_cd = :affiliate_cd';
        }

        $sql = <<<SQL
            select distinct
                partner_site.site_cd,
                partner_site.site_nm
            from
                partner_site_rate
                inner join partner_site
                    on partner_site_rate.site_cd = partner_site.site_cd
            where 1 = 1
                and partner_site_rate.site_cd != :site_cd
                and partner_site_rate.fee_type = 2 -- self::FEE_TYPE_STOCK
                {$whereSql}
        SQL;

        $result = DB::select($sql, $parameters);

        return count($result) > 0;
    }
}
