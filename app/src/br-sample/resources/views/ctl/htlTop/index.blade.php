@extends('ctl.common._htl_base')
@section('title', 'メインメニュー')
@inject('service', 'App\Http\Controllers\ctl\HtlTopController')

@section('content')

{{-- {literal} --}}
<style type="text/css">
/* <!--　-->　の書き換えはコメントアウトで合っている？元ソースでも効いていない？ */
	/* table.info_system_rate_point_rate a:hover{
		opacity: 0.6;
	}
		table.info_account_transfer a:hover{
		opacity: 0.6;
	} */
</style>
{{-- /* {/literal} */ --}}

{{-- ================================================================================================== --}}
{{-- 受託販売の施設にのみ表示するメニュー                                                             --}}
{{--==================================================================================================--}}
@if ($views->stock_type == 0)
	{{--------------------------------------------------------------------------------}}
	{{-- 年末年始営業に関する告知                                                   --}}
	{{--------------------------------------------------------------------------------}}
	<div style="width: 800px; margin: auto; text-align: center;">
		@include ('ctl.common._business_new_years_holiday')
	</div>
	<div style="width: 800px; margin: auto; text-align: center;">

		{{-- ??[]追記でいいのか？↓ --}}
				@foreach ($views->broadcast_messages->values ?? [] as $key => $value) 
				@if (!empty($value['header_message']) && ($value['accept_header_s_dtm'] < now() && now() < $value['accept_header_e_dtm']))
					<div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
						<table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
							<tr>
							<td style="text-align: center;">
							{{$value['header_message']}}
							</td>
							</tr>
						</table>
					</div>
					<br />
				@endif
				@endforeach


		<div style="text-align: left; margin-left: 50px;">
			{{--------------------------------------------------------------------------------}}
			{{-- 2014/4 消費税に関する告知      (テンプレートで残す)                          --}}
			{{--------------------------------------------------------------------------------}}
			@include ('ctl.common._consumption_tax_201404',["type" => 'pdf'])

			{{--------------------------------------------------------------------------------}}
			{{-- マイグレーションに関する告知案内                                           --}}
			{{-- ※旧管理画面利用施設のみ表示       (テンプレートで残す)                     --}}
			{{--------------------------------------------------------------------------------}}
			@if (in_array(1, ($views->is_disp_room_plan_list ?? [])))
				{{-- 旧画面利用施設 --}}
				@include ('ctl.htlTop._renew_info')
			@endif
		</div>

	</div>
@endif

<div align="center">

{{-- bfsの紹介を表示する。 --}}
    <div style="width: 800px; margin: auto; text-align: center;">
      <div style="border:1px solid #38a1db; width: 700px; padding:5px;  margin: 0 auto;">
      <table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
        <tr><td style="text-align: center;">
                <div style="background-color:a0d8ef; padding: 5px">
                    <b>可視化で3密対策！<br>
                    簡単・安心・低コストのホテル・旅館向け３密対策システム<br>
                    <div style="background-color:a0d8ef; padding: 5px; font-size:200%;">Best Facility Signal<br>（bfs）</div>
                    <div style="padding: 0px 0px 10px 0px;">をリリース致しました。</div>
                    <div style="background-color:DD2222; color:FFFFFF; padding:8px 50px">ただ今無料で31日間お試し頂けます。</div>
                    <div style="padding: 10px 0px 2px 0px;">詳細は<a href="https://www.bestrsv.com/hs/manual/pdf/bfs_overview.pdf" target="_blank" style="color:#0B0080; text-decoration:underline; text-decoration-color:#0B0080;">コチラ</a>からご確認下さい。</div></b>
                </div>
        </td></tr>
      </table>
      </div>
    </div>
    <br>
  
{{-- お知らせ表示 --}}
@foreach ($views->twitters['values'] as $key => $value)
	@if ($loop->first)
		<br>
		<div align="center">
		<table border="0" cellpadding="0" cellspacing="1" bgcolor="#0000ff"><tr><td>
		<table border="0" cellpadding="2" cellspacing="0">
	@endif

	<tr>
		{{-- <td bgcolor="#ffffff">【 {{strip_tags($value->alert_system_nm)}} 】{{strip_tags($value->title, '<font>', false)}}</td>
		@if (!($service->is_empty($value->description)))<td>{{strip_tags($value->description, '<br><div><font><img><li><small><span><strong><ul>', false)}}</td>@endif --}}
		{{-- false削除していいか＆タグ出力のため{!! !!}で記述変更でOK？ --}}
		<td bgcolor="#ffffff">【 {{strip_tags($value->alert_system_nm)}} 】{!! strip_tags($value->title, '<font>') !!}</td>
		@if (!($service->is_empty($value->description)))<td>{!! strip_tags($value->description, '<br><div><font><img><li><small><span><strong><ul>') !!}</td>@endif
	</tr>

	@if ($loop->last == true)
		</table>
		</td></tr></table>
		</div>
	@endif
