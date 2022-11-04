{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\updatemanagement.tpl --}}
{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設管理更新情報"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

{* 施設情報詳細 *}
{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_info.tpl'}
<br>

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/show/">

  {include file=$v->env.module_root|cat:'/views/brhotel/_info_management_form.tpl'}

<INPUT TYPE="submit" VALUE="詳細変更へ">

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}