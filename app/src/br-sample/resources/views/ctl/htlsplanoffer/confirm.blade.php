@extends('ctl.common._htl_base')
@section('content')
@section('title', '提供室数調整')
@include('ctl.common._htls_room_header')
    @section('headScript')
      <script type="text/javascript"> 
        <!--
          function select_submit(form_name) {
            document.forms[form_name].submit();
          }
        -->
      </script>
    @endsection

    {!! Form::open(['route' => ['ctl.htlsplanoffer.edit'], 'method' => 'get', 'style' =>'display:inline;' , 'name' => 'back_form']) !!}
        <input type="hidden" name="target_cd" value="{{$form_params['target_cd']}}" />
        <input type="hidden" name="ui_type" value="{{$form_params['ui_type']}}" />
        <input type="hidden" name="current_ymd" value="{{$form_params['current_ymd']}}" />
        <input type="hidden" name="target_ymd" value="{{$form_params['target_ymd']}}" />
        @foreach($form_params['plan_id'] as $plan_id)
            <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
        @endforeach
    {!! Form::close() !!}

    {!! Form::open(['route' => ['ctl.htlsplanoffer.update'], 'method' => 'get', 'style' =>'display:inline;' , 'name' => 'update_form']) !!}
        <input type="hidden" name="target_cd" value="{{$form_params['target_cd']}}" />
        <input type="hidden" name="ui_type" value="{{$form_params['ui_type']}}" />
        <input type="hidden" name="current_ymd" value="{{$form_params['current_ymd']}}" />
        <input type="hidden" name="target_ymd" value="{{$form_params['accept_status']}}" />
        @foreach($form_params['plan_id'] as $plan_id)
            <input type="hidden" name="plan_id[]" value="{{$plan_id}}" />
        @endforeach
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
                <input type="hidden" name="accept_status" value="{{$form_params['accept_status']}}" />
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
                <input type="hidden" name="from_year"  value="{{$form_params['from_year']}}" />
                <input type="hidden" name="from_month" value="{{$form_params['from_month']}}" />
                <input type="hidden" name="from_day"   value="{{$form_params['from_day']}}" />
                <input type="hidden" name="to_year"    value="{{$form_params['to_year']}}" />
                <input type="hidden" name="to_month"   value="{{$form_params['to_month']}}" />
                <input type="hidden" name="to_day"     value="{{$form_params['to_day']}}" />
                <input type="hidden" name="target_ymd" value="{{$form_params['target_ymd']}}" />
            </div>
        </div>
    {!! Form::close() !!}
    <br />
<div align="center">
    <input type="button" value="修正" onClick="select_submit('back_form');return false;" />
    <input type="button" value="更新" onClick="select_submit('update_form');return false;" />  
</div>

@endsection