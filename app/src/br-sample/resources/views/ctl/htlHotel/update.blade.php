{{-- MEMO: 移植元 public\app\ctl\views\htlhotel\update.tpl --}}

@extends('ctl.common._htl_base')
@section('title', '施設情報登録内容の変更')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelController')

@section('content')

{{-- TODO コメントアウト外す --}}
{{-- <a href="{{ route('ctl.htl_top.index', ['target_cd' => $target_cd]) }}"> --}}
	メインメニュー
	{{-- </a> --}}
	&nbsp;&gt;&nbsp;
	{{-- <a href="{{ route('ctl.htl_top.show', ['target_cd' => $target_cd]) }}"> --}}
	  施設情報詳細
	{{-- </a> --}}
	&nbsp;&gt;&nbsp;施設情報登録内容の変更
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

{{ Form::open(['route' => 'ctl.htl_hotel.show', 'method' => 'post']) }}
@include('ctl.htlHotel._info_form')
	<INPUT TYPE="hidden" NAME="target_cd" VALUE="{{strip_tags($target_cd)}}">
	<INPUT type="submit" value="施設情報詳細へ">
{{ Form::close() }}

<br>
@endsection