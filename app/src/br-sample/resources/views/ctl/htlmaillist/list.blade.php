@section('headScript')
<script>
$(function(){

	$('.msg-success').hide().fadeIn(1500);

	$('#btn_update_person').click(function(){

		$('.required_msg').remove();
		$('.required').each(function(i, elem) {
			if($(elem).val() ==''){
				$(elem).before('<font class="required_msg" color="red"><b>未入力です</b><br></font>');
				$(elem).css('background-color','#f66');
			}else{
				$(elem).css('background-color','#ffedc9');
			}
		});

		if($('.required_msg').length > 0){
			alert('未入力の項目があります。');
			return false;
		}

	});

});
</script>
@endsection

@extends('ctl.common._htl_base')
@section('title', '各種メール設定')
@inject('service', 'App\Http\Controllers\ctl\HtlMailListController')
@section('content')

{{-- パンくず --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
各種メール設定
<br>
<br>
{{-- メッセージ --}}
@includeWhen($errors,'ctl.common.message')

<div class="htlmaillist">

{{-- MEMO 旧ソースbase.cssのstyleを直接記述 --}}
@if(!empty($guides))
{!! Form::open(['route' => ['ctl.htl_top.index'], 'method' => 'get']) !!}
	<div class="msg-success" 
         style = ' padding: 0.5em 1em;
                   margin: 2em 0;
                   font-weight: bold;
                   color: #6091d3;
                   background: #e0f6fd;
                   border: solid 3px #6091d3;
                   border-radius: 10px;
                   display: none;
                   text-align: center;
                '>
        <p>担当者情報の更新が完了しました。こちらのボタンからメインメニューに戻れます。</p>
            <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
		    <input type='submit' value='メインメニューに戻る'>
	</div>
{{ Form::close() }}
@endif


<table border="1" cellspacing="0" cellpadding="4">
	<tr>
		<td bgcolor="#EEEEFF" rowspan="2" nowrap></td>
		<td bgcolor="#EEEEFF" rowspan="2" nowrap>
			<b>メールアドレス</b>
			<div>
			<span style="color: #FFF;background-color: #ff0044; font-size: 10px;padding: 2px 7px 2px;border-radius: 6px;">必須</span>
			<span style="font-size: 12px;color: #a8411e;">[半角英数字]</span>
			</div>
		</td>
		<td bgcolor="#EEEEFF" colspan="4"nowrap><b>各種設定</b></td>
		<td bgcolor="#EEEEFF" rowspan="2" nowrap>　</td>
		<td bgcolor="#EEEEFF" rowspan="2" nowrap style="width: 240px;"><b>備考</b></td>
	</tr>

	<tr>
		<td bgcolor="#EEEEFF" nowrap>
			<b>部署・役職</b>
			<div>
			<span style="color: #FFF;background-color: #4B4B50;font-size: 10px;padding: 2px 7px 2px;border-radius: 6px;">任意</span>
			<span style="font-size: 12px;color: #a8411e;">&nbsp;</span>
			</div>
		</td>
			<td bgcolor="#EEEEFF" nowrap>
			<b>氏名</b>
			<div>
			<span style="color: #FFF;background-color: #ff0044;font-size: 10px;padding: 2px 7px 2px;border-radius: 6px;">必須</span>
			<span style="font-size: 12px;color: #a8411e;">&nbsp;</span>
			</div>
		</td>
		<td bgcolor="#EEEEFF" nowrap>
			<b>電話番号</b>
			<div>
			<span style="color: #FFF;background-color: #ff0044;font-size: 10px;padding: 2px 7px 2px;border-radius: 6px;">必須</span>
			<span style="font-size: 12px;color: #a8411e;">[半角数字]</span>
			</div>
		<td bgcolor="#EEEEFF" nowrap>
			<b>FAX</b>
			<div>
			<span style="color: #FFF;background-color: #4B4B50;font-size: 10px;padding: 2px 7px 2px;border-radius: 6px;">任意</span>
			<span style="font-size: 12px;color: #a8411e;">[半角数字]</span>
			</div>
		</td>
	</tr>
    {!! Form::open(['route' => ['ctl.htl_mail_list.edit'], 'method' => 'get']) !!}
	<tr>
		<td nowrap class="hotel_person">施設担当者様</td>
		<td nowrap class="hotel_person">
			<input id="Hotel_Person_person_email" type="text" name="Hotel_Person[person_email]" value="{{old('Hotel_Person.person_email' , strip_tags($hotel_person['person_email']))}}" size="32" maxlength="50" class="required" placeholder="施設担当者様のメールアドレス">
		</td>
		<td nowrap class="hotel_person" ><input type="text" name="Hotel_Person[person_post]" value="{{old('Hotel_Person.person_post' , strip_tags($hotel_person['person_post']))}}" size="20" maxlength="32"  placeholder="施設担当者様の部署･役職"></td>
		<td nowrap class="hotel_person"><input type="text" name="Hotel_Person[person_nm]" value="{{old('Hotel_Person.person_nm' , strip_tags($hotel_person['person_nm']))}}" size="20" maxlength="32" class="required" placeholder="施設担当者様の氏名"><small>様</small></td>
		<td nowrap class="hotel_person"><input type="text" name="Hotel_Person[person_tel]" value="{{old('Hotel_Person.person_tel' , strip_tags($hotel_person['person_tel']))}}" size="18" maxlength="15" class="required" placeholder="施設担当者様の電話番号"></td>
		<td nowrap class="hotel_person"><input type="text" name="Hotel_Person[person_fax]" value="{{old('Hotel_Person.person_fax' , strip_tags($hotel_person['person_fax']))}}" size="18" maxlength="15" placeholder="施設担当者様のFAX番号"></td>
        @if(count($extend_setting_count) > 0 && $extend_setting['email_notify'] == 1)
    		<td nowrap rowspan="4">
        @else
		    <td nowrap rowspan="3">
        @endif

			<input type="submit" value="更新" id="btn_update_person">
			<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
			<input type="hidden" name="customer[customer_id]" value="{{strip_tags($customer['customer_id'])}}">

		</td>
		<td class="hotel_person" ><small>施設担当様のご連絡先です。<br><font color="red">予約通知先のFAX番号の変更については、イオンコンパス（株）までご連絡ください。</font></small></td>
	</tr>

	<tr>
		<td nowrap rowspan="2" class="customer">請求担当者様</td>
		<td nowrap rowspan="2" class="customer"><input type="text" name="customer[email]" value="{{old('customer.email' , strip_tags($customer['email']))}}" size="32" maxlength="50" class="required" placeholder="請求担当者様のメールアドレス"></td>

		<td nowrap class="customer"><input type="text" name="customer[person_post]" value="{{old('customer.person_post' , strip_tags($customer['person_post']))}}" size="20" maxlength="32" placeholder="請求担当者様の部署･役職"></td>

		<td nowrap class="customer"><input type="text" name="customer[person_nm]" value="{{old('customer.person_nm' , strip_tags($customer['person_nm']))}}" size="20" maxlength="32" class="required" placeholder="請求担当者様の氏名"><small>様</small></td>
		<td nowrap class="customer"><input type="text" name="customer[tel]" value="{{old('customer.tel' , strip_tags($customer['tel']))}}" size="18" maxlength="15" class="required" placeholder="請求担当者様の電話番号"></td>
		<td nowrap class="customer"><input type="text" name="customer[fax]" value="{{old('customer.fax' , strip_tags($customer['fax']))}}" size="18" maxlength="15" placeholder="請求担当者様のFAX番号"></td>
		<td rowspan="2" class="customer"><small><p>請求担当者様のご連絡先と請求書の送付先です。</p></small></td>
	</tr>

	<tr>
		<td nowrap colspan="4" class="customer">

		<p style="margin-bottom: 0px;"><small>[請求書発送先]</small></p>
		<div style="padding: 5px;">
		〒{{strip_tags($customer['postal_cd'])}}&nbsp;
		{{strip_tags($a_customer_pref['pref_nm'])}}{{strip_tags($customer['address'])}}<br>
		{{strip_tags($customer['customer_nm'])}}<br>
		<input type="text" name="customer[section_nm]" value="{{old('customer.section_nm' , strip_tags($customer['section_nm']))}}" size="45" maxlength="32" placeholder="[会社名]以降の請求書の宛名を入力してください"  class="required"><small>様</small>
		</div>

		<input type="hidden" name="customer[postal_cd]" value="{{strip_tags($customer['postal_cd'])}}">
		<input type="hidden" name="customer[customer_nm]" value="{{strip_tags($customer['customer_nm'])}}">
		<input type="hidden" name="a_customer_pref[pref_nm]" value="{{strip_tags($a_customer_pref['pref_nm'])}}">
		<input type="hidden" name="customer[address]" value="{{strip_tags($customer['address'])}}">
		<small><p>※会社名・住所の変更が必要な場合は、イオンコンパス（株）までご連絡ください。</p></small>
		</td>
	</tr>

    {{-- 自動延長確認 --}}
    @if(count($extend_setting_count) > 0 && $extend_setting['email_notify'] == 1)
        <input type="hidden" name="extend_setting[email_notify]" value="{{strip_tags($extend_setting['email_notify'])}}">
        <tr>
            <td nowrap>自動延長確認</td>
            <td nowrap><input type="text" name="extend_setting[email]" value="{{old('extend_setting.email' , strip_tags($extend_setting['email']))}}" size="32" maxlength="50" placeholder="自動延長確認のメールアドレス" class= "required"></td>
            <td nowrap colspan="4">
                <select name="extend_setting[email_type]">
                    <option value="{{old('extend_setting.email_type' , 0)}}" @if($extend_setting['email_type'] == '0') selected @endif>詳細なメール文章</option>
                    <option value="{{old('extend_setting.email_type' , 1)}}" @if($extend_setting['email_type'] == '1') selected @endif>簡易なメール文章</option>
                </select>
            </td>
            <td><small>販売自動延長後に指定したメールアドレスへ連絡します。</small></td>
        </tr>
    {{ Form::close() }}


@endif
{{ Form::close() }}


{{-- 予約通知 --}}
@foreach($notify_device as $notify_device)
    @if($notify_device == 2)
        <tr>
            <td nowrap>予約通知</td>
            <td nowrap>{{strip_tags($hotel_notify->notify_email)}}</td>
            <td nowrap colspan="4"><small>※変更が必要な場合は、ベストリザーブ・宿ぷらざ事務局にご連絡ください。</small></td>
            <td nowrap></td>
            <td><small>宿泊予約時に指定したメールアドレスへ連絡します。</small></td>
        </tr>
    @endif
@endforeach

{{-- 口コミ投稿通知 --}}
@if(count($a_alert_mail_voice['values']) == 0)
	<tr>
		<td nowrap>口コミ投稿通知</td>
		<td nowrap>(未設定)</td>
		<td nowrap colspan="4"></td>
		<td nowrap>
            {!! Form::open(['route' => ['ctl.htl_alert_mail_voice.list'], 'method' => 'get']) !!}
				<input type="submit" value="変更">
				<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
            {!! Form::close() !!}
        </td>
		<td><small>口コミ投稿時に指定したメールアドレスへ連絡します。</small></td>
	</tr>
@else
    @foreach($a_alert_mail_voice['values'] as $alert_mail_voice)
        @if($loop->first)
			<tr>
				<td nowrap rowspan="{{count($a_alert_mail_voice['values'])}}">口コミ投稿通知</td>
				<td nowrap>
                    {{strip_tags($alert_mail_voice->email)}}
                    @if($alert_mail_voice->email_notify == 0)
                        (送信しない)
                    @endif
				</td>
				<td nowrap rowspan="{{count($a_alert_mail_voice['values'])}}"  colspan="4"></td>
				<td nowrap rowspan="{{count($a_alert_mail_voice['values'])}}">
                    {!! Form::open(['route' => ['ctl.htl_alert_mail_voice.list'], 'method' => 'get']) !!}
						<input type="submit" value="変更">
						<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                    {!! Form::close() !!}
                </td>
				<td rowspan="{{count($a_alert_mail_voice['values'])}}"><small>口コミ投稿時に指定したメールアドレスへ連絡します。</small></td>
			</tr>
        @else
			<tr>
				<td nowrap>
                    {{strip_tags($alert_mail_voice->email)}}
                    @if($alert_mail_voice->email_notify == 0)
					    <small>(送信しない)</small>
                    @endif
				</td>
			</tr>
        @endif
	@endforeach
@endif

{{-- 満室通知 --}}
@if(count($a_alert_mail_hotel['values']) == 0)
		<tr>
			<td nowrap>満室通知</td>
			<td nowrap>(未設定)</td>
			<td nowrap colspan="4"></td>
			<td nowrap>
                {!! Form::open(['route' => ['ctl.htl_alert_mail_hotel.list'], 'method' => 'get']) !!}
					<input type="submit" value="変更">
					<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                {!! Form::close() !!}
			</td>
			<td> <small>満室時に指定したメールアドレスへ連絡します。</small></td>
		</tr>

@else
    @foreach($a_alert_mail_hotel['values'] as $alert_mail_hotel)
		@if($loop->first)
			<tr>
				<td nowrap rowspan="{{count($a_alert_mail_hotel['values'])}}">満室通知</td>
				<td nowrap>
                    {{strip_tags($alert_mail_hotel->email)}}
                    @if($alert_mail_hotel->email_notify == 0)
					    (送信しない)
                    @endif
				</td>
				<td nowrap rowspan="{{count($a_alert_mail_hotel['values'])}}"  colspan="4"></td>
				<td nowrap rowspan="{{count($a_alert_mail_hotel['values'])}}">
                    {!! Form::open(['route' => ['ctl.htl_alert_mail_hotel.list'], 'method' => 'get']) !!}
						<input type="submit" value="変更">
						<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
                    {!! Form::close() !!}
                </td>
				<td rowspan="{{count($a_alert_mail_hotel['values'])}}"><small>満室時に指定したメールアドレスへ連絡します。</small></td>
			</tr>
        @else
			<tr>
				<td nowrap>
                    {{strip_tags($alert_mail_hotel->email)}}
					@if($alert_mail_hotel->email_notify == 0)
                        <small>(送信しない)</small>
                    @endif
				</td>
			</tr>
		@endif
	@endforeach
@endif

</table>
<hr size=1>
</div>
@endsection