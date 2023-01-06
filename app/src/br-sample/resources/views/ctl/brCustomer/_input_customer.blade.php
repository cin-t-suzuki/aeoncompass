{{--削除でいいか？ {literal} --}}
<script type="text/javascript">
// ↓<!--はそのままでいい？コメントアウト？
	<!--
	$(document).ready(function () {
		{/literal}{{--
		//===================================================================
		// FAX通知先の選択状態に沿って、FAX通知任意宛先の入力制御を設定
		// 1:精算先    -- FAX通知任意宛先の入力 不可
		// 2:任意宛先  -- FAX通知任意宛先の入力 可
		//===================================================================
		// 画面表示時に実行
		//===================================================================
		--}}{literal}
		switch ($('input[name="customer[fax_recipient_cd]"]:checked').val()) {
		case '0':
		$('.jqs-optional_input').attr('disabled', '');
		break;
		case '1':
		$('.jqs-optional_input').attr('disabled', 'disabled');
		break;
		}
		{/literal}{{--
		//===================================================================
		// FAX通知先が変更されたとき
		//===================================================================*
		--}}{literal}
		$('input[name^="customer[fax_recipient_cd]"]').change(function() {
		if ( $('input[name^="customer[fax_recipient_cd]"]:checked').val() == 1  ) {
			$('.jqs-optional_input').attr('disabled', 'disabled');
		} else {
			$('.jqs-optional_input').attr('disabled', '');
		}
		});

		{/literal}{{--
		//===================================================================
		// 引落口座名義が変更されたとき（漢字チェック）
		//===================================================================*
		--}}{literal}
		$('input[name="customer[factoring_bank_account_kn]"]').change(function() {
			if($(this).val().match(/[\u4E00-\u9FFF]/)){
				$('#msg_factoring_bank_account_kn').text('口座名義に漢字が含まれています。');
				//$(':submit').prop("disabled", true);
			}else{
				$('#msg_factoring_bank_account_kn').text('');
			}
		});
		$('input[name="customer[payment_bank_account_kn]"]').change(function() {
			if($(this).val().match(/[\u4E00-\u9FFF]/)){
				$('#msg_payment_bank_account_kn').text('口座名義に漢字が含まれています。');
				//$(':submit').prop("disabled", true);
			}else{
				$('#msg_payment_bank_account_kn').text('');
			}
		});
		{/literal}{{--
		//===================================================================
		// 変更ボタンが押下されたとき
		//===================================================================*
		--}}{literal}
		$('input[type=submit]').click(function() {
			if($('input[name="customer[factoring_bank_account_kn]"]').val().match(/[\u4E00-\u9FFF]/)){
				alert('引落口座名義に漢字が含まれています。');
				return false;
			}
			if($('input[name="customer[payment_bank_account_kn]"]').val().match(/[\u4E00-\u9FFF]/)){
				alert('支払口座名義に漢字が含まれています。');
				return false;
			}
		});
		{/literal}{{--
		//===================================================================
		// お振込み予定日が変更されたとき
		//===================================================================*
		--}}{literal}
		$('input[name="customer[bill_add_month]"]').change(function() {
			if($(this).val() == 0){
				$('#ck_bill_day input').val(['99']);
				$('#ck_bill_day label:has(input:not(:checked))').css('color', 'darkgray');
				$('#ck_bill_day input:not(:checked)').attr('disabled', 'disabled');
			}else{
				$('#ck_bill_day label').css('color', 'black');
				$('#ck_bill_day input').removeAttr('disabled');
			}
		});
	});
	//-->
