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

@include('ctl.common.message')

{!! Form::open(['route' => ['ctl.htlsplanoffer.list'], 'method' => 'get', 'style' =>'display:inline;' , 'name' => 'back_form']) !!}
    <input type="hidden" name="target_cd" value="{{$form_params['target_cd']}}" />
    <input type="hidden" name="start_ymd[year]"  value="{{date("Y", $form_params['current_ymd'])}}" />
    <input type="hidden" name="start_ymd[month]" value="{{date("m", $form_params['current_ymd'])}}" />
    <input type="hidden" name="start_ymd[day]"   value="{{date("j", $form_params['current_ymd'])}}" />
{!! Form::close() !!}

{!! Form::open(['route' => ['ctl.htlsplanoffer.confirm'], 'method' => 'get', 'style' =>'display:inline;' , 'name' => 'confirm_form']) !!}
    <input type="hidden" name="target_cd"   value="{{$form_params['target_cd']}}" />
    <input type="hidden" name="ui_type"     value="{{$form_params['ui_type']}}" />
    <input type="hidden" name="target_ymd"  value="{{$form_params['target_ymd']}}" />
    <input type="hidden" name="current_ymd" value="{{$form_params['current_ymd']}}" />
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
            <input id="batch_sale" type="radio" name="accept_status" value="1" @if(isset($form_params['accept_status']) && $form_params['accept_status'] == 1) checked @endif/><label for="batch_sale">一括販売</label>
            <input id="batch_stop" type="radio" name="accept_status" value="0" @if(!isset($form_params['accept_status']) || $form_params['accept_status'] == 0) checked @endif/><label for="batch_stop">一括売止</label>
        </div>
        <br />
        <p style="width:860px; text-align:left;">
            <font color="#cdcdcd">■</font>期間の設定
        </p>
        <div style="width:860px; text-align:left;">
        <select name="from_year">
            @for($from_year = $start_date['year']; $from_year < $end_date['year']+1; $from_year++)
                <option value="{{$from_year}}" @if($from_year == $form_params['from_year']) selected @endif>{{$from_year}}</option>
            @endfor
        </select>年
        &nbsp;
        <select name="from_month">
            @for($from_month=1; $from_month <= 12; $from_month++)
                <option value="{{$from_month}}" @if($from_month == $form_params['from_month']) selected @endif>{{$from_month}}</option>
            @endfor
        </select>月
        &nbsp;
        <select name="from_day">
            @for($from_day=1; $from_day <= 31; $from_day++)
                <option value="{{$from_day}}" @if($from_day == $form_params['from_day']) selected @endif>{{$from_day}}</option>
            @endfor
        </select>日
        &nbsp;～&nbsp;
        <select name="to_year">
            @for($to_year = $start_date['year']; $to_year < $end_date['year']+1; $to_year++)
                <option value="{{$to_year}}" @if($to_year == $form_params['to_year']) selected @endif>{{$to_year}}</option>
            @endfor
        </select>年
        &nbsp;
        <select name="to_month">
            @for($to_month=1; $to_month <= 12; $to_month++)
                <option value="{{$to_month}}" @if($to_month == $form_params['to_month']) selected @endif>{{$to_month}}</option>
            @endfor
        </select>月
        &nbsp;
        <select name="to_day">
            @for($to_day=1; $to_day <= 31; $to_day++)
                <option value="{{$to_day}}" @if($to_day == $form_params['to_day']) selected @endif>{{$to_day}}</option>
            @endfor
        </select>日
    </div>
</div>
{!! Form::close() !!}
<br />
<div align="center">
    <input type="button" value="戻る" onClick="select_submit('back_form');return false;" />
    <input type="button" value="確認" onClick="select_submit('confirm_form');return false;" />
</div>

@endsection