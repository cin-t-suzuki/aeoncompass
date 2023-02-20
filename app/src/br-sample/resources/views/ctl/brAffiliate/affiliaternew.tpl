{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="アフィリエイター情報編集"}
{* header end *}
<form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/">
  <td nowrap>
    <input type="submit" value="アフィリエイト管理TOPへ戻る">
  </td>
</form>
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
<table border="1" cellpadding="4" cellspacing="0">
  <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/affiliatercreate/">

      {* アフィリエイト編集 フォーム *}
      {include file=$v->env.module_root|cat:'/views/braffiliate/_input_affiliater_new_form.tpl'}

      <td nowrap colspan="2" align="center"><input type="submit" value="登録実行"></td>  
    </tr>
  </form>
</table>	

<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}