@endforeach


@if ($views->stock_type == 0 || $views->stock_type == 3)
	{{-- 部屋の管理 --}}
	@include ('ctl.htlTop._stock')
	{{-- 部屋の管理 --}}
	<br>
@endif

{{-- 予約の管理と会員へのサポート --}}
@include ('ctl.htlTop._support')
{{-- 予約の管理と会員へのサポート --}}
<br>

{{-- 基本情報の管理 --}}
@include ('ctl.htlTop._basis_info')
{{-- 基本情報の管理 --}}
<br>

@if ($views->stock_type == 0)
<table border="1" cellspacing="0" cellpadding="3" width="600">
	<tr>
		<td  bgcolor="#EEEEFF"  colspan="2" align="center">
<strong>その他</strong></td></tr>
	<tr>
		<td width="40%">ベストリザーブ宿ぷらざ利用約款</td>
<form action="//{$v->config->system->rsv_host_name}/hs/manual/pdf/BR_YDP_clause.pdf" method="GET" target="_blank">
		<td><input type="submit" value="表示"></td>

		</form>
	</tr>
	<tr>
		<td width="40%">管理画面操作マニュアル</td>
                {{--nullも追記 {if $v->user->hotel_system_version.version == 1} --}}
				@if (($views->hotel_system_version['version'] ??null) == 1)
<form action="//{$v->config->system->rsv_host_name}/hs/manual/" method="GET" target="_blank">
                @else
<form action="//{$v->config->system->rsv_host_name}/hs/manual/pdf/instruction.pdf?{0|rand:999}" method="GET" target="_blank">
				@endif
		<td><input type="submit" value="移動"></td>

	</form>
	</tr>
</table>
<br>
@endif

{{-- サービスのお問い合わせ先 --}}
@include ('ctl.htlTop._info')
{{-- サービスのお問い合わせ先 --}}

				<br />
				<br />
		<div style="border:1px solid #EF0000; width: 700px; padding:5px;  margin: 0 auto;">
			<table class="info_account_transfer" border="0" cellspacing="0" cellpadding="4" style="width: 700px;">
					<tr>
					<td style="text-align: center;">
						<p style="margin: 0; line-height: 1.5em; font-size:17px;">
							<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/account_transfer.pdf" style="color:#EF0000; text-decoration: underline;" target="_blank">
							ご精算における口座振替に関するご案内
							</a>
						</p>
						<p style="margin: 0; font-size:15px; line-height: 1.5em;">
							<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/account_transfer.pdf" style="color:#EF0000;" target="_blank">
								クリックしていただくと案内の文書に遷移いたします。
							</a>
						</p>
					</td>
					</tr>
			</table>
		</div>
		<div style="width: 700px; padding:3px 5px 5px 5px; margin: 0 auto;">
			<p style="margin: 0; font-size: 15px;">※「預金口座振替依頼書」の表示は<a href="http://{$v->config->system->rsv_host_name}/hs/manual/pdf/RequestForm.pdf
" style="font-weight: bold; text-decoration: underline" target="_blank">こちらのリンク</a>をクリック下さい。</p>
		</div>

@if ($views->stock_type == 0)
	<br><br>

	{{-- お知らせ表示 --}}
	@include ('ctl.htlTop._broadcast_messages')
	{{-- お知らせ表示 --}}
@endif

</div>

{{-- {literal} --}}
<script language="javascript"  type="text/javascript">
//<!--
	if (window.focus){
		window.focus();
	}
//-->
</script>
{{-- {/literal} --}}

<br>

{{-- 担当者情報確認ダイアログ --}}
@if (
	$views->a_confirm_hotel_person['confirm_dtm_check']
    || $views->a_confirm_hotel_person['hotel_person_email_check']
	|| $views->a_confirm_hotel_person['customer_email_check']
	|| $views->confirm_hotel_person_force
	)
<link rel="stylesheet" href="/scripts/Remodal-master/remodal.css">
<link rel="stylesheet" href="/scripts/Remodal-master/remodal-default-theme.css">
<script src="/scripts/jquery1.11.js"></script>
<script src="/scripts/Remodal-master/remodal.min.js"></script>

<script>
// {literal}
$(function(){
	$('[data-remodal-id=modal_confirm_info]').remodal().open();
});
// {/literal}
</script>

@if ($views->confirm_hotel_person_force)
	<div class="remodal" data-remodal-id="modal_confirm_info" data-remodal-options="hashTracking: false, closeOnOutsideClick: false,closeOnEscape:false">
@else
	<div class="remodal" data-remodal-id="modal_confirm_info" data-remodal-options="hashTracking: false">
@endif
<div class="pop_confirm-header">
	<!-- button data-remodal-action="close" class="remodal-close"></button-->

	<p>ご担当者に変更はございませんか？</p>
