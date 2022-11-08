{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\editsurvey.tpl --}}

@extends('ctl.common.base')
@section('title', '施設測地更新')

@section('page_blade')

    {{-- メッセージ --}}
    @include('ctl.common.message')

    {{-- 施設情報詳細 --}}
    @include('ctl.brhotel._hotel_info')

    <br>

    {{ Form::open(['route' => 'ctl.br_hotel.update_survey', 'method' => 'post']) }}
        @include('ctl.brhotel._input_survey_form')
        <INPUT TYPE="submit" VALUE="施設測地更新">
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form')

    <br>

@endsection
