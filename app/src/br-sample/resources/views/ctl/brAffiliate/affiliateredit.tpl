{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="アフィリエイター情報編集"}
{* header end *}
<form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/details/">
  <td nowrap>
    <input type="submit" value="編集キャンセル">
    <input type="hidden" name="affiliater_cd" value={$v->helper->form->strip_tags($v->assign->affiliater_value.affiliater_cd)} />
  </td>
</form>
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
<table border="1" cellpadding="4" cellspacing="0">
  <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/affiliaterupdate/">

      {* アフィリエイト編集 フォーム *}
      {include file=$v->env.module_root|cat:'/views/braffiliate/_input_affiliater_edit_form.tpl'}
      
      <input type="hidden" name="affiliater_cd" value="{$v->assign->affiliater_cd}">
      <td nowrap colspan="2" align="center"><input type="submit" value="変更実行"></td>  
    </tr>
  </form>
</table>	

<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}
