{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\editmanagement.tpl --}}

@extends('ctl.common.base')
@section('title', '施設管理情報更新')

@inject('service', 'App\Http\Controllers\ctl\BrhotelController')

@section('page_blade')
    {{-- メッセージ --}}
    @include('ctl.common.message', $messages)

    {{-- 施設情報詳細 --}}
    @include(
        'ctl.brhotel._hotel_info'
        , [
            "hotel" => $views->hotel,
            "mast_pref" => $views->mast_pref,
            "mast_city" => $views->mast_city,
            "mast_ward" => $views->mast_ward
        ]
    )

    <br>

    {{ Form::open(['route' => 'ctl.br_hotel.update_management', 'method' => 'post']) }}

        @include('ctl.brhotel._input_management_form')

        <input type="submit" value="施設管理情報更新">
        ※は必須です。

    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form', ["target_cd" => $views->target_cd])

    <br>

    @include('ctl.brhotel._log_hotel_person_form')

    <br>
@endsection