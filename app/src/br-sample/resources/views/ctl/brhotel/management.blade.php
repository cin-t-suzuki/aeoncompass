{{-- MEMO: 移植元 public\app\ctl\views\brhotel\management.tpl --}}

@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

{{-- MEMO: 移植元では .../views/_common/_br_header.tpl' を読み込んでいる (title="施設管理情報　STEP3/6") --}}
@extends('ctl.common.base')
@section('title', '施設管理情報　STEP3/6')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{ Form::open(['route' => 'ctl.br_hotel.create_management', 'method' => 'post']) }}
        @include('ctl.brhotel._input_management_form', [
            'new_flg' => true,
        ])
        <input type="submit" value="施設管理情報登録">
        ※は必須です。
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')
    <br>
@endsection
