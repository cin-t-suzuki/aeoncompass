{{-- MEMO: 移植元 public\app\ctl\views\brhotel\create.tpl --}}

{* header start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="施設登録情報　STEP2/6"}
{* header end *}

{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}

<form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/management/">

  {include file=$v->env.module_root|cat:'/views/brhotel/_info_hotel_form.tpl'}

<input type="submit" value="施設管理情報登録へ">

</form>

<hr size="1">
<form method="post" action="{$v->env.source_path}{$v->env.module}/brhotel/">
<small>
  <input type="submit" value="施設情報メインへ">
</small>
</form>
<br>

{* footer start *}
	{include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}