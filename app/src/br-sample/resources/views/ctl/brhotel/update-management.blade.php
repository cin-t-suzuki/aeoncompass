{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\updatemanagement.tpl --}}

@extends('ctl.common.base')
@section('title', '施設管理情報更新')

@section('page_blade')

    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{-- 施設情報詳細 --}}
    @include('ctl.brhotel._hotel_info')

    <br>

    {{ Form::open(['route' => 'ctl.brhotel.show', 'method' => 'get']) }}
        @include('ctl.brhotel._info_management_form')
        <input type="submit" value="詳細変更へ">
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')

    <br>

@endsection
