{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\brhotel\editsurvey.tpl --}}

{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設測地更新"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

{* 施設情報詳細 *}
{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_info.tpl'}
<br>

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/updatesurvey/">

  {include file=$v->env.module_root|cat:'/views/brhotel/_input_survey_form.tpl'}

<INPUT TYPE="submit" VALUE="施設測地更新">

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}
<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}