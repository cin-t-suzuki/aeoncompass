{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設管理情報更新"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

{* 施設情報詳細 *}
{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_info.tpl'}
<br>

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/updatemanagement/">

  {include file=$v->env.module_root|cat:'/views/brhotel/_input_management_form.tpl'}

<INPUT TYPE="submit" VALUE="施設管理情報更新">
※は必須です。

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{include file=$v->env.module_root|cat:'/views/brhotel/_log_hotel_person_form.tpl'}

<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}