<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Expires" content="0">
<meta name="robots" content="none">
<script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.cookies.js') }}"></script>
<title>[ストリーム]予約受付管理 [{{$title}}]</title>
<link type="text/css" rel="stylesheet" href="/styles/base.css?9465-2">
{{-- 印刷用スタイルシート --}}
@if($print_flg)
<link type="text/css" rel="stylesheet" href="/styles/print.css" media="print">
<link type="text/css" rel="stylesheet" href="/styles/screen.css" media="screen">
@endif
{{-- Googleアナリティクス --}}
{{-- include file=$v->env.module_root|cat:'/views/_common/_google_analytics.tpl' --}}
</head>
@if($screen_type == "test")
	<body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #297;"><div style="margin-left:-12px;padding:0.25em 0;background-color:#297;color:#fff;font-weifht:bold;width:6em;text-align:center;">検証環境</div>
@elseif($screen_type == "development")
	<body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #36A;"><div style="margin-left:-12px;padding:0.25em 0;background-color:#36A;color:#fff;font-weifht:bold;width:6em;text-align:center;">開発環境</div>
@elseif($screen_type != "product")
	<body topmargin="0" marginheight="0" style="margin-top:0;margin-left:0;padding-left:8px;border-left:4px solid #A63;"><div style="margin-left:-12px;padding:0.25em 0;background-color:#A63;color:#fff;font-weifht:bold;width:6em;text-align:center;">環境不明</div>
@else
	<body topmargin="0" marginheight="0">
@endif
@if($no_print)<div class="noprint">@endif
{{-- staffのみ情報を表示 --}}
@if($is_staff)
	@include('ctl.common._htl_staff_header')
@elseif($is_nta)
	@include('ctl.common._nta_staff_header')
@endif
<table border="0" width="100%" cellspacing="0" cellpadding="5" bgcolor="#EEEEFF" >
		<tr>
			<td nowrap>{{ $header_number }}</td>
			<td>
				<a href="{$v->env.source_path}{$v->env.module}/redirect/rsvhotel/?target_cd={$v->user->hotel.hotel_cd}" target="_blank">
					{{ $hotel_nm }}
					@if(!is_null($hotel_old_nm))
						(旧{{ $hotel_old_nm }})
					@endif
				</a> 様
                    　　　(施設コード：{{ $hotel_cd }})
			</td>
			<td nowrap align="right">
				<table cellspacing="0" cellpadding="2" border="0">
					<tr>
						<!-- {* aやん! *} -->
						<td rowspan="2">
							<!-- {* <a href="http://www.nihon-weekly.com/hotelask/" target="_blank"><img src="/images/intro/ayan/banner.gif" width="300" height="56" border="0" alt="aやん! ウィークリーホテルズ" /></a> *} -->
							@if(date('Y-m-d H:i:s') >= '2020-06-01 00:00:00' && date('Y-m-d H:i:s') <= '2020-07-15 23:59:59')
								<a href="https://www.kanxashi.co.jp/cp/202006kanxashi/?key=brv" target="_blank"><img src="/images/intro/kanzashi/kanxashi_zenryoku.png" alt="かんざしクラウド" height="56" width="300" /></a>
							@endif
						</td>
<!-- {* 一時削除 ２月早々にリニューアル予定 *} -->
<!-- {*            <td rowspan="2"><a href="http://{$v->config->system->rsv_host_name}/intro/htlorimo/" target="_blank"><img src="/images/intro/htlorimo/banner.gif" width="328" height="56" border="0" alt="ＯＲＩＭＯケータイプレミアムモニターキャンペーン" /></a></td> *} -->
							<td rowspan="2">
							@if($entry_status == 0 && !($acceptance_status_flg === false))
								@include('ctl.common._change_acceptance')
							@elseif($ydp2_status && !($acceptance_status_flg === false))
								@include('ctl.common._change_acceptance')
							@endif
						</td>
						<td><br></td>
					</tr>
					<tr>
					<td>
					<form action="{$v->env.source_path}{$v->env.module}/htltop/" method="POST">
						<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
						<input type="submit" value="メニュー">
					</form>
					</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br />
	<!-- {* サービスセンター *} -->
	@if($service_info_flg !== false)
		@include('ctl.common._htl_service_info', ['ad' => false])
	@endif
	@if($no_print == true)</div>@endif
	@if($no_print_title == true)<div class="noprint">@endif
	<!-- {* サービスセンター *} -->
	@if(!(is_null($menu_title)) || !(is_null($title)))
		<table border="3" cellspacing="0" cellpadding="2">
			<tr>
				<td  bgcolor="#EEEEFF"  align="center">
					<big>
						@if(is_null($menu_title))
							{{ $title }}
						@else
							{{ $menu_title }}
						@endif
					</big>
				</td>
			</tr>
		</table>
	@endif
	<br />
	@if($no_print_title == true)</div>@endif