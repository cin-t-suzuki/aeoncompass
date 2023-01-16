@extends('ctl.common._htl_base')
@section('title', 'リンクページ')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelLinkController')

@section('content')

{{-- サブメニュー --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel.show' , ['target_cd'=>$target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htl_hotel_link.list' , ['target_cd'=>$target_cd]) }}">リンクページ</a>&nbsp;&gt;&nbsp;
変更
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')

{!! Form::open(['route' => ['ctl.htl_hotel_link.update'], 'method' => 'post']) !!}
<table border="1" cellspacing="0" cellpadding="4">
    @include('ctl.htlhotellink._form')
</table>
<br>
<input type="submit" value="変更">
<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
<input type="hidden" name="HotelLink[branch_no]" value="{{strip_tags($a_hotel_link['branch_no'])}}">
<input type="hidden" name="HotelLink[othercount]" value="{{strip_tags($a_hotel_link['othercount'])}}">
{!! Form::close() !!}

@endsection