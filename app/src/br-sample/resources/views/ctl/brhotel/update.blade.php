@section('title', '施設更新情報')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

<!--TODO form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/show/"-->
{!! Form::open(['route' => ['ctl.brhotel.show'], 'method' => 'post']) !!}

	{{--include file=$v->env.module_root|cat:'/views/brhotel/_info_hotel_form.tpl'--}}
	@section('detail')
	@include('ctl.brhotel._info_hotel_form', [
		"hotel" => $views->hotel
		,"a_mast_pref" => $views->a_mast_pref
		,"a_mast_city" => $views->a_mast_city
		,"a_mast_ward" => $views->a_mast_ward??null
	])

	<input type="submit" value="詳細変更へ">

{!! Form::close() !!}

<hr size="1">
<!--TODO form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/"-->
{!! Form::open(['route' => ['ctl.brhotel.index'], 'method' => 'post']) !!}
	<small>
		<input type="submit" value="施設情報メインへ">
	</small>
{!! Form::close() !!}

<br>

@section('title', 'footer')
@include('ctl.common.footer')