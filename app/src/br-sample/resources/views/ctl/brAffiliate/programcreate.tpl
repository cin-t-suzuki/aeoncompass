{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="プログラム登録終了"}
{* header end *}
<br>
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<br>
<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/">
      <td>
        <input type="submit" value="アフィリエイト管理TOPへ戻る">
      </td>
    </form>
  </tr>
</table>

<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}