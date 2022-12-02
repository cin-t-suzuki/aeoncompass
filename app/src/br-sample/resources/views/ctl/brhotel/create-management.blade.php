{{-- MEMO: 移植元 public\app\ctl\views\brhotel\createmanagement.tpl --}}

{{-- MEMO: 移植元では .../views/_common/_br_header.tpl' を読み込んでいる --}}
@extends('ctl.common.base')
@section('title', '施設管理登録情報　STEP4/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{-- TODO: タグのインデントを整理 --}}
    @if (!$existsHotelNotify)
        {{ Form::open(['route' => 'ctl.br_hotel.status', 'method' => 'post']) }}
        {{-- <form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/state/"> --}}
    @else
        {{ Form::open(['route' => 'ctl.brhotel.show', 'method' => 'post']) }}
        {{-- <form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/show/"> --}}
    @endif

    @include('ctl.brhotel._info_management_form')

    @if (!$existsHotelNotify)
        <input type="submit" value="施設状態登録へ">
    @else
        <input type="submit" value="詳細変更へ">
    @endif

    {{-- </form> --}}
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')
    {{-- {include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'} --}}

    <br>
@endsection
