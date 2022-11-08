@section('title', '詳細変更')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrbroadcastMessageController')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

<table border="0" cellspacing="0" cellpadding="4">
	<td valign="top">
		{{-- 施設情報詳細 --}}
		@section('hotel_info')
		@include('ctl.brhotel._hotel_info',
			["hotel" => $views->hotel,
			"mast_pref" => $views->mast_pref,
			"mast_city" => $views->mast_city,
			"mast_ward" => $views->mast_ward ])
		<br>

		<table border="0" cellspacing="0" cellpadding="4">
			<tr valign="top">施設関連<td>
				<table border="2" cellspacing="0" cellpadding="4">
					<tr>
						<td bgcolor="#EEFFEE" nowrap>
						施設情報
						</td>
					@if ($views->hotel_regist == true)
						{!! Form::open(['route' => ['ctl.brhotel.edit'], 'method' => 'post']) !!}
						<td nowrap><input type="submit" value=" 変更 "></td>
						<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}">
						{!! Form::close() !!}
					@else
						{!! Form::open(['route' => ['ctl.brhotel.new'], 'method' => 'post']) !!}
						<td nowrap><input type="submit" value=" 登録"></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						{!! Form::close() !!}
					@endif
					</tr>

					<tr>
						<td bgcolor="#EEFFEE" nowrap>
						施設管理情報
						</td>
					@if ($views->hotel_management_regist == true)
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/editmanagement/">
						<td nowrap><input type="submit" value=" 変更 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@elseif ($views->hotel_regist != true)
						<td nowrap colspan="2" width="90%">施設管理情報 <font color="red">※</font>施設を登録してください。</td>
					@else
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/management/">
						<td nowrap><input type="submit" value=" 登録 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@endif
					</tr>

					<tr>
						<td bgcolor="#EEFFEE" nowrap>
						施設状態情報
						</td>
					@if ($views->hotel_state_regist == true)
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/editstate/">
						<td nowrap><input type="submit" value=" 変更 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@elseif ($views->hotel_regist != true)
						<td nowrap colspan="2" width="90%">施設状態情報 <font color="red">※</font>施設を登録してください。</td>
					@else
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/state/">
						<td nowrap><input type="submit" value=" 登録 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@endif
					</tr>

					<tr>
						<td bgcolor="#EEFFEE" nowrap>
						施設測地情報
						</td>
					@if ($views->hotel_survey_regist == true)
						{{ Form::open(['route' => 'ctl.br_hotel.edit_survey', 'method' => 'get']) }}
						{{-- <form method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/editsurvey/"> --}}
						<td nowrap><input type="submit" value=" 変更 "></td>
						{{-- <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}"> --}}
			  			{{ Form::hidden('target_cd', strip_tags($views->target_cd)) }}

						</form>
						{{ Form::close() }}
					@elseif ($views->hotel_regist != true)
						<td nowrap colspan="2" width="90%">施設測地情報 <font color="red">※</font>施設を登録してください。</td>
					@else
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/survey/">
						<td nowrap><input type="submit" value=" 登録 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@endif
					</tr>
					<tr>
						<td bgcolor="#EEFFEE" nowrap>
							施設・地域関連付け
						</td>
						<td>
							<form method="post" action="{$v->env.source_path}{$v->env.module}/brhotelarea/" style="display: inline;">
								<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
								<input type="submit" value=" 変更 " />
							</form>
						</td>
					</tr>
				</table>
			</td>

			<td>
				<table border="2" cellspacing="0" cellpadding="4">
					<tr>
						<td bgcolor="#EEEEFF" nowrap>
						施設情報詳細
						</td>
					@if ($views->hotel_regist == true)
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/htlhotel/show/">
						<td nowrap><input type="submit" value=" 詳細 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@else
						<td nowrap colspan="2" width="90%">施設情報詳細 <font color="red">※</font>施設を登録してください。</td>
					@endif
					</tr>
					<tr>
						<td bgcolor="#EEEEFF" nowrap>
						施設画像情報
						</td>
					@if ($views->hotel_state_regist == true)
						{{--新バージョンのみ（htlmedia不使用）--}}
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/htlsmedia/">
						<td nowrap><input type="submit" value=" 変更 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@elseif ($views->hotel_regist != true)
						<td nowrap colspan="2" width="90%">施設画像情報 <font color="red">※</font>施設を登録してください。</td>
					@else
						{{--新バージョンのみ（htlmedia不使用）--}}
						<form method="POST" action="{$v->env.source_path}{$v->env.module}/htlsmedia/">
						<td nowrap><input type="submit" value=" 登録 "></td>
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}">
						</form>
					@endif
					</tr>
				 <tr>
					 <td bgcolor="EEEEFF">予約受付状態の変更</td>
					 <form action="{$v->env.source_path}{$v->env.module}/htlacceptance/edit/" method="POST">
					 <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($views->target_cd)}" />
					 <td><input type="submit" value=" 変更 ">
					 </td>
					 </form>
				 </tr>
				</table>
			</td>
			<td>
				<table border="2" cellspacing="0" cellpadding="4">
					<tr>
						<td bgcolor="#EEFFEE">MSC利用状況</td>
						<td>
							@forelse ($views->hotel_msc_info as $msc_usage_situation) {{--name=loop_hotel_msc--}}
								@if ($loop->first)
									<table border="1" cellspacing="0" cellpadding="2">
										<tr style="background-color:#eee;">
											<td style="padding: 4px;">MSC名称</td>
											<td style="padding: 4px;">最終更新日時</td>
										</tr>
								@endif
								<tr>
									<td style="padding: 4px;">{{$msc_usage_situation['msc_nm']}}</td>
									<td style="padding: 4px;">{{\Carbon\Carbon::createFromTimeString($msc_usage_situation['login_dtm'])->format('Y年m月d日 h:i')}}</td>
								</tr>
								@if ($loop->last)
									</table>
								@endif
							@empty
								利用なし
							@endforelse
						</td>
					</tr>
				</table>
			</td>
			</tr>
		</table>

		<br>

		<table border="2" cellspacing="0" cellpadding="4">
			請求関連
			<tr>
				<td bgcolor="#EEFFEE">
				請求先
				</td>
				<form action="{$v->env.source_path}{$v->env.module}/brcustomerhotel/list/" method="post">
				<td>
					<input type="submit" name="RateBtn" value="設定">
				</td>
				<input type="hidden" name="target_cd" value="{strip_tags($views->target_cd)}">
				</form>
				<td>
					@if (!($service->is_empty($views->customer_hotel)))
						{{$views->customer_hotel['customer_id']}}&nbsp;({{$views->customer_hotel['customer_nm']}})
					@else
						(未登録)
					@endif
				</td>
			</tr>
			<tr>
				<td bgcolor="#EEFFEE">
				料率
				</td>
				{!! Form::open(['route' => ['ctl.brhotelRate.index'], 'method' => 'post']) !!}
				<td>
					<input type="submit" name="RateBtn" value="設定">
				</td>
				<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}">
				{!! Form::close() !!}
				<td>
					<table border="1" cellspacing="0" cellpadding="2">
						<tr style="background-color:#eee;">
							<td>開始</td>
							<td>終了</td>
							<td>ベストリザーブサイト料率</td>
							<td>その他サイト料率</td>
						</tr>

						@for ($i = 0; $i < count($views->hotelrates); $i++)
						<tr>
							<td>
								{{\Carbon\Carbon::createFromTimeString($views->hotelrates[$i]['accept_s_ymd'])->format('Y年m月d日～')}}
							</td>
							<td>
								@if ($i != 0)
									{{\Carbon\Carbon::createFromTimeString($views->hotelrates[$i-1]['accept_s_ymd'])->modify('-1 day')->format('Y年m月d日')}}
								@endif
							</td>
							<td align="right">
								{{strip_tags($views->hotelrates[$i]['system_rate'])}}%
							</td>
							<td align="right">{{strip_tags($views->hotelrates[$i]['system_rate_out'])}}%</td>
						</tr>
					@endfor
					</table>
					<div style="margin-top:1em;">
					　※イオンコンパス料率：提携先コード「0000000000」の予約に適用<br />
					　※その他サイト料率：提携先コード「0000000000」以外の予約に適用<br />
					</div>
				</td>
			</tr>
		</table>
		<br />

	</td>

	<td><br/></td>

	<td valign="top">
{{-- 未設定かどうかのチェック		@if (count($views->hotel_staff_note) == 0)--}}
	@if ( $service->is_empty($views->hotel_staff_note)) 
		{!! Form::open(['route' => ['ctl.brhotel.createnote'], 'method' => 'post']) !!}
	@else
		{!! Form::open(['route' => ['ctl.brhotel.updatenote'], 'method' => 'post']) !!}
	@endif
			施設管理特記事項
			<table border="2" cellspacing="0" cellpadding="4">
				<tr>
					<td  bgcolor="#EEFFEE" >特記事項</td>
					<td>
						<textarea NAME="Hotel_Staff_Note[staff_note]" cols=40 rows=20>{{strip_tags($views->hotel_staff_note['staff_note']??'')}}</textarea>
					</td>
				</tr>
			</table>

			@if ($service->is_empty($views->hotel_staff_note))
				<input type="submit" value=" 登録 ">
			@else
				<input type="submit" value=" 変更 ">
			@endif
			<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}">
		{!! Form::close() !!}
	</td>
</table>

<div style="float:right">
	<FORM ACTION="{$v->env.source_path}{$v->env.module}/htlhotel/staticupdate/" METHOD="POST" target="page_test">
	情報ページHTML
	<small>
	<INPUT TYPE="submit" VALUE="更新する">
	<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}">
	<input type="hidden" name="redirect_url" value="http://{$v->config->system->rsv_host_name}/hotel/{$v->helper->form->strip_tags($views->target_cd)}/">
	</small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')