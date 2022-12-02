{{-- MEMO: 移植元 public\app\ctl\views\brhotel\state.tpl --}}

{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設状態情報　STEP5/6"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/createstate/">

  {include file=$v->env.module_root|cat:'/views/brhotel/_input_state_form.tpl'}

<INPUT TYPE="submit" VALUE="施設状態登録">
※は必須です。

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}