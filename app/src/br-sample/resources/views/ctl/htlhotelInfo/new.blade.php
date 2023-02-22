@extends('ctl.common._htl_base')
@section('title', '施設情報')
@inject('service', 'App\Http\Controllers\ctl\HtlhotelInfoController')

@section('content')

{{-- サブメニュー --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$views->target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$views->hotelinfos['hotel_cd'] ] ) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
新規

<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message', $messages)

<br>
{{-- 登録 --}}
{!! Form::open(['route' => ['ctl.htlhotelInfo.create'], 'method' => 'post']) !!}
  {{-- 入力フォーム を取り込む --}}
	@include('ctl.htlhotelInfo._form',["hotelinfos" => $views->hotelinfos,"input_data" => $views->input_data])
  <br>
<input type="submit" value="新規登録">
<input type="hidden" name="HotelInfo[hotel_cd]" value="{{strip_tags($views->hotelinfos['hotel_cd'])}}">
<input type="hidden" name="target_cd" value="{{strip_tags($views->hotelinfos['hotel_cd'])}}">
{!! Form::close() !!}
<br>
@endsection