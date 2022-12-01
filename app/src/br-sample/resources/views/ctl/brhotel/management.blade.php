{{-- MEMO: 移植元 public\app\ctl\views\brhotel\management.tpl --}}

{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設管理情報　STEP3/6"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<FORM method="POST" action="{$v->env.source_path}{$v->env.module}/brhotel/createmanagement/">

{* new_flg はここで使う。 $status = 'new' は、ここからは設定されている *}
  {include file=$v->env.module_root|cat:'/views/brhotel/_input_management_form.tpl' new_flg = 1}

<INPUT TYPE="submit" VALUE="施設管理情報登録">
※は必須です。

</FORM>

{include file=$v->env.module_root|cat:'/views/brhotel/_hotel_top_form.tpl'}

<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}