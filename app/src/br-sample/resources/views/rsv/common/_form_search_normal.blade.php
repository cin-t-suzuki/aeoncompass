{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_normal.tpl --}}

{if ($v->assign->search_condition.form.rooms|@count == 0)}
  <div style="background:#ffffff;margin:0 auto 10px;width:300px;height:168px;padding:9px 8px 8px;">
    <div>ご指定のプランは満室となりましたので、ご予約いただけなくなりました。 他のプランをご検討ください。</div>
  </div>
{else}
{* 検索フォーム *}
    <form method="get" action="{$v->env.path_base}/query/" class="jqs-query parseForm">
      <table border="0" cellpadding="0" cellspacing="0" width="322">
        <tr>
          <th><div class="div-h">旅行日程</div></th>
          <td>
            {* 年月表示（13月まで表示) *}
            <select size="1" name="year_month">
            {foreach from=$v->assign->search_condition.form.year_month item=months}
            <option value="{$months.date_ym}"{if $months.current_status} selected="selected"{/if}>{$months.date_ym|substr:0:4}年{$months.date_ym|substr:5:2|number_format}月</option>
            {/foreach}
            </select>
            {* 日表示のための31回ループ *}
            <select size="1" name="day" class="text-right">
            {foreach from=$v->assign->search_condition.form.days item=days}
            <option value="{$days.date_ymd}"{if $days.current_status} selected="selected"{/if}>{$days.date_ymd}日</option>
            {/foreach}
            </select>
            &nbsp;&nbsp;
            <a class="jqs-calendar" href="#calendar"><img src="{$v->env.path_img}/lhd/lhd-calendar.gif" alt="" /></a>
            <br />
            <select size="1" name="stay" class="text-right">
              {foreach from=$v->assign->search_condition.form.stay item=stay}
                <option value="{$stay.days}"{if $stay.current_status} selected="selected"{/if}>{$stay.days}泊</option>
              {/foreach}
            </select>
            &nbsp;&nbsp;
            {if is_empty($v->assign->search_condition.form.hotel.room_id)}
              <input name="date_status" value="on" type="checkbox"{if $v->assign->search_condition.form.date_status == 'on'} checked="checked"{/if} id="date_status" /> <label for="date_status" class="checkbox">日程未定</label>
            {/if}
          </td>
        </tr>
        <tr>
          <th><div>部屋数</div></th>
          <td>
          <select size="1" name="rooms" class="text-right">
          {foreach from=$v->assign->search_condition.form.rooms item=rooms}
            <option value="{$rooms.room_count}"{if $rooms.current_status} selected="selected"{/if}>{$rooms.room_count}室</option>
          {/foreach}
          </select>
          </td>
        </tr>
        <tr>
          <th><div>利用人数／室</div></th>
          <td>
            大人<select size="1" name="senior" class="text-right">
          {foreach from=$v->assign->search_condition.form.senior.capacities item=senior}
            <option value="{$senior.capacity}"{if $senior.current_status} selected="selected"{/if}>{$senior.capacity}名</option>
          {/foreach}
        </select>
        {if $v->assign->search_condition.form.childs.accept_status }
            <a name="guests-normal" class="panelsw" href="">子供 <span name="children">{$v->assign->params.child1+$v->assign->params.child2+$v->assign->params.child3+$v->assign->params.child4+$v->assign->params.child5}</span>名</a>
{include file='../_common/_form_capacity.tpl' form_capacity_nm='guests-normal'}
        {/if}
          </td>
        </tr>
{  if is_empty($v->assign->search_condition.form.hotel.hotel_cd)}
        <tr>
          <th><div class="div-h">予算</div></th>
          <td>1泊1部屋1人あたり<br />
        {foreach from=$v->assign->search_condition.form.charges.min item=charge_min name=charge_min}
          {if $smarty.foreach.charge_min.total == 1}
            {$charge_min.name}
            <input type="hidden" name="charge_min" value="{$charge_min.charge}" />
          {else}
            {if $smarty.foreach.charge_min.first}
              <select size="1" name="charge_min" class="text-right">
            {/if}
              <option value="{$charge_min.charge}"{if $charge_min.current_status} selected="selected"{/if}>{$charge_min.name}</option>
            {if $smarty.foreach.charge_min.last}
              </select>
            {/if}
          {/if}
        {/foreach}
        {foreach from=$v->assign->search_condition.form.charges.max item=charge_max name=charge_max}
          {if $smarty.foreach.charge_max.total == 1}
            {if $smarty.foreach.charge_min.total == 1 and $charge_max.charge == $v->assign->search_condition.form.charges.min.0.charge}{else}～{$charge_max.name}{/if}
            <input type="hidden" name="charge_max" value="{$charge_max.charge}" />
          {else}
            {if $smarty.foreach.charge_max.first}
              ～<select size="1" name="charge_max" class="text-right">
            {/if}
              <option value="{$charge_max.charge}"{if $charge_max.current_status} selected="selected"{/if}>{$charge_max.name}</option>
            {if $smarty.foreach.charge_max.last}
              </select>
            {/if}
          {/if}
        {/foreach}
          </td>
        </tr>
{  /if}
{* 地図 *}
{if is_empty($v->assign->search_condition.form.hotel.hotel_cd) and is_empty($v->assign->search_condition.form.hotel.title)}
        <tr>
          <th><div class="div-h">地域</div></th>
          <td>
            {include file='../_common/_form_select_place.tpl'}
          </td>
        </tr>
{  /if}
{if (!is_empty($v->assign->search_condition.form.type)) }
    <tr>
      <th><div>表示方法</div></th>
      <td><input name="type" value=""{if $v->assign->search_condition.form.type == 'list'} checked="checked"{/if} type="radio" id="list" /><label for="list">テキストで表示</label>&nbsp;&nbsp;
          <input name="type" value="map"{if $v->assign->search_condition.form.type == 'map'} checked="checked"{/if} type="radio" id="map" /><label for="map">地図で表示</label>
      </td>
    </tr>
{/if}
        <tr>
          <th><div>GoToトラベル<br>キャンペーン</div></th>
          <td>
            対象プランのみ表示<input type="checkbox" id="goto" name="goto" value="1" {if $v->assign->search_condition.form.goto == "1" }checked="checked"{/if}>
          </td>
        </tr>
      </table>
{if is_empty($v->assign->search_condition.form.hotel.hotel_cd)}
      <div class="btn-b01-143-sb" style="margin:0 auto;">
        <input class="btnimg collectBtn" type="image" src="{$v->env.root_path}img/btn/b01-search1.gif" alt="空室検索" />
      </div>
{  else}
      <div class="btn-b06-138-sc" style="margin:0 auto;">
        <input class="btnimg collectBtn" type="image" src="{$v->env.root_path}img/btn/b06-booking.gif" alt="予約へすすむ" />
      </div>
{  /if}
      <input type="hidden" name="today" value="{$smarty.now|date_format:'%Y-%m-%d'}" />

{* 施設・プラン *}
{  if !is_empty($v->assign->search_condition.form.hotel.hotel_cd)}<input type="hidden" name="hotel_cd" value="{$v->assign->search_condition.form.hotel.hotel_cd}" />{/if}
{  if !is_empty($v->assign->search_condition.form.hotel.plan_id)}<input type="hidden" name="plan_id" value="{$v->assign->search_condition.form.hotel.plan_id}" />{/if}
{  if !is_empty($v->assign->search_condition.form.hotel.room_id)}<input type="hidden" name="room_id" value="{$v->assign->search_condition.form.hotel.room_id}" />{/if}
    </form>
{/if}