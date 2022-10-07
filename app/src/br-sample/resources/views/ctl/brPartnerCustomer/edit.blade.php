
@extends('ctl.common.brPartnerCustomerBaseToBeRenamed')

@section('title', '精算先情報')

@section('headScript')
    @include('ctl.brPartnerCustomer._css')
@endsection

@section('content')

    {{-- エラーメッセージ --}}
    {{-- TODO: 外部ファイルどちらにするか判断 --}}
    {{-- 移植元では、 view2 のものを埋め込んでいた。 --}}
    @include('ctl.common.message', ['errors' => ["エラー view"], 'guides' =>["ガイド view"]])
    @include('ctl.common.message2', ['errors' => ["エラー view2"], 'guides' =>["ガイド view2"]])

    <hr class="contents-margin" />

    {{-- 入力フォーム --}}
    {{ Form::open(['route' => 'brpartnercustomer.modify', 'method' => 'post']) }}

        {{-- 精算先内容 --}}
        {include file='./_input_customer.tpl'}

        <hr class="contents-margin" />

        {{-- 引数 --}}
        <input type="hidden" name="customer_id" value="{$v->helper->form->strip_tags($v->assign->form_params.customer_id)}" />
        {foreach from=$v->assign->search_params item=value key=key}
            {if ($key != 'customer_id')}<input type="hidden" name="{$key}" value="{$value}" />{/if}
        {/foreach}

        <input type="submit" value="更新">

    {{ Form::close() }}

    <hr class="contents-margin" />

    {{-- 一覧へ戻る --}}
    {{ Form::open(['route' => 'brpartnercustomer.search', 'method' => 'post']) }}
        <small>
            {{-- TODO: CHECK: ここで、検索条件を保持して一覧画面に戻っている。 --}}
            {foreach from=$v->assign->search_params item=value key=key}
                <input type="hidden" name="{$key}" value="{$value}" />
            {/foreach}
            <input type="submit" value="請求先一覧へ">
        </small>
    {{ Form::close() }}


    <hr class="contents-margin" />

    {{-- 精算サイト一覧へ戻る --}}
    {{ Form::open(['route' => 'brpartnersite.search', 'method' => 'post']) }}
        <small>
            {foreach from=$v->assign->search_params item=value key=key}
                <input type="hidden" name="{$key}" value="{$value}" />
            {/foreach}

            <input type="submit" value="精算サイト一覧へ">
        </small>
    {{ Form::close() }}

    <hr class="contents-margin" />
@endsection
