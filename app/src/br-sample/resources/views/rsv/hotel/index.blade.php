@extends('rsv.common.base')
@section('title', '')
@section('words', '')


@section('page_blade')
{{-- ヘッダナビゲーションバー --}}
@include('rsv.common.header_main_nav1', ['pgh1_mnv' => 1])

<div id="pgh2">
    <div class="pg">
        <div class="pgh2-inner"></div>
    </div>
</div>

<div id="pgc1">
    <div class="pg">
        <div class="pgc1-inner">
            @include('rsv.common.pgc1_breadcrumbs', [])
            @include('rsv.common.snv_text.blade', ['current' => 'hotel'])
        </div>
    </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">

<div class="pgc2-hotel">
  {include file='../_common/_hi_box.tpl' b06_clip=true b06_search=true rel="on"}

  {* キャンペーン *}
  {if $v->hotel.camps.values|@count != 0}
  <div class="hi-box-camp">
    <div class="title">キャンペーン</div>
    {foreach from=$v->hotel.camps.values key=camp_no item=hotel_camps}
      <div>
          <a href="{$v->env.path_base_module}/campaign/reserve/?hotel_cd={$v->hotel.hotel_cd}&camp_cd={$camp_no}">{$v->helper->form->strip_tags($hotel_camps.camp_nm)}</a>
      </div>
    {/foreach}
  </div>
  {/if}


  <div class="hi-box2">
    <table class="hi-table" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <th valign="top" nowrap="nowrap"><div>特色</div></th>
        <td valign="top" id="info">
          {$v->helper->form->strip_tags($v->hotel.info.values.info)|replace:"\n":"<br />"}
          {if isset($v->hotel.agoda_local.values)}
          <br/><br/>【周辺情報】<br/>
          {foreach from=$v->hotel.agoda_local.values item=item_local}
          {if $item_local.type == 3}
            <span style="display:inline-block; width: 160px; text-align: left;">{$item_local.name}</span>
            <span>{$item_local.distance}{$item_local.distance_unit}</span>
            <br/>
          {/if}
          {/foreach}
          {/if}
        </td>
      </tr>
      <tr>
        <th valign="top" nowrap="nowrap"><div>住所</div></th>
        <td valign="top">〒{$v->helper->form->strip_tags($v->hotel.postal_cd)} {$v->helper->form->strip_tags($v->hotel.pref_nm)}{$v->helper->form->strip_tags($v->hotel.address)}</td>
      </tr>
      <tr>
        <th valign="top" nowrap="nowrap"><div>連絡先</div></th>
        <td valign="top">TEL：{$v->helper->form->strip_tags($v->hotel.tel)} FAX：{$v->helper->form->strip_tags($v->hotel.fax)}</td>
      </tr>
      <tr>
        <th valign="top" nowrap="nowrap"><div>チェックイン</div></th>
        <td valign="top">
          {$v->helper->form->strip_tags($v->hotel.check_in)} ～{if ($v->helper->form->strip_tags($v->hotel.check_in_end) != "")}{$v->helper->form->strip_tags($v->hotel.check_in_end)}{/if}
        </td>
      </tr>
      <tr>
        <th valign="top" nowrap="nowrap"><div>チェックアウト</div></th>
        <td valign="top">{$v->helper->form->strip_tags($v->hotel.check_out)}</td>
      </tr>
      <tr>
        <th valign="top" nowrap="nowrap"><div>アクセス情報</div></th>
        <td valign="top">
          {if $v->assign->hotel_stations.values|@count != 0}
            {foreach from=$v->assign->hotel_stations.values item=hotel_station}
              {if ($hotel_station.traffic_way == 0)}
                  {if $hotel_station.minute == 0}すぐ ← {$hotel_station.route_nm}{$hotel_station.station_nm}<br />
                  {else}徒歩 {$hotel_station.minute}分 ← {$hotel_station.route_nm}{$hotel_station.station_nm}<br />
                  {/if}
              {elseif ($hotel_station.traffic_way == 1)}
                車 {$hotel_station.minute}分 ← {$hotel_station.route_nm}{$hotel_station.station_nm}<br />
              {/if}
            {/foreach}
          {/if}
          {if isset($v->hotel.agoda_local.values)}
            {foreach from=$v->hotel.agoda_local.values item=item_local}
              {if $item_local.type == 1 || $item_local.type == 2}
                <span style="display:inline-block; width: 160px; text-align: left;">{$item_local.name}</span>
                <span>{$item_local.distance}{$item_local.distance_unit}</span>
                <br/>
              {/if}
            {/foreach}
          {/if}
        </td>
      </tr>

      {* 設備サービス情報 *}
      {assign var=elements_hotel value=''}
      {foreach from=$v->hotel.facilities.values item=hotel_facilities}
         {if !is_empty($elements_hotel)}
           {assign var=elements_hotel value=$elements_hotel|cat:'、'}
         {/if}
         {assign var=elements_hotel value=$elements_hotel|cat:$hotel_facilities.element_nm}
         {if $hotel_facilities.element_value_text != 'あり'}
           {assign var=elements_hotel value=$elements_hotel|cat:'（'|cat:$hotel_facilities.element_value_text|cat:'）'}
         {/if}
      {/foreach}
      {foreach from=$v->hotel.services.values item=hotel_services}
        {if !is_empty($elements_hotel)}
          {assign var=elements_hotel value=$elements_hotel|cat:'、'}
        {/if}
        {assign var=elements_hotel value=$elements_hotel|cat:$hotel_services.element_nm}
        {if $hotel_services.element_value_text != 'あり'}
          {assign var=elements_hotel value=$elements_hotel|cat:'（'|cat:$hotel_services.element_value_text|cat:'）'}
        {/if}
      {/foreach}
      {if !is_empty($v->hotel.nearbies.values) || !is_empty($v->hotel.agoda_local.values)}
        {assign var=elements_hotel value=$elements_hotel|cat:'<br /><br />【 周辺情報 】<br />'}
      {/if}
      {foreach from=$v->hotel.nearbies.values item=hotel_nearbies name=hotel_nearbies}
        {if !$smarty.foreach.hotel_nearbies.first}
          {assign var=elements_hotel value=$elements_hotel|cat:'、'}
        {/if}
        {assign var=elements_hotel value=$elements_hotel|cat:$hotel_nearbies.element_nm}
        {if isset($hotel_nearbies.distance)}
          {assign var=elements_hotel value=$elements_hotel|cat:$hotel_nearbies.distance}
        {elseif $hotel_nearbies.element_value_text != 'あり'}
          {assign var=elements_hotel value=$elements_hotel|cat:'（5分以内）'}
        {/if}
      {/foreach}
      {assign var=is_first value=true}
      {foreach from=$v->hotel.agoda_local.values item=item_local}
        {if $item_local.type == 4}
          {if $is_first === false}
            {assign var=elements_hotel value=$elements_hotel|cat:'、'}
          {/if}
          {assign var=elements_hotel value=$elements_hotel|cat:$item_local.name}
          {assign var=elements_hotel value=$elements_hotel|cat:"("|cat:$item_local.distance|cat:$item_local.distance_unit|cat:")"}
          {assign var=is_first value=false}
        {/if}
      {/foreach}
      {if (!is_empty($elements_hotel))}
      <tr>
        <th valign="top" nowrap="nowrap"><div>館内設備</div></th>
        <td valign="top">{$elements_hotel}</td>
      </tr>
      {/if}

      {assign var=elements_room value=''}
      {foreach from=$v->hotel.facility_rooms.values item=hotel_facility_rooms}
        {if !is_empty($elements_room)}
          {assign var=elements_room value=$elements_room|cat:'、'}
        {/if}
        {assign var=elements_room value=$elements_room|cat:$hotel_facility_rooms.element_nm}
        {if $hotel_facility_rooms.element_value_text != 'あり'}
          {assign var=elements_room value=$elements_room|cat:'（'|cat:$hotel_facility_rooms.element_value_text|cat:'）'}
        {/if}
      {/foreach}
      {foreach from=$v->hotel.amenities.values item=hotel_amenities}
        {if !is_empty($elements_room)}
          {assign var=elements_room value=$elements_room|cat:'、'}
        {/if}
        {assign var=elements_room value=$elements_room|cat:$hotel_amenities.element_nm}
        {if $hotel_amenities.element_value_text != 'あり'}
          {assign var=elements_room value=$elements_room|cat:'（'|cat:$hotel_amenities.element_value_text|cat:'）'}
        {/if}
      {/foreach}
      {if !is_empty($elements_room)}
      <tr>
        <th valign="top" nowrap="nowrap"><div>部屋設備・アメニティ</div></th>
        <td valign="top">{$elements_room}</td>
      </tr>
      {/if}

      {if $v->hotel.cards.values|@count != 0}
      <tr>
        <th valign="top" nowrap="nowrap"><div>クレジットカード</div></th>
        <td valign="top">
           {foreach from=$v->hotel.cards.values item=hotel_cards name=cards}
             {if ($smarty.foreach.cards.first != true)}、{/if }
             {$v->helper->form->strip_tags($hotel_cards.card_nm)}
           {/foreach}
           {if (!is_empty($v->hotel.info.values.card_info))}<br />{$v->helper->form->strip_tags($v->hotel.info.values.card_info)}{/if}
        </td>
      </tr>
      {else}
      <tr>
        <th valign="top" nowrap="nowrap"><div>クレジットカード</div></th>
        {if !isset($v->assign->is_agoda) || (isset($v->assign->is_agoda) && is_empty($v->hotel.info.values.card_info))}
        <td valign="top">クレジットカードはご利用いただけません。</td>
        {else}
        <td valign="top">{$v->hotel.info.values.card_info}</td>
        {/if}
      </tr>
      {/if}

      <tr>
        <th valign="top" nowrap="nowrap"><div>注意事項</div></th>
        <td valign="top">
          {foreach from=$v->hotel.inform.values.0 item=hotel_inform_cancel name=nearbies}
            {$v->helper->form->strip_tags($hotel_inform_cancel.inform)|replace:"\n":"<br />"}<br />
          {/foreach}

           <br />
           ■キャンセルポリシー{$v->hotel.policy.values.cancel_policy_type}
           <br />
          {foreach from=$v->hotel.policy.values.cancel_rate item=value}
            {if ($value.cancel_rate != 0)}
              {if       $value.days < 0 }連絡なしの不泊
              {elseif   $value.days == 0}宿泊日の当日
              {elseif   $value.days == 1}宿泊日の前日
              {elseif $value.days == 999}予約時から
              {else}宿泊日の{$v->helper->form->strip_tags($value.days)}日前から
              {/if}：{$v->helper->form->strip_tags($value.cancel_rate)}％<br />
            {/if}
          {/foreach}
          <br />
          {$v->helper->form->strip_tags($v->hotel.policy.values.cancel_policy)|replace:"\n":"<br />"}
          {if !isset($v->hotel.is_agoda)}
           <br /><br />※ キャンセルポリシーはプラン個別に設定されている場合があります。<br /><br />
          {/if}

          {foreach from=$v->hotel.inform.values.1 item=hotel_inform_free}
            {$v->helper->form->strip_tags($hotel_inform_free.inform)|replace:"\n":"<br />"}<br />
          {/foreach}
        </td>
      </tr>

     {* 施設URLリンク情報 *}
     {if ($v->hotel.links.values|@count != 0)}
      <tr>
        <th valign="top" nowrap="nowrap"><div>URL</div></th>
        <td valign="top">
        {* 施設オリジナルサイトURL *}
        {if !is_empty($v->hotel.links.values.1.0)}
          <a href="{$v->hotel.links.values.1.0.url}" target="_blank"> {$v->helper->form->strip_tags($v->hotel.links.values.1.0.title)}</a><br />
        {/if}
        {* 携帯サイトURL *}
        {if !is_empty($v->hotel.links.values.2.0)}
          <a href="{$v->hotel.links.values.2.0.url}" target="_blank"> {$v->helper->form->strip_tags($v->hotel.links.values.2.0.title)}</a><br />
        {/if}
        {* その他URL *}
        {foreach from=$v->hotel.links.values.3 item=hotel_link name=link}
          <a href="{$hotel_link.url}" target="_blank"> {$v->helper->form->strip_tags($hotel_link.title)}</a><br />
        {/foreach}
        {/if}
        </td>
      </tr>
    </table>
  </div>

{include file='../_common/_voice_review.tpl' hotel_review=$v->hotel.hotel_review total_voice_cnt=$v->hotel.voices.values.0.total_count}

</div>

    </div>
  </div>
</div>

<div class="jqs-include" name="{$v->env.path_base_module}/recommend/?prod={$v->hotel.hotel_cd}&type=pc311&num=0"></div>
@endsection
