
@section('title', 'メインメニュー')
@include('ctl.common.base')

@inject('service', 'App\Http\Controllers\ctl\BrtopController')

<br>


{{-- メインメニュー --}}
<table border="0" cellspacing="12" cellpadding="8" >
	<tr>
		<td bgcolor="#FFF9FF" valign="top">
			<table border="1" cellspacing="0" cellpadding="4">
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brreserve/">
					<td nowrap width="100%">予約の検索</td>
					<td nowrap><input type="submit" value=" 検索 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brreserveck/">
					<td nowrap width="100%">送客実績・料金変更</td>
					<td nowrap><input type="submit" value=" 確認 "></td>
					</form>
				</tr>
				<tr>
					<form method="post" action="{$v->env.source_path}{$v->env.module}/brdemandresult/list/">
						<td width="100%" nowrap="nowrap">送客請求実績</td>
						<td nowrap="nowrap">
							<input value=" 確認 " type="submit">
						</td>
					</form>
				</tr>
			</table>
			<br>
			<table border="1" cellspacing="0" cellpadding="4">
				<tr>
					{!! Form::open(['route' => ['ctl.brhotel.index'], 'method' => 'get']) !!}
					<td nowrap width="100%">施設の登録・変更</td>
					<td nowrap><input type="submit" value=" 施設 "></td>
					{!! Form::close() !!}
				</tr>
			</table>
			<br>
			<table border="1" cellspacing="0" cellpadding="4">
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brpartner/">
					<td nowrap width="100%">パートナー設定</td>
					<td nowrap><input type="submit" value=" 設定 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/">
					<td nowrap width="100%">アフィリエイト設定</td>
					<td nowrap><input type="submit" value=" 設定 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brpartnercustomer/">
					<td nowrap width="100%">精算先設定</td>
					<td nowrap><input type="submit" value=" 設定 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/payment/">
					<td nowrap width="100%">支払</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
			</table>
			<br>
			<table border="1" cellspacing="0" cellpadding="4">
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/confirmation/">
					<td nowrap width="100%">確認</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/registration/">
					<td nowrap width="100%">登録</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/offer/">
					<td nowrap width="100%">提供</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/stock/">
					<td nowrap width="100%">仕入</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/claim/">
					<td nowrap width="100%">請求書・支払書</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brgroupbuying/deals/">
					<td nowrap width="100%">ベストク（クーポン）</td>
					<td nowrap><input type="submit" value=" 表示 "></td>
					</form>
				</tr>
			</table>
		</td>

		<td bgcolor="#FFFFEF" valign="top">
			<table border="1" cellspacing="0" cellpadding="4">
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brtop/inspect/">
					<td nowrap width="100%">会員情報の確認・変更</td>
					<td nowrap><input type="submit" value=" 確認 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brvoice/">
					<td nowrap width="100%">クチコミ投稿表示・返信</td>
					<td nowrap><input type="submit" value=" 確認 "></td>
					</form>
				</tr>
				<tr>
					<FORM ACTION="{$v->env.source_path}{$v->env.module}/brpoint/" METHOD="POST">
					<td nowrap width="100%">ＢＲポイント・ギフト・サービスの管理</td>
					<td><INPUT TYPE="submit" VALUE=" 確認 "></td>
					</FORM>
				</tr>
				<tr>
					<td nowrap width="100%"> <font color="#bfbfbf">メールマガジン 差し込み可</font></td>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brmailmagazine/">
					<td nowrap>
								<input type="hidden" name="send_system" value="reserve">
								<input type="submit" value=" 設定 ">
					</td>
					</form>
				</tr>
				<tr>
					<td nowrap width="100%">メールマガジン 差し込み<s>不</s>可</td>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brmailmagazine2/">
					<td nowrap>
								<input type="submit" value=" 設定 ">
					</td>
					</form>
				</tr>
				<tr>
					<td nowrap width="100%">メール一括送信プログラムについて</td>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brmailmagazine2/">
						<td nowrap>

						<a href="javascript:void(0);" onclick="openWin()">説明</a>

						<script language="JavaScript" type="text/javascript">
							function openWin(){
								newWin = window.open('http://logbook.bestrsv.com/index_tsv_sendmail.html','tsv_sendmail','width=1200,height=900,scrollbars=no,status=no,toolbar=no,location=no,menubar=no,resizable=yes');
								newWin.focus();
							}
						</script>

						</td>
					</form>
				</tr>

				<tr>
					<form method="POST" action="kbs_brv_hs_weekday_plan.main"></form>
				</tr>
				<tr>
					<form method="POST" action="kbs_brv_hs_special_price.main"></form>
				</tr>
				<tr>
					<form method="POST" action="kbs_brv_hs_email_tool.main"></form>
				</tr>
			</table>
			<br>
			<table border="1" cellspacing="0" cellpadding="4">
				<tr>
					<form method="POST" action="{$v->env.source_path}{$v->env.module}/brchangepass/">
					<td nowrap width="100%">管理画面用パスワード変更</td>
					<td nowrap><input type="submit" value=" 確認 "></td>
					</form>
				</tr>
				<tr>
					<form method="POST" action="kbs_brv_tool_member.touroku">
					<td nowrap width="100%">管理画面操作者登録</td>
					<td nowrap><input type="submit" value=" 登録 "></td>
					</form>
				</tr>
			</table>
		</td>

