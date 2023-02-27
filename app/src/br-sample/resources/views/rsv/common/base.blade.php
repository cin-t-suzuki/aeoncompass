{{-- MEMO: ↓ public\app\rsv\view2\_common\_header.tpl --}}

{{-- 説明、キーワード、タイトルの定型を設定 --}}
{{-- @set($regdesc, '日本全国宿泊予約なら「ベストリザーブ・宿ぷらざ」、レンタカー予約もお得です。さらに新幹線とお宿がセットになったお得なプランが満載のＪＲコレクション。インターネットで楽々即時予約。便利でお得なポイント貯めて！使えちゃう！')
@set($regwords, '旅館,ホテル,ビジネスホテル,ホテル予約,予約,宿泊,格安,割引,レジャー,出張,宿泊予約')
@set($regtitle, '旅館・ホテル・ビジネスホテルの予約はベストリザーブ') --}}
@php
    $regdesc = '日本全国宿泊予約なら「ベストリザーブ・宿ぷらざ」、レンタカー予約もお得です。さらに新幹線とお宿がセットになったお得なプランが満載のＪＲコレクション。インターネットで楽々即時予約。便利でお得なポイント貯めて！使えちゃう！';
    $regwords = '旅館,ホテル,ビジネスホテル,ホテル予約,予約,宿泊,格安,割引,レジャー,出張,宿泊予約';
    $regtitle = '旅館・ホテル・ビジネスホテルの予約はベストリザーブ';
    $title = $title ?? '';
@endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @if (config('app.env') != 'product')
        <meta name="robots" content="none" />
    @endif

    {{-- 説明を出力 --}}
    @if (isset($desc))
        @if ($desc == 'regular')
            <meta name="description" content="{{ $regdesc }}">
        @elseif($desc != '')
            @if (mb_strlen($desc) < 64)
                <meta name="description" content="{{ $desc }} - {{ $regdesc }}">
            @else
                <meta name="description" content="{{ $desc }}">
            @endif
        @endif
    @else
        @if ($title == 'regular')
            <meta name="description" content="{{ $regdesc }}">
        @else
            <meta name="description" content="@yield('title') - {{ $regdesc }}">
        @endif
    @endif

    {{-- キーワードを出力 --}}
    @if (isset($words))
        @if ($words == 'regular')
            <meta name="keywords" content="{$regwords}">
        @elseif($words != '')
            <meta name="keywords" content="{{ $words }},{{ $regwords }}">
        @endif
    @else
        @if ($title == 'regular')
            <meta name="keywords" content="{{ $regwords }}">
        @else
            <meta name="keywords" content="{{ str_replace(' - ', ',', $title) }},{{ $regwords }}">
        @endif
    @endif
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    {{-- タイトルを出力 --}}
    @hasSection('title')
        @if ($title == 'regular')
            <title>{{ $regtitle }}</title>
        @elseif($title != '')
            <title>{{ $title }} - {{ $regtitle }}</title>
        @endif
    @else
        @if (config('app.env') != 'product')
            <title>{{ $regtitle }}</title>
        @else
            <title>タイトルが指定されていません。</title>
        @endif
    @endif

    @if (isset($current) && $current == 'top')
        <link href="http://www.bestrsv.com/m/" rel="alternate" media="handheld" />
    @endif
    <link href="/apple-touch-icon-precomposed.png" rel="apple-touch-icon-precomposed">
    <link type="image/x-icon" href="/favicon.ico" rel="shortcut icon">
    <link type="image/x-icon" href="/favicon.ico" rel="icon">

    <meta property="og:title" content="ベストリザーブ・宿ぷらざ | 国内宿泊予約" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.bestrsv.com" />
    <meta property="og:image" content="https://www.bestrsv.com/ogp.png?1067820190123">
    <meta property="og:site_name" content="ベストリザーブ・宿ぷらざ" />
    <meta property="og:description" content="{{ $regdesc }}" />

    {{-- スタイルシートの読み込み --}}
    <link href="{{ asset('/css/base2.css') . '?r=' . mt_rand() }}" rel="stylesheet">
    <link href="{{ asset('/css/agoda.css') . '?r=' . mt_rand() }}" rel="stylesheet">

    @yield('page_css')

    {{-- スクリプトの読込 --}}
    @if (config('app.env') != 'product' && config('app.jsload') == 'unpack')
        <script type="text/javascript" src="{{ asset('/js/_source/jquery/jquery-1.11.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/jquery/jquery.cookies.2.2.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/jquery/jquery.timer-1.2.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.js/brj.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.js/brj.ui.expand.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.js/brj.ui.panel.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.js/brj.ui.switch.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.js/brj.ui.tab.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/brj.today.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/brj.data.place.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.reserve.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.reserve.area.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.reserve.checkinselector.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.reserve.jrc.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.rsv.panelcalendar.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.gmap.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.rsv.highrank.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/_source/brj.reserve.js/brj.reserve.condition.js?') . '?r=' . mt_rand() }}"></script>
    @else
        <script type="text/javascript" src="{{ asset('/js/jquery.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/brj.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/brj.today.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/brj.data.place.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/popup.js') . '?r=' . mt_rand() }}"></script>
        <script type="text/javascript" src="{{ asset('/js/brj.reserve.js') . '?r=' . mt_rand() }}"></script>
    @endif
