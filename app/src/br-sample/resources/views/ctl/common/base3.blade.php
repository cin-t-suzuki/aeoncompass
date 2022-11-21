{{-- MEMO: 移植元 ここから public\app\ctl\view2\_common\_header2.tpl --}}

{{-- <?xml version="1.0" encoding="UTF-8"?> --}}

{{-- ================================================================================================ --}}
{{-- 引数                                                                                           --}}
{{--                                                                                                --}}
{{--   $title                                                                                       --}}
{{--     ページのタイトルを指定します。                                                             --}}
{{--                                                                                                --}}
{{--   $js_action                                                                                   --}}
{{--     ページで使用するJavaScriptのアクション定義を変数で指定します。                             --}}
{{--       例：                                                                                     --}}
{{--         {{capture name='js'}}                                                                    --}}
{{--           <literal>                                                                            --}}
{{--             $(クラス名|ID名).メソッド();                                                       --}}
{{--           </literal>                                                                           --}}
{{--         {{/capture}}                                                                             --}}
{{--                                                                                                --}}
{{--   $screen_type                                                                                 --}}
{{--     画面タイプの指定                                                                           --}}
{{--       br ：社内向け画面                                                                        --}}
{{--       htl：施設向け画面                                                                        --}}
{{--       ptn：提携先向け画面                                                                      --}}
{{--                                                                                                --}}
{{--   $is_htl_navi                                                                                 --}}
{{--      施設ナビを表示する場合に指定します。（例：'on', true）                                    --}}
{{--                                                                                                --}}
{{--   $is_staff_navi                                                                               --}}
{{--      （社内 or 日旅）スタッフナビを表示する場合に指定します。（例：'on', trueなど）            --}}
{{--                                                                                                --}}
{{--   $is_ptn_navi                                                                                 --}}
{{--      提携先ナビを表示する場合に指定します。（例：'on', trueなど）                              --}}
{{--                                                                                                --}}
{{--   $is_ctl_menu                                                                                 --}}
{{--      「室数調整、料金調整」、「プランメンテ」などの各画面への遷移メニューを表する場合に設定    --}}
{{-- ================================================================================================ --}}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

@php
    // TODO: 認証関連、環境変数関連
    $assign = (object) [
        'is_migration' => rand(0, 1),
    ];
    class Operator
    {
        public $staff_nm = '';
        public $nta_login_data;
        public function __construct($staffName)
        {
            $this->staff_nm = $staffName;
            $this->nta_login_data = (object) [
                'staff_nm' => 'staff_nm' . Str::random(3, 6),
            ];
        }
        public function is_staff()
        {
            return rand(0, 1);
        }
        public function is_nta()
        {
            return rand(0, 1);
        }
        public function is_login()
        {
            return rand(0, 1);
        }
    }
    $user = (object) [
        'operator' => new Operator(Str::random(16)),
        'hotel' => (object) [
            'hotel_nm' => 'hotel_nm_' . Str::random(rand(3, 8)),
            'hotel_cd' => 'hotel_cd_' . Str::random(rand(3, 8)),
            'hotel_old_nm' => 'hotel_old_nm_' . Str::random(rand(3, 8)),
            'ydp2_status' => rand(0, 1) == 0,
            'premium_status' => rand(0, 1) == 0,
            'visual_package_status' => rand(0, 1) == 0,
            'accept_status' => rand(0, 1),
            'jrset_status' => rand(0, 5),
        ],
        'hotel_status' => (object) [
            'entry_status' => rand(0, 1),
        ],
        'hotel_control' => (object) [
            'stock_type' => rand(0, 2),
        ],
        'hotel_person' => (object) [
            'person_nm' => 'person_nm_' . Str::random(rand(8, 16)),
            'person_post' => 'person_post_' . Str::random(rand(8, 16)),
            'person_tel' => 'person_tel_' . rand(1000000, 9999999),
            'person_fax' => 'person_fax_' . rand(1000000, 9999999),
        ],
        'hotel_system_version' => (object) [
            'version' => rand(0, 2),
        ],
    ];
    $env = (object) [
        'controller' => ['brtop', 'htlsroomoffer', 'htlsroomplan2', 'htlreserve', 'htlsroomplandp', 'pmscode'][rand(0, 5)],
        'action' => 'index',
        'source_path' => '',
        'module' => '/ctl',
        'path_base_module' => 'ctl/statics',
    ];
    $config = (object) [
        'environment' => (object) [
            'status' => ['development', 'test', 'product', 'unknown'][rand(0, 3)],
            'mail' => (object) [
                'from' => (object) [
                    'opc' => 'mail_' . Str::random(rand(20, 30)),
                ],
            ],
        ],
    ];
    $v = (object) [
        'assign' => $assign,
        'user' => $user,
        'env' => $env,
        'config' => $config,
    ];
    //   $print_flg              = rand(0,9) == 0;
    //   $no_print               = rand(0,1) == 0;
    //   $no_print_title         = rand(0,1) == 0;
    //   $service_info_flg       = rand(0,1) == 0;
    //   $menu_title             = rand(0,2) ? '' : 'menu_title_' . Str::random(rand(2,4));
    //   $title                  = rand(0,2) ? '' : 'title_' . Str::random(rand(2,4));
    //   $acceptance_status_flg  = rand(0,1) == 0;
    //   $header_number          = 'header_number_' . rand(0,100);
    //   $ad = Str::random(16);
    $screen_type = ['htl', 'br', 'ptn'][rand(0, 2)];
    $is_staff_navi = rand(0, 1);
    $is_htl_navi = rand(0, 1);
    $is_ptn_navi = rand(0, 1);
    $is_ctl_menu = rand(0, 1);
    $title = [null, 'TITLE_' . Str::random(5)][rand(0, 1)];
