@extends('ctl.common.base')
@section('title', '施設情報')

@section('page_blade')

{{-- TODO  サブメニュー 
<a href="{$v->env.source_path}{$v->env.module}/htltop/index/target_cd/{$v->assign->target_cd}">メインメニュー</a>&nbsp;&gt;&nbsp;<a href="{$v->env.source_path}{$v->env.module}/htlhotel/show/target_cd/{$views->hotelInfo.hotel_cd}">施設情報詳細</a>&nbsp;&gt;&nbsp;施設情報
--}}
<a href="{{--TODO route( 'ctl.htlTop.index',['target_cd'=>$views->target_cd]) --}}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{--TODO route( 'ctl.htlhotel.show', ['target_cd'=>$views->hotelInfo['hotel_cd'] ] ) --}}">施設情報詳細（未）</a>&nbsp;&gt;&nbsp;施設情報


<br>
{{-- メッセージ --}}
@include('ctl.common.message', $messages)

<br>
{!! Form::open(['route' => ['ctl.htlhotelInfo.edit'], 'method' => 'post']) !!}
<table border="1" cellspacing="0" cellpadding="4">
	<tr>
		<td bgcolor="#EEEEFF">
			駐車場詳細
		</td>
		<td>
			@if(isset($views->hotelInfo["parking_info"]))
				{!!nl2br(e(strip_tags($views->hotelInfo["parking_info"])))!!}
			@else 
				&nbsp;
			@endif
		</td>
	</tr>
	<tr>
		<td bgcolor="#EEEEFF">
			カード利用条件
		</td>
		<td>
			@if(isset($views->hotelInfo["card_info"]))
				{!!nl2br(e(strip_tags($views->hotelInfo["card_info"])))!!}
			@else
				&nbsp;
			@endif
		</td>
	</tr>
	<tr>
		<td bgcolor="#EEEEFF">
			特色
		</td>
		<td>
			@if(isset($views->hotelInfo["info"]))
				{!!nl2br(e(strip_tags($views->hotelInfo["info"])))!!}
			@else
				&nbsp;
			@endif
		</td>
	</tr>
</table>
<br/>
<input type="submit" value="編集">
<input type="hidden" name="HotelInfo[hotel_cd]" value="{{strip_tags($views->hotelInfo["hotel_cd"])}}" >
<input type="hidden" name="target_cd" value="{{strip_tags($views->hotelInfo["hotel_cd"])}}">
{!! Form::close() !!}

{{-- include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl' --}}
@endsection