{{-- スケージュールの表示 --}}
		<td valign="top">
			<strong>-- スケジュール --</strong><br><br>
			{{-- 前月表示 --}}
			@section('pre_month')
			@include('ctl.common._date',["timestamp" => \Carbon\Carbon::now()->subMonth(1)->format('Y-m-d'), "format" =>"ym" ] )
			<table border="0" cellpadding="4" cellspacing="0">
				@foreach ( $views->Schedules['pre_month'] as $schedule )
					<tr>
						<td> {{ $schedule->schedule_nm }} </td>
						@if ( $service->is_empty($schedule->date_ymd) )
							<td><a href="{$v->env.source_path}{$v->env.module}/brmoneyschedule/new/?Money_Schedule[money_schedule_id]={$schedule.money_schedule_id}&Money_Schedule[ym]={$ym}">登録する</a></td>
						@else
							@if ( $schedule->date_ymd == \Carbon\Carbon::now()->format('Y-m-d') )
								<td bgcolor="#ffccff">
							@else
								<td>
							@endif

									@section('this_month_days')
									@include('ctl.common._date',["timestamp" => $schedule->date_ymd, "format" =>"ymd(w)" ] )
								</td>
						@endif
					</tr>
				@endforeach
			</table><br>
			{{--  当月表示		--}}
			@section('this_month')
			@include('ctl.common._date',["timestamp" => \Carbon\Carbon::now()->format('Y-m-d'), "format" =>"ym" ] )
			
			<table border="0" cellpadding="4" cellspacing="0">
				@foreach ( $views->Schedules['this_month'] as $schedule )
					<tr>
						<td> {{$schedule->schedule_nm}} </td>
						@if ( $service->is_empty( $schedule->date_ymd ) )
							<td><a href="{$v->env.source_path}{$v->env.module}/brmoneyschedule/new/?Money_Schedule[money_schedule_id]={$schedule.money_schedule_id}&Money_Schedule[ym]={$ym}">登録する</a></td>
						@else
							@if ( $schedule->date_ymd == \Carbon\Carbon::now()->format('Y-m-d') )
								<td bgcolor="#ffccff">
							@else
								<td>
							@endif

									@section('this_month_days')
									@include('ctl.common._date',["timestamp" => $schedule->date_ymd, "format" =>"ymd(w)" ] )
								</td>
						@endif
					</tr>
				@endforeach
			</table><br>
		{{-- 来月表示  --}}
		@section('next_month')
		@include('ctl.common._date',["timestamp" => \Carbon\Carbon::now()->addMonth(1)->format('Y-m-d'), "format" =>"ym" ] )

		<table border="0" cellpadding="4" cellspacing="0">
			@foreach ($views->Schedules['next_month'] as $schedule )
				<tr>
					<td> {{ $schedule->schedule_nm }} </td>
					@if ( $service->is_empty($schedule->date_ymd) )
						<td><a href="{$v->env.source_path}{$v->env.module}/brmoneyschedule/new/?Money_Schedule[money_schedule_id]={$schedule.money_schedule_id}&Money_Schedule[ym]={$ym}">登録する</a></td>
					@else
						@if ( $schedule->date_ymd == \Carbon\Carbon::now()->format('Y-m-d') )
							<td bgcolor="#ffccff">
						@else
							<td>
						@endif
								@section('next_month_days')
								@include('ctl.common._date',["timestamp" => $schedule->date_ymd, "format" =>"ymd(w)" ] )
							</td>
					@endif
				</tr>
			@endforeach
		</table>

				</td>
			</tr>
</table>


{{-- TODO loop_license
		<div style="text-align:left">
			{foreach from=$v->assign->licenses item=license name=loop_license}
				{if $smarty.foreach.loop_license.first}<ul>{/if}
				<li style="padding:0; margin:0;">{$license}</li>
				{if $smarty.foreach.loop_license.last}</ul>{/if}
			{/foreach}
		</div>
--}}

@section('title', 'footer')
@include('ctl.common.footer')
