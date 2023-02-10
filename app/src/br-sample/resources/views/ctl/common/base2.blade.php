{{-- ここから svn_trunk/public/app/ctl/view2/_common/_br_header2.tpl --}}

{{-- MEMO: 移植元にて新画面が追加された際のテンプレート。base1.blade.php が旧のもの --}}

{{-- TODO: なぞ xml <?xml version="1.0" encoding="UTF-8"?> --}}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">{{-- TODO: なぞ doctype 宣言 --}}
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
    $v->user->operator = new \stdClass;
    $v->env = [
        'controller'        => "brtop",
        'action'            => "index",
        'source_path'       => 'source_path_val',
        'module'            => 'module_val',
        'path_base_module'  => 'ctl/statics',
    ];
    $v->config = new \stdClass;
    $v->config->environment = new \stdClass;
    $v->config->environment->status = 'test';
@endphp
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <meta name="robots" content="none" />
        {{-- TODO: jquery 存在確認 --}}<script type="text/javascript" src="{{ asset('/js/jquery.js') }}"></script>
        <title>
            STREAM社内管理 @yield('title')
        </title>
        <link rel="stylesheet" href="{{ asset('css/style_base.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style_br.css') }}">

        @yield('headScript')

        {{-- Googleアナリティクス --}}
            {{-- TODO: {{ include file=$v->env['module_root']|cat:'/views/_common/_google_analytics.tpl' }} --}}
        {{-- /Googleアナリティクス --}}
    </head>
    <body>
        @include('ctl.common._auth'){{-- TODO: to be deleted--}}
        {{-- 環境表示 --}}
        @include('ctl.common._env_info')
        <div class="wrap{{ $v->config->environment->status }}">
            {{-- パートナー管理ヘッダー --}}
            <div class="header-br">
                <div class="header-br-back">
                    <div class="header-br-contents">
                        <div id="system-name">STREAM社内管理</div>
                        <div id="main-menu">
                            {{-- TODO: route() --}}
                            <form action="{{ route('ctl.br.top') }}" method="post">
                                <div>
                                    <input type="submit" value="メインメニュー" />担当：{{ $staffName }}
                                </div>
                            </form>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>{{-- /パートナー管理ヘッダー --}}
            {{-- コンテンツ --}}
            <div class="active-contents">
                {{-- ここまで svn_trunk/public/app/ctl/view2/_common/_br_header2.tpl --}}

                @yield('content')

                {{-- ここから svn_trunk/public/app/ctl/view2/_common/_br_footer.tpl --}}
            </div>
            {{-- /コンテンツ --}}
        </div>
        {{-- /環境表示 --}}
        {{-- 提携先管理フッター --}}

        <div class="footer-br">
            <div class="footer-br-back">
                <div class="footer-br-contents">
                    <div id="logout">
                        <div>
                            @if ($isLogin && $isStaff)
                                @if ($v->env['controller'] === "brtop" && $v->env['action'] === "index")
                                    <form method="post" action="{{ $v->env['source_path'] }}{{ $v->env['module'] }}/logout/">
                                        <div><input type="submit" value="ログアウト" /></div>
                                    </form>
                                @else
                                    <div>&nbsp;</div>
                                @endif
                            @else
                                &nbsp;
                            @endif
                        </div>
                    </div>
                    <div id="dtm">
                        <div>{{ date('Y-m-d H:i:s') }}</div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        {{-- /提携先管理フッター --}}
        @yield('footerScript')
    </body>
</html>
{{-- ここまで svn_trunk/public/app/ctl/view2/_common/_br_footer.tpl --}}