</script>
{{--削除でいいか？ {/literal} --}}
{{-- このファイル全体的に??null追記でいいか --}}
<table border="1" cellspacing="0" cellpadding="4">
<tr><td bgcolor="#eeffee" colspan="3">基本</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">請求連番</td><td>{{$views->customer['customer_id']}}<input type="hidden" name="customer[customer_id]" value="{{strip_tags($views->customer['customer_id'])}}"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">精算先名称</td><td><input type="text" name="customer[customer_nm]" SIZE="100" MAXLENGTH="100" value="{{strip_tags($views->customer['customer_nm'] ?? null)}}"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">請求書宛名</td><td><input type="text" name="customer[section_nm]" SIZE="50" MAXLENGTH="50" value="{{strip_tags($views->customer['section_nm'] ?? null)}}"><small>※請求書に印字されます。</small></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">担当者役職・部署名</td><td><input type="text" name="customer[person_post]" SIZE="50" MAXLENGTH="50" value="{{strip_tags($views->customer['person_post'] ?? null)}}"><small>※請求書に印字しません。</small></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">担当者</td><td><input type="text" name="customer[person_nm]" SIZE="20" MAXLENGTH="20" value="{{strip_tags($views->customer['person_nm'] ?? null)}}"><small>※請求書に印字しません。</small></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">郵便番号・都道府県</td><td><input type="text" name="customer[postal_cd]" SIZE="9" MAXLENGTH="8" value="{{strip_tags($views->customer['postal_cd'] ?? null)}}">
	<select size="1" name="customer[pref_id]">
	@foreach ($views->mast_pref['values'] as $value)
		<option value="{{$value['pref_id']}}"@if ($views->customer['pref_id'] ?? null == $value['pref_id']) selected @endif>{{$value['pref_nm']}}</option>
	@endforeach
	</select>