@if ($views->confirm_hotel_person_force)
	<p style="font-size:14px;color: #900;margin-top: -20px;">ご登録のない施設にご案内させて頂いております。</p>
@else
	<p style="font-size:14px;color: #900;margin-top: -20px;">定期的にご確認させていただいております。</p>
@endif
	<hr size=1>
</div>


	<div class="pop_confirm" style="width:100%;">
			<h4>施設ご担当者様</h4>
			<ul>
			<li class="li-title">[氏名]</li>
			<li class="li-name">
			@if (empty($views->hotel_person['person_nm']))
				<font color="red">※氏名のご登録をお願いします。</font>
			@else
				{{$views->hotel_person['person_nm']}}<span class="li-name-sama">様</span>
			@endif
			</li>
			<li class="li-title">[電話番号]</li>
			<li class="li-tel" >
			@if (empty($views->hotel_person['person_tel']))
				<font color="red">※電話番号のご登録をお願いします。</font>
			@else
				{{$views->hotel_person['person_tel']}}
			@endif
			</li>
			<li class="li-title">[メールアドレス]</li>
			<li class="li-mail">
				@if (empty($views->hotel_person['person_email']))
					<font color="red">※メールアドレスのご登録をお願いします。</font>
				@elseif ($views->a_confirm_hotel_person['hotel_person_email_check'])
					<font color="red">{{$views->hotel_person['person_email']}}<br>
					※メールアドレスが正しくない可能性があります。
					</font>
				@else
					{{$views->hotel_person['person_email']}}
				@endif
			</li>
			</ul>
	</div>

	<div class="pop_confirm" style="width:100%;">
			<h4>請求ご担当者様</h4>
			<ul>
			<li class="li-title">[氏名]</li>
			<li class="li-name">
			@if (empty($views->customer['person_nm']))
				<font color="red">※氏名のご登録をお願いします。</font>
			@else
				{{$views->customer['person_nm']}}<span class="li-name-sama">様</span>
			@endif
			</li>
			<li class="li-title">[電話番号]</li>
			<li class="li-tel">
			@if (empty($views->customer['tel']))
				<font color="red">※電話番号のご登録をお願いします。</font>
			@else
				{{$views->customer['tel']}}
			@endif
			</li>
			<li class="li-title">[メールアドレス]
			<li class="li-mail">
			@if (empty($views->customer['email']))
				<font color="red">※メールアドレスのご登録をお願いします。</font>
			@elseif ($views->a_confirm_hotel_person['customer_email_check'])
				<font color="red">{{$views->customer['email']}}<br>
				※メールアドレスが正しくない可能性があります。
				</font>
			@else
				{{$views->customer['email']}}
			@endif
			</li>
			</ul>
	</div>

<hr size=1>

	<div class='mt20'>
		@if ($views->confirm_hotel_person_force)
		<form action="{$v->env.source_path}{$v->env.module}/htlmaillist/list" method="POST">
		<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
		<input type="submit" name="genreupload"  value="登録する"  class="remodal-confirm btn btn-success"  style=" width: 450px;margin: 0 100px 0 100px;"/>
		</form>
		@else
		<form action="{$v->env.source_path}{$v->env.module}/htlmaillist/list" method="POST">
		<input type="hidden" name="target_cd" value="{{strip_tags($views->target_cd)}}" />
		<input type="submit" name="genreupload"  value="変更が必要"  class="remodal-confirm btn btn-success"  style="float: left;margin: 0 50px 0 100px;"/>
		</form>
		<button data-remodal-action="cancel" class="btn btn-danger remodal-cancel">変更しない</button>
		@endif
	</div>

	<table border="1" cellpadding="6" cellspacing="0"  style=" position: absolute; bottom: -110px; right: 30px; background-color: #fff;">
		<tbody><tr>
		<td nowrap="" style="font-size:10pt;">
			お問い合わせ先<br>
			{{-- MAIL:<a href="mailto:{$v->config->environment->mail->from->opc}">{$v->config->environment->mail->from->opc}<br> --}}
			MAIL:<a href="mailto:aeon_info@email.aeon.biz">aeon_info@email.aeon.biz<br>
			</a>TEL : 043-388-0187<br>
			受付: 月～金 9:30～17:00<br>
			（土曜・日曜・祝祭日・弊社休日は除く）
		</td>
		</tr>
	</tbody></table>

</div>
@endif
{{-- 担当者情報確認ダイアログ ここまで --}}

		{{--------------------------------------------------------------------------------}}
		{{-- プライスコンシェルジュの案内  2018/07/05 ページ上部からここへ位置変更      --}}
		{{--------------------------------------------------------------------------------}}
		<div style="width: 800px; margin: auto; text-align: center;">
		<p><a href="http://price.bestrsv.com/" target="_blank">日本最大級の競合料金分析サービス！「プライスコンシェルジュ」はこちら</a></p>
		</div>

@endsection
