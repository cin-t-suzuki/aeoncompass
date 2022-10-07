@section('title', '料率マスタ')
@include('ctl.common.base')

{{-- サブメニュー --}}
<a href="{$v->env.source_path}{$v->env.module}/brhotel/show/target_cd/{$v->assign->hotelrate.hotel_cd}">施設情報詳細</a>&nbsp;&gt;&nbsp;<a href="{{ route( 'ctl.brhotelRate.index' , ['target_cd'=>$views->target_cd ] ) }}">料率一覧</a>&nbsp;&gt;&nbsp;更新
<br>
{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)
<br>

{{-- 施設情報詳細 --}}
@section('hotel_info')
@include('ctl.brhotel._hotel_info',
		["hotel" => $views->hotel,
		"mast_pref" => $views->mast_pref,
		"mast_city" => $views->mast_city,
		"mast_ward" => $views->mast_ward ])
<br>

{{-- 新規登録フォーム --}}
{!! Form::open(['route' => ['ctl.brhotelRate.update'], 'method' => 'post']) !!}

	{{-- 入力フォーム を取り込む --}}
	@include('ctl.brhotelRate._form',
	["hotelrate" => $views->hotelrate ])
  </br>
  <input type="submit" value="変更">
  <input type="hidden" name="hotel_cd" value="{{strip_tags($views->hotelrate['hotel_cd'])}}">
  <input type="hidden" name="target_cd" value="{{strip_tags($views->hotelrate['hotel_cd'])}}">
  <input type="hidden" name="branch_no" value="{{strip_tags($views->hotelrate['branch_no'])}}">
{!! Form::close() !!}


@section('title', 'footer')
@include('ctl.common.footer')