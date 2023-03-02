<div class="search-re-inner">
<form method="get" action="{$v->env.path_base}{$v->env.path_x_uri}/" class="pgc1-form jqs-query {if (is_empty($v->assign->params.year_month) or is_empty($v->assign->params.day)) and is_empty($v->assign->params.capacity) and is_empty($v->assign->params.senior)}parseForm{/if}">

{if (!is_empty($v->assign->keywords.words) or ($v->assign->keywords.status == 'error' ))}

  {* エラーメッセージ *}
  {if (($v->assign->keywords.status == 'error' ) and ($v->error->has()))}
    <div class="msg-error">
  {  foreach from=$v->error->gets() item=error name=error}
  {      $v->helper->form->strip_tags($error, '<br>', false)}<br />
  {  /foreach}
    </div>
  {/if}

<script src="/js/jquery-ui.js"></script>
<script src="/js/keyword_suggest.js"></script>
<link type="text/css" rel="stylesheet" href="/css/jquery-ui.css" />

  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>
        <dl>
          <dt>キーワード</dt>
          <dd class="keep" style="margin-right:0;">
            <input class="focus" type="text" name="keywords" style="width:30em;" value="{$v->assign->keywords.words|escape:'html'}" id="f_query" autocomplete="off" >
         </dd>
        </dl>
      </td>
      <td>
        <input class="btn-b01-113-sc btnimg collectBtn" type="image" src="{$v->env.path_img}/btn/b01-search2.gif" alt="再検索" />
      </td>
    </tr>
  </table>

  <div class="pgc1-hr"></div>

  {assign var=s_keywords_display value="enabled" }

{/if}

