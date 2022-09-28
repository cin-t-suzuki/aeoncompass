{{-- header start --}}
@section('title', '管理画面一覧')
@extends('ctl.common.base')
{{-- header end --}}


<table border="3" cellspacing="0" cellpadding="2"><tr><td  bgcolor="#EEEEFF"  align="center">
<big>管理画面一覧</big></td></tr></table><br>
<table border="0" cellspacing="12" cellpadding="8" width="600">
<tr><td valign="top">
<table border="1" cellspacing="0" cellpadding="4">

  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/brlogin/">
    <td nowrap width="100%">社内管理ログイン</td>
    <td nowrap>
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>


  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/htllogin/">
    <td nowrap width="100%">施設管理ログイン</td>
    <td nowrap>
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>

  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/mbllogin/">
    <td nowrap width="100%">施設管理モバイルメニュー</td>
    <td nowrap>
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>

  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/svrlogin/">
    <td nowrap width="100%">施設統括管理メニュー</td>
    <td nowrap>
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>

  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/ptnlogin/">
    <td nowrap width="100%">提携先管理メニュー</td>
    <td nowrap>
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>
  
  <tr>
    <form method="post" action="{$v->env.source_path}{$v->env.module}/aftlogin/">
    <td nowrap="nowrap" width="100%">アフィリエイト管理メニュー</td>
    <td nowrap="nowrap">
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>

  <tr>
    <form method="post" action="{$v->env.source_path}{$v->env.module}/pbklogin/">
    <td nowrap="nowrap" width="100%">実績管理メニュー</td>
    <td nowrap="nowrap">
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>

  <tr>
    <form method="post" action="{$v->env.source_path}{$v->env.module}/ntalogin/">
    <td nowrap="nowrap" width="100%">NTA専用管理ログイン</td>
    <td nowrap="nowrap">
      <input type="submit" value=" 確認 ">
    </td>
    </form>
  </tr>

</table>

</table>
<td></tr>
</table>

{{-- footer start --}}
	{{--TODO include file=$v->env.module_root|cat:'/views/_common/_footer.tpl'}}
{{-- footer end --}}