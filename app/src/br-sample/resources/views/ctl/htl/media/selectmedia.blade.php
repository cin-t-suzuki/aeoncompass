{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/selectmedia.tpl --}}

<!-- Header -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='画像選択'}
<!-- /Header -->
<!-- CSS -->
{include file='./_css.tpl'}
<!-- /CSS -->
<!-- JavaScript -->
{include file='./_script.tpl'}
<!-- /JavaScript -->
<div class="clear"><hr></div>
<hr width="100%" size="1">
<!-- Main -->
<div id="page_top_symbol">
  <p>
    {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
  </p>
  <div>
    {include file=$v->env.module_root|cat:'/view2/htlsmedia/_upload_form.tpl'}
  </div>
  <p><font color="cdcdcd">■</font>登録画像一覧</p>
  {if is_empty($v->assign->medias)}
    <font color="ff0000">現在アップロードされている画像はありません。</font>
  {else}
  {if !$v->assign->form_params.label_cd.map}{* 地図画像の設定時は検索BOXは表示しない *}
  <div>
    <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
      <table border="1" cellpadding="4" cellspacing="0" width="700">
        <tr>
          <td>
            <input type="checkbox" id="label_outside" name="label_cd[outside]" value="1" {if !is_empty($v->assign->form_params.label_cd.outside)}checked="checked"{/if} /><label for="label_outside"><font color="#FF9999" title="外観">■</font>外観</label>
          </td>
{*          <td>  *}
{*            <input type="checkbox" id="label_map" name="label_cd[map]" value="1" {if !is_empty($v->assign->form_params.label_cd.map)}checked="checked"{/if} /><label for="label_map"><font color="#FFCC66" title="地図">■</font>地図</label> *}
{*          </td> *}
          <td>
            <input type="checkbox" id="label_inside" name="label_cd[inside]" value="1" {if !is_empty($v->assign->form_params.label_cd.inside)}checked="checked"{/if} /><label for="label_inside"><font color="#99FF99" title="館内">■</font>フォトギャラリー</label>
          </td>
          <td>
            <input type="checkbox" id="label_room" name="label_cd[room]" value="1" {if !is_empty($v->assign->form_params.label_cd.room)}checked="checked"{/if} /><label for="label_room"><font color="#66CCFF" title="客室">■</font>客室</label>
          </td>
          <td>
            <input type="checkbox" id="label_other" name="label_cd[other]" value="1" {if !is_empty($v->assign->form_params.label_cd.other)}checked="checked"{/if} /><label for="label_other"><font color="#FF99FF" title="その他">■</font>その他</label>
          </td>
          <td>
            <input type="checkbox" id="label_nothing" name="label_cd[nothing]" value="1" {if !is_empty($v->assign->form_params.label_cd.nothing)}checked="checked"{/if} /><label for="label_nothing"><font color="#cccccc" title="ラベル無し">■</font>ラベル無し</label>
          </td>
        </tr>
        <tr>
          <td colspan="6" align="center">
            <input type="hidden" name="target_cd"       value="{$v->assign->form_params.target_cd}" />
            <input type="hidden" name="room_id"         value="{$v->assign->form_params.room_id}" />
            <input type="hidden" name="plan_id"         value="{$v->assign->form_params.plan_id}" />
            <input type="hidden" name="label_type"        value="{$v->assign->form_params.label_type}" />
            <input type="hidden" name="target_order_no"  value="{$v->assign->form_params.target_order_no}" />
            <input type="hidden" name="setting_media_no" value="{$v->assign->form_params.setting_media_no}" />
            <input type="hidden" name="media_type"       value="{$v->assign->form_params.media_type}" />
            <input type="hidden" name="list_width_ref"  value="1" />
            <input type="submit" value="表示"  />
            <input type="checkbox" id="list_width" name="list_width" value="1" {if $v->assign->form_params.list_width === '1'} checked="checked" {/if}/>
            <label for="list_width"><span style="color:#2655a0; font-size: 13px;">画像一覧をワイド表示にする</span></label>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <br />
  {/if}
  {if $v->assign->form_params.list_width === '1'}
  {assign var="table_width" value="1170"}
  {assign var="disp_order_width" value="width:50px;"}
  {assign var="label_width" value="width:50px;"}
  {assign var="image_width" value="width:64px;"}
  {assign var="title_label_width" value="width:12%;"}
  {  if $v->assign->form_params.label_cd.map}
  {assign var="update_label_width" value="width:13%;"}
  {  else}
  {assign var="update_label_width" value="width:11%;"}
  {  /if}
  {assign var="update_width" value="width:15%;"}
  {assign var="file_colspan" value="3"}
  {else}
  {assign var="table_width" value="700"}
  {assign var="file_colspan" value="1"}
  {assign var="title_label_width" value="width:24%;"}
  {/if}
  <div class="wrap_media_scroll" style="width:{$table_width}px;">
    <div class="scroll_button_btm">
      <a href="#page-bottom">▼画像一覧の最下部へ</a>
    </div>
  </div>
  <table border="1" cellpadding="4" cellspacing="0" width="{$table_width}">
    <tr bgcolor="#EEEEFF">
      <th style="{$disp_order_width}">表示順</th>
      <th style="{$label_width}">ラベル</th>
      <th style="{$image_width}">画像</th>
      <th>ファイル</th>
      <th>利用状況</th>
      <th>設定</th>
    </tr>
  {foreach from=$v->assign->medias item=media name=loop_media}
    <tr>
      <td style="{$disp_order_width}">
        {if $smarty.foreach.loop_media.first}
          <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortmedia/" method="post" style="display:inline;">
            <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
            <input type="hidden" name="media_no"          value="{$media.media_no}" />
            <input type="hidden" name="order_no"          value="1" />
            <input type="hidden" name="edit_order_no"     value="{$media.order_no_plus}" />
            <input type="hidden" name="room_id"           value="{$v->assign->form_params.room_id}" />
            <input type="hidden" name="plan_id"           value="{$v->assign->form_params.plan_id}" />
            <input type="hidden" name="label_type"        value="{$v->assign->form_params.label_type}" />
            <input type="hidden" name="label_cd[outside]" value="{$v->assign->form_params.label_cd.outside}" />
            <input type="hidden" name="label_cd[map]"     value="{$v->assign->form_params.label_cd.map}" />
            <input type="hidden" name="label_cd[inside]"  value="{$v->assign->form_params.label_cd.inside}" />
            <input type="hidden" name="label_cd[room]"    value="{$v->assign->form_params.label_cd.room}" />
            <input type="hidden" name="label_cd[other]"   value="{$v->assign->form_params.label_cd.other}" />
            <input type="hidden" name="label_cd[nothing]" value="{$v->assign->form_params.label_cd.nothing}" />
            <input type="hidden" name="target_order_no"  value="{$v->assign->form_params.target_order_no}" />
            <input type="hidden" name="setting_media_no" value="{$v->assign->form_params.setting_media_no}" />
            <input type="hidden" name="media_type"       value="{$v->assign->form_params.media_type}" />
            <input type="submit" value="↓" />
          </form>
        {elseif $smarty.foreach.loop_media.last}
          <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortmedia/" method="post" style="display:inline;">
            <input type="hidden" name="target_cd"     value="{$v->assign->form_params.target_cd}" />
            <input type="hidden" name="media_no"      value="{$media.media_no}" />
            <input type="hidden" name="order_no"      value="{$media.order_no}" />
            <input type="hidden" name="edit_order_no" value="{$media.order_no_minus}" />
            <input type="hidden" name="room_id"         value="{$v->assign->form_params.room_id}" />
            <input type="hidden" name="plan_id"         value="{$v->assign->form_params.plan_id}" />
            <input type="hidden" name="label_type"        value="{$v->assign->form_params.label_type}" />
            <input type="hidden" name="label_cd[outside]" value="{$v->assign->form_params.label_cd.outside}" />
            <input type="hidden" name="label_cd[map]"     value="{$v->assign->form_params.label_cd.map}" />
            <input type="hidden" name="label_cd[inside]"  value="{$v->assign->form_params.label_cd.inside}" />
            <input type="hidden" name="label_cd[room]"    value="{$v->assign->form_params.label_cd.room}" />
            <input type="hidden" name="label_cd[other]"   value="{$v->assign->form_params.label_cd.other}" />
            <input type="hidden" name="label_cd[nothing]" value="{$v->assign->form_params.label_cd.nothing}" />
            <input type="hidden" name="target_order_no"  value="{$v->assign->form_params.target_order_no}" />
            <input type="hidden" name="setting_media_no" value="{$v->assign->form_params.setting_media_no}" />
            <input type="hidden" name="media_type"       value="{$v->assign->form_params.media_type}" />
            <input type="submit" value="↑" />
          </form>
        {else}
          <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortmedia/" method="post" style="display:inline;">
            <input type="hidden" name="target_cd"     value="{$v->assign->form_params.target_cd}" />
            <input type="hidden" name="media_no"      value="{$media.media_no}" />
            <input type="hidden" name="order_no"      value="{$media.order_no}" />
            <input type="hidden" name="edit_order_no" value="{$media.order_no_minus}" />
            <input type="hidden" name="room_id"         value="{$v->assign->form_params.room_id}" />
            <input type="hidden" name="plan_id"         value="{$v->assign->form_params.plan_id}" />
            <input type="hidden" name="label_type"        value="{$v->assign->form_params.label_type}" />
            <input type="hidden" name="label_cd[outside]" value="{$v->assign->form_params.label_cd.outside}" />
            <input type="hidden" name="label_cd[map]"     value="{$v->assign->form_params.label_cd.map}" />
            <input type="hidden" name="label_cd[inside]"  value="{$v->assign->form_params.label_cd.inside}" />
            <input type="hidden" name="label_cd[room]"    value="{$v->assign->form_params.label_cd.room}" />
            <input type="hidden" name="label_cd[other]"   value="{$v->assign->form_params.label_cd.other}" />
            <input type="hidden" name="label_cd[nothing]" value="{$v->assign->form_params.label_cd.nothing}" />
            <input type="hidden" name="target_order_no"  value="{$v->assign->form_params.target_order_no}" />
            <input type="hidden" name="setting_media_no" value="{$v->assign->form_params.setting_media_no}" />
            <input type="hidden" name="media_type"       value="{$v->assign->form_params.media_type}" />
            <input type="submit" value="↑" />
          </form>
          <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortmedia/" method="post" style="display:inline;">
            <input type="hidden" name="target_cd"     value="{$v->assign->form_params.target_cd}" />
            <input type="hidden" name="media_no"      value="{$media.media_no}" />
            <input type="hidden" name="order_no"      value="{$media.order_no}" />
            <input type="hidden" name="edit_order_no" value="{$media.order_no_plus}" />
            <input type="hidden" name="room_id"         value="{$v->assign->form_params.room_id}" />
            <input type="hidden" name="plan_id"         value="{$v->assign->form_params.plan_id}" />
            <input type="hidden" name="label_type"        value="{$v->assign->form_params.label_type}" />
            <input type="hidden" name="label_cd[outside]" value="{$v->assign->form_params.label_cd.outside}" />
            <input type="hidden" name="label_cd[map]"     value="{$v->assign->form_params.label_cd.map}" />
            <input type="hidden" name="label_cd[inside]"  value="{$v->assign->form_params.label_cd.inside}" />
            <input type="hidden" name="label_cd[room]"    value="{$v->assign->form_params.label_cd.room}" />
            <input type="hidden" name="label_cd[other]"   value="{$v->assign->form_params.label_cd.other}" />
            <input type="hidden" name="label_cd[nothing]" value="{$v->assign->form_params.label_cd.nothing}" />
            <input type="hidden" name="target_order_no"  value="{$v->assign->form_params.target_order_no}" />
            <input type="hidden" name="setting_media_no" value="{$v->assign->form_params.setting_media_no}" />
            <input type="hidden" name="media_type"       value="{$v->assign->form_params.media_type}" />
            <input type="submit" value="↓" />
          </form>
        {/if}
      </td>
      <td style="{$label_width}">
        {include file=$v->env.module_root|cat:'/view2/htlsmedia/_label_cd_type.tpl' label_cd=$media.label_cd}
      </td>
      <td align="center" class="wrap_media_pop_view" style="{$image_width}">
        <img border="0" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_054/{$media.file_nm}" width="54" height="54" title="{$media.title}">
        <div class="media_pop_frame">
          <img border="1" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_138/{$media.file_nm}" width="1" height="1" title="{$media.title}" class="media_pop_view">
        </div>
      </td>
      <td>
        <table border="1" cellspacing="0" cellpadding="6" width="100%">
          <tr>
            <td class="title_label" style="{$title_label_width}">タイトル</td>
            <td>{$media.title}</td>
            {if $v->assign->form_params.list_width === '1'}
            <td class="update_label" style="{$update_label_width}">更新日時</td>
            <td style="{$update_width}">{$media.modify_ts|date_format:'%Y-%m-%d'}</td>
            {/if}
          </tr>
          <tr>
            <td class="file_label">ファイル名</td>
            <td colspan="{$file_colspan}"><span style="font-size:18px; display:block; margin-bottom: 3px; ">{$media.disp_file_nm}</span></td>
          </tr>
          {if $v->assign->form_params.list_width !== '1'}
          <tr>
            <td class="update_label">更新日時</td>
            <td>{$media.modify_ts|date_format:'%Y-%m-%d'}</td>
          </tr>
          {/if}
        </table>
      </td>
      <td>
        {include file=$v->env.module_root|cat:'/view2/htlsmedia/_use_type.tpl' is_use=$media.is_use}
      </td>
      <td>
        <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/update{$v->assign->form_params.media_type}/" method="post" style="display:inline;">
          <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
          <input type="hidden" name="room_id"           value="{$v->assign->form_params.room_id}" />
          <input type="hidden" name="plan_id"           value="{$v->assign->form_params.plan_id}" />
          <input type="hidden" name="media_no"          value="{$media.media_no}" />
          <input type="hidden" name="media_type"        value="{$v->assign->form_params.media_type}" />
          <input type="hidden" name="target_order_no"   value="{$v->assign->form_params.target_order_no}" />
          <input type="hidden" name="setting_media_no"  value="{$v->assign->form_params.setting_media_no}" />
          <input type="hidden" name="label_type"        value="{$v->assign->form_params.label_type}" />
          <input type="submit" value="設定" />
        </form>
      </td>
    </tr>
  {/foreach}
  </table>
  {/if}
  <div class="wrap_media_scroll" style="width:{$table_width}px;">
   <div class="scroll_button_btm">
     <a href="#page-top">▲画像一覧のTOPへ</a>
   </div>
  </div>
</div>
{include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'}
<!-- /Main -->
<div class="clear"><hr></div>
<!-- Footer -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}
<!-- /Footer -->