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
    {{ Form::open(['route' => 'ctl.brPartnerSite.modify', 'method' => 'post']) }}

        {{-- 精算先内容 --}}
        @include('ctl.brPartnerSite._input_site')

        <hr class="contents-margin" />

        {{-- 引数 --}}
        <input type="hidden" name="site_cd" value="{{ strip_tags($form_params['site_cd']) }}" />
        @foreach ($search_params as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
        @endforeach

        <input type="submit" value="更新">

    {{ Form::close() }}

    {{-- 料率表示 --}}
    @include('ctl.brPartnerSite._info_rate')

    <hr class="contents-margin" />

    {{-- 一覧へ戻る --}}
    {{ Form::open(['route' => 'ctl.brPartnerSite.search', 'method' => 'get']) }}
        <small>
            @foreach ($search_params as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
            @endforeach

            <input type="submit" value="精算サイト一覧へ">
        </small>
    {{ Form::close() }}

    <hr class="contents-margin" />

@endsection