</td>
</tr>
<tr><td bgcolor="#eeffee" colspan="2">住所</td><td><input type="text" name="customer[address]" SIZE="100" MAXLENGTH="200" value="{{strip_tags($views->customer['address'] ?? null)}}"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">電話番号</td><td><input type="text" name="customer[tel]" SIZE="15" MAXLENGTH="15" value="{{strip_tags($views->customer['tel'] ?? null)}}"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">ファックス番号</td><td><input type="text" name="customer[fax]" SIZE="15" MAXLENGTH="15" value="{{strip_tags($views->customer['fax'] ?? null)}}"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">E-Mail</td><td><input type="text" name="customer[email]" SIZE="50" MAXLENGTH="50" value="{{strip_tags($views->customer['email'] ?? null)}}"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">請求方法</td><td><input type="radio" id="bill_way_0" name="customer[bill_way]" value="0" @if ((($views->customer['bill_way'] ?? null) ?? "0") === "0")checked @endif /><label for="bill_way_0">振込</label><input type="radio" id="bill_way_1" name="customer[bill_way]" value="1" @if (($views->customer['bill_way'] ?? null) === "1")checked @endif /><label for="bill_way_1">引落</label></td></tr>
<tr><td bgcolor="#eeffee" colspan="3">請求銀行</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">振込銀行と支店</td><td><input type="text" name="customer[bill_bank_nm]" SIZE="40" MAXLENGTH="30" value="@if ($service->is_empty($views->customer['bill_bank_nm'] ?? null)) 三井住友銀行　大阪第一支店
@else {{strip_tags($views->customer['bill_bank_nm'])}}@endif"></td></tr>
<tr><td bgcolor="#eeffee" colspan="2">振込口座</td><td><input type="text" name="customer[bill_bank_account_no]" SIZE="20" MAXLENGTH="20" value="{{strip_tags($views->customer['bill_bank_account_no'] ?? null)}}"></td></tr>
<tr><td bgcolor="#ededff" colspan="3">■引落銀行</td></tr>
<tr><td bgcolor="#ededff" >銀行コード</td><td bgcolor="#ededff" rowspan="2"><input type="button" value="検索" onclick="this.form.action=this.form.action + '?bank_query=検索&amp;is_fact=1';this.form.submit();"></td><td><input type="text" name="customer[factoring_bank_cd]" SIZE="6" MAXLENGTH="4" value="{{strip_tags($views->customer['factoring_bank_cd'] ?? null)}}">（数字4文字） {{$views->factoring_bank['bank_nm'] ?? null}} 　　（ゆうちょ銀行の場合は、9900を入力）</td></tr>
<tr><td bgcolor="#ededff" >支店コード</td><td><input type="text" name="customer[factoring_bank_branch_cd]" SIZE="5" MAXLENGTH="3" value="{{strip_tags($views->customer['factoring_bank_branch_cd'] ?? null)}}">（数字3文字） {{$views->factoring_bank_branch['bank_branch_nm'] ?? null}}@if ($service->is_empty($views->factoring_bank_branch['bank_branch_nm'] ?? null))（ゆうちょ銀行の場合は、通帳記号の５桁の中３桁 例：10290 → 029 ）@endif</td></tr>
<tr><td bgcolor="#ededff" colspan="2">引落口座種別</td><td><input type="radio" name="customer[factoring_bank_account_type]" @if (($views->customer['factoring_bank_account_type'] ?? 1) == 1)checked @endif value="1">普通</input><input type="radio" name="customer[factoring_bank_account_type]" @if (($views->customer['factoring_bank_account_type'] ?? null) == 2) checked @endif value="2">当座</input></td></tr>
<tr><td bgcolor="#ededff" colspan="2">引落口座番号</td><td><input type="text" name="customer[factoring_bank_account_no]" SIZE="10" MAXLENGTH="7" value="{{strip_tags($views->customer['factoring_bank_account_no'] ?? null)}}">（数字7文字、ゆうちょ銀行の場合は、8桁なので最後の1(固定)以外の7桁を入力）</td></tr>
<tr><td bgcolor="#ededff" colspan="2">引落口座名義</td><td><input type="text" name="customer[factoring_bank_account_kn]" SIZE="60" MAXLENGTH="30" value="{{strip_tags($views->customer['factoring_bank_account_kn'] ?? null)}}">（全角カナ30文字）<br />「ひらがな」は「カタカナ」に変換して登録します。<div style="color: #f00;" id="msg_factoring_bank_account_kn"></div></td></tr>
<tr><td bgcolor="#ededff" colspan="2">引落顧客番号</td><td><input type="text" name="customer[factoring_cd]" SIZE="20" MAXLENGTH="12" value="{{strip_tags($views->customer['factoring_cd'] ?? null)}}">（数字12桁）<br />口座振替依頼書で指定した番号を登録してください。</td></tr>
<tr><td bgcolor="#ffeded" colspan="3">■支払銀行</td></tr>
<tr><td bgcolor="#ffeded" >銀行コード</td><td bgcolor="#ffeded" rowspan="2"><input type="button" value="検索" onclick="this.form.action=this.form.action + '?bank_query=検索';this.form.submit();"></td><td><input type="text" name="customer[payment_bank_cd]" SIZE="6" MAXLENGTH="4" value="{{strip_tags($views->customer['payment_bank_cd'] ?? null)}}">（数字4文字）{{$views->bank['bank_nm'] ?? null}}</td></tr>
<tr><td bgcolor="#ffeded" >支店コード</td><td><input type="text" name="customer[payment_bank_branch_cd]" SIZE="5" MAXLENGTH="3" value="{{strip_tags($views->customer['payment_bank_branch_cd'] ?? null)}}">（数字3文字） {{$views->bank_branch['bank_branch_nm'] ?? null}}</td></tr>
<tr><td bgcolor="#ffeded" colspan="2">支払口座種別</td><td><input type="radio" name="customer[payment_bank_account_type]" @if ((($views->customer['payment_bank_account_type'] ?? null) ?? 1) == 1) checked @endif value="1">普通</input><input type="radio" name="customer[payment_bank_account_type]" @if (($views->customer['payment_bank_account_type'] ?? null) == 2) checked @endif value="2">当座</input></td></tr>
<tr><td bgcolor="#ffeded" colspan="2">支払口座番号</td><td><input type="text" name="customer[payment_bank_account_no]" SIZE="10" MAXLENGTH="7" value="{{strip_tags($views->customer['payment_bank_account_no'] ?? null)}}">（数字7文字）</td></tr>
<tr><td bgcolor="#ffeded" colspan="2">支払口座名義</td><td><input type="text" name="customer[payment_bank_account_kn]" SIZE="60" MAXLENGTH="30" value="{{strip_tags($views->customer['payment_bank_account_kn'] ?? null)}}">（全角カナ30文字）<br />「ひらがな」は「カタカナ」に変換して登録します。<div style="color: #f00;" id="msg_payment_bank_account_kn"></div></td></tr>
<tr><td bgcolor="#eeffee" COLSPAN="3">その他（精算タイミング）</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">請求必須月</td>
	<td nowrap>
		{{-- チェックが空、かつ別の項目がバリデーションエラーで戻ってきたとき用で??nullを追記 --}}
	<input type="checkbox" name="customer[bill_month04]" id="bill_month04" value="1"@if (($views->customer['bill_month04'] ?? null) == 1) checked @endif><label for="bill_month04">4月</label>
	<input type="checkbox" name="customer[bill_month05]" id="bill_month05" value="1"@if (($views->customer['bill_month05'] ?? null) == 1) checked @endif><label for="bill_month05">5月</label>
	<input type="checkbox" name="customer[bill_month06]" id="bill_month06" value="1"@if (($views->customer['bill_month06'] ?? null) == 1) checked @endif><label for="bill_month06">6月</label>
	<input type="checkbox" name="customer[bill_month07]" id="bill_month07" value="1"@if (($views->customer['bill_month07'] ?? null) == 1) checked @endif><label for="bill_month07">7月</label>
	<input type="checkbox" name="customer[bill_month08]" id="bill_month08" value="1"@if (($views->customer['bill_month08'] ?? null) == 1) checked @endif><label for="bill_month08">8月</label>
	<input type="checkbox" name="customer[bill_month09]" id="bill_month09" value="1"@if (($views->customer['bill_month09'] ?? null) == 1) checked @endif><label for="bill_month09">9月</label>
	<input type="checkbox" name="customer[bill_month10]" id="bill_month10" value="1"@if (($views->customer['bill_month10'] ?? null) == 1) checked @endif><label for="bill_month10">10月</label>
	<input type="checkbox" name="customer[bill_month11]" id="bill_month11" value="1"@if (($views->customer['bill_month11'] ?? null) == 1) checked @endif><label for="bill_month11">11月</label>
	<input type="checkbox" name="customer[bill_month12]" id="bill_month12" value="1"@if (($views->customer['bill_month12'] ?? null) == 1) checked @endif><label for="bill_month12">12月</label>
	<input type="checkbox" name="customer[bill_month01]" id="bill_month01" value="1"@if (($views->customer['bill_month01'] ?? null) == 1) checked @endif><label for="bill_month01">1月</label>
	<input type="checkbox" name="customer[bill_month02]" id="bill_month02" value="1"@if (($views->customer['bill_month02'] ?? null) == 1) checked @endif><label for="bill_month02">2月</label>
	<input type="checkbox" name="customer[bill_month03]" id="bill_month03" value="1"@if (($views->customer['bill_month03'] ?? null) == 1) checked @endif><label for="bill_month03">3月</label>
	</td>
