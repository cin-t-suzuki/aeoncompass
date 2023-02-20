{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="アフィリエイター新規登録終了"}
{* header end *}
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/">
      <td>
        <input type="submit" value="アフィリエイト管理TOPへ戻る">
      </td>
    </form>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/details/">
      <td>
        <input type="submit" value="続けてプログラムを登録する">
        <input type="hidden" name="affiliater_cd" value="{$v->helper->form->strip_tags($v->assign->affiliater.affiliater_cd)}">
      </td>
    </form>
  </tr>
</table>
<br />
アフィリエイターの登録が完了しました。<br />
<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}