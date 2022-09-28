@section('title', '施設情報更新')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brhotel.update'], 'method' => 'post']) !!}

{{--TODO 別ファイル読込 include file=$v->env.module_root|cat:'/views/brhotel/_input_hotel_form.tpl'--}}
	@section('detail')
	@include('ctl.brhotel._input_hotel_form',
			["hotel" => $views->hotel
			,"mast_prefs" => $views->mast_prefs
			,"mast_cities" => $views->mast_cities
			,"mast_wards" => $views->mast_wards
			,"target_cd" => $views->target_cd
			])
{{-- TODO 施設情報更新では使用していない			,"target_stock_type" => $views->target_stock_type --}}

<INPUT TYPE="submit" VALUE="施設情報更新">
※は必須です。

{!! Form::close() !!}

{{--TODO 別ファイル読込 include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'--}}
	@section('brhotelShow')
	@include('ctl.brhotel._hotel_top_form',
				["target_cd" => $views->target_cd])

<br>

@section('title', 'footer')
@include('ctl.common.footer')