
{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_header.tpl --}}
<html>

@php
    // TODO: 他の guard で認証しているとき、これを追加
    // 他の guard: hotel(施設管理者), supervisor(施設統括), partner, affiliate
    // MEMO: 移植元では、 lib\Br\Models\Authorize\Operator.php の is_login() で判定
    $isLogin = Auth::guard('staff')->check();

    $isStaff = Auth::guard('staff')->check();
    if ($isStaff) {
        $staffName = Auth::guard('staff')->user()->staffInfo->staff_nm;
    } else {
        $staffName = 'TODO: ロール未実装';
    }

    // TODO: 認証関連、環境変数関連
    $v = new \stdClass;

    $v->user = new \stdClass;

    class Operator
    {
        public $staff_nm = '';
        public $nta_login_data;
        public function __construct($staffName)
        {
            $this->staff_nm = $staffName;
            $this->nta_login_data = (object)[
                'staff_nm' => 'staff_nm' . Str::random(3,6),
            ];
        }
    }
    $v->user->operator = new Operator(Str::random(16));

    $v->user->hotel = (object)[
        'hotel_nm'      => 'hotel_nm_'      . Str::random(rand(3,8)),
        'hotel_cd'      => 'hotel_cd_'      . Str::random(rand(3,8)),
        'hotel_old_nm'  => 'hotel_old_nm_'  . Str::random(rand(3,8)),
        'ydp2_status'   => rand(0,1) == 0,

        'premium_status' => rand(0,1) == 0,
        'visual_package_status' => rand(0,1) == 0,
        'accept_status' => rand(0,1),
    ];

    $v->user->hotel_status = (object)[
        'entry_status' => rand(0,1),
    ];

    $v->user->hotel_control = (object)[
        'stock_type' => rand(0,2),
    ];
    $v->user->hotel_person = (object)[
        'person_nm' => 'person_nm_' . Str::random(rand(8, 16)),
        'person_post' => 'person_post_' . Str::random(rand(8, 16)),
        'person_tel' => 'person_tel_' . rand(1000000, 9999999),
        'person_fax' => 'person_fax_' . rand(1000000, 9999999),
    ];

    $v->env = (object)[
        'controller'        => "brtop",
        'action'            => "index",
        'source_path'       => '',
        'module'            => '/ctl',
        'path_base_module'  => 'ctl/statics',
    ];

    $v->config = new \stdClass;
    $v->config->environment = new \stdClass;
    $statuses = ['development', 'test', 'product', 'unknown'];
    $v->config->environment->status = $statuses[rand(0,3)];

    $v->config->environment->mail = (object)[
        'from' => (object)[
            'opc' => Str::random(rand(20, 30)),
        ],
    ];

    $print_flg              = rand(0,9) == 0;
    $no_print               = rand(0,1) == 0;
    $no_print_title         = rand(0,1) == 0;
    $service_info_flg       = rand(0,1) == 0;
    $acceptance_status_flg  = rand(0,1) == 0;
    $header_number          = 'header_number_' . rand(0,100);
    $ad = Str::random(16);

@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="robots" content="none">

    <script type="text/javascript" src="/scripts/jquery.js?9465-2"></script>
    <script type="text/javascript" src="/scripts/jquery.cookies.js"></script>

    <title>[ストリーム]予約受付管理 [@yield('title')]</title>

    <link type="text/css" rel="stylesheet" href="/styles/base.css?9465-2">
    {{-- 印刷用スタイルシート --}}
    @if ($print_flg)
        <link type="text/css" rel="stylesheet" href="/styles/print.css" media="print">
        <link type="text/css" rel="stylesheet" href="/styles/screen.css" media="screen">
    @endif
    {{-- TODO: Googleアナリティクス --}}
        {{-- { include file=$v->env['module_root']|cat:'/views/_common/_google_analytics.tpl' } --}}
    {{-- /Googleアナリティクス --}}
    @yield('headScript')
</head>

{{-- HACK: （工数次第か）タグのインデントを修正。 CSS を共通化して、class の付与で分岐したらスッキリすると思われる。 --}}
@if ($v->config->environment->status == "test")
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left-width:4px;border-left-style:solid;border-left-color:#297;">
        <div style="margin-left:-12px;padding:0.25em 0;color:#fff;font-weifht:bold;width:6em;text-align:center;background-color:#297;">
            検証環境
        </div>
@elseif ($v->config->environment->status == "development")
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left-width:4px;border-left-style:solid;border-left-color:#36A;">
        <div style="margin-left:-12px;padding:0.25em 0;color:#fff;font-weifht:bold;width:6em;text-align:center;background-color:#36A;">
            開発環境
        </div>
@elseif ($v->config->environment->status == "product")
    <body topmargin="0" marginheight="0">
        <div>
        </div>
@else
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left-width:4px;border-left-style:solid;border-left-color:#A63;">
        <div style="margin-left:-12px;padding:0.25em 0;color:#fff;font-weifht:bold;width:6em;text-align:center;background-color:#A63;">
            環境不明
        </div>
