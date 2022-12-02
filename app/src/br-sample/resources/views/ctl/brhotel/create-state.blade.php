{{-- MEMO: 移植元 public\app\ctl\views\brhotel\createstate.tpl --}}

{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設状態登録情報　STEP6/6"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/show/">

  {include file=$v->env.module_root|cat:'/views/brhotel/_info_state_form.tpl'}

<INPUT TYPE="submit" VALUE="詳細変更へ">

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}