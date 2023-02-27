@extends('ctl.common._htl_base')
@section('title', '交通アクセス')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelStationController')

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
交通アクセス
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

登録済みの交通アクセス
<table border="1" cellspacing="0" cellpadding="4">
  <tr bgcolor="#EEEEFF">
    <td >
      表示順序並べ替え
    </td>
    <td >
      路線
    </td>
    <td>
      駅
    </td>
    <td>
      移動方法
    </td>
    <td>
      時間
    </td>
    <td>
      &nbsp;
    </td>
  </tr>
  @foreach($a_hotel_station['values'] as $key => $value)
  <tr @if(isset($station_id) && $station_id == $value->station_id && isset($traffic_way) && $traffic_way == $value->traffic_way) style="background-color:#FEF" @endif>
    <td >
        {!! Form::open(['route' => ['ctl.htl_hotel_station.move'], 'method' => 'post']) !!}
            @if(!$loop->first)
                <input name="top" type="submit" value="先頭へ"><input name="up" type="submit" value="上へ"><br />
            @endif
            @if(!$loop->last)
                <input name="bottom" type="submit" value="末尾へ"><input name="down" type="submit" value="下へ">
            @endif
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                <input type="hidden" name="HotelStation[station_id]" value="{{strip_tags($value->station_id)}}">
                <input type="hidden" name="HotelStation[traffic_way]" value="{{strip_tags($value->traffic_way)}}">
        {!! Form::close() !!}
    </td>
    <td >
      {{strip_tags($value->route_nm)}}
    </td>
    <td>
      {{strip_tags($value->station_nm)}}
    </td>
    <td style="text-align:center;">
      @if($value->traffic_way == 0)
        徒歩
      @elseif($value->traffic_way == 1)
        車
      @endif
    </td>
    <td style="text-align:right;">
      {{strip_tags($value->minute)}}分
    </td>

    <td>
      <table>
        <tr>
          <td>
            {!! Form::open(['route' => ['ctl.htl_hotel_station.edit'], 'method' => 'get']) !!}
                <input type="submit" value="編集">
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                <input type="hidden" name="HotelStation[traffic_way]" value="{{strip_tags($value->traffic_way)}}">
                <input type="hidden" name="HotelStation[station_id]" value="{{strip_tags($value->station_id)}}">
                <input type="hidden" name="HotelStation[route_id]" value="{{strip_tags($value->route_id)}}">
            {!! Form::close() !!}
          </td>
          <td>
            {!! Form::open(['route' => ['ctl.htl_hotel_station.delete'], 'method' => 'get']) !!}
                <input type="submit" value="削除">
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                <input type="hidden" name="HotelStation[traffic_way]" value="{{strip_tags($value->traffic_way)}}">
                <input type="hidden" name="HotelStation[station_id]" value="{{strip_tags($value->station_id)}}">
            {!! Form::close() !!}
          </td>
        </tr>
      </table>
    </td>
  </tr>
  @endforeach
</table>
{{-- {if $v->user->operator->is_staff() and count($v->assign->a_hotel_station.values) > 1} --}}
{{-- TODO $v->user->operator->is_staff() の判定 --}}
@if(count($a_hotel_station['values']) > 1)
<div style="margin:0.5em 0;">
    {!! Form::open(['route' => ['ctl.htl_hotel_station.defaultorder'], 'method' => 'post']) !!}
        <input type="submit" value="表示順序初期化"> <small>（ 移動方法 、時間、路線、駅 の順で並べ替えます。 ）</small>
        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
    {!! Form::close() !!}
</div>
@endif
<br>
{!! Form::open(['route' => ['ctl.htl_hotel_station.new'], 'method' => 'post']) !!}
  <input type="submit" value="新規登録">
  <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}
@endsection