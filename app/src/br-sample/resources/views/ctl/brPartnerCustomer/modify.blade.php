
@extends('ctl.common.brPartnerCustomerBaseToBeRenamed')

@section('title', '精算先情報')

@section('headScript')
    @include('ctl.brPartnerCustomer._css')
@endsection

@section('content')

    <hr class="contents-margin" />

    {{-- エラーメッセージ --}}
    {{-- TODO: 外部ファイルどちらにするか判断 --}}
    {{-- 移植元では、 views のものを埋め込んでいた。(edit では view2 なのに) --}}
    {{-- @include('ctl.common.message') --}}
    @include('ctl.common.message2')
    <hr class="contents-margin" />

    {{-- 精算先情報表示 --}}
    @include('ctl.brPartnerCustomer._info_customer')

    <hr class="contents-margin" />

    {{-- 一覧に戻る --}}
    {{ Form::open(['route' => 'brpartnercustomer.search', 'method' => 'get']) }}
        <small>
            <input type="submit" value="精算先請求先一覧へ">
        </small>
    {{ Form::close() }}

@endsection