</tr>
<tr><td bgcolor="#eeffee" colspan="2">支払必須月</td>
	<td nowrap>
		{{-- チェックが空、かつ別の項目がバリデーションエラーで戻ってきたとき用で??nullを追記 --}}
	<input type="checkbox" name="customer[payment_month04]" id="payment_month04" value="1"@if (($views->customer['payment_month04'] ?? null) == 1) checked @endif><label for="payment_month04">4月</label>
	<input type="checkbox" name="customer[payment_month05]" id="payment_month05" value="1"@if (($views->customer['payment_month05'] ?? null) == 1) checked @endif><label for="payment_month05">5月</label>
	<input type="checkbox" name="customer[payment_month06]" id="payment_month06" value="1"@if (($views->customer['payment_month06'] ?? null) == 1) checked @endif><label for="payment_month06">6月</label>
	<input type="checkbox" name="customer[payment_month07]" id="payment_month07" value="1"@if (($views->customer['payment_month07'] ?? null) == 1) checked @endif><label for="payment_month07">7月</label>
	<input type="checkbox" name="customer[payment_month08]" id="payment_month08" value="1"@if (($views->customer['payment_month08'] ?? null) == 1) checked @endif><label for="payment_month08">8月</label>
	<input type="checkbox" name="customer[payment_month09]" id="payment_month09" value="1"@if (($views->customer['payment_month09'] ?? null) == 1) checked @endif><label for="payment_month09">9月</label>
	<input type="checkbox" name="customer[payment_month10]" id="payment_month10" value="1"@if (($views->customer['payment_month10'] ?? null) == 1) checked @endif><label for="payment_month10">10月</label>
	<input type="checkbox" name="customer[payment_month11]" id="payment_month11" value="1"@if (($views->customer['payment_month11'] ?? null) == 1) checked @endif><label for="payment_month11">11月</label>
	<input type="checkbox" name="customer[payment_month12]" id="payment_month12" value="1"@if (($views->customer['payment_month12'] ?? null) == 1) checked @endif><label for="payment_month12">12月</label>
	<input type="checkbox" name="customer[payment_month01]" id="payment_month01" value="1"@if (($views->customer['payment_month01'] ?? null) == 1) checked @endif><label for="payment_month01">1月</label>
	<input type="checkbox" name="customer[payment_month02]" id="payment_month02" value="1"@if (($views->customer['payment_month02'] ?? null) == 1) checked @endif><label for="payment_month02">2月</label>
	<input type="checkbox" name="customer[payment_month03]" id="payment_month03" value="1"@if (($views->customer['payment_month03'] ?? null) == 1) checked @endif><label for="payment_month03">3月</label>
	</td>
