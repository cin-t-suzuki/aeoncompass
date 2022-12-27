@extends('ctl.common.base')
@section('title', '口座振替　追加処理')
@inject('service', 'App\Http\Controllers\ctl\BrAdditonalZenginController')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)


{{--削除でいいか？ {literal} --}}
<script type="text/javascript">
	$(document).ready(function () {

		//{/literal}{{--
		//===================================================================
		// 理由が変更されたとき
		//===================================================================*
		//--}}{literal}
		$('textarea[name="reason"]').change(function() {
			chk_reason(this);
		});
		//{/literal}{{--
		//===================================================================
		// 理由が変更されたとき
		//===================================================================*
		//--}}{literal}
		$('textarea[name="reason_internal"]').change(function() {
			chk_reason_internal(this);
		});
		//{/literal}{{--
		//===================================================================
		// 金額が変更されたとき
		//===================================================================*
		//--}}{literal}
		$('input[name="additional_charge"]').change(function() {
			chk_additional_charge(this);
		});
		//{/literal}{{--
		//===================================================================
		// 登録ボタンが押下されたとき
		//===================================================================*
		//--}}{literal}
		$('#btn_create').click(function() {
			var chk1 = true;
			var chk2 = true;
			var chk3 = true;
			chk1 = 	chk_additional_charge('input[name="additional_charge"]');
			chk2 = chk_reason('textarea[name="reason"]');
			chk3 = chk_reason_internal('textarea[name="reason_internal"]');

			if(!chk1 || !chk2|| !chk3){return false;}
		});
	});
	//{/literal}{{--
	//===================================================================
	// 理由欄入力チェック
	//===================================================================*
	//--}}{literal}
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
	//{/literal}{{--
	//===================================================================
	// 備考欄入力チェック
	//===================================================================*
	//--}}{literal}
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

	//{/literal}{{--
	//===================================================================
	// 金額欄入力チェック
	//===================================================================*
	//--}}{literal}
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


  <strong>理由・金額の入力</strong><br>
 対象の施設への口座振替の追加金額と理由の入力を行う。

<table cellspacing="0" cellpadding="2" border="1">
  <tr>
    <td bgcolor="#EEFFEE" nowrap>登録状態</td>
    <td bgcolor="#EEFFEE" nowrap>施設コード<br>施設名</td>
    <td bgcolor="#EEFFEE" nowrap>精算先名称</td>
    <td bgcolor="#EEFFEE" nowrap>送客請求実績</td>
  </tr>

  @foreach ($views->hotel_list['values'] as $hotel_list)
  <tr>
    <td nowrap>
      @if ($hotel_list['entry_status'] == 0)
        公開中
      @elseif ($hotel_list['entry_status'] == 1)
        登録作業中<br>
      @elseif ($hotel_list['entry_status'] == 2)
        解約
      @endif<br />
      @if ($hotel_list['accept_status'] == 1)
        [受付中]
      @elseif ($hotel_list['accept_status'] == 0)
        <font color="#ff0000">[停止中]</font>
      @endif
    </td>

    <td nowrap>
      {{$hotel_list['hotel_cd']}}<br>
      <a href="http://{$v->config->system->rsv_host_name}/hotel/{{$hotel_list['hotel_cd']}}/" target="_blank" style="text-decoration: none; color:#000066;">{{$hotel_list['hotel_nm']}}@if (!$service->is_empty($hotel_list['hotel_old_nm']))（{{$hotel_list['hotel_old_nm']}}）</font>@endif</a>
      @if ($hotel_list['stock_type'] == 1)
        <font color="#0000ff">[買]</font>
      @endif
      @if (!$service->is_empty($hotel_list['pref_nm']))
        （{{$hotel_list['pref_nm']}}）</font>
      @endif
    </td>

    <td>
    @if (!$service->is_empty($hotel_list['customer_id']))
      ({{$hotel_list['customer_id']}}){{$hotel_list['customer_nm']}}
    @else
    	未設定
    @endif
    </td>

    <td nowrap align="middle">
    @if (!$service->is_empty($hotel_list['customer_id']))
        @if (!$hotel_list['factoring_flg'])
          <font color="#ff0000">未設定</font>
        @endif
		{{-- TODO htldemand作成後に遷移先設定 --}}
      <form action="{$v->env.source_path}{$v->env.module}/htldemand/" method="post" target= "_blank">
        <input type="submit" value="詳細情報">
        <input type="hidden" name="target_cd" value="{{$hotel_list['hotel_cd']}}" />
      </form>
    @endif

    </td>

  </tr>
  @endforeach
</table>


