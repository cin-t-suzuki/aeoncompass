@section('title', '施設情報')
@include('ctl.common.base')

{{-- TODO サブメニュー --}}
<a href="{$v->env.source_path}{$v->env.module}/htltop/index/target_cd/{$v->assign->target_cd}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{$v->env.source_path}{$v->env.module}/htlhotel/show/target_cd/{$v->assign->hotelinfos.hotel_cd}">施設情報詳細</a>&nbsp;&gt;&nbsp;
<a href="{$v->env.source_path}{$v->env.module}/htlhotelinfo/show/target_cd/{$v->assign->hotelinfos.hotel_cd}">施設情報</a>&nbsp;&gt;&nbsp;
変更<br>

<a href="{{--TODO route( 'ctl.htlTop.index',['target_cd'=>$views->target_cd]) --}}">メインメニュー（未）</a>&nbsp;&gt;&nbsp;
<a href="{{--TODO  route( 'ctl.htlhotel.show',['target_cd'=>$views->hotelinfos['hotel_cd']]) --}}">施設情報詳細 （未）</a>&nbsp;&gt;&nbsp;
<a href="{{ route( 'ctl.htlhotelInfo.show',['target_cd'=>$views->hotelInfo['hotel_cd'] ] ) }}">施設情報</a>&nbsp;&gt;&nbsp;変更<br>

<br>
{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{{--登録 --}}
{!! Form::open(['route' => ['ctl.htlhotelInfo.update'], 'method' => 'post']) !!}
	{{--入力フォーム を取り込む --}}
	@section('detail')
	@include('ctl.htlhotelInfo._input_form',["hotelInfo" => $views->hotelInfo])

	<br/>
	<input type="submit" value="変更">
	<input type="hidden" name="HotelInfo[hotel_cd]" value= "{{strip_tags($views->hotelInfo['hotel_cd'])}}">
	<input type="hidden" name="target_cd" value="{{strip_tags($views->hotelInfo['hotel_cd'])}}">
{!! Form::close() !!}

{{-- TODO 
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}
--}}