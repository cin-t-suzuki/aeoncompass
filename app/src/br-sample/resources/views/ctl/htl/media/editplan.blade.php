{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/editplan.tpl --}}

<!-- Header -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_header.tpl' title='プラン画像設定'}
<!-- /Header -->
<!-- CSS -->
{include file='./_css.tpl'}
<!-- /CSS -->
<div class="clear">
    <hr>
</div>
<hr width="100%" size="1">
<!-- Main -->
<div id="page_top_symbol">
    <p>
        {include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}
    </p>
    <p>
        <font color="cdcdcd">■</font>プラン画像
    </p>
    <table class="table-plan" style="width:1200px;">
        <tr>
            <th>プラン名</th>
            <th>設定画像</th>
        </tr>
        <tr>
            <td class="edit-image" style="width:30%;">
                {$v->assign->plan.plan_nm}
            </td>
            <td class="edit-image">
                <table>
                    <tr>
                        {assign var=b_is_edit_target value=true}
                        {assign var=b_already_disp_plan_no_image value=false}
                        {assign var=n_real_display_plan_img_cnt value=0}
                        {section name=loop_plan_media start=0 loop=$v->assign->media_count_plan}
                        {if $n_real_display_plan_img_cnt == $smarty.section.loop_plan_media.index and !$b_already_disp_plan_no_image}
                        <td>
                            <table>
                                {if is_empty($v->assign->plan.medias[$smarty.section.loop_plan_media.index].file_nm) and !$b_already_disp_plan_no_image}
                                <tr>
                                    <td>
                                        <div class="no_image_box" style="padding:2px;text-align: center; font-size: 15px;">
                                            <font color="ff0000">NO<br />IMAGE</font>
                                        </div>
                                    </td>
                                </tr>
                                {if (!$v->assign->is_relo_plan) or ($v->assign->is_relo_plan and $v->user->operator->is_nta())}
                                <tr>
                                    <td>
                                        {if $b_is_edit_target}
                                        <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
                                            <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
                                            <input type="hidden" name="plan_id" value="{$v->assign->form_params.plan_id}" />
                                            <input type="hidden" name="target_order_no" value="{$smarty.section.loop_plan_media.iteration}" />
                                            <input type="hidden" name="media_type" value="plan" />
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
                                {/if}
                                {assign var=b_already_disp_plan_no_image value=true}
                                {assign var=n_real_display_plan_img_cnt value="`$n_real_display_plan_img_cnt+1`"}
                                {elseif !is_empty($v->assign->plan.medias[$smarty.section.loop_plan_media.index].file_nm)}
                                <tr>
                                    <td class="wrap_media_pop_view">
                                        <div class="image_box">
                                            <img border="0" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_054/{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].file_nm}" width="54" height="54" title="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].title}">
                                        </div>
                                        <div class="media_pop_frame">
                                            <img border="1" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_138/{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].file_nm}" width="1" height="1" title="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].title}" class="media_pop_view">
                                        </div>
                                    </td>
                                </tr>
                                {if (!$v->assign->is_relo_plan) or ($v->assign->is_relo_plan and $v->user->operator->is_nta())}
                                <tr>
                                    <td>
                                        <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/selectmedia/" method="post" style="display:inline;">
                                            <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
                                            <input type="hidden" name="plan_id" value="{$v->assign->form_params.plan_id}" />
                                            <input type="hidden" name="target_order_no" value="{$smarty.section.loop_plan_media.iteration}" />
                                            <input type="hidden" name="media_type" value="plan" />
                                            <input type="hidden" name="setting_media_no" value="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].media_no}" />
                                            <input type="submit" value="画像変更" />
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/updateplan/" method="post" style="display:inline;">
                                            <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
                                            <input type="hidden" name="plan_id" value="{$v->assign->form_params.plan_id}" />
                                            <input type="hidden" name="target_order_no" value="{$smarty.section.loop_plan_media.iteration}" />
                                            <input type="hidden" name="media_type" value="plan" />
                                            <input type="hidden" name="setting_media_no" value="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].media_no}" />
                                            <input type="submit" value="画像を外す" />
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {if !$smarty.section.loop_plan_media.first}
                                        <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortplan/" method="post" style="display:inline;">
                                            <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
                                            <input type="hidden" name="plan_id" value="{$v->assign->form_params.plan_id}" />
                                            <input type="hidden" name="target_order_no" value="{$smarty.section.loop_plan_media.iteration}" />
                                            <input type="hidden" name="edit_order_no" value="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].order_no_minus}" />
                                            <input type="hidden" name="media_type" value="plan" />
                                            <input type="hidden" name="setting_media_no" value="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].media_no}" />
                                            <input type="submit" value="←" />
                                        </form>
                                        {/if}
                                        {if !$smarty.section.loop_plan_media.last and !is_empty($v->assign->plan.medias[$smarty.section.loop_plan_media.iteration].media_no)}
                                        <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/sortplan/" method="post" style="display:inline;">
                                            <input type="hidden" name="target_cd" value="{$v->assign->form_params.target_cd}" />
                                            <input type="hidden" name="plan_id" value="{$v->assign->form_params.plan_id}" />
                                            <input type="hidden" name="target_order_no" value="{$smarty.section.loop_plan_media.iteration}" />
                                            <input type="hidden" name="edit_order_no" value="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].order_no_plus}" />
                                            <input type="hidden" name="media_type" value="plan" />
                                            <input type="hidden" name="setting_media_no" value="{$v->assign->plan.medias[$smarty.section.loop_plan_media.index].media_no}" />
                                            <input type="submit" value="→" />
                                        </form>
                                        {/if}
                                    </td>
                                </tr>
                                {/if}
                                {assign var=n_real_display_plan_img_cnt value="`$n_real_display_plan_img_cnt+1`"}
                                {/if}
                            </table>
                            {if $n_real_display_plan_img_cnt % 10 == 0 and !$smarty.section.loop_plan_media.last}
                </table>
                <table>
                    {/if}
            </td>
            {/if}
            {/section}
        </tr>
    </table>
    </td>
    </tr>
    </table>
    <p>
        <font color="cdcdcd">■</font>関連部屋画像
    </p>
    {if is_empty($v->assign->rooms)}
    <font color="ff0000">設定されている部屋はありません</font>
    {else}
    <table class="table-room" style="width:1200px;">
        <tr>
            <th>部屋名</th>
            <th>設定画像</th>
        </tr>
        {assign var=n_real_display_room_img_cnt value=0}
        {foreach from=$v->assign->rooms item=room}
        <tr>
            <td class="edit-image" style="width:30%;">
                {$room.room_nm}
            </td>
            <td class="edit-image">
                <table>
                    <tr>
                        {assign var=b_already_disp_room_no_image value=false}
                        {section name=loop_room_media start=0 loop=$v->assign->media_count_room}
                        {if is_empty($room.medias[$smarty.section.loop_room_media.index].file_nm) and !$b_already_disp_room_no_image and $smarty.section.loop_room_media.first}
                        <td class="edit-image2 wrap_media_pop_view">
                            <div class="no_image_box" style="padding:2px;text-align: center; font-size: 15px;">
                                <font color="ff0000">NO<br />IMAGE</font>
                            </div>
                        </td>
                        {assign var=b_already_disp_room_no_image value=true}
                        {assign var="n_real_display_room_img_cnt" value="`$n_real_display_room_img_cnt+1`"}
                        {elseif !is_empty($room.medias[$smarty.section.loop_room_media.index].file_nm)}
                        <td class="edit-image2 wrap_media_pop_view">
                            <div class="image_box">
                                <img border="0" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_054/{$room.medias[$smarty.section.loop_room_media.index].file_nm}" width="54" height="54" title="{$room.medias[$smarty.section.loop_room_media.index].title}}">
                            </div>
                            <div class="media_pop_frame">
                                <img border="1" src="/images/hotel/{$v->assign->form_params.target_cd}/trim_138/{$room.medias[$smarty.section.loop_room_media.index].file_nm}" width="1" height="1" title="{$room.medias[$smarty.section.loop_room_media.index].title}}" class="media_pop_view">
                            </div>
                        </td>
                        {assign var="n_real_display_room_img_cnt" value="`$n_real_display_room_img_cnt+1`"}
                        {/if}
                        {if $n_real_display_room_img_cnt % 10 == 0 and !$smarty.section.loop_room_media.last}
                    </tr>
                    <tr>
                        {/if}
                        {/section}
                    </tr>
                </table>
            </td>
        </tr>
        {/foreach}
    </table>
    {/if}
</div>
{include file=$v->env.module_root|cat:'/view2/htlsmedia/_common_menu.tpl'}
<!-- /Main -->
<div class="clear">
    <hr>
</div>
<!-- Footer -->
{include file=$v->env.module_root|cat:'/views/_common/_htl_footer.tpl'}
<!-- /Footer -->