@foreach ($views->hotel_list['values'] as $hotel_list)
<table cellspacing="0" cellpadding="2" border="1" style="margin-top: 10px;min-width: 380px;">
  <tr>
    {{-- 以下factoring_bank関連の値すべて??null追記でいいか --}}
    <tr><td bgcolor="#eeffee" colspan="3">引落銀行</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">銀行コード</td><td>{{strip_tags($hotel_list['factoring_bank_cd'] ?? null)}} : {{strip_tags($hotel_list['factoring_bank_nm'] ?? null)}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">支店コード</td><td>{{strip_tags($hotel_list['factoring_bank_branch_cd'] ?? null)}} : {{strip_tags($hotel_list['factoring_bank_branch_nm'] ?? null)}}</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座種別</td><td>
          @if (($hotel_list['factoring_bank_account_type'] ?? null) == 1)普通
      @elseif (($hotel_list['factoring_bank_account_type'] ?? null) == 2)当座
      @elseif (($hotel_list['factoring_bank_account_type'] ?? null) == 4)貯蓄
      @elseif (($hotel_list['factoring_bank_account_type'] ?? null) == 9)その他
          @endif</td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座番号</td><td>{{strip_tags($hotel_list['factoring_bank_account_no'] ?? null)}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落口座名義</td><td>{{strip_tags($hotel_list['factoring_bank_account_kn'] ?? null)}}<br /></td></tr>
  <tr><td bgcolor="#eeffee" colspan="2">引落顧客番号</td><td>{{strip_tags($hotel_list['factoring_cd'] ?? null)}}<br /></td></tr>
@endforeach

  </tr>

</table>




{{ Form::open(['route' => 'ctl.brAdditionalZengin.create', 'method' => 'post']) }}
<table cellspacing="0" cellpadding="2" border="1" style="margin-top: 30px;">

  <tr><td bgcolor="#eeffee" colspan="2">引落追加情報</td></tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>引落日</td>
    <td nowrap>
              <select size="1" name="year">
            {{--書き換え合っている？ {if $v->helper->date->set($v->assign->reserve_select_year)}{/if} --}}
            @php        
            if (!$service->is_empty($views->reserve_select_year)) {
              $date_Y = date('Y', strtotime($views->reserve_select_year));
            } else {
              $date_Y = null;
            }
            @endphp
            {{--書き替えはforで合っているか？ {section name = year start = 0 loop = $v->assign->s_cnt} --}}
            @for ($y = 0; $y <= $views->s_cnt; $y++) 
              {{--書き替え以下であっている？ <option value="{{$v->helper->date->to_format('Y')}" --}}
              <option value="{{$date_Y}}"
                {{-- $views->searchでの取得データ、初期表示では値がないためnull追記でいいか --}}
              @if (!$service->is_empty($views->search['year'] ?? null))
                {{--書き替え以下であっている？ {if $v->helper->date->to_format('Y') == $v->assign->search.year} --}}
                @if ($date_Y == $views->search['year'] ?? null)
                  selected="selected"
                @endif
              @else
                {{--書き換え以下で合っている？ {if $v->helper->date->to_format('Y') == $smarty.now|date_format:"%Y"} --}}
                @if ($date_Y == date('Y'))
                  selected="selected"
                @endif
              @endif
              >
              {{--書き替え以下であっている？？ {$v->helper->form->strip_tags($v->helper->date->to_format('Y'))} --}}
              {{strip_tags($date_Y)}}
              {{--書き替え以下であっている？？ {if $v->helper->date->add('y',1)}{/if} --}}
              @php        
              if (!$service->is_empty($date_Y)) {
                $date_Y = $date_Y + 1;
              } 
              @endphp
              </option>
            @endfor
          </select>&nbsp;年
            <select size="1" name="month">
              {{-- 月表示のための12回ループ --}}
              {{--書き換えはforで合っているか？ {section name = month start = 1 loop = 13} --}}
              @for($m = 1; $m < 13; $m++)
                {{--書き替え以下であっている？？ <option value="{$v->helper->form->strip_tags($smarty.section.month.index)|string_format:"%02d"}" --}}
                <option value="{{sprintf('%02d',strip_tags($m))}}"
                {{-- $views->searcでの取得データ、初期表示では値がないためnull追記でいいか --}}
                @if (!$service->is_empty($views->search['month']))
                  {{--書き替え以下であっている？？ {if ($smarty.section.month.index|string_format:"%02d" == $v->assign->search.month) --}}
                  @if (sprintf('%02d',$m) == $views->search['month'] ?? null)
                    selected="selected"
                  @endif
                {{--書き替え以下であっている？？ {elseif ($smarty.section.month.index|string_format:"%02d" == $smarty.now|date_format:'%m') --}}
                @elseif (sprintf('%02d',$m) == date('m'))
                  selected="selected"
                @endif>
                {{--書き替え以下であっている？？ {$v->helper->form->strip_tags($smarty.section.month.index)|string_format:"%02d"} --}}
                {{sprintf('%02d', strip_tags($m))}}
                </option>
              @endfor
            </select>&nbsp;月&nbsp;23日
    </td>
  </tr>

  <tr>
    <td bgcolor="#EEFFEE" nowrap>追加金額</td>
    <td nowrap>
    <div style="color: #f00;" id="msg_additional_charge"></div>
    <input maxlength="20" name="additional_charge" class="num" value="{{$views->additional_charge}}">
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>理由<br>(施設向け)<br>1000文字まで</td>
    <td nowrap>
    <div style="color: #f00;" id="msg_reason"></div>
    <textarea name="reason" cols="100" rows="6">{{$views->reason}}</textarea>
    </td>
  </tr>
  <tr>
    <td bgcolor="#EEFFEE" nowrap>備考<br>(内部のみ)<br>1000文字まで</td>

    <td nowrap>
    <div style="color: #f00;" id="msg_reason_internal"></div>
    <textarea name="reason_internal" cols="100" rows="6">{{$views->reason_internal}}</textarea>
    </td>
  </tr>
</table>
    <input type="submit" value="登録" style="width: 100px;height: 25px;margin: 10px 335px;" id="btn_create">
    <input type="hidden" name="hotel_cd" value="{{$hotel_list['hotel_cd']}}" />
    <input type="hidden" name="customer_id" value="{{$hotel_list['customer_id']}}" />
  {{ Form::close() }}

</div>

@endsection