@endphp

<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">

<head>
    {{-- ------------------------------------------------------------------------ --}}
    {{-- metaタグの定義                                                         --}}
    {{-- ------------------------------------------------------------------------ --}}
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="robots" content="none" />

    {{-- ------------------------------------------------------------------------ --}}
    {{-- CSSの定義                                                              --}}
    {{-- ------------------------------------------------------------------------ --}}
    {{-- 共通CSS --}}
    {{-- <link type="text/css" rel="stylesheet" href="{{ asset('css/style_base.css?6735-4') }}" /> --}}
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style_base.css') }}" />

    {{-- 画面の種類に合わせたCSSを追加で読み込む --}}
    @if ($screen_type === 'br')
        {{-- 社内向け画面   --}}
        {{-- <link type="text/css" rel="stylesheet" href="{{ asset('css/style_br.css?6735-4')" }} /> --}}
        <link type="text/css" rel="stylesheet" href="{{ asset('css/style_br.css') }}" />
    @elseif ($screen_type === 'htl')
        {{-- 施設向け画面   --}}
        {{-- <link type="text/css" rel="stylesheet" href="{{ asset('css/style_htl.css?6735-4') }}" /> --}}
        <link type="text/css" rel="stylesheet" href="{{ asset('css/style_htl.css') }}" />
    @elseif ($screen_type === 'ptn')
        {{-- 提携先向け画面 --}}
        {{-- <link type="text/css" rel="stylesheet" href="{{ asset('css/style_ptn.css?6735-4') }}" /> --}}
        <link type="text/css" rel="stylesheet" href="{{ asset('css/style_ptn.css') }}" />
    @endif

    {{-- ------------------------------------------------------------------------ --}}
    {{-- JavaScriptの定義                                                       --}}
    {{-- ------------------------------------------------------------------------ --}}
    {{-- ファイル読み込み --}}
    {{-- TODO: ファイル存在確認 --}}
    <script type="text/javascript" src="{{ asset('js/jquery.js?6735-2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.cookies.js?6735-2') }}"></script>
    <script type="text/javascript" src="{{ asset('js/brj.ctl.js?6735-2') }}"></script>


    {{-- Google Analytics --}}
    {{-- TODO: Google Analitics --}}
    {{-- include file=$v->env->module_root|cat:'/views/_common/_google_analytics.tpl' --}}

    {{-- ページ個別に指定されたJavaScript --}}
    @yield('headScript')


    {{-- ------------------------------------------------------------------------ --}}
    {{-- ページタイトル                                                         --}}
    {{-- ------------------------------------------------------------------------ --}}
    <title>STREAM社内管理[{{ $title }}]</title>
</head>

