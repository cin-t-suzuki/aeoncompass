{{-- MEMO: 移植元 public\app\ctl\views\brhotel\new.tpl --}}

@extends('ctl.common.base')
@section('title', '施設情報　STEP1/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{ Form::open(['route' => 'ctl.br_hotel.create', 'method' => 'post']) }}
        @include('ctl.brhotel._input_hotel_form')
        <input type="submit" value="施設登録">
        ※は必須です。
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')
    <br>
@endsection
