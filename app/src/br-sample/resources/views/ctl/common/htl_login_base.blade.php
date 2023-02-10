{{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_login_header.tpl --}}
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
    $no_print = rand(0, 1) == 0;
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>[ストリーム　BestReserve]管理　ログイン</title>
    <link type="text/css" href="/styles/base.css" rel="stylesheet">
    {{-- {* Googleアナリティクス *}
    {include file=$v->env.module_root|cat:'/views/_common/_google_analytics.tpl'} --}}
</head>
@php
    if (config('app.env') == 'product') {
        $coloring = '';
        $environment = '';
    } else {
        if (config('app.env') == 'test') {
            $coloring = '#297';
            $environment = '検証環境';
        } elseif (config('app.env') == 'development') {
            $coloring = '#36A';
            $environment = '開発環境';
        } else {
            $coloring = '#A63';
            $environment = '環境不明';
        }
    }
@endphp

<body @if (config('app.env') != 'product') style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid {{ $coloring }};" @endif topmargin="0" marginheight="0">
    @include('ctl.common._auth'){{-- TODO: to be deleted--}}

    @if (config('app.env') != 'product')
        <div style="margin-left:-12px;padding:0.25em 0;background-color: {{ $coloring }};color:#fff;font-weifht:bold;width:6em;text-align:center;">{{ $environment }}</div>
    @endif

    <table border="0" width="100%" cellspacing="0" cellpadding="5" bgcolor="#EEEEFF">
        <tr>
            <td width="100%">　『ベストリザーブ』お部屋管理画面　</td>
        </tr>
    </table>
    <br>

    {{-- MEMO: ここまで svn_trunk\public\app\ctl\views\_common\_htl_login_header.tpl --}}

    @yield('content')

    {{-- MEMO: ここから svn_trunk\public\app\ctl\views\_common\_htl_footer.tpl --}}

    <div class="{{ $no_print == true ? 'noprint' : '' }}">
        <br>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2" width="100%">
                    <hr size="1" width="100%">
                </td>
            </tr>
            <tr>
                {{-- ログインしていれば --}}
                @if ($isLogin)
                    <td nowrap>
                        @if (!$isStaff)
                            {{-- TODO: URL, create Route --}}
                            <small><a href="/logout/">ログアウト</a></small>
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
