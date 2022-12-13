@section('title', '施設情報メイン')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

<script language="JavaScript" type="text/javascript">
<!--
	function help1Form() {
		var f = document.getElementById('help1');
		if (f.style.display == 'none') {
		f.style.display = 'block';
		} else {
		f.style.display = 'none';
		}
		var g = document.getElementById('help2');
		g.style.display = 'none';
	}
	function help2Form() {
		var f = document.getElementById('help2');
		if (f.style.display == 'none') {
		f.style.display = 'block';
		} else {
		f.style.display = 'none';
		}
		var g = document.getElementById('help1');
		g.style.display = 'none';
	}
//-->
</script>
{{--TODO 精算先 画面遷移--}}
<table border="1" cellspacing="0" cellpadding="5">
	<!--TODO form action="{$v->env.source_path}{$v->env.module}/brhotel/new/" method="post"-->
	{!! Form::open(['route' => ['ctl.brhotel.new'], 'method' => 'post']) !!}
	<tr>
	<td bgcolor="#EEFFEE">宿泊施設（発番処理）</td>
	<td bgcolor="#EEFFEE">新規登録</td>
	<td><input type="submit" value="施設登録"></td>
	</tr>
	{!! Form::close() !!}
</table>

<br />
<table border="1" cellspacing="0" cellpadding="4">
	<tr>
	<form method="post" action="{$v->env.source_path}{$v->env.module}/brcustomer/list/">
		<td nowrap bgcolor="#EEFFEE">精算先の登録・変更</td>
		<td nowrap>
		<input name="keywords" size="20" maxlength="30" type="text">
		<input name="i_btn" value="設定" type="submit">
		</td>
	</form>
	<form method="post" action="{$v->env.source_path}{$v->env.module}/brcustomer/csv/">
		<td nowrap>
		<input name="i_btn" value="精算先全件CSVダウンロード" type="submit">
		</td>
	</form>
	</tr>
</table>

{{-- 検索のヘルプ --}}
<script language="JavaScript" type="text/javascript">
<!--
if(document.getElementById){
	document.write('<div style="float:left"><a href="" onclick="help1Form(); return false;">請求先検索のヘルプ</a></div>');}
//-->
</script>

<br />
<br />
<br />

<style type="text/css">
<!-- 
 form {margin:0px}
-->
</style>

<script language="javascript"  type="text/javascript">
<!--
	//base.blade jquery
	$(document).ready(function () {

		var entry_status_input = ""; //TODO 初期化状態だとどのような条件になるか未確認
		$('input[name="query"]').click(function () {
			var uri = "{{ route('ctl.brhotel.hotelsearch')}}";
			uri= uri + '?keywords='+ encodeURI($('input[name="keyword"]').val());
			uri= uri + '&pref_id='+ $('select[name="pref_id"]').val()

			if ($('input[name="entry_status"]').is(':checked')) {
				uri= uri + '&entry_status=0';
			}
			$.get(uri, function(html){
			$('#hotel').html(html);
			});
		});
	});
//-->
</script>

<table border="1" cellspacing="0" cellpadding="4" class="search">
	<tr>
		<td bgcolor="#EEFFEE">宿泊施設</td>
		<td nowrap><input type="checkbox" value="0" name="entry_status" id="entry_status_0" /><label for="entry_status_0">公開中のみ</label></td>
		<td nowrap>
		<select size="1" name="pref_id">
			@foreach ($views->mast_prefs["values"] as $mast_pref)
				@if (strip_tags($mast_pref["pref_id"]) == 0)
					<option value="">
				@else
					<option value="{{strip_tags($mast_pref["pref_id"])}}">
				@endif
				{{strip_tags($mast_pref["pref_nm"])}}
				</option>
			@endforeach
		</select>
		</td>
		<td nowrap><input maxlength="20" name="keyword" size="20"></td>
		<td nowrap><input type="submit" name="query" value=" 検索 "></td>
	</tr>
</table>
<span id="hotel"></span>

{{-- 検索のヘルプ --}}
<script language="JavaScript" type="text/javascript">
	<!--
	if(document.getElementById){
	document.write('<div style="float:left"><a href="" onclick="help2Form(); return false;">宿泊施設検索のヘルプ</a></div>');}
	//-->
</script>

<br>
<br>

<div id="help1" style="border: 1px solid rgb(0, 0, 0); display: none; position: absolute; background-color: rgb(255, 255, 255);" align="left">
	<div style="margin: 2px 4px; text-align: right;"><a href="" onclick="help1Form();return false;"><nobr>×閉じる</nobr></a></div>
		<div style="font-size:10px;margin-top:8px">
			下記での検索を前後方一致にて行います。
			<ul style="margin-top:0px">
			<li>団体名称</li>
			<li>電話番号</li>
			<li>FAX番号</li>
			</ul>
		</div>
	<div style="font-size:10px">
		下記での検索を完全一致にて行います。
		<ul style="margin-top:0px">
		<li>請求連番</li>
		<li>振込口座番号</li>
		<li>引落顧客番号</li>
		<li>引落口座番号</li>
		</ul>
	</div>
</div>

<div id="help2" style="border: 1px solid rgb(0, 0, 0); display: none; position: absolute; background-color: rgb(255, 255, 255);" align="left">
	<div style="margin: 2px 4px; text-align: right;"><a href="" onclick="help2Form();return false;"><nobr>×閉じる</nobr></a></div>
		<div style="font-size:10px;margin-top:8px">
		下記での検索を前方一致にて行います。
		<ul style="margin-top:0px">
		<li>ホテルコード</li>
		<li>郵便番号</li>
		<li>住所</li>
		<li>電話番号</li>
		<li>FAX番号</li>
		</ul>
	</div>
	<div style="font-size:10px">
		下記での検索を前後方一致にて行います。
		<ul style="margin-top:0px">
		<li>施設名称</li>
		<li>施設名称かな</li>
		<li>グループ名称（※黄色の背景色で表示されます。）</li>
		</ul>
	</div>
	<div style="font-size:10px">
		下記での検索を完全一致にて行います。
		<ul style="margin-top:0px">
		<li>担当者メールアドレス</li>
		</ul>
	</div>
</div>

@section('title', 'footer')
@include('ctl.common.footer')