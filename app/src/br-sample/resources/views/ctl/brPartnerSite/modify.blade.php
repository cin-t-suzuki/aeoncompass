{{-- 移植元: svn_trunk\public\app\ctl\view2\brpartnersite\modify.tpl --}}

{{-- 提携先管理ヘッダー・フッター --}}
@extends('ctl.common.base2')

@section('title', '精算サイト情報')

@section('headScript')
    @include('ctl.brPartnerSite._css')
@endsection

@section('content')
    <hr class="contents-margin" />

    {{-- エラーメッセージ --}}
    {{-- TODO: 外部ファイルどちらにするか判断 --}}
    {{-- 移植元では、 view2 のものを埋め込んでいた。 --}}
    {{-- @include('ctl.common.message') --}}
    @include('ctl.common.message2')

    <hr class="contents-margin" />

    {{-- 精算先情報表示 --}}
    @include('ctl.brPartnerSite._info_site')

    {{-- 料率表示 --}}
    @include('ctl.brPartnerSite._info_rate')


    <hr class="contents-margin" />

    {{-- 入力画面を表示 --}}
    <p>
        <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/edit/" method="POST">
            <small>
                <input type="hidden" name="site_cd" value="{$v->assign->partner_site.site_cd}" />
                {foreach from=$v->assign->search_params item=value key=key}
                    <input type="hidden" name="{$key}" value="{$value}" />
                {/foreach}
                <input type="submit" value="精算サイト情報の表示・編集へ">
            </small>
        </form>
    </p>

    {{-- 一覧に戻る --}}
    <p>
        <form action="{$v->env.source_path}{$v->env.module}/brpartnersite/search/" method="POST">
            <small>
                {foreach from=$v->assign->search_params item=value key=key}
                    <input type="hidden" name="{$key}" value="{$value}" />
                {/foreach}
                <input type="submit" value="精算サイト一覧へ">
            </small>
        </form>
    </p>
@endsection