</head>

<body>
    <!-- ClickTale Top part -->
    <script type="text/javascript">
        var WRInitTime = (new Date()).getTime();
    </script>
    <!-- ClickTale end of Top part -->

    {{-- MEMO: ↑ public\app\rsv\view2\_common\_header.tpl --}}


    {{-- blade --}}
    @hasSection('page_blade')
        @yield('page_blade')
    @endif
    @hasSection('content')
        @yield('content')
    @endif


    {{-- javascript --}}
    @yield('page_js')


    {{-- MEMO: ↓ public\app\rsv\view2\_common\_footer.tpl --}}


    <div id="pgf1">
        <div class="pg"></div>
    </div>
    <div id="pgf2">
        <div class="pg">
            <div class="pgf2-inner">
                @if (\Route::currentRouteName() == 'rsv.top')
                    <div class="pgf2-column">
                        <p><a href="{{-- {$v->env.ssl_path}rsv/member/edit/ --}}">会員情報を変更する</a></p>
                        <p><a href="{{-- {$v->env.ssl_path}rsv/member/withdraw/ --}}">退会手続きをする</a></p>
                        <p><a href="{{-- {$v->env.ssl_path}rsv/reminder/ --}}">会員コード・パスワードを照会する</a></p>
                        <p><a href="{{-- {$v->env.ssl_path}rsv/member/mail1/ --}}">メールマガジンの受信状態を変更する</a></p>
                        <br />
                        <p><a href="{{ route('rsv.point.index') }}">ＢＲポイント</a></p>
                        <br />
                        <br />
                        <br />
                        <br />
                    </div>
                    <div class="pgf2-column">
                        <p><a href="{{-- {$v->env.path_base}/guide/visitor/ --}}">初めての方へ</a></p>
                        <p><a href="{{-- {$v->env.path_base}/about/policy/member/ --}}">会員規約について</a></p>
                        <p><a href="{{-- {$v->env.path_base}/about/policy/ --}}">利用規約について</a></p>
                        <p><a href="{{-- {$v->env.path_base}/about/policy/privacy/ --}}" target="_blank">プライバシーポリシーについて</a></p>
                        <p><a href="{{ route('rsv.help.index') }}">ヘルプ</a></p>
                        <br />
                        <p><a href="{{-- {$v->env.path_base}/hotel/new.html --}}">新着ホテル</a></p>
                        <br />
                    </div>
                    <div class="pgf2-column">
                        <p><a href="{{-- {$v->env.path_base}/contact/hotel/ --}}">宿泊施設関係者様へ</a></p>
                        <p><a href="{{-- {$v->env.path_base}/contact/partner/ --}}">業務提携について</a></p>
                        <p><a href="{{-- {$v->env.path_base}/about/recruit/ --}}">人材募集</a></p>
                        <p><a href="{{-- {$v->env.path_base}/about/ --}}">会社概要</a></p>
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                    </div>
                    <div class="pgf2-column">
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <div class="">Copyright (c){{ date('Y') }} BestReserve Co.,Ltd. All Rights Reserved.
                        </div>
                        <br />
                    </div>
                @else
                    <div class="pgf2-column">
                        <div class="">Copyright (c){{ date('Y') }} BestReserve Co.,Ltd. All Rights Reserved.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- google_analytics用 スクリプト --}}
    {{--
    {if ($google_analytics != 'off')}{include file='../_common/_js/_google_analytics.tpl'}{/if}
    --}}
    <div class="bg-wrapper"></div>
    {{-- ローディング、注意 ポップアップ --}}
    {{-- include file='../_common/_pop_up.tpl' --}}
</body>

</html>

{{-- MEMO: ↑ public\app\rsv\view2\_common\_footer.tpl --}}