</tr>
{{-- 以下null追記でいいか --}}
<tr><td bgcolor="#eeffee" colspan="2">請求最低金額</td><td><input type="text" name="customer[bill_charge_min]" SIZE="5" MAXLENGTH="5" value="{{strip_tags($views->customer['bill_charge_min'] ?? null)}}" > 空欄にすると請求処理は請求必須月欄指定月のみ処理されます。</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">支払最低金額</td><td><input type="text" name="customer[payment_charge_min]" SIZE="5" MAXLENGTH="5" value="{{strip_tags($views->customer['payment_charge_min'] ?? null)}}"> 空欄にすると支払処理は支払必須月欄指定月のみ処理されます。</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">振込予定日</td><td>
	<div>請求書発行月の
		<label><input type="radio" name="customer[bill_add_month]" value="0" @if (($views->customer['bill_add_month'] ?? "0") === "0")checked @endif />当月</label>
		<label><input type="radio" name="customer[bill_add_month]" value="1" @if (($views->customer['bill_add_month'] ?? "1") === "0")checked @endif />翌月</label>
		<label><input type="radio" name="customer[bill_add_month]" value="2" @if (($views->customer['bill_add_month'] ?? "2") === "0")checked @endif />翌々月</label>
	</div>
	<div style="margin-left: 117px;" id="ck_bill_day">
		<label @if (($views->customer['bill_add_month'] ?? "0") === "0") style="color:darkgray" @endif><input type="radio" name="customer[bill_day]" value="5"  @if (($views->customer['bill_day'] ?? null) === "5") checked @endif @if (($views->customer['bill_add_month'] ?? "0") === "0") disabled @endif />5日</label>
		<label @if (($views->customer['bill_add_month'] ?? "0") === "0") style="color:darkgray" @endif><input type="radio" name="customer[bill_day]" value="10" @if (($views->customer['bill_day'] ?? null) === "10") checked @endif @if (($views->customer['bill_add_month'] ?? "0") === "0") disabled @endif />10日</label>
		<label @if (($views->customer['bill_add_month'] ?? "0") === "0") style="color:darkgray" @endif><input type="radio" name="customer[bill_day]" value="15" @if (($views->customer['bill_day'] ?? null) === "15") checked @endif @if (($views->customer['bill_add_month'] ?? "0") === "0") disabled @endif />15日</label>
		<label @if (($views->customer['bill_add_month'] ?? "0") === "0") style="color:darkgray" @endif><input type="radio" name="customer[bill_day]" value="20" @if (($views->customer['bill_day'] ?? null) === "20") checked @endif @if (($views->customer['bill_add_month'] ?? "0") === "0") disabled @endif />20日</label>
		<label @if (($views->customer['bill_add_month'] ?? "0") === "0") style="color:darkgray" @endif><input type="radio" name="customer[bill_day]" value="25" @if (($views->customer['bill_day'] ?? null) === "25") checked @endif @if (($views->customer['bill_add_month'] ?? "0") === "0") disabled @endif />25日</label>
		<label><input type="radio" name="customer[bill_day]" value="99" @if ((($views->customer['bill_day'] ?? null) ?? "99") === "99")checked @endif />月末</label>
	</div>
	</td>
</tr>

<tr><td bgcolor="#eeffee" colspan="3">発送方法</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">請求書</td><td>
	{{--  一番上の元ソース?? "1"に併せていいか？ --}}
	<label><input type="radio" name="customer[bill_send]" value="1" @if (($views->customer['bill_send'] ?? "1") === "1")checked @endif />印刷（郵送）</label>
	<label><input type="radio" name="customer[bill_send]" value="2" @if (($views->customer['bill_send'] ?? "1") === "2")checked @endif />FAX</label>
	<label><input type="radio" name="customer[bill_send]" value="3" @if (($views->customer['bill_send'] ?? "1") === "3")checked @endif />両方（印刷（郵送）・FAX ）</label>
	<label><input type="radio" name="customer[bill_send]" value="0" @if (($views->customer['bill_send'] ?? "1") === "0")checked @endif />不要</label>
	</td>
