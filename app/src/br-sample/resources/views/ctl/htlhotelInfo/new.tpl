{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title = '施設情報'}

{* サブメニュー *}
<a href="{$v->env.source_path}{$v->env.module}/htltop/index/target_cd/{$v->assign->target_cd}">メインメニュー</a>&nbsp;&gt;&nbsp;<a href="{$v->env.source_path}{$v->env.module}/htlhotel/show/target_cd/{$v->assign->hotelinfos.hotel_cd}">施設情報詳細</a>&nbsp;&gt;&nbsp;新規
<br>
<br>
{* メッセージ *}
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
<br>
{* 登録 *}
<form action="{$v->env.source_path}{$v->env.module}/htlhotelinfo/create/" method="post">
  {* 入力フォーム を取り込む *}
  {include file=$v->env.module_root|cat:'/views/htlhotelinfo/_form.tpl'}
  <br>
<input type="submit" value="新規登録">
<input type="hidden" name="HotelInfo[hotel_cd]" value="{$v->helper->form->strip_tags($v->assign->hotelinfos.hotel_cd)}">
<input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->hotelinfos.hotel_cd)}">
</form>
<br>
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}