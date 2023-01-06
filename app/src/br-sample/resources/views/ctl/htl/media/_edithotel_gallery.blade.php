{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_edithotel_gallery.tpl --}}

<p><font color="cdcdcd">■</font>フォトギャラリー画像</p>
<table border="1" cellpadding="4" cellspacing="0">
  <tr bgcolor="#EEEEFF">
    <th>設定画像</th>
  </tr>
  <tr>
    <td class="edit-image">
      <table>
        <tr>
        {assign var=b_is_edit_target value=true}
        {assign var=b_already_disp_hotel_no_image value=false}
        {assign var=n_real_display_hotel_img_cnt value=0}
        {section name=loop_gallerys_media start=0 loop=$v->assign->media_count_inside}
          {if $n_real_display_hotel_img_cnt % 10 == 0 and $n_real_display_hotel_img_cnt != 0 and $n_real_display_hotel_img_cnt == $smarty.section.loop_gallerys_media.index}
            </tr></table>
            <table><tr><td>
          {/if}
          {if $n_real_display_hotel_img_cnt == $smarty.section.loop_gallerys_media.index and !$b_already_disp_hotel_no_image}
          <td>
            <table>
            {if is_empty($v->assign->gallerys[$smarty.section.loop_gallerys_media.index].file_nm) and !$b_already_disp_hotel_no_image}
              <tr>
                <td>
                  <div class="no_image_box" style="padding:2px;text-align: center; font-size: 15px;"><font color="ff0000">NO<br />IMAGE</font></div>
                </td>
              </tr>
              <tr>
                <td>
                {if $b_is_edit_target}
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"        value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="media_type"       value="hotel" />
                    <input type="hidden" name="label_type"       value="3" />
                    <input type="hidden" name="target_order_no"  value="{$smarty.section.loop_gallerys_media.iteration}" />
                    <input type="submit" value="画像設定" />
                  </form>
                  {assign var=b_is_edit_target value=false}
                {else}
                  <br />
                {/if}
                <td>
              </tr>
              <tr>
                <td>
                  <br />
                </td>
              </tr>
              <tr>
                <td>
                  <br />
                </td>
              </tr>
              {assign var=b_already_disp_hotel_no_image value=true}
              {assign var=n_real_display_hotel_img_cnt value="`$n_real_display_hotel_img_cnt+1`"}
              {elseif !is_empty($v->assign->gallerys[$smarty.section.loop_gallerys_media.index].file_nm)}
              <tr>
                <td class="wrap_media_pop_view">
                  <div class="image_box">
                    <img border="0" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_054/{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].file_nm}" width="54" height="54" title="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].title}">
                  </div>
                  <div class="media_pop_frame">
                    <img border="1" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_138/{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].file_nm}" width="1" height="1" title="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].title}" class="media_pop_view">
                  </div>                  
                </td>
              </tr>
              <tr>
                <td>
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="media_type"        value="hotel" />
                    <input type="hidden" name="label_type"        value="3" />
                    <input type="hidden" name="target_order_no"   value="{$smarty.section.loop_gallerys_media.iteration}" />
                    <input type="hidden" name="setting_media_no"  value="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].media_no}" />
                    <input type="submit" value="画像変更" />
                  </form>
                </td>
              </tr>
              <tr>
                <td>
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/updatehotel/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="media_type"        value="hotel" />
                    <input type="hidden" name="label_type"        value="3" />
                    <input type="hidden" name="target_order_no"   value="{$smarty.section.loop_gallerys_media.iteration}" />
                    <input type="hidden" name="setting_media_no"  value="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].media_no}" />
                    <input type="submit" value="画像を外す" />
                  </form>
                </td>
              </tr>
              <tr>
                <td>
                  {if !$smarty.section.loop_gallerys_media.first}
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortgallery/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="target_order_no"   value="{$smarty.section.loop_gallerys_media.iteration}" />
                    <input type="hidden" name="edit_order_no"   value="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].order_no_minus}" />
                    <input type="hidden" name="setting_media_no"  value="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].media_no}" />
                    <input type="hidden" name="label_type"       value="3" />
                    <input type="submit" value="←" />
                  </form>
                  {/if}
                  {if !$smarty.section.loop_gallerys_media.last and !is_empty($v->assign->gallerys[$smarty.section.loop_gallerys_media.iteration].media_no)}
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortgallery/" method="post" style="display:inline;">
                    <input type="hidden" name="target_cd"         value="{$v->assign->form_params.target_cd}" />
                    <input type="hidden" name="target_order_no"   value="{$smarty.section.loop_gallerys_media.iteration}" />
                    <input type="hidden" name="edit_order_no"   value="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].order_no_plus}" />
                    <input type="hidden" name="setting_media_no"  value="{$v->assign->gallerys[$smarty.section.loop_gallerys_media.index].media_no}" />
                    <input type="hidden" name="label_type"       value="3" />
                    <input type="submit" value="→" />
                  </form>
                  {/if}
                </td>
              </tr>
            {assign var=n_real_display_hotel_img_cnt value="`$n_real_display_hotel_img_cnt+1`"}
            {/if}
            </table>
          </td>
          {/if}
          {/section}
        </tr>
      </table>
    </td>
  </tr>
</table>
