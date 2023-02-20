{* header start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="プログラム編集"}
{* header end *}
<br />
{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
<table border="1" cellpadding="4" cellspacing="0">
<form method="POST" action="{$v->env.source_path}{$v->env.module}/braffiliate/programupdate/">

  {* プログラム編集 フォーム *}
  {include file=$v->env.module_root|cat:'/views/braffiliate/_input_program_edit_form.tpl'}
  
  <input type="hidden" name="affiliate_cd" value="{$v->assign->affiliate_cd}">
  <input type="hidden" name="reserve_system" value="{$v->assign->reserve_system}">
  <td nowrap colspan="2" align="center"><input type="submit" value="更新">
</form>
</table>
<ul style="line-height:130%">
  <li>/* BASE */・・・・プロトコルの変更（http:// or https://）</li>
  <li>/* RESERVE_CD */・・・・予約参照コード</li>

  <li>/* HOTEL_CD */・・・・施設コード</li>
  <li>/* ROOM_CD */・・・・部屋コード</li>
  <li>/* PLAN_CD */・・・・プランコード</li>
  <li>/* TIMESTUMP */・・・・タイムスタンプ（SYSDATE）</li>
  <li>/* PAYMENT_PRICE */・・・・アフィリエイターへの支払い料金（ハードコーディング必要）</li>
  <li>/* DATE_DTM */・・・・宿泊日</li>

  <li>/* R_CHARGE */・・・・税別サ込み料金</li>
  <li>/* ETC */・・・・その他オールマイティー</li>
</ul>
<br>
{* footer start *}
  {include file=$v->env.module_root|cat:'/views/_common/_br_footer.tpl'}
{* footer end *}
