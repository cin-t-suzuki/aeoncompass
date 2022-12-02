{{-- MEMO: 移植元 public\app\ctl\views\brhotel\createmanagement.tpl --}}

{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設管理登録情報　STEP4/6"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

{if $v->assign->hotel_notify|@count == 0}
  <FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/state/">
{else}
  <FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/show/">
{/if}

  {include file=$v->env.module_root|cat:'/views/brhotel/_info_management_form.tpl'}


{if $v->assign->hotel_notify|@count == 0}
  <INPUT TYPE="submit" VALUE="施設状態登録へ">
{else}
  <INPUT TYPE="submit" VALUE="詳細変更へ">
{/if}

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}