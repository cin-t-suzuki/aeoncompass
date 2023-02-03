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
  全ての項目が完全一致で該当する登録会員全てに対して、登録メールアドレス宛てに会員コード・パスワードを案内します。
</div>

{!! Form::open(['route' => ['ctl.brremindermember.search'], 'method' => 'get']) !!}
<p><table class="proto" border="1" cellpadding="4" cellspacing="0">
  <tr>
    <td class="caption" nowrap>氏</td>
    <td nowrap>
      <input type="text" size="25"  name="family_nm" value="{{strip_tags($family_nm)}}" />
    </td>
  </tr>
  <tr>
    <td class="caption" nowrap>名</td>
    <td nowrap>
      <input type="text" size="25" name="given_nm" value="{{strip_tags($given_nm)}}" />
    </td>
  </tr>
  <tr>
    <td class="caption" nowrap>生年月日</td>
    <td nowrap><input type="text" size="25"  name="birth_ymd" value="{{strip_tags($birth_ymd)}}" />（2000-01-01）</td>
  </tr>
  <tr>
    <td class="caption" nowrap>電話番号</td>
    <td nowrap><input type="text" size="25"  name="tel" value="{{strip_tags($tel)}}" />（000-0000-0000）</td>
  </tr>
</table></p>
<p><input type="submit" value="　検索　" /></p>
{!! Form::close() !!}


<br>
@endsection