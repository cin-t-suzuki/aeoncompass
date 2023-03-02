<div class="sch-163-1">
  <div class="sch-163-2">
    <div class="sch-163-3">

      <div class="sch-push">
        <div class="sch-push-title">おすすめ！ホテルのご紹介</div>
        {foreach from = $v->assign->hotel_priorities.values key = key item = value}
        <div class="sch-push-hotel-1">
          <div class="sch-push-hotel-2">
            <div class="sch-push-hotel-3">
              <div class="sch-push-area">【{if ($v->assign->a_pref.pref_id != $value.pref_id)}{$value.pref_nm}{/if}{if ($value.city_nm != '東京２３区')}{$value.city_nm}{/if}{if (!is_empty($value.ward_nm))}{$value.ward_nm}{/if}】</div>
              <div class="sch-push-name"><a href="{$v->env.base_path}plan/{$v->helper->form->strip_tags($value.hotel_cd)}/" class="ccc" id="hotel_priority1_{$smarty.foreach.hotel_priority.index+1}:{$value.hotel_cd}">{$v->helper->form->strip_tags($value.hotel_nm)}</a></div>
              <div class="sch-push-photo"><a href="{$v->env.base_path}plan/{$v->helper->form->strip_tags($value.hotel_cd)}/" class="ccc" id="hotel_priority2_{$smarty.foreach.hotel_priority.index+1}:{$value.hotel_cd}"><img src="/images/hotel/{$v->helper->form->strip_tags($value.hotel_cd)}/trim_054/{$v->helper->form->strip_tags($value.hotel_media.values.0.file_nm)}" width="54" height="54" alt="{$v->helper->form->strip_tags($value.hotel_nm)}" /></a></div>
              <div class="sch-push-btn btn-b03-081-s"><a class="btnimg" href="{$v->env.base_path}hotel/{$value.hotel_cd}/"><img src="/img/btn/b03-hotel.gif" width="81" height="21" alt="{$v->helper->form->strip_tags($value.hotel_nm)}の詳細" /></a></div>
              <div class="sch-push-btn btn-b03-081-s"><a class="btnimg" href="{$v->env.base_path}voice/{$value.hotel_cd}/"><img src="/img/btn/b03-voice.gif" width="81" height="21" alt="{$v->helper->form->strip_tags($value.hotel_nm)}の詳細" /></a></div>
            </div>
          </div>
        </div>
        {/foreach}
      </div>

    </div>
  </div>
</div>
