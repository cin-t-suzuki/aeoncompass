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
</p>


<br>
@endsection