{{-- MEMO: 移植元 public\app\rsv\view2\error\500.tpl --}}

<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <meta http-equiv="Content-Language" content="ja">
   <META http-equiv="Pragma" content="no-cache">
   <title>株式会社ベストリザーブ</title>
{literal}
<script language="JavaScript" type="text/javascript"><!--
if(navigator.platform){
  if(navigator.platform.charAt(0)=='W'){
    if(navigator.userAgent.indexOf("MSIE")>-1){
      document.write('<style type="text/css"><!-- body, td, th { font-size:80% } --><'+'/style>')
    } else if(navigator.userAgent.indexOf("Netscape6")>-1){
      document.write('<style type="text/css"><!-- body, td, th { font-size:73%; font-family: sans-serif; } --><'+'/style>')
    }
  }
}
// -->
</script>
{/literal}
</head>
<body text="#000000" bgcolor="#FFFFFF">
&nbsp;
<center><table BORDER=0 CELLSPACING=0 CELLPADDING=4 WIDTH="540" >
<tr>
<td><a href="{$v->env.base_path}"><img alt="ベストリザーブ" src="/images/logo.gif" border="0" width="136" height="52" hspace="8"></a></td>
</tr>

<tr>
<td>
<br><big>ただいまメンテナンス中です。しばらくお待ち下さい。</big>
<br></td>
</tr>

<tr>
<td ALIGN=RIGHT>株式会社ベストリザーブ</td>
</tr>
</table></center>

{* 開発の場合エラーメッセージ表示 *}
{if $v->config->environment->status != 'product'}
<div style="background-color:#eee">
{foreach from=$v->error->gets() item=error name=errors}
  {if $smarty.foreach.errors.first}
    <br />
    PHP ERROR:<br />
    =============<br />
  {/if}
  {$error}<br />
{/foreach}
</div>
{/if}

</body>
</html>

