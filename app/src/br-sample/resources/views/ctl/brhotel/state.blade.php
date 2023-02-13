{{-- MEMO: 移植元 public\app\ctl\views\brhotel\state.tpl --}}

{{-- MEMO: 移植元では .../views/_common/_br_header.tpl' を読み込んでいる --}}
@extends('ctl.common.base')
@section('title', '施設状態情報　STEP5/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{ Form::open(['route' => 'ctl.br_hotel.create_state', 'method' => 'post']) }}
    {{-- <form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/createstate/"> --}}

    @include('ctl.brhotel._input_state_form')
    {{-- {include file=$v->env.module_root|cat:'/views/brhotel/_input_state_form.tpl'} --}}

    <input type="submit" value="施設状態登録">
    ※は必須です。

    {{-- </form> --}}
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')
    {{-- {include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'} --}}

    <br>
@endsection
