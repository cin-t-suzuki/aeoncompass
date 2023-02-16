{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="グループ登録"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

{* 入力フォーム *}
{include file=$v->env.module_root|cat:'/views/brsupervisor/_info.tpl'}

<div align="right">
  <small>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/brsupervisor/list/">
      <input type="submit" value="グループ一覧へ">
      <input type="hidden" name="supervisor_cd" value="{$v->helper->form->strip_tags($v->assign->supervisor_cd)}">
    </form>
  </small>
</div>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}