@endif
    @include('ctl.common._auth'){{-- TODO: to be deleted--}}

    <div class="{{ $no_print == true ? 'noprint' : '' }}">
        {{-- staffのみ情報を表示 --}}
        @if ($isStaff)
            @include ('ctl.common._htl_staff_header')
        @endif
        <table border="0" width="100%" cellspacing="0" cellpadding="5" bgcolor="#EEEEFF" >
            <tr>
                <td nowrap>{{ strip_tags($header_number) }}</td>
                <td>
                    {{-- TODO: URL, create Route --}}
                    <a href="{{ $v->env->source_path }}{{ $v->env->module }}/redirect/rsvhotel/?target_cd={{ $v->user->hotel->hotel_cd }}" target="_blank">
                        {{ strip_tags($v->user->hotel->hotel_nm) }}
                        @if ((!$service->is_empty(strip_tags($v->user->hotel->hotel_old_nm))))
                            (旧{{ strip_tags($v->user->hotel->hotel_old_nm) }})
                        @endif
                    </a> 様
                    (施設コード：{{ strip_tags($v->user->hotel->hotel_cd) }})
                </td>
                <td nowrap align="right">
                    <table cellspacing="0" cellpadding="2" border="0">
                        <tr>
                            {{-- aやん! --}}
                            <td rowspan="2">
                                {{-- <a href="http://www.nihon-weekly.com/hotelask/" target="_blank"><img src="/images/intro/ayan/banner.gif" width="300" height="56" border="0" alt="aやん! ウィークリーホテルズ" /></a> --}}
                                {{-- TODO: 期間外、対応確認 --}}
                                {{-- @if ($smarty->now >= '2020-06-01 00:00:00'|strtotime and $smarty->now <= '2020-07-15 23:59:59'|strtotime)
                                    <a href="https://www.kanxashi.co.jp/cp/202006kanxashi/?key=brv" target="_blank"><img src="/images/intro/kanzashi/kanxashi_zenryoku.png" alt="かんざしクラウド" height="56" width="300" /></a>
                                @endif --}}
                            </td>
                            {{-- 一時削除 ２月早々にリニューアル予定 --}}
                            {{-- <td rowspan="2"><a href="http://{$v->config->system->rsv_host_name}/intro/htlorimo/" target="_blank"><img src="/images/intro/htlorimo/banner.gif" width="328" height="56" border="0" alt="ＯＲＩＭＯケータイプレミアムモニターキャンペーン" /></a></td> --}}
                            <td rowspan="2">
                                @if ($v->user->hotel_status->entry_status == 0 && !($acceptance_status_flg === false))
                                    @include ('ctl.common._change_acceptance')
                                @elseif ($v->user->hotel->ydp2_status && !($acceptance_status_flg === false))
                                    @include ('ctl.common._change_acceptance')
                                @endif
                            </td>
                                <td><br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{-- TODO: Form Facades, create Route --}}
                                <form action="{{ $v->env->source_path }}{{ $v->env->module }}/htltop/" method="post">
                                    <input type="hidden" name="target_cd" value="{{ strip_tags($target_cd) }}" />
                                    <input type="submit" value="メニュー">
                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br />
        {{-- サービスセンター --}}
        @if ($service_info_flg !== false)
            @include ('ctl.common._htl_service_info', ['ad' => $ad])
        @endif
    </div>

    <div class="{{ $no_print_title == true ? 'noprint' : '' }}">
        {{-- サービスセンター --}}
        @if (View::hasSection('menu_title') || View::hasSection('title'))
            <table border="3" cellspacing="0" cellpadding="2">
                <tr>
                    <td bgcolor="#EEEEFF" align="center">
                        <big>
                            @hasSection('menu_title')
                                @yield('menu_title')
                            @else
                                @yield('title')
                            @endif
                        </big>
                    </td>
                </tr>
            </table>
        @endif
        <br />
    </div>

{{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_header.tpl --}}

    @yield('content')

{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}

    <div class="{{ $no_print == true ? 'noprint' : '' }}">
        <br>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" >
            <tr>
                <td colspan="2" width="100%"><hr size="1" width="100%"></td>
            </tr>
            <tr>
                {{-- ログインしていれば --}}
                @if ($isLogin)
                    <td nowrap>
                        @if (!$isStaff)
                            {{-- TODO: URL, create Route --}}
                            <small><a href="{{ $v->env->source_path }}{{ $v->env->module }}/logout/">ログアウト</a></small>
                        @endif
                    </td>
                @endif
                <td align="right">
                    <small>画面更新日時({{ date('Y-m-d H:i:s') }})</small>
                </td>
            </tr>
        </table>
        <br />
        <small>(c)Copyright {{ date('Y') }} BestReserve Co.,Ltd. All Rights Reserved.</small>
        <br><br>
    </div>

</body>

</html>
{{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}
