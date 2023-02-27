@extends('ctl.common._htl_base')
@section('content')
@section('title', '提供室数調整')
@include('ctl.common._htls_room_header')

@include('ctl.common.message')


<div align="center">
    <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:860px;">
        @if($form_params['ui_type'] == 'date')
            {{date('Y年m月j日',$disp_date['target_date'])}}（{{$disp_date['week_day']}}）
        @else
            {{$plan_data['plan_nm']}}
        @endif
    </div>
    <p style="width:860px; text-align:left;">
        <font color="#cdcdcd">■</font>販売ステータス（一括）
    </p>
    <div style="width:860px; text-align:left;">
        @if($form_params['accept_status'] == 0) 一括売止
        @elseif($form_params['accept_status'] == 1)一括販売
        @else ステータスを変更しない
        @endif
    </div>
    <br />
    <p style="width:860px; text-align:left;">
        <font color="#cdcdcd">■</font>期間の設定
    </p>
    <div style="width:860px; text-align:left;">
        {{$form_params['from_year']}} 年 {{$form_params['from_month']}}月 {{$form_params['from_day']}}日～ {{$form_params['to_year']}} 年 {{$form_params['to_month']}}月 {{$form_params['to_day']}}日
    </div>
</div>
<br />
<div align="center">
    {!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'get', 'style' =>'display:inline;']) !!}
        <input type="hidden" name="target_cd" value="{{$form_params['target_cd']}}" />
        <input type="hidden" name="start_ymd[year]"  value="{{date("Y", $form_params['current_ymd'])}}" />
        <input type="hidden" name="start_ymd[month]" value="{{date("m", $form_params['current_ymd'])}}" />
        <input type="hidden" name="start_ymd[day]"   value="{{date("j", $form_params['current_ymd'])}}" />
        <input type="submit" value="一覧へ" />
    {!! Form::close() !!}
</div>

@endsection