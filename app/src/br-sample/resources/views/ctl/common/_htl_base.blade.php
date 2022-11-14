
{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_header.tpl --}}
<html>

@php
    // TODO: 認証関連、環境変数関連
    $v = new \stdClass;

    $v->user = new \stdClass;

    class Operator
    {
        public $staff_nm = '';
        public function __construct($staffName) { $this->staff_nm = $staffName; }
        public function is_staff() { return rand(0,1); }
        public function is_nta() { return rand(0,1); }
        public function is_login() { return rand(0,1); }
    }
    $v->user->operator = new Operator(Str::random(16));

    $v->user->hotel = new stdClass();
    $v->user->hotel->hotel_old_nm = 'hotel_old_nm_value';
    $v->user->hotel->ydp2_status = rand(0,1) == 0;

    $v->user->hotel_status = new stdClass();
    $v->user->hotel_status->entry_status = rand(0,1);

    // $v->env = [
    //     'controller'        => "brtop",
    //     'action'            => "index",
    //     'source_path'       => 'source_path_val',
    //     'module'            => 'module_val',
    //     'path_base_module'  => 'ctl/statics',
    // ];

    $v->config = new \stdClass;
    $v->config->environment = new \stdClass;
    $v->config->environment->status = 'development';
    if (rand(0,99) == 0) $v->config->environment->status = 'test';
    if (rand(0,99) == 1) $v->config->environment->status = 'product';
    if (rand(0,99) == 2) $v->config->environment->status = 'unknown';

    $print_flg              = rand(0,9) == 0;
    $no_print               = rand(0,1) == 0;
    $no_print_title         = rand(0,1) == 0;
    $service_info_flg       = rand(0,1) == 0;
    $menu_title             = rand(0,1) == 0;
    $acceptance_status_flg  = rand(0,1) == 0;

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
        {{-- {{ include file=$v->env['module_root']|cat:'/views/_common/_google_analytics.tpl' }} --}}
    {{-- /Googleアナリティクス --}}
    @yield('headScript')
</head>

{{-- TODO: タグのインデントを修正 --}}
@if ($v->config->environment->status == "test")
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #297;">
        <div style="margin-left:-12px;padding:0.25em 0;background-color:#297;color:#fff;font-weifht:bold;width:6em;text-align:center;">
            検証環境
        </div>
@elseif ($v->config->environment->status == "development")
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #36A;">
        <div style="margin-left:-12px;padding:0.25em 0;background-color:#36A;color:#fff;font-weifht:bold;width:6em;text-align:center;">
            開発環境
        </div>
@elseif ($v->config->environment->status != "product")
    <body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #A63;">
        <div style="margin-left:-12px;padding:0.25em 0;background-color:#A63;color:#fff;font-weifht:bold;width:6em;text-align:center;">
            環境不明
        </div>
@else
    <body topmargin="0" marginheight="0">
@endif

@if ($no_print == true)
    <div class="noprint">
@endif

{{-- staffのみ情報を表示 --}}
@if ($v->user->operator->is_staff())
    @include ('ctl.common._htl_staff_header')
    {include file=$v->env.module_root|cat:'/views/_common/_htl_staff_header.tpl'}
@elseif ($v->user->operator->is_nta())
    @include ('ctl.common._nta_staff_header')
    {include file=$v->env.module_root|cat:'/view2/_common/_nta_staff_header.tpl'}
@endif

<table border="0" width="100%" cellspacing="0" cellpadding="5" bgcolor="#EEEEFF" >
    <tr>
        <td nowrap>{strip_tags($header_number)}</td>
        <td>
            {{-- TODO: URL --}}
            <a href="{$v->env.source_path}{$v->env.module}/redirect/rsvhotel/?target_cd={$v->user->hotel.hotel_cd}" target="_blank">
                {strip_tags($v->user->hotel.hotel_nm)}
                @if ((!$service->is_empty(strip_tags($v->user->hotel->hotel_old_nm))))
                    (旧{strip_tags($v->user->hotel.hotel_old_nm)})
                @endif
            </a> 様
            (施設コード：{strip_tags($v->user->hotel.hotel_cd)})
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
                            {include file=$v->env.module_root|cat:'/views/_common/_change_acceptance.tpl'}
                        @elseif ($v->user->hotel->ydp2_status && !($acceptance_status_flg === false))
                            @include ('ctl.common._change_acceptance')
                            {include file=$v->env.module_root|cat:'/views/_common/_change_acceptance.tpl'}
                        @endif
                    </td>
                        <td><br>
                    </td>
                </tr>
                <tr>
                    <td>
                        {{-- TODO: Form Facades --}}
                        <form action="{$v->env.source_path}{$v->env.module}/htltop/" method="post">
                            <input type="hidden" name="target_cd" value="{strip_tags($v->assign->target_cd)}" />
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
    {include file=$v->env.module_root|cat:'/views/_common/_htl_service_info.tpl' ad=$ad}
@endif

@if ($no_print == true)
    </div>
@endif

@if ($no_print_title == true)
    <div class="noprint">
@endif

{{-- サービスセンター --}}
@if (!($service->is_empty($menu_title)) || !($service->is_empty($title)))
    <table border="3" cellspacing="0" cellpadding="2">
        <tr>
            <td bgcolor="#EEEEFF" align="center">
                <big>
                    @if ($service->is_empty($menu_title))
                        {{ strip_tags($title) }}
                    @else
                        {{ strip_tags($menu_title) }}
                    @endif
                </big>
            </td>
        </tr>
    </table>
@endif

<br />

@if ($no_print_title == true)
    </div>
@endif

{{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_header.tpl --}}

@yield('content')

{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}
@if ($no_print == true)
    <div class="noprint">
@endif
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="0" >
    <tr>
        <td colspan="2" width="100%"><hr size="1" width="100%"></td>
    </tr>
    <tr>
        {{-- ログインしていれば --}}
        @if ($v->user->operator->is_login() == true)
            <td nowrap>
                @if (!$v->user->operator->is_staff())
                    {{-- TODO: URL --}}
                    <small><a href="{$v->env.source_path}{$v->env.module}/logout/">ログアウト</a></small>
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
@if ($no_print == true)
    </div>
@endif

</body>
</html>
{{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}
