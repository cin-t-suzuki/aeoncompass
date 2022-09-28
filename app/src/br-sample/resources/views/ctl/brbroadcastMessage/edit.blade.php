{{-- 提携先管理ヘッダー  TODO include file='../_common/_br_header2.tpl' --}}
@section('title', '施設管理TOPお知らせ変更画面')
@include('ctl.common.base')

{{-- css  --}}
@extends('ctl.brbroadcastMessage._css')
{{-- js  --}}
@extends('ctl.brbroadcastMessage._js')

@section('page_blade')
{{-- エラーメッセージ --}}
@include('ctl.common.message', $messages)


	 <hr class="contents-margin" />

	<div style="width:960px;">
		<div style="width:200px; float: right; margin-bottom: 15px;">

				 {!! Form::open(['route' => ['ctl.brbroadcastMessage.index'], 'method' => 'get']) !!}
						<small>
							<input type="submit" style="width:110px; height: 30px; border-radius: 4px; background-color: #d4dcd6" value="<<一覧へ戻る">
						</small>
				 {!! Form::close() !!}

		 </div>
		 <div style="clear:both;"></div>
	{{-- 入力フォーム --}}
	{!! Form::open(['route' => ['ctl.brbroadcastMessage.update'], 'method' => 'post']) !!}

		<h2 style="color:#444">施設管理TOPお知らせ変更画面</h2>
		{{-- 精算先内容 --}}
		<input type="hidden" name="brbroadcast_id" value="{{  $views->form_params['brbroadcast_id'] }}" />
		{{-- TODO include file='./_edit_broadcast_message.tpl'  →input とほぼ一緒--}}
		@section('detail')
		@include('ctl.brbroadcastMessage._input_broadcast_message',
				["form_params" => $views->form_params,
				"accept_header_ymd_selecter" => $views->accept_header_ymd_selecter,
				"accept_ymd_selecter" => $views->accept_ymd_selecter,
				"accept_header_time_selecter" => $views->accept_header_time_selecter,
				"accept_time_selecter" => $views->accept_time_selecter ])

		<hr class="contents-margin" />
		<input type="submit" style="width:80px; height: 35px; border-radius: 4px; background-color: #c3d825" value="変更">

	{!! Form::close() !!}

	</div>
	<hr class="contents-margin" />

	{{-- TODO  提携先管理フッター --}}
	{{--include file='../_common/_br_footer.tpl'--}}
	{{-- /提携先管理フッター --}}
