@extends('ctl.common._htl_base')
@section('title', '交通アクセス')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelStationController')

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel_station.list' , ['target_cd'=>$target_cd]) }}">交通アクセス</a>&nbsp;&gt;&nbsp;
新規登録
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

{!! Form::open(['route' => ['ctl.htl_hotel_station.new'], 'method' => 'get']) !!}
  <input type="submit" value="入力条件クリア">
  <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{!! Form::close() !!}

<table border="1" cellspacing="0" cellpadding="4" >
{!! Form::open(['route' => ['ctl.htl_hotel_station.new'], 'method' => 'get']) !!}
    <tr >
        <td bgcolor="#EEEEFF">
        路線
        </td>
        <td>
            @if(isset($a_mast_route->route_id) && $a_mast_route->route_id != "")
                {{strip_tags($a_mast_route->route_nm)}}
            @else
                <select name="HotelStation[route_id]">
                    <option value="">未選択
                        @foreach($a_mast_routes['values'] as $key => $value)
                            <option value="{{strip_tags($value->route_id)}}" @if(isset($a_mast_route) && $a_mast_route['route_id'] == $value->route_id) selected @endif>{{strip_tags($value->route_nm)}}
                        @endforeach
                </select>
                <input type="submit" value="路線指定" >
                <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
            @endif
        </td>
    </tr>

    {{-- 航路以外の時、駅表示 --}}
    @if(isset($a_mast_route->route_id) && $a_mast_route->route_id != 'B2001')
        <tr>
            <td bgcolor="#EEEEFF">
            駅
            </td>
            <td>
                @if(isset($a_mast_station->station_id) && isset($a_mast_route->route_id) && $a_mast_station->station_id != "" && $a_mast_route->route_id != "")
                    {{strip_tags($a_mast_station->station_nm)}}
                @else
                    <select name="HotelStation[station_id]">
                    <option value="">未選択
                        @foreach($a_mast_stations['values'] as $key => $value)
                            <option value="{{strip_tags($value->station_id)}}" @if(isset($a_mast_station['station_id']) && $a_mast_station['station_id'] == $value->station_id) selected @endif>{{strip_tags($value->station_nm)}}
                        @endforeach
                </select>
                @if(count($a_mast_stations['values']) > 0)
                    <input type="submit" value="駅指定" name="HotelStation[stationbtn]">
                    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                    <input type="hidden" name="HotelStation[route_id]" value="{{strip_tags($a_mast_route->route_id)}}">
                @endif
            @endif
            </td>
        </tr>
    @endif
{!! Form::close() !!}
</table>
{!! Form::open(['route' => ['ctl.htl_hotel_station.create'], 'method' => 'get']) !!}
<!-- formテンプレート使用 -->
@include('ctl.htlhotelstation._form')
    @if(isset($a_mast_station->station_id) && $a_mast_station->station_id != "")
  <tr>
    <td colspan="2" align="center">
      <input type ="submit" value="新規登録">
      <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
      <input type="hidden" name="HotelStation[station_id]" value="{{strip_tags($a_mast_station->station_id)}}">
      <input type="hidden" name="HotelStation[route_id]" value="{{strip_tags($a_mast_station->route_id)}}">
      <input type="hidden" name="HotelStation[stationbtn]" value="{{strip_tags($stationbtn)}}">
    </td>
  </tr>
  @elseif(isset($a_mast_route->route_id) && $a_mast_route->route_id == 'B2001')
  <tr>
    <td colspan="2" align="center">
      <input type ="submit" value="新規登録">
      <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
      <input type="hidden" name="HotelStation[station_id]" value="B200101">
      <input type="hidden" name="HotelStation[route_id]" value="B2001">
      <input type="hidden" name="HotelStation[stationbtn]" value="駅指定">
    </td>
  </tr>
  @endif
</table>
{!! Form::close() !!}
@endsection