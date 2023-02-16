@extends('ctl.common._htl_base')
@section('content')
@section('title', '提供室数調整')
  @include('ctl.common._htls_room_header')

  @if($views->form_params['ui_type'] == 'date')
    @include('ctl.htlsroomoffer._update_ui_date')
  @elseif($views->form_params['ui_type'] =='room' || $views->form_params['ui_type'] == 'accept')
    @include('ctl.htlsroomoffer._update_ui_room')
  @elseif($views->form_params['ui_type'] =='calender')
    @include('ctl.htlsroomoffer._update_ui_calender')
  @endif

  <div align="right">
    {!! Form::open(['route' => ['ctl.htlsroomplan2.list'], 'method' => 'post']) !!}
      <div>
        <input type="hidden" name="target_cd" value="{{$views->target_cd}}" />
        <input type="submit" name="back" value="プランメンテナンスへ" />
      </div>
    {!! Form::close() !!}
  </div>

@endsection