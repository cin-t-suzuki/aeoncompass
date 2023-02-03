@extends('ctl.common.base')
@section('title', '会員コード・パスワード案内')

@section('page_blade')

{{--削除でいいか？ {literal} --}}
<!-- Style Sheet : print_style_sheet -->
<style type="text/css">
  .proto    { font-size: 10pt; font-family: Arial; }
  .proto td { font-size: 10pt; font-family: Arial; }
  .proto th { font-size: 10pt; font-family: Arial; font-weight: normal; }
  .small td { font-size: 8pt; font-family: Arial; }
  .proto .caption { background-color: #EEFFEE; }
</style>
<!-- / Style Sheet : print_style_sheet -->
{{--削除でいいか？ {/literal} --}}

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

<div class="proto">
  下記項目が完全一致で該当する登録会員全てに対して、登録メールアドレス宛てに会員コード・パスワードを案内メールを通知いたします。
</div>
<p>
<table class="proto" border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td class="caption" nowrap>氏</td>
    <td nowrap>
      {{strip_tags($family_nm)}}
    </td>
  </tr>
  <tr>
    <td class="caption" nowrap>名</td>
    <td nowrap>
      {{strip_tags($given_nm)}}
    </td>
  </tr>
  <tr>
    <td class="caption" nowrap>生年月日</td>
    <td nowrap>{{strip_tags($birth_ymd)}}</td>
  </tr>
  <tr>
    <td class="caption" nowrap>電話番号</td>
    <td nowrap>{{strip_tags($tel)}}</td>
  </tr>
</table>


{!! Form::open(['route' => ['ctl.brremindermember.send'], 'method' => 'get']) !!}
  <input type="hidden" name="family_nm" value="{{strip_tags($family_nm)}}" />
  <input type="hidden" name="given_nm"  value="{{strip_tags($given_nm)}}" />
  <input type="hidden" name="tel"       value="{{strip_tags($tel)}}" />
  <input type="hidden" name="birth_ymd" value="{{strip_tags($birth_ymd)}}" />
  {{--以下書き換えあっているか？ {assign var=tracking_id value=$v->config->system->cookie->tracking_id_name} --}}
  {{-- <input type="hidden" name="sfck" value="{$smarty.cookies.$tracking_id}.{$smarty.now}" /> --}}
  @php $tracking_id = 'BGQ'; /* 元ソースのconfigから */ @endphp
  <input type="hidden" name="sfck" value="{{Cookie::get($tracking_id);}}.{{time()}}" />
<div style="margin:1em auto;">
  <input type="submit" value="通知実行" />
</div>
{!! Form::close() !!}
</p>

<br>
@endsection