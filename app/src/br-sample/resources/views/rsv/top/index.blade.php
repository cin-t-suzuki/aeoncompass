<p>
    {{ 'ログイン: ' . (Auth::guard('web')->check() ? '済' : '未') }}
    @if (Auth::guard('web')->check())
        {{ Auth::guard('web')->user()->name() }}
    @endif
</p>

@if (Auth::guard('web')->check())
    <a href="{{ route('rsv.auth.logout') }}">ログアウト</a>
@else
    <a href="{{ route('rsv.auth.login') }}">ログイン</a>
@endif

{{-- MEMO: 移植元 public\app\rsv\view2\top\index.tpl --}}

{{-- {include file='../_common/_header.tpl' title="regular" current="top" css="_cpn.tpl" } --}}
@php
    $title = 'regular';
    $current = 'top';
@endphp
@extends('rsv.common.base')
@section('title', 'regular')

@section('content')
    {{-- {include file='../_common/_pgh1.tpl' pgh1_mnv=1} --}}
    @include('rsv.common._pgh1', [
        'pgh1_mnv' => 1,
    ])

    <style>
        .ept_banner {
            text-decoration: none;
            color: #ca00fd;
        }

        .ept_banner:hover {
            color: #ca00fd57;
            /* #a349a4; */
        }

        .ept_banner div {
            border: 2px solid #ca00fd;
            padding: 10px 20px 10px 5px;
            line-height: 1em;
            font-size: 13px;
            font-family: \\30D2\30E9\30AE\30CE\89D2\30B4 Pro W3, Hiragino Kaku Gothic Pro, \\30E1\30A4\30EA\30AA, Meiryo, Osaka, "\FF2D\FF33 \FF30\30B4\30B7\30C3\30AF", MS PGothic, "sans-serif", YuGothic Bold;
            background-color: #ffffff;
        }

        .ept_banner p {
            font-size: 14px;
            margin: 8px 0px 0px 680px;
            line-height: 0;
        }
    </style>
    {{-- EPARKトラベルバナーここまで --}}

    <div id="pgh2">
        <div class="pg">
            <div class="pgh2-inner pgh2-top">
                <ul class="info">
                    @include('rsv.top._link_text')
                </ul>
                {{-- {include file='../_common/_pgh2_inner.tpl'} --}}
                @include('rsv.common._pgh2_inner')
            </div>
        </div>
    </div>

    <div id="pgc1">
        <div class="pg">
            <div class="pgc1-inner pgc1-top">
                <div class="jqs-banner-tosp">
                </div>
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="top-layout1" rowspan="2">
                            {{-- キーワード検索 --}}
                            @include('rsv.top._form_keyword')

                            {{-- 空室検索 日付から探す --}}
                            @include('rsv.common._form_search', [
                                'isTop' => true,
                            ])

                            {{-- ホテル検索 --}}
                            {{-- MEMO: 移植元ソースでコメントアウト --}}
                            {{-- @include('rsv.top._form_hotel') --}}
                        </td>
                        <td class="top-layout2" colspan="2">
                            {{-- メインバナー --}}
                            @include('rsv.top._rotation_banner')
                        </td>
                    </tr>
                    <tr>
                        <td class="top-layout3">
                            {{-- 地図 --}}
                            @include('rsv.top._link_map')
                        </td>
                        <td>
                            {{-- 駅検索 --}}
                            @include('rsv.top._link_station')

                            {{-- ランドマーク検索 --}}
                            {{-- MEMO: 移植元ソースでコメントアウト --}}
                            {{-- @include('rsv.top._link_landmark') --}}

                            <div class="sfm-extra">
                                {{-- Go To トラベル キャンペーン --}}
                                <a id="sfm-extra-atag-id" href="{{ route('rsv.campaign.goto') }}" title="Go To トラベル キャンペーン">
                                    <img src="{{ asset('img/tpc/banner-goto-306-159-2.gif') }}" alt="Go To トラベル キャンペーン" />
                                </a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="pgc2">
        <div class="pg">
            <div class="pgc2-inner pgc2-top">
                <table style="float:left;width:754px" border="0" cellpadding="0" cellspacing="0">
                    {{-- ＪＲ＋宿泊 --}}
                    <tr class="jqs-jrc">
                        <td class="top-layout11" colspan="2">
                            {{-- {include file='./_link_jrc.tpl'} --}}
                            <div style="margin-top:6px;float:left;">
                                <a class="btnimg" href="/feature/jrc/">
                                    <img src="/img/tpc/tpc-jrc-title.gif" alt="ＪＲ＋宿泊" width="753" height="40" />
                                </a>
                            </div>
                            <div style="clear:both">
                                <div style="padding: 3px 9px 3px 5px;float:left;">
                                    <a href="/jrc/?SiteCode=00574251&PageType=hotel&ListMode=Plan&HotelCD=2000050121&Departure=56" target="_blank">
                                        <img src="/feature/jrc/img/banner-2000050121_175-161.gif" alt="スマイルホテル東京日本橋" width="175" height="161" />
                                    </a>
                                </div>
                                <div style="padding: 3px 9px 3px 5px;float:left;">
                                    <a href="/jrc/?SiteCode=00574251&PageType=hotel&ListMode=Plan&HotelCD=2001080061&Departure=81" target="_blank">
                                        <img src="/feature/jrc/img/banner-2001080061_175-161.gif" alt="新大阪サニーストンホテル" width="175" height="161" />
                                    </a>
                                </div>
                                <div style="padding: 3px 9px 3px 5px;float:left;">
                                    <a href="/jrc/?SiteCode=00574251&PageType=hotel&ListMode=Plan&HotelCD=2011110923&Departure=31" target="_blank">
                                        <img src="/feature/jrc/img/banner-2011110923_175-161.gif" alt="和倉温泉湯けむりの宿美湾荘" width="175" height="161" />
                                    </a>
                                </div>
                                <div style="padding: 3px 0px 3px 5px;float:left;">
                                    <a href="/jrc/?SiteCode=00574251&PageType=hotel&ListMode=Plan&HotelCD=2001040033&Departure=31&Pax=1" target="_blank">
                                        <img src="/feature/jrc/img/banner-2001040033_175-161.gif" alt="ホテルウィングインターナショナル名古屋" width="175" height="161" />
                                    </a>
                                </div>
                            </div>
                            {{-- /include --}}
                        </td>
                    </tr>

                    {{-- バナー --}}
                    <tr>
                        <td class="top-layout12">
                            {{-- バナーB（大） --}}
                            <div>
                                <div style="clear:both;width:446px;">
                                    {{-- {include file='./_link_tdr.tpl'} --}}
                                    @php
                                        $alt = '東京ディズニーリゾート®・オフィシャルホテルへ行こう！';
                                        $uri = '/feature/tdr/';
                                        $img = '/feature/tdr/img/banner-218-219.gif';
                                    @endphp
                                    <div style="float:left;">
                                        <a href="{{ $uri }}" title="{{ $alt }}" alt="{{ $alt }}">
                                            <img src="{{ $img }}" alt="{{ $alt }}" width="218" height="219" />
                                        </a>
                                    </div>
                                    {{-- /include --}}
                                </div>
                                <div>
                                    {{-- {include file='./_link_yufuin.tpl'} --}}
                                    @php
                                        $alt = 'ルートインホテルズ 特集';
                                        $uri = '/hotel/grouphotel/JP2000RTIH.html';
                                        $img = '/hotel/grouphotel/images/bn_JP2000RTIH_wide.jpg';
                                    @endphp
                                    <div>
                                        <a href="{{ $uri }}" title="{{ $alt }}" alt="{{ $alt }}">
                                            <img src="{{ $img }}" alt="{{ $alt }}" style="margin-left:9px;" width="218" height="105" />
                                        </a>
                                    </div>
                                    {{-- /include --}}
                                    {{-- {include file='./_link_miyakojima.tpl'} --}}
                                    @php
                                        $alt = '地域振興 宮古島へ行こう';
                                        $uri = '/journey/japan/miyakojima/';
                                        $img = '/journey/japan/miyakojima/img/banner-218-105.png';
                                    @endphp
                                    <div>
                                        <a href="{{ $uri }}" title="{{ $alt }}" alt="{{ $alt }}">
                                            <img src="{{ $img }}" alt="{{ $alt }}" style="margin:9px 0 0 9px;" width="218" height="105" />
                                        </a>
                                    </div>
                                    {{-- /include --}}
                                </div>
                            </div>
                        </td>
                        <td class="top-layout13">
                            {{-- バナーB（小上） --}}
                            {{-- 一番上のバナーにだけ style="margin-bottom:11px" を付けてください --}}

                            {{-- 城崎特集 --}}
                            <div style="margin-bottom:11px">
                                {{-- {include file="./_link_kinosaki_middle.tpl"} --}}
                                <a href="#" title="" alt="" target="_blank">
                                    <img src="#" alt="仮実装" width="297" height="105" />
                                </a>
                                {{-- /include --}}
                            </div>

                            {{-- バナーC（小下） --}}
                            {{-- 温泉特集 --}}
                            <div style="margin-bottom:11px">
                                {{-- {include file="./_link_onsen_middle.tpl"} --}}
                                <a href="{$uri}" title="alt" alt="alt" target="_blank">
                                    <img src="{$img}" alt="alt" width="297" height="105" />
                                </a>
                                {{-- /include --}}
                            </div>
                        </td>
                    </tr>


                    {{-- レコメンド --}}
                    <tr>
                        <td colspan="2">
                            <div class="jqs-include" name="{$v->env.path_base_module}/recommend/?type=pc111">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="top-layout14" colspan="2">
                            <div>
                                @include('rsv.top._link_advert_hotels')
                            </div>
                        </td>
                    </tr>
                </table>

                {{-- MEMO: 画面下半分の右端 --}}
                <div class="top-layout15">
                    {{--
                        MEMO: 仮実装
                            使用しない可能性があるため、仮実装
                    --}}
                    @for ($i = 0; $i < 10; $i++)
                        @php
                            $uri = '#';
                            $alt = 'alt(' . $i . ')';
                            $img_path = '/img/#';
                            $width = 180;
                            $height = $i === 0 ? 120 : 60;
                        @endphp
                        <div style="margin-bottom:10px;text-align:right;">
                            {{-- {include file="./_link_2017mother.tpl"} --}}
                            <a href="{{ $uri }}" title="{{ $alt }}" alt="{{ $alt }}">
                                <img src="{{ $img_path }}" alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}" />
                            </a>
                            {{-- /include --}}
                        </div>
                    @endfor
                    {{--
                    <!-- MOTHER化粧品付きプラン特集 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_2017mother.tpl"}
                    </div>

                    <!-- 旅亭懐石のとや -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_notoya.tpl"}
                    </div>

                    <!-- 露天風呂付き客室特集 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_rotenroom.tpl"}
                    </div>

                    <!-- 東横イン特集 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_toyokoinn.tpl"}
                    </div>

                    <!-- 東北物語 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tohoku-monogatari.tpl"}
                    </div>

                    <!-- テレビde通訳 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tvde.tpl"}
                    </div>

                    <!-- おみせフォト特集 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_gphoto.tpl"}
                    </div>

                    <!-- バスぷらざ -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tabiplaza-bus.tpl"}
                    </div>

                    <!-- レンタカー -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tabiplaza-rentcar.tpl"}
                    </div>

                    <!-- MOTHER化粧品付きプラン特集 -->
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_mothercosme.tpl"}
                    </div>
                    --}}

                    {{-- スマフォ --}}
                    <div style="margin:0 0 10px 7px;text-align:right; border:2px solid #c00; width:176px;">
                        {{-- {include file="./_link_smartphone.tpl"} --}}
                        @php
                            $alt = 'スマートフォン版イオンコンパスホテル';
                            $uri = '/sp/';
                            $img = '/img/cnr/spqr/banner-176-144-spqr.gif';
                        @endphp
                        <a href="{{ $uri }}" title="{{ $alt }}" alt="{{ $alt }}" target="_blank">
                            <img src="{{ $img }}" alt="{{ $alt }}" width="176" height="144" />
                        </a>
                        <p style="font-size:12px; padding:5px 0 3px 6px; text-align:left;">
                            <a href="{{ $uri }}" style="color:#333;" target="_blank">
                                http://www.bestrsv.com/sp/
                            </a>
                        </p>
                        {{-- /include --}}
                    </div>

                    {{-- facebook --}}
                    <div class="fbshare" style="margin-bottom:10px;">
                        <h4>
                            <a href="https://www.facebook.com/bestrsv" target="_blank">ｆａｃｅｂｏｏｋでお得情報Ｇｅｔ！</a>
                        </h4>
                        <div class="cont">
                            <div class="info">
                                <h5>
                                    <a href="https://www.facebook.com/bestrsv" target="_blank">イオンコンパス運用ページ</a>
                                </h5>
                                <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.bestrsv.com%2F&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" style="border:none; overflow:hidden; width:70px; height:21px;" scrolling="no" frameborder="0" allowTransparency="true">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:both;">
                </div>

                {{-- EPARKトラベルバナー ここから --}}
                {{-- <div id="pgh2">
                    <div class="pg">
                        <a class="ept_banner" href="http://eparktravel.{{ config('app.env') != 'product' ? 'dev.' : '' }}bestrsv.com/lp/epark/" target="_blank">
                            <div class="pgh2-inner pgh2-top">
                                2019年2月28日　EPARKとイオンコンパスが連携し宿泊予約サイト『EPARKトラベル』をオープンしました！！　→　詳しくはこちらをクリック！
                            </div>
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    {{-- {include file='../_common/_footer.tpl' js="_af_2011001400.tpl"} --}}

@endsection
