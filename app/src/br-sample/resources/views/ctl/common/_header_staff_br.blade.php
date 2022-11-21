{{-- MEMO: 移植元 public\app\ctl\view2\_common\_header_staff_br.tpl --}}


{strip}

  <div class="hd-base" id="hd-staff-br">
  
{*    <div class="hd-contents"> *}
      
      {*--------------------------------------------------------------------*}
      {* 施設情報                                                           *}
      {*--------------------------------------------------------------------*}
      <div id="hd-staff-htl">
        {* 施設名称 & 施設コード & 施設オプション *}
        <p>
          {$v->helper->form->strip_tags($v->user->hotel.hotel_nm)}&nbsp;（{$v->helper->form->strip_tags($v->user->hotel.hotel_cd)}）
          
          {* ハイランクホテルの場合 *}
          {if $v->user->hotel_control.stock_type == 1}<span class="msg-text-info">[買]</span>{/if}
          
          {* プレミアムパッケージ適用施設 *}
          {if $v->user->hotel.premium_status}<span class="msg-text-info">[プ]</span>{/if}
          
          {*ヴィジュアルパッケージ適用施設 *}
          {if $v->user->hotel.visual_package_status}<span class="msg-text-info">[ヴィ]</span>{/if}
          
          {* 日本旅行移行施設 *}
          {if $v->user->hotel.ydp2_status}<span class="msg-text-info">[日]</span>{/if}
        </p>
        
        {* 旧施設名称が存在しているときは表示する *}
        {if (!is_empty($v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)))}
          <p>（旧&nbsp;{$v->helper->form->strip_tags($v->user->hotel.hotel_old_nm)})</p>
        {/if}
        
        {* 施設担当者情報 *}
        <p>
          担当者名 : {$v->helper->form->strip_tags($v->user->hotel_person.person_nm)}
          &nbsp;
        （{$v->helper->form->strip_tags($v->user->hotel_person.person_post)}）
        </p>
        
        {* 施設電話番号 & 施設FAX番号 *}
        <p>
          TEL : {$v->helper->form->strip_tags($v->user->hotel_person.person_tel)}
          &nbsp;
          FAX : {$v->helper->form->strip_tags($v->user->hotel_person.person_fax)}
        </p>
        
      </div>
      
      {*--------------------------------------------------------------------*}
      {* スタッフメニュー                                                   *}
      {*--------------------------------------------------------------------*}
      <form action="{$v->env.source_path}{$v->env.module}/htltop/" method="post">
        <div id="hd-staff-act">
          <p><a href="{$v->env.source_path}{$v->env.module}/brtop/">メインメニュー</a></p>
          <p>担当：{$v->user->operator->staff_nm}</p>
          <p>
            施設コード：<input type="text" size="12" maxlength="10" name="target_cd" value="" />
            &nbsp;
            <input type="submit" value="移動" />
          </p>
        </div>
      </form>
      
      <div class="clear"></div>
      
{*    </div>  *}
    
  </div>

{/strip}