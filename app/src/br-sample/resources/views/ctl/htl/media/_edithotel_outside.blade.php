{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_edithotel_outside.tpl --}}

<p><font color="cdcdcd">■</font>外観画像</p>
<table border="1" cellpadding="4" cellspacing="0">
  <tr bgcolor="#EEEEFF">
    <th>設定画像</th>
  </tr>
  <tr>
    <td class="edit-image">
      <table>
        <tr>
          <td>
            <table>
            {if is_empty($v->assign->outside[0].file_nm)}
              <tr>
                <td>
                  <div class="no_image_box"><font color="ff0000">NO<br />IMAGE</font></div>
                </td>
              </tr>
              <tr>
                <td>
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="media_type"        value="hotel" />
                    <input type="hidden" name="label_type"        value="1" />
                    <input type="hidden" name="target_order_no"   value="1" />
                    <input type="submit" value="画像設定" />
                  </form>
                <td>
              </tr>
              {else}
              <tr>
                <td class="wrap_media_pop_view">
                    <div class="image_box">
                      <img border="0" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_054/{$v->assign->outside[0].file_nm}" width="54" height="54" title="{$v->assign->outside[0].title}">
                    </div>
                    <div class="media_pop_frame">
                      <img border="1" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_138/{$v->assign->outside[0].file_nm}" width="1" height="1" title="{$v->assign->outside[0].title}" class="media_pop_view">
                    </div>                    
                </td>
              </tr>
              <tr>
                <td>
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="media_type"        value="hotel" />
                    <input type="hidden" name="label_type"        value="1" />
                    <input type="hidden" name="setting_media_no"  value="{$v->assign->outside[0].media_no}" />
                    <input type="hidden" name="target_order_no"   value="1" />
                    <input type="submit" value="画像変更" />
                  </form>
                </td>
              </tr>
              {/if}
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>