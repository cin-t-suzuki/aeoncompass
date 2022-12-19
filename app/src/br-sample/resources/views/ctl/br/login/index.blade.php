{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="ログイン"}
<center>
<form action="{$v->env.source_path}{$v->env.module}/brlogin/login/" method="POST">
<p>
  ベストリザーブ社内管理　ログイン画面
</p>
{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td>ＩＤ</td>
    <td colspan="2"><input type="text" name="account_id" value="{$v->assign->account_id}" size="25" maxlength="60"></td>
  </tr>
  <tr>
    <td>パスワード</td>
    <td colspan="2"><input type="password" name="password" value="" size="25"></td>
  </tr>
</table>
<p>
<INPUT TYPE="submit" VALUE="ログイン">
</p>
</FORM>
</center>
{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
