{{-- HACK: edit とほとんど重複、 form の action(route) と、ボタンの登録・更新だけが違う。 --}}
@extends('ctl.common.brPartnerCustomerBaseToBeRenamed')

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
    {{ Form::open(['route' => 'brpartnercustomer.register', 'method' => 'post']) }}

        {{-- 精算先内容 --}}
        @include('ctl.brPartnerCustomer._input_customer')

        <hr class="contents-margin" />

        <input type="submit" value="登録">

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
    {{ Form::open(['route' => 'brpartnersite.search', 'method' => 'post']) }}
        <small>
            <input type="submit" value="精算サイト一覧へ">
        </small>
    {{ Form::close() }}

    <hr class="contents-margin" />
@endsection
