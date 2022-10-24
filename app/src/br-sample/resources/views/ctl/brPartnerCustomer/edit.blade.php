
@extends('ctl.common.base2')

@section('title', '精算先情報')

@section('headScript')
    @include('ctl.brPartnerCustomer._css')
@endsection

@section('content')

    {{-- エラーメッセージ --}}
    {{-- TODO: 外部ファイルどちらにするか判断 --}}
    {{-- 移植元では、 view2 のものを埋め込んでいた。 --}}
    {{-- @include('ctl.common.message') --}}
    @include('ctl.common.message2')

    <hr class="contents-margin" />

    {{-- 入力フォーム --}}
    {{ Form::open(['route' => 'brpartnercustomer.modify', 'method' => 'post']) }}

        {{-- 精算先内容 --}}
        @include('ctl.brPartnerCustomer._input_customer')

        <hr class="contents-margin" />

        {{-- 引数 --}}
        <input type="hidden" name="customer_id" value="{{ strip_tags($customer_id) }}" />

        <input type="submit" value="更新">

    {{ Form::close() }}

    <hr class="contents-margin" />

    {{-- 一覧へ戻る --}}
    {{ Form::open(['route' => 'brpartnercustomer.search', 'method' => 'get']) }}
        <small>
            <input type="submit" value="請求先一覧へ">
        </small>
    {{ Form::close() }}

    <hr class="contents-margin" />

    {{-- 精算サイト一覧へ戻る --}}
    {{ Form::open(['route' => 'ctl.brPartnerSite.search', 'method' => 'get']) }}
        <small>
            <input type="submit" value="精算サイト一覧へ">
        </small>
    {{ Form::close() }}

    <hr class="contents-margin" />
@endsection
