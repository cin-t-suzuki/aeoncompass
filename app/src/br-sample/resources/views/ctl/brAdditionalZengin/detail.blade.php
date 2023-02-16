@extends('ctl.common.base')
@section('title', '口座振替　追加処理')
@inject('service', 'App\Http\Controllers\ctl\BrAdditonalZenginController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

{{--削除でいいか？ {literal} --}}
<script type="text/javascript">
	$(document).ready(function () {
		//{/literal}{*
		//===================================================================
		// 理由が変更されたとき
		//===================================================================*
		//*}{literal}
		$('textarea[name="reason"]').change(function() {
			chk_reason(this);
		});
		//{/literal}{*
		//===================================================================
		// 理由が変更されたとき
		//===================================================================*
		//*}{literal}
		$('textarea[name="reason_internal"]').change(function() {
			chk_reason_internal(this);
		});
		//{/literal}{*
		//===================================================================
		// 金額が変更されたとき
		//===================================================================*
		//*}{literal}
		$('input[name="additional_charge"]').change(function() {
			chk_additional_charge(this);
		});
		//{/literal}{*
		//===================================================================
		// 削除ボタンが押下されたとき
		//===================================================================*
		//*}{literal}
		$('#btn_delete').click(function() {
			if(!confirm('削除しますか？')){
				return false;
			}else{
				$('form').attr('action',$(this).data('url'));
				// $('form').submit(); //二重送信になるので非表示（削除でいいか？）
			}
		});
		//{/literal}{*
		//===================================================================
		// 変更ボタンが押下されたとき
		//===================================================================*
		//*}{literal}
		$('#btn_update').click(function() {
			var chk1 = true;
			var chk2 = true;
			var chk3 = true;
			chk1 = 	chk_additional_charge('input[name="additional_charge"]');
			chk2 = chk_reason('textarea[name="reason"]');
			chk3 = chk_reason_internal('textarea[name="reason_internal"]');

			if(!chk1 || !chk2|| !chk3){
				return false;
			}else{
				$('form').attr('action',$(this).data('url'));
				// $('form').submit();　//二重送信になるので非表示（削除でいいか？）
			}
		});
	});

	//{/literal}{*
	//===================================================================
	// 理由欄入力チェック
	//===================================================================*
	//*}{literal}
	function chk_reason(obj){
		if($(obj).val() == ""){
			$('#msg_reason').text('理由は入力必須です。');
			return false;
		}else if($(obj).val().length > 1000){
			$('#msg_reason').text('理由は1000文字までです。');
			return false;
		}else{
			$('#msg_reason').text('');
			return true;
		}
	}
	//{/literal}{*
	//===================================================================
	// 備考欄入力チェック
	//===================================================================*
	//*}{literal}
	function chk_reason_internal(obj){
		if($(obj).val() == ""){
			$('#msg_reason_internal').text('備考は入力必須です。');
		}else if($(obj).val().length > 1000){
			$('#msg_reason_internal').text('備考は1000文字までです。');
			return false;
		}else{
			$('#msg_reason_internal').text('');
			return true;
		}
	}

	//{/literal}{*
	//===================================================================
	// 金額欄入力チェック
	//===================================================================*
	//*}{literal}
	function chk_additional_charge(obj){
		if($(obj).val() == ""){
			$('#msg_additional_charge').text('金額は入力必須です。');
			return false;
		}else if($(obj).val().match(/[\D+]/)){
			$('#msg_additional_charge').text('金額は数字のみです。');
			return false;
		}else{
			$('#msg_additional_charge').text('');
			return true;
		}
	}
//削除でいいか？ {/literal}
</script>


<div style="line-height:150%" style="margin:1em 1em">


  <strong>理由・金額の編集</strong><br>
 対象の施設への口座振替の追加金額と理由の入力を行う。

<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td bgcolor="#EEFFEE" nowrap>施設コード<br>施設名</td>
    <td bgcolor="#EEFFEE" nowrap>精算先名称</td>
    <td bgcolor="#EEFFEE" nowrap>送客請求実績</td>
  </tr>

  <tr>

    <td nowrap>
      {{$additional_zengin['hotel_cd']}}<br>
	  {{-- ↓null追記でいいか？ --}}
      <a href="http://{$v->config->system->rsv_host_name}/hotel/{{$additional_zengin['hotel_cd']}}/" target="_blank" style="text-decoration: none; color:#000066;">{{$additional_zengin['hotel_nm']}}@if (!$service->is_empty($additional_zengin['hotel_old_nm'] ?? null))（{{$additional_zengin['hotel_old_nm']}}）</font>@endif</a>
	  <font color="#0000ff">[買]</font>
      @if (($additional_zengin['stock_type'] ?? null) == 1)
      @endif
      @if (!$service->is_empty($additional_zengin['pref_nm'] ?? null))
        （{{$additional_zengin['pref_nm']}}）</font>
      @endif
    </td>

    <td>
      ({{$additional_zengin['customer_id']}}){{$additional_zengin['customer_nm']}}
    </td>

    <td nowrap align="middle">
		{{-- TODO htldemand作成後に遷移先設定 --}}
      <form action="{$v->env.source_path}{$v->env.module}/htldemand/" method="post" target= "_blank">
        <input type="submit" value="詳細情報">
        <input type="hidden" name="target_cd" value="{{$additional_zengin['hotel_cd']}}" />
      </form>
    </td>

  </tr>
</table>


<table cellspacing="0" cellpadding="2" border="1" style="margin-top: 10px;min-width: 380px;">
  <tr>
    <tr><td bgcolor="#eeffee" colspan="3">引落銀行</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">銀行コード</td><td>{{strip_tags($additional_zengin['factoring_bank_cd'])}} : {{strip_tags($additional_zengin['factoring_bank_nm'])}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支店コード</td><td>{{strip_tags($additional_zengin['factoring_bank_branch_cd'])}} : {{strip_tags($additional_zengin['factoring_bank_branch_nm'])}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座種別</td><td>
          @if ($additional_zengin['factoring_bank_account_type'] == 1)普通
      @elseif ($additional_zengin['factoring_bank_account_type'] == 2)当座
      @elseif ($additional_zengin['factoring_bank_account_type'] == 4)貯蓄
      @elseif ($additional_zengin['factoring_bank_account_type'] == 9)その他
          @endif</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座番号</td><td>{{strip_tags($additional_zengin['factoring_bank_account_no'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座名義</td><td>{{strip_tags($additional_zengin['factoring_bank_account_kn'])}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落顧客番号</td><td>{{strip_tags($additional_zengin['factoring_cd'])}}<br /></td></tr>
  </tr>

</table>

{{-- form+jquery関連書き換えあっているか？ --}}
{{-- <form action="" method="post" > --}}
{{ Form::open(['method' => 'post']) }}
<table cellspacing="0" cellpadding="2" border="1" style="margin-top: 30px;width:600px;">

  <tr><td bgcolor="#eeffee" colspan="2">引落追加情報</td></tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap style="width: 20%;">引落日</td>
    <td nowrap style="width: 80%;">
		@include('ctl.common._date',["timestamp" => $additional_zengin['billpay_ymd'], "format" =>"y/m/d" ] )
    </td>
  </tr>

  <tr>
    <td bgcolor="#EEFFEE" nowrap>追加金額</td>
    <td nowrap>
	{{-- {{number_format($additional_zengin['additional_charge'])}} --}}
    <div style="color: #f00;" id="msg_additional_charge"></div>
    <input maxlength="20" name="additional_charge" class="num" value="{{$additional_zengin['additional_charge']}}">
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>理由<br>(施設向け)<br>1000文字まで</td>
    <td nowrap>
    <div style="color: #f00;" id="msg_reason"></div>
    <textarea name="reason" cols="100" rows="6">{{$additional_zengin['reason']}}</textarea>
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>備考<br>(内部のみ)<br>1000文字まで</td>

    <td nowrap>
    <div style="color: #f00;" id="msg_reason_internal"></div>
    <textarea name="reason_internal" cols="100" rows="6">{{$additional_zengin['reason_internal']}}</textarea>
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>登録者</td>

    <td nowrap>
    {{$additional_zengin['staff_nm']}}
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>登録日</td>

    <td nowrap>
	{{$additional_zengin['entry_ts'] ?? null}}
    </td>
  </tr>
</table>
@if ($additional_zengin['notactive_flg'] == 0)
{{-- ↓form+jquery関連書き換えあっているか？ --}}
    <input type="submit" value="更新" style="width: 100px;height: 25px;margin: 10px 100px;" id="btn_update" data-url="update">
    <input type="submit" value="削除" style="width: 100px;height: 25px;margin: 10px 100px;" id="btn_delete" data-url="delete">

    <input type="hidden" name="zengin_ym" value="{{$additional_zengin['zengin_ym']}}" />
    <input type="hidden" name="branch_id" value="{{$additional_zengin['branch_id']}}" />
{{ Form::close() }}
@else
<div style="margin: 10px 300px;">
<font color="#ff0000">削除しました</font>
</div>
@endif

</div>

@endsection
