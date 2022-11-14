{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\_common\_htl_staff_header.tpl --}}

<div id="staff" style="line-height:120%;padding:4px;background-color:#efe; border-color:#363;border-width:0px 1px 1px 1px;border-style:solid;">
    <table border="0" cellpadding="4" cellspacing="0" style="font-size:80%;">
      <tr>
        <td nowrap>
        {$v->helper->form->strip_tags($v->user->hotel.hotel_nm)} {if (!is_empty($v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)))}(旧{$v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)}){/if}({$v->helper->form->strip_tags($v->user->hotel.hotel_cd)})
          {* パワーホテルの場合 *}
          {if $v->user->hotel_control.stock_type == 1}
            <font color="#0000ff">[買]</font>
          {/if}
          {* プレミアム施設の場合 *}
          {if $v->user->hotel.premium_status}
            <font color="#0000ff">[プ]</font>
          {/if}
          {* 施設ヴィジュアルパッケージの場合 *}
          {if $v->user->hotel.visual_package_status}
            <font color="#0000ff">[ヴィ]</font>
          {/if}
          {* 日本旅行移行施設の場合 *}
          {if $v->user->hotel.ydp2_status}
            <font color="#0000ff">[日]</font>
          {/if}
        <br>
        担当者名 : {$v->helper->form->strip_tags($v->user->hotel_person.person_nm)}　（{$v->helper->form->strip_tags($v->user->hotel_person.person_post)}）<br>
        TEL : {$v->helper->form->strip_tags($v->user->hotel_person.person_tel)}　FAX : {$v->helper->form->strip_tags($v->user->hotel_person.person_fax)}
        </div>
        </td>
        <form action="{$v->env.source_path}{$v->env.module}/htltop/" method="POST" target="_blank">
        <td style="text-align:right" width="100%">
          <a href="{$v->env.source_path}{$v->env.module}/brtop/">メインメニュー</a><br>
          担当：{$v->user->operator->staff_nm}<br>
          施設コード：<input type="text" size="12" maxlength="10" name="target_cd" value="" />
          <input type="submit" value="移動">
          
        </td>
        </form>
      </tr>
    </table>
  </div>
  
  