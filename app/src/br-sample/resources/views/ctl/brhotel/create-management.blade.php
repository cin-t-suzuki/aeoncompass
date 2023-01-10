{{-- MEMO: 移植元 public\app\ctl\views\brhotel\createmanagement.tpl --}}

{{-- MEMO: 移植元では .../views/_common/_br_header.tpl' を読み込んでいる --}}
@extends('ctl.common.base')
@section('title', '施設管理登録情報　STEP4/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{ Form::open(['route' => !$existsHotelNotify ? 'ctl.br_hotel.state' : 'ctl.brhotel.show', 'method' => 'get']) }}
        @include('ctl.brhotel._info_management_form')
        <input type="submit" value="{{ !$existsHotelNotify ? '施設状態登録へ' : '詳細変更へ' }}">
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')
    <br>
@endsection
