@extends('ctl.common._htl_base')
@section('title', '施設連絡事項')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelInformController')

@section('content')

<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel_inform.list' , ['target_cd'=>$target_cd]) }}">施設連絡事項</a>&nbsp;&gt;&nbsp;
変更
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')

<br>
{!! Form::open(['route' => ['ctl.htl_hotel_inform.update'], 'method' => 'post']) !!}
@include('ctl.htlHotelInform._form')
    <tr>
      <td align="left" colspan="2">
        <input type="submit" value="変更">
        <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
        <input type="hidden" name="HotelInform[branch_no]" value="{{strip_tags($a_hotel_inform['branch_no'])}}">
        <input type="hidden" name="HotelInform[order_no]" value="{{strip_tags($a_hotel_inform['order_no'])}}">
      </td>
    </tr>
  </table>
{!! Form::close() !!}
@endsection