{if !is_empty($use_expand)}
  {if (is_empty($v->assign->params.year_month) or is_empty($v->assign->params.day)) and is_empty($v->assign->params.capacity) and is_empty($v->assign->params.senior)}
    <h3 class="accordion_head"><div name="open_sesame_form_search" class="jqs-expand">旅程条件を追加して空室を探す</div></h3>
    <div name="open_sesame_form_search_box" style="display:none;">
    {if !is_empty($s_keywords_display)}
      {assign var=s_disabled value='disabled="disabled"'}
    {/if}
  {else}
    <h3 class="accordion_head"><div name="open_sesame_form_search" class="jqs-expand">旅程条件を解除する</div></h3>
    <div name="open_sesame_form_search_box">
    {if !is_empty($s_keywords_display)}
      {assign var=s_disabled value=""}
    {/if}
  {/if}
{else}
  {if (is_empty($v->assign->params.year_month) or is_empty($v->assign->params.day)) and is_empty($v->assign->params.capacity) and is_empty($v->assign->params.senior)}
    <h3 class="accordion_head"><div name="open_sesame_form_search" class="jqs-expand">旅程条件を追加して空室を探す</div></h3>
    <div name="open_sesame_form_search_box" style="display:none;">
    {if !is_empty($s_keywords_display)}
      {assign var=s_disabled value='disabled="disabled"'}
    {/if}
  {else}
    <h3><div name="open_sesame_form_search"></div></h3>
    <div name="open_sesame_form_search_box">
    {if !is_empty($s_keywords_display)}
      {assign var=s_disabled value=""}
    {/if}
  {/if}
{/if}

  <div name="open_sesame_form_search_msg_open" style="display:none;">旅程条件を解除する</div>
  <div name="open_sesame_form_search_msg_close" style="display:none;">旅程条件を追加して空室を探す</div>

      <dl>
        <dt>旅行日程</dt>
        <dd class="keep text-right valgn-t">
            {* 年月表示（13月まで表示) *}
            <select size="1" name="year_month" {$s_disabled} >
            {foreach from=$v->assign->search_condition.form.year_month item=months}
            <option value="{$months.date_ym}"{if $months.current_status} selected="selected"{/if}>{$months.date_ym|substr:0:4}年{$months.date_ym|substr:5:2|number_format}月</option>
            {/foreach}
            </select>
            {* 日表示のための31回ループ *}
            <select size="1" name="day" class="text-right" {$s_disabled} >
            {foreach from=$v->assign->search_condition.form.days item=days}
            <option value="{$days.date_ymd}"{if $days.current_status} selected="selected"{/if}>{$days.date_ymd}日</option>
            {/foreach}
            </select>&nbsp;
            <a class="jqs-calendar" href="#calendar"><img src="{$v->env.path_img}/lhd/lhd-calendar.gif" /></a>&nbsp;
        </dd>
        <dd>
          <select size="1" name="stay" class="text-right" {$s_disabled} >
            {foreach from=$v->assign->search_condition.form.stay item=stay}
              <option value="{$stay.days}"{if $stay.current_status} selected="selected"{/if}>{$stay.days}泊</option>
            {/foreach}
          </select>
        </dd>
        <dd>
         <input name="date_status" value="on" type="checkbox"{if $v->assign->search_condition.form.date_status == 'on'} checked='checked'{/if} id="date_status"  {$s_disabled} /> <label for="date_status">日程未定</label>
        </dd>
      </dl>
      <br clear="all">
      <dl class="pgc1-hr">
        <dt>部屋数</dt>
        <dd  class="text-right">
          <select size="1" name="rooms" class="text-right"  {$s_disabled} >
          {foreach from=$v->assign->search_condition.form.rooms item=rooms}
            <option value="{$rooms.room_count}"{if $rooms.current_status} selected="selected"{/if}>{$rooms.room_count}室</option>
          {/foreach}
          </select>
        </dd>
        <dt>利用人数／室</dt>
        <dd class="keep">
            大人<select size="1" name="senior" class="text-right" {$s_disabled} >
          {foreach from=$v->assign->search_condition.form.senior.capacities item=senior}
            <option value="{$senior.capacity}"{if $senior.current_status} selected="selected"{/if}>{$senior.capacity}名</option>
          {/foreach}
        </select>
        {if $v->assign->search_condition.form.childs.accept_status }
            <a name="guests-query" class="panelsw" href="">子供 <span name="children">{$v->assign->params.child1+$v->assign->params.child2+$v->assign->params.child3+$v->assign->params.child4+$v->assign->params.child5}</span>名</a>
{include file='../_common/_form_capacity.tpl' form_capacity_nm='guests-query' s_disabled = $s_disabled }
        {/if}
        </dd>
        <dt>予算</dt>
        <dd><div class="charge-ds">1泊1部屋<br>1人あたり</div>
        {foreach from=$v->assign->search_condition.form.charges.min item=charge_min name=charge_min}
          {if $smarty.foreach.charge_min.total == 1}
            {$charge_min.name}
            <input type="hidden" name="charge_min" value="{$charge_min.charge}" {$s_disabled}  />
          {else}
            {if $smarty.foreach.charge_min.first}
              <select size="1" name="charge_min" class="text-right" {$s_disabled} >
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
            <input type="hidden" name="charge_max" value="{$charge_max.charge}" {$s_disabled}  />
          {else}
            {if $smarty.foreach.charge_max.first}
              ～<select size="1" name="charge_max" class="text-right" {$s_disabled} >
            {/if}
              <option value="{$charge_max.charge}"{if $charge_max.current_status} selected="selected"{/if}>{$charge_max.name}</option>
            {if $smarty.foreach.charge_max.last}
              </select>
            {/if}
          {/if}
        {/foreach}
        </dd>
      </dl>


    {* 駅が指定された場合は地域および施設カテゴリを表示しない。 *}
    {if is_empty($v->assign->search_condition.form.station.station_id) and (is_empty($v->assign->keywords.words) and !($v->assign->keywords.status == 'error' ))}
      <br clear="all">
      <dl class="pgc1-hr">
        {*  旅館・ホテル検索などデフォルト表示 *}
        {if $search_place == 'on'}
          <dt>地域</dt>
          <dd>
            {include file='../_common/_form_select_place.tpl'}

        {*   施設固定での検索 *}
        {elseif $v->assign->search_condition.form.hotel.hotel_cd|count_characters == 10}
          <dt>旅館・ホテル</dt>
          <dd>
            {assign var=hotel_cd value=$v->assign->search_condition.form.hotel.hotel_cd}
            {assign var=vhotel   value=$v->assign->values.hotels[$hotel_cd]}

            <a href="{$v->env.path_base}/hotel/{$hotel_cd}/">{$v->helper->form->strip_tags($vhotel.hotel_nm)}</a>

        {* 複数ホテル *}
        {elseif 10 < $v->assign->search_condition.form.hotel.hotel_cd|count_characters}
          <dt>旅館・ホテル</dt>
          <dd>
            {if is_empty($v->assign->search_condition.form.hotel.title)}
              キャンペーン中の旅館・ホテル
            {else}
              {if is_empty($v->assign->landing_url)}
                {$v->assign->search_condition.form.hotel.title}
              {else}
                <a href="{$v->assign->landing_url}">{$v->assign->search_condition.form.hotel.title}</a>
              {/if}
            {/if}
        {* ホテルのタイトル指定 *}
        {elseif !is_empty($v->assign->search_condition.form.hotel.title)}
          <dt>旅館・ホテル</dt>
          <dd>
            {if is_empty($v->assign->landing_url)}
              {$v->assign->search_condition.form.hotel.title}
            {else}
              <a href="{$v->assign->landing_url}">{$v->assign->search_condition.form.hotel.title}</a>
            {/if}
        {* クリップホテル *}
        {elseif !is_empty($v->assign->search_condition.clip_hotel)}
          <dt>旅館・ホテル</dt>
          <dd>
            クリップホテル
        {* 最近見たホテル *}
        {*elseif !is_empty($v->assign->conditions.recent_hotel)*}
          {*<dd>*}
            {*最近見たホテルの検索*}

        {* ハイランクホテル等 *}
        {*elseif !is_empty($v->assign->conditions.hotel_type)*}
        {*{if !is_empty($v->assign->conditions.hotel_type_uri)}<a href="{$v->env.path_base}{$v->assign->conditions.hotel_type_uri}">{$v->assign->conditions.hotel_type_nm}</a>{else}{$v->assign->conditions.hotel_type_nm}{/if}*}

        {* 地図オープン後に遷移した場合 *}
        {elseif $v->assign->search_condition.various.map == 'opened'}
          <dt>地域</dt>
          <dd>
            {include file='../_common/_form_select_place.tpl'}

        {* 地図表示の場合 *}
        {elseif nvl($v->assign->search_condition.type, 'hotel') == 'map'}
          <dt>地域</dt>
          <dd>
            <div id="place"></div>

        {* ランドマーク *}
        {elseif !is_empty($v->assign->piece.areas.landmarks)}
          <dt>ランドマーク</dt>
          <dd>
            {assign var=landmark_id value=$v->assign->search_condition.form.landmark.landmark_id}
            {$v->assign->values.landmarks[$landmark_id].landmark_nm}

        {* 指定されたラベルの場合 *}
        {elseif !is_empty($v->assign->area_label)}
          <dd>
            {$v->assign->area_label}

            {foreach from=$v->assign->hiddens key=key item=value}
              {if is_array($value)}
                {foreach from=$v->assign->hiddens.$key key=index item=text}
                  <input type="hidden" name="{$v->helper->form->strip_tags($key)}[{$index}]" value="{$v->helper->form->strip_tags($text)}" />
                {/foreach}
              {else}
                <input type="hidden" name="{$v->helper->form->strip_tags($key)}" value="{$v->helper->form->strip_tags($value)}" />
              {/if}
            {/foreach}

        {* 都道府県・区域 *}
        {else}
          <dt>地域</dt>
          <dd>
            {include file='../_common/_form_select_place.tpl'}
          {/if}
        </dd>
      </dl>

      {*   プラン固定での検索 *}
      {if $v->assign->search_condition.form.hotel.hotel_cd|count_characters == 10 && !is_empty($v->assign->search_condition.form.hotel.plan_id)}
        {assign var=hotel_cd value=$v->assign->search_condition.form.hotel.hotel_cd}
        {assign var=plan_id  value=$v->assign->search_condition.form.hotel.plan_id}
        {assign var=vhotel   value=$v->assign->values.hotels[$hotel_cd]}
        {assign var=vplan    value=$v->assign->values.hotels[$hotel_cd].plans[$plan_id]}
        <br clear="all">
        <dl class="pgc1-hr">
          <dt>プラン</dt>
          <dd>
            <a href="{$v->env.path_base}/plan/{$hotel_cd}/{$plan_id}/">{$v->helper->form->strip_tags($vplan.plan_nm)}</a>
          </dd>
        </dl>

      {/if}
    {/if}
        <br clear="all">
        <dl class="pgc1-hr">
          <dt>GoToキャンペーン</dt>
          <dd  class="text-right">
              <input type="checkbox"  size="1" name="goto" value="1" {if $v->assign->search_condition.form.goto == "1" }checked="checked"{/if} >対象プランのみ表示
          </dd>
        </dl>


    {if $search_first == 'on'}
      <div class="search-re-inner-btn">
      <div class="btn-b06-138-s">
        <input class="btnimg collectBtn" type="image" src="{$v->env.path_img}/btn/b06-search1.gif" alt="空室検索" />
      </div>
      </div>
    {else}
      <div class="search-re-inner-btn">
      <div class="submit">
        <input class="btnimg collectBtn" type="image" src="{$v->env.path_img}/btn/b01-search2.gif" alt="再検索" />
      </div>
      </div>
    {/if}

    <input type="hidden" name="hotel_cd" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.hotel.hotel_cd)}" />
    <input type="hidden" name="room_id" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.hotel.room_id)}" />
    <input type="hidden" name="plan_id" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.hotel.plan_id)}" />
    <input type="hidden" name="station_id" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.station.station_id)}" />
    <input type="hidden" name="landmark_id" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.landmark.landmark_id)}" />
    <input type="hidden" name="today" value="{$smarty.now|date_format:'%Y-%m-%d'}" />
    <input type="hidden" name="lat" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.wgs.wgs_lat_d)}" />
    <input type="hidden" name="lng" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.wgs.wgs_lng_d)}" />
    <input type="hidden" name="zoomlevel" value="15" />
    {if !is_empty($v->assign->search_condition.clip_hotel)}
      <input type="hidden" name="clip_hotel" value="$v->assign->search_condition.clip_hotel" />
    {/if}

    {if !is_empty($v->assign->search_condition.form.hotel.title)}
      <input type="hidden" name="hotels_title" value="{$v->helper->form->strip_tags($v->assign->search_condition.form.hotel.title)}" />
    {/if}
    {if !is_empty($v->assign->params.point_min)}
      <input type="hidden" name="point_min" value="{$v->assign->params.point_min}" />
    {/if}
    {if !is_empty($v->assign->params.point_max)}
      <input type="hidden" name="point_max" value="{$v->assign->params.point_max}" />
    {/if}

    {if !is_empty($v->assign->params.hotel_type)}
      {foreach from=$v->assign->params.hotel_type key=index item=text}
        <input type="hidden" name="hotel_type[{$index}]" value="{$v->helper->form->strip_tags($text)}" />
        {if !is_empty($v->assign->place)}
          <input type="hidden" name="place" value="{$v->assign->place}" />
        {/if}
      {/foreach}
    {/if}


</div>
  </form>
</div>