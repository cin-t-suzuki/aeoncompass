{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\updatesurvey.tpl --}}

@extends('ctl.common.base')
@section('title', '施設測地更新情報')

@section('page_blade')

{{-- メッセージ --}}
@include('ctl.common.message')

{{-- 施設情報詳細 --}}
@include('ctl.brhotel._hotel_info')
<br>

{{ Form::open(['route' => 'ctl.brhotel.show', 'method' => 'post']) }}
  @include('ctl.brhotel._info_survey_form')
  <INPUT TYPE="submit" VALUE="詳細変更へ">
{{ Form::close() }}

@include('ctl.brhotel._hotel_top_form')

<br>

@endsection
