{{-- 提携先管理ヘッダー・フッター --}}
@extends('ctl.common.base2')

@section('title', '精算サイト情報')

@section('headScript')
    @include('ctl.brPartnerSite._css')
@endsection

@section('content')

{{-- エラーメッセージ --}}
{{-- TODO: 外部ファイルどちらにするか判断 --}}
{{-- 移植元では、 view2 のものを埋め込んでいた。 --}}
{{-- @include('ctl.common.message') --}}
@include('ctl.common.message2')

<hr class="contents-margin" />

{{-- 入力フォーム --}}
{{-- TODO: Form Facades --}}
<form action="{$v->env.source_path}{$v->env.module}/brpartnersite/modify/" method="POST">

    {{-- 精算先内容 --}}
    @include('ctl.brPartnerSite._input_site')

    <hr class="contents-margin" />

    {{-- 引数 --}}
    <input type="hidden" name="site_cd" value="{$v->helper->form->strip_tags($v->assign->form_params.site_cd)}" />
    {foreach from=$v->assign->search_params item=value key=key}
        <input type="hidden" name="{$key}" value="{$value}" />
    {/foreach}

    <input type="submit" value="更新">

</form>

{{-- 料率表示 --}}
@include('ctl.brPartnerSite._info_rate')

<hr class="contents-margin" />

{{-- 一覧へ戻る --}}
{{-- TODO: Form Facades --}}
<form action="{$v->env.source_path}{$v->env.module}/brpartnersite/search/" method="POST">
    <small>
        {foreach from=$v->assign->search_params item=value key=key}
            <input type="hidden" name="{$key}" value="{$value}" />
        {/foreach}

        <input type="submit" value="精算サイト一覧へ">
    </small>
</form>

<hr class="contents-margin" />

@endsection
