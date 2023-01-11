@extends('ctl.common._htl_base')
@section('title', '施設情報')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelInfoController')

{{-- TODO サブメニュー --}}
@section('content')

<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$views->target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$views->hotelInfo['hotel_cd'] ] ) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel_info.show' , ['target_cd'=>$views->hotelInfo['hotel_cd'] ] ) }}">施設情報</a>&nbsp;&gt;&nbsp;
変更<br>


<br>
{{-- メッセージ --}}
@include('ctl.common.message', $messages)

{{--登録 --}}
{!! Form::open(['route' => ['ctl.htlhotelInfo.update'], 'method' => 'post']) !!}
	{{--入力フォーム を取り込む --}}
	@include('ctl.htlhotelInfo._input_form',["hotelInfo" => $views->hotelInfo])

	<br/>
	<input type="submit" value="変更">
	<input type="hidden" name="HotelInfo[hotel_cd]" value= "{{strip_tags($views->hotelInfo['hotel_cd'])}}">
	<input type="hidden" name="target_cd" value="{{strip_tags($views->hotelInfo['hotel_cd'])}}">
{!! Form::close() !!}
@endsection