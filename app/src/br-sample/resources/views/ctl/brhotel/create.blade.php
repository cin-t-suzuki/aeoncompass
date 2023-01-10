{{-- MEMO: 移植元 public\app\ctl\views\brhotel\create.tpl --}}

@extends('ctl.common.base')
@section('title', '施設登録情報　STEP2/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{ Form::open(['route' => 'ctl.br_hotel.management', 'method' => 'get']) }}
        @include('ctl.brhotel._info_hotel_form')
        <input type="submit" value="施設管理情報登録へ">
    {{ Form::close() }}

    <hr size="1">

    {{ Form::open(['route' => 'ctl.brhotel.index', 'method' => 'get']) }}
        <small>
            <input type="submit" value="施設情報メインへ">
        </small>
    {{ Form::close() }}
@endsection