</tr>
<tr><td bgcolor="#eeffee" colspan="2">支払通知書</td><td>
	{{--  一番下の元ソース?? "0"に併せていいか？ --}}
	<label style="color:darkgray"><input disabled="disabled" type="radio" name="customer[payment_send]" value="1" @if (($views->customer['payment_send'] ?? "0") === "1")checked @endif />印刷（郵送）</label>
	<label><input type="radio" name="customer[payment_send]" value="2" @if (($views->customer['payment_send'] ?? "0") === "2")checked @endif />FAX</label>
	<label style="color:darkgray"><input  disabled="disabled"type="radio" name="customer[payment_send]" value="3" @if (($views->customer['payment_send'] ?? "0") === "3")checked @endif />両方（印刷（郵送）・FAX ）</label>
	<label><input type="radio" name="customer[payment_send]" value="0" @if (($views->customer['payment_send'] ?? "0") === "0")checked @endif />不要</label>
	</td>
</tr>
<tr><td bgcolor="#eeffee" colspan="2">引落通知書</td><td>
	{{--  一番下の元ソース?? "0"に併せていいか？ --}}
	<label style="color:darkgray"><input disabled="disabled" type="radio" name="customer[factoring_send]" value="1" @if (($views->customer['factoring_send'] ?? "0") === "1")checked @endif />印刷（郵送）</label>
	<label ><input type="radio" name="customer[factoring_send]" value="2" @if (($views->customer['factoring_send'] ?? "0") === "2")checked @endif />FAX</label>
	<label style="color:darkgray"><input disabled="disabled" type="radio" name="customer[factoring_send]" value="3" @if (($views->customer['factoring_send'] ?? "0") === "3")checked @endif />両方（印刷（郵送）・FAX ）</label>
	<label><input type="radio" name="customer[factoring_send]" value="0" @if (($views->customer['factoring_send'] ?? "0") === "0")checked @endif />不要</label>
	</td>
</tr>
<tr><td bgcolor="#eeffee" colspan="3">FAX通知先</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">通知先</td><td>
	{{--  上の元ソース?? "1"に併せていいか？ --}}
	<label><input type="radio" name="customer[fax_recipient_cd]" value="1" @if (($views->customer['fax_recipient_cd'] ?? "1")  === "1")checked @endif />精算先（上付なし）</label>
	<label><input type="radio" name="customer[fax_recipient_cd]" value="2" @if (($views->customer['fax_recipient_cd'] ?? "1") === "2")checked @endif />任意宛先（上付あり <a href="/ctl//brcustomer/sendletter/" target="_blank">サンプル</a>）</label>
	</td>
</tr>
<tr><td bgcolor="#eeffee" colspan="3">FAX通知任意宛先</td></tr>
<tr><td bgcolor="#eeffee" colspan="2">施設・会社名</td><td><input class="jqs-optional_input"  type="text" name="customer[optional_nm]" SIZE="100" MAXLENGTH="100" value="{{strip_tags($views->customer['optional_nm'] ?? null)}}"@if (($views->customer['fax_recipient_cd'] ?? "1")  === "1") disabled="disabled" @endif /></td>
</tr>
<tr><td bgcolor="#eeffee" colspan="2">役職（部署名）</td><td><input class="jqs-optional_input"  type="text" name="customer[optional_section_nm]" SIZE="50" MAXLENGTH="25" value="{{strip_tags($views->customer['optional_section_nm'] ?? null)}}"@if (($views->customer['fax_recipient_cd'] ?? "1")  === "1") disabled="disabled" @endif /></td>
<tr><td bgcolor="#eeffee" colspan="2">担当者</td><td><input class="jqs-optional_input"  type="text" name="customer[optional_person_nm]" SIZE="50" MAXLENGTH="20" value="{{strip_tags($views->customer['optional_person_nm'] ?? null)}}"@if (($views->customer['fax_recipient_cd'] ?? "1")  === "1") disabled="disabled" @endif /></td>
</tr>
<tr><td bgcolor="#eeffee" colspan="2">ファックス番号</td><td><input  class="jqs-optional_input" type="text" name="customer[optional_fax]" SIZE="15" MAXLENGTH="15" value="{{strip_tags($views->customer['optional_fax'] ?? null)}}"@if (($views->customer['fax_recipient_cd'] ?? "1")  === "1") disabled="disabled" @endif /></td>
</tr>
</table>