<body>
    {{-- ------------------------------------------------------------------------ --}}
    {{-- 実行環境の表示（※本番環境では表示しない）                             --}}
    {{-- ------------------------------------------------------------------------ --}}
    @if ($v->config->environment->status !== 'product')
        <div class="env{{ $v->config->environment->status }}">
            @if ($v->config->environment->status === 'test')
                開発環境
            @elseif ($v->config->environment->status === 'development')
                検証環境
            @else
                環境不明
            @endif
        </div>
    @endif
    <div class="clear"></div>
    <div class="wrap{{ $v->config->environment->status }}">
        {{-- ------------------------------------------------------------------------ --}}
        {{-- ログイン別ナビ                                                         --}}
        {{-- ------------------------------------------------------------------------ --}}
        {{-- スタッフ --}}
        @if ($is_staff_navi)

            {{-- BRスタッフ --}}
            @if ($v->user->operator->is_staff())
                @include('ctl.common._header_staff_br')
            @endif

            {{-- NTAスタッフ --}}
            @if ($v->user->operator->is_nta())
                @include('ctl.common._header_staff_nta')
            @endif

        @endif

        {{-- 施設 --}}
        @if ($is_htl_navi)
            @include('ctl.common._header_htl')
        @endif

        {{-- 提携先 --}}
        @if ($is_ptn_navi)
            @include('ctl.common._header_ptn')
        @endif

        @if ($screen_type === 'br')
            @include('ctl.common._header_br')
        @endif

        {{-- ------------------------------------------------------------------------ --}}
        {{-- ページトップメニュー                                                   --}}
        {{-- ------------------------------------------------------------------------ --}}
        <div class="page-top-menu">
            {{-- ページタイトル --}}
            <div class="page-title-base">
                <h1 class="page-title">{{ $title }}</h1>
            </div>

            {{-- 問い合わせ先 --}}
            @if ($screen_type === 'htl')
                <div class="info-box-br">
                    <div class="info-box-br-back">
                        <p>お問い合わせ先</p>
                        <div class="info-box-br-contents">
                            <ul>
                                <li>
                                    <div id="item-mail">MAIL</div>：&nbsp;<a
                                        href="mailto:{{ $v->config->environment->mail->from->opc }}">{{ $v->config->environment->mail->from->opc }}</a>
                                </li>
                                <li>
                                    <div id="item-tel">TEL</div>：&nbsp;03-5751-8243
                                </li>
                                <li>
                                    <div id="item-fax">FAX</div>：&nbsp;03-5751-8242
                                </li>
                                <li>
                                    <div id="item-days">受付</div>：&nbsp;月～金 9:30～18:30
                                </li>
                                <li>（土曜・日曜・祝祭日・弊社休日は除く）</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="clear"></div>
        </div>

        {{-- ------------------------------------------------------------------------ --}}
        {{-- 管理画面各メニューへの遷移                                             --}}
        {{-- ------------------------------------------------------------------------ --}}
        @if ($is_ctl_menu)

            <script type="text/javascript">
                <!--
                $(document).ready(function() {
                    $('#jqs-change-expert-mode').showAndHideExpertMenu();
                });
                //-->
            </script>

            <div class="ctl-menu">
                {{-- 室数・料金・期間調整 --}}
                @if ($v->env->controller === 'htlsroomoffer')
                    <div class="elm-1st elm-active">
                        【室数・料金・期間の調整】
                    </div>
                @else
                    <form action="{{ $v->env->source_path }}{{ $v->env->module }}/htlsroomoffer/" method="post">
                        <div class="elm-1st">
                            <input type="hidden" name="target_cd" value="{{ $v->user->hotel->hotel_cd }}" />
                            <input type="submit" value="室数・料金・期間の調整" />
                        </div>
                    </form>
                @endif

                {{-- プランメンテナンス --}}
                @if ($v->env->controller === 'htlsroomplan2')
                    <div class="elm elm-active elm-active">
                        【プランメンテナンス】
                    </div>
                @else
                    <form action="{{ $v->env->source_path }}{{ $v->env->module }}/htlsroomplan2/list/" method="post">
                        <div class="elm">
                            <input type="hidden" name="target_cd" value="{{ $v->user->hotel->hotel_cd }}" />
                            <input type="submit" value="プランメンテナンス" />
                        </div>
                    </form>
                @endif

                {{-- 予約情報の確認 --}}
                @if ($v->env->controller === 'htlreserve')
                    <div class="elm elm-active elm-active">
                        【予約情報の確認】
                    </div>
                @else
                    <form action="{{ $v->env->source_path }}{{ $v->env->module }}/htlreserve/" method="post">
                        <div class="elm">
                            <input type="hidden" name="target_cd" value="{{ $v->user->hotel->hotel_cd }}" />
                            <input type="submit" value="予約情報の確認" />
                        </div>
                    </form>
                @endif

                {{-- JRコレクション審査状況 --}}
                @if ((int) $v->user->hotel->jrset_status === '0' or
                    (int) $v->user->hotel->jrset_status === '1' or
                    (int) $v->user->hotel->jrset_status === '4')
                    @if ($v->env->controller === 'htlsroomplandp')
                        <div class="elm elm-active elm-active">
                            【JRコレクション審査状況】
                        </div>
                    @else
                        <form action="{{ $v->env->source_path }}{{ $v->env->module }}/htlsroomplandp/" method="post">
                            <div class="elm">
                                <input type="hidden" name="target_cd" value="{{ $v->user->hotel->hotel_cd }}" />
                                <input type="submit" value="JRコレクション審査状況" />
                            </div>
                        </form>
                    @endif
                @endif

                {{-- PMSコード --}}
                @if ($v->env->controller === 'pmscode')
                    <div class="elm elm-active elm-active jqs-expert-menu">
                        【PMSコード】
                    </div>
                @else
                    <form action="{{ $v->env->source_path }}{{ $v->env->module }}/pmscode/" method="post">
                        <div class="elm jqs-expert-menu">
                            <input type="hidden" name="target_cd" value="{{ $v->user->hotel->hotel_cd }}" />
                            <input type="submit" value="PMSコード" />
                        </div>
                    </form>
                @endif

                {{-- マイグレーション --}}
                @if (!$v->assign->is_migration and $v->user->hotel_system_version->version == 1)
                    @if ($v->env->controller === 'pmscode')
                        <div class="elm elm-active elm-active">
                            【プラン・部屋登録方式移行ツール】
                        </div>
                    @else
                        <form method="post" action="{{ $v->env->source_path }}{{ $v->env->module }}/htlmigration/"
                            style="display:inline;">
                            <div class="elm">
                                <input type="submit" value="プラン・部屋登録方式移行ツール" />
                                <input type="hidden" name="ctl_nm" value="{{ $v->env->controller }}" />
                                <input type="hidden" name="act_nm" value="{{ $v->env->action }}" />
                                <input type="hidden" name="target_cd"
                                    value="{{ strip_tags($v->user->hotel->hotel_cd) }}" />
                            </div>
                        </form>
                    @endif
                @endif

                <div id="expert">
                    <input id="jqs-change-expert-mode" type="button" value="エキスパートメニュー表示" />
                </div>

                <div class="clear"></div>
            </div>
        @endif

        <hr class="line" />

        {{-- ------------------------------------------------------------------------ --}}
        {{-- メインコンテンツ                                                       --}}
        {{-- ------------------------------------------------------------------------ --}}
        <div class="active-contents">

            {{-- ページタイトルが指定されているかチェック --}}
            @if (!$title)
                <div style="background-color: #ff0000; color: #ffffff; padding: 4px; font-weight: bold;">
                    ページのtitleを指定してください。
                </div>
            @endif

            {{-- MEMO: 移植元 ここまで public\app\ctl\view2\_common\_header2.tpl --}}

            @yield('content')

            {{-- MEMO: 移植元 ここから public\app\ctl\view2\_common\_footer2.tpl --}}

        </div>{{-- .active-contents --}}

        {{-- =========================================================================================== --}}
        {{-- フッター                                                                                  --}}
        {{-- =========================================================================================== --}}
        <div class="ft-base">
            <div class="ft-back" id="ft-htl">

                {{-- ---------------------------------------------------------------------- --}}
                {{-- ログアウトメニュー                                                   --}}
                {{-- ---------------------------------------------------------------------- --}}
                {{-- ログイン中 --}}
                @if ($v->user->operator->is_login())
                    {{-- スタッフ以外 --}}
                    @if (!$v->user->operator->is_staff())
                        <div id="ft-logout">
                            <a href="{{ $v->env->source_path }}{{ $v->env->module }}/logout/">ログアウト</a>
                        </div>
                    @endif
                @endif

                {{-- ---------------------------------------------------------------------- --}}
                {{-- 画面更新日時                                                         --}}
                {{-- ---------------------------------------------------------------------- --}}
                <div id="ft-dtm">
                    画面更新日時({{ date('Y-m-d H:i:s') }})
                </div>

                <div class="clear"></div>

                {{-- ---------------------------------------------------------------------- --}}
                {{-- コピーライト                                                         --}}
                {{-- ---------------------------------------------------------------------- --}}
                <div>
                    (c)Copyright {{ date('Y') }} BestReserve Co.,Ltd. All Rights Reserved.
                </div>

            </div>
        </div>

    </div>{{-- wrap(test, product, development) --}}
</body>

</html>

{{-- MEMO: 移植元 ここまで public\app\ctl\view2\_common\_footer2.tpl --}}
