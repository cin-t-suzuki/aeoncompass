
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
    @include('ctl.common.message', ['errors' => ["エラー view", "エラー2 view"], 'guides' => $guides])
    @include('ctl.common.message2', ['errors' => ["エラー view2", "エラー2 view2"], 'guides' => $guides])

    <hr class="contents-margin" />

    {{-- 精算先情報表示 --}}
    @include('ctl.brPartnerCustomer._input_customer')

    <hr class="contents-margin" />

    {{-- 一覧に戻る --}}
    {{ Form::open(['route' => 'brpartnercustomer.search', 'method' => 'get']) }}
        <small>
            @foreach ($search_params as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
            @endforeach
            <input type="submit" value="精算先請求先一覧へ">
        </small>
    {{ Form::close() }}

@endsection
