{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/editmedia.tpl --}}

<!-- Header -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='画像管理'}
<!-- /Header -->
<!-- CSS -->
{include file='./_css.tpl'}
<!-- /CSS -->
<div class="clear"><hr></div>
<hr width="100%" size="1">
<!-- Main -->
<div>
  <p>
    {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
  </p>
  <p><font color="cdcdcd">■</font>部屋画像情報</p>
  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/updatemedia/" method="post" style="display:inline;">
    <table border="1" cellspacing="0" cellpadding="4">
      <tr bgcolor="#EEEEFF">
        <th>画像</th>
        <th>詳細情報</th>
      </tr>
      <tr>
        <td>
          <img border="0" src="/images/hotel/{$v->assign->form_params.target_cd}/{$v->assign->media.file_nm}" title="{$v->assign->media.title}">
        </td>
        <td>
          <table border="1" cellspacing="0" cellpadding="4">
            <tr>
              <td>ファイル名</td>
              <td><span>{$v->assign->media.disp_file_nm}</span>
              </td>
            </tr>
            <tr>
              <td>サイズ</td>
              <td>{$v->assign->media.width}*{$v->assign->media.height}</td>
            </tr>
            <tr>
              <td>更新日</td>
              <td>{$v->assign->media.modify_ts|date_format:'%Y&#24180;%m&#26376;%d&#26085;'}</td>
            </tr>
            <tr>
              <td>タイトル</td>
              <td><input type="text" name="title" value="{$v->assign->media.title}" maxlength="30" size="40" /></td>
            </tr>
            <tr>
              <td>画像ラベル</td>
              <td>
                {if !is_empty($v->assign->form_params.label_cd.map)}
                <span style="color:#ff0000;">地図画像の為、変更できません。</span>
                {else}
                <input type="checkbox" id="label_outside" name="label_cd[outside]" value="1" {if !is_empty($v->assign->form_params.label_cd.outside)}checked="checked"{/if} /><label for="label_outside"><font color="#FF9999" title="外観">■</font>外観</label>
                <input type="checkbox" id="label_inside" name="label_cd[inside]" value="1" {if !is_empty($v->assign->form_params.label_cd.inside)}checked="checked"{/if} /><label for="label_inside"><font color="#99FF99" title="フォトギャラリー">■</font>フォトギャラリー</label>
                <input type="checkbox" id="label_room" name="label_cd[room]" value="1" {if !is_empty($v->assign->form_params.label_cd.room)}checked="checked"{/if} /><label for="label_room"><font color="#66CCFF" title="客室">■</font>客室</label>
                <input type="checkbox" id="label_other" name="label_cd[other]" value="1" {if !is_empty($v->assign->form_params.label_cd.other)}checked="checked"{/if} /><label for="label_other"><font color="#FF99FF" title="その他">■</font>その他</label>
                {/if}
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
                <input type="hidden" name="media_no"  value="{$v->assign->form_params.media_no}" />
                <input type="submit" value="更新" />
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </form>
</div>
{include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'}
<!-- /Main -->
<div class="clear"><hr></div>
<!-- Footer -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}
<!-- /Footer -->