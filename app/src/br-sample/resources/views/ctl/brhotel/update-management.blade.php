{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\updatemanagement.tpl --}}

@extends('ctl.common.base')
@section('title', '施設管理情報更新')

@section('page_blade')

    {{-- メッセージ --}}
    @include('ctl.common.message', $messages)

    {{-- 施設情報詳細 --}}
    @include('ctl.brhotel._hotel_info', [
        "hotel" => $views->hotel,
        "mast_pref" => $views->mast_pref,
        "mast_city" => $views->mast_city,
        "mast_ward" => $views->mast_ward
    ])

    <br>

    {{-- TODO: 要確認 移植元は post だが、 get でよさそう --}}
    {{ Form::open(['route' => 'ctl.brhotel.show', 'method' => 'post']) }}
        @include('ctl.brhotel._info_management_form')
        <INPUT TYPE="submit" VALUE="詳細変更へ">
    {{ Form::close() }}

    @include('ctl.brhotel._hotel_top_form', [
        "target_cd" => $views->target_cd
    ])

    <br>

@endsection
