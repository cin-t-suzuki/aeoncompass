@extends('ctl.common._htl_base')
@section('title', '交通アクセス')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelStationController')

@section('content')

{{-- パンクズ --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel_station.list' , ['target_cd'=>$target_cd]) }}">交通アクセス</a>&nbsp;&gt;&nbsp;
変更
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')
<br>
{!! Form::open(['route' => ['ctl.htl_hotel_station.update'], 'method' => 'get']) !!}
<!-- formテンプレート使用 -->
@include('ctl.htlhotelstation._form')
  <tr>
    <td colspan="2" align="center">  
      <input type ="submit" value="変更">
      <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
      <input type="hidden" name="HotelStation[station_id]" value="{{strip_tags($a_mast_station['station_id'])}}">
      <input type="hidden" name="HotelStation[old_traffic_way]" value="{{strip_tags($a_hotel_station['traffic_way'])}}">
    </td>
  </tr>
</table>
{!! Form::close() !!}
@endsection