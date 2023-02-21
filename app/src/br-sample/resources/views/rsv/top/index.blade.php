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
                    {include file="./_link_text.tpl"}
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
                            {include file='./_link_jrc.tpl'}
                        </td>
                    </tr>

                    {{-- バナー --}}
                    <tr>
                        <td class="top-layout12">
                            {{-- バナーB（大） --}}
                            <div>
                                <div style="clear:both;width:446px;">
                                    {include file='./_link_tdr.tpl'}
                                </div>
                                <div>
                                    {include file='./_link_yufuin.tpl'}
                                    {include file='./_link_miyakojima.tpl'}
                                </div>
                            </div>
                        </td>
                        <td class="top-layout13">
                            {{-- バナーB（小上） --}}
                            {{-- 一番上のバナーにだけ style="margin-bottom:11px" を付けてください --}}

                            {{-- 城崎特集 --}}
                            <div style="margin-bottom:11px">
                                {include file="./_link_kinosaki_middle.tpl"}
                            </div>

                            {{-- バナーC（小下） --}}
                            {{-- 温泉特集 --}}
                            <div style="margin-bottom:11px">
                                {include file="./_link_onsen_middle.tpl"}
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
                                {include file="./_link_advert_hotels.tpl"}
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="top-layout15">

                    {{-- MOTHER化粧品付きプラン特集 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_2017mother.tpl"}
                    </div>

                    {{-- 旅亭懐石のとや --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_notoya.tpl"}
                    </div>

                    {{-- 宮古島 --}}
                    {{-- <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_miyakojima_small.tpl"}
                    </div> --}}

                    {{-- 露天風呂付き客室特集 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_rotenroom.tpl"}
                    </div>

                    {{-- 東横イン特集 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_toyokoinn.tpl"}
                    </div>

                    {{-- 東北物語 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tohoku-monogatari.tpl"}
                    </div>

                    {{-- テレビde通訳 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tvde.tpl"}
                    </div>

                    {{-- おみせフォト特集 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_gphoto.tpl"}
                    </div>

                    {{-- バスぷらざ --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tabiplaza-bus.tpl"}
                    </div>

                    {{-- レンタカー --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_tabiplaza-rentcar.tpl"}
                    </div>

                    {{-- スマフォ --}}
                    <div style="margin:0 0 10px 7px;text-align:right; border:2px solid #c00; width:176px;">
                        {include file="./_link_smartphone.tpl"}
                    </div>


                    {{-- MOTHER化粧品付きプラン特集 --}}
                    <div style="margin-bottom:10px;text-align:right;">
                        {include file="./_link_mothercosme.tpl"}
                    </div>

                    {{-- facebook --}}
                    <div class="fbshare" style="margin-bottom:10px;">
                        <h4>
                            <a href="https://www.facebook.com/bestrsv" target="_blank">ｆａｃｅｂｏｏｋでお得情報Ｇｅｔ！</a>
                        </h4>
                        <div class="cont">
                            <div class="info">
                                <h5>
                                    <a href="https://www.facebook.com/bestrsv" target="_blank">ベストリザーブ・宿<br />ぷらざ運用ページ</a>
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
                <div id="pgh2">
                    <div class="pg">
                        <a class="ept_banner" href="http://eparktravel.{{ config('app.env') != 'product' ? 'dev.' : '' }}bestrsv.com/lp/epark/" target="_blank">
                            <div class="pgh2-inner pgh2-top">
                                2019年2月28日　EPARKとベストリザーブが連携し宿泊予約サイト『EPARKトラベル』をオープンしました！！　→　詳しくはこちらをクリック！
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- {include file='../_common/_footer.tpl' js="_af_2011001400.tpl"} --}}

@endsection
