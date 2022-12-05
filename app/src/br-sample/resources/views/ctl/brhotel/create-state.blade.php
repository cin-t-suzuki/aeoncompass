{{-- MEMO: 移植元 public\app\ctl\views\brhotel\createstate.tpl --}}

{{-- MEMO: 移植元では .../views/_common/_br_header.tpl' を読み込んでいる --}}
@extends('ctl.common.base')
@section('title', '施設状態登録情報　STEP6/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{ Form::open(['route' => 'ctl.brhotel.show', 'method' => 'get']) }}
    {{-- <form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/show/"> --}}

    @include('ctl.brhotel._info_state_form')
    {{-- {include file=$v->env.module_root|cat:'/views/brhotel/_info_state_form.tpl'} --}}

    <input type="submit" value="詳細変更へ">

    {{-- </form> --}}
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')
    {{-- {include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'} --}}

    <br>
@endsection
