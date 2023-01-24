{{-- MEMO: 移植元 public\app\ctl\views\htlhotel\edit.tpl --}}
@extends('ctl.common._htl_base')
@section('title', '施設情報登録内容の変更')
@inject('service', 'App\Http\Controllers\ctl\HtlHotelController')

@section('content')

<a href="{{ route('ctl.htl_top.index', ['target_cd' => $target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route('ctl.htl_top.show', ['target_cd' => $target_cd]) }}">施設情報詳細</a>&nbsp;&gt;&nbsp;
施設情報登録内容の変更
<br>
{{-- メッセージ --}}
@include('ctl.common.message')

<br>
{{ Form::open(['route' => 'ctl.htl_hotel.update', 'method' => 'post']) }}
<INPUT TYPE="hidden" NAME="target_cd" VALUE="{{strip_tags($target_cd)}}">

<font color="#0000FF">変更終了後、ページ右下の「更新する」を押して下さい。</font>ホテル情報ページが変更後の内容に変わります。

@include('ctl.htlHotel._input_form')

<input type="submit" value="変　　　　　更">
{{ Form::close() }}

<table border="0" cellpadding="4" cellspacing="0">

  <tr>
    <td><small>※1：</small></td>
    <td><small>修正は『ＩＤとパスワードの変更』メニューから行ってください。</small></td>
    <form action="{$v->env.source_path}{$v->env.module}/htlchangepass/" method="POST">
    <td><small><input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
    </td>
    </form>
  </tr>

  <tr>
    <td><small>※2：</small></td>
    <td><small>修正は『リンクページ』メニューから行ってください。</small></td>
    <form action="{$v->env.source_path}{$v->env.module}/htlhotellink/list/" method="POST">
    <td><small><input type="submit" value="リンクページへ"></small>
      <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
    </td>
    </form>
  </tr>
  
  <tr>
    <td><small>※3：</small></td>
    <td><small>修正する場合は<a href="mailto:{$v->config->environment->mail->from->opc}">{$v->config->environment->mail->from->opc}</a>までご連絡下さい。</small></td>
    <td></td>
  </tr>
  
</table>

<br>
<!--
<hr size="1">

<div align="right">
<FORM ACTION="{$v->env.source_path}{$v->env.module}/htlhotel/staticupdate/" METHOD="POST" target="page_test">
情報ページHTML
<small>
<INPUT TYPE="submit" VALUE="更新する">
<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}">
<input type="hidden" name="redirect_url" value="http://{$v->config->system->rsv_host_name}/hotel/{$v->helper->form->strip_tags($v->assign->target_cd)}/">
</small>
<br>
<font color="#FF0000">各項目の変更終了後に更新して下さい。</font></FORM>
</div>
-->
<br>
@endsection