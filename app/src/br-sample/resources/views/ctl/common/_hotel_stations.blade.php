{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\_common\_hotel_stations.tpl  --}}

{if $hotel_stations.values|@count != 0}
  {if $limit != false}
    {section name=limit_cnt start=0 loop=$limit}
      {if !zap_is_empty($hotel_stations.values[limit_cnt].traffic_way)}
        {if ($hotel_stations.values[limit_cnt].traffic_way == 0)}
          {if $hotel_stations.values[limit_cnt].minute == 0}
            すぐ ← {$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].route_nm)}{$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].station_nm)}<br />
          {elseif $hotel_stations.values[limit_cnt].minute > 0}
            徒歩 {$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].minute)}分 ← {$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].route_nm)}{$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].station_nm)}<br />
          {/if}
        {elseif ($hotel_stations.values[limit_cnt].traffic_way == 1)}
            車 {$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].minute)}分 ← {$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].route_nm)}{$v->helper->form->strip_tags($hotel_stations.values[limit_cnt].station_nm)}<br />
        {/if}
      {/if}
    {/section}
  {else}
    {foreach from=$hotel_stations.values item=hotel_station}
      {if ($hotel_station.traffic_way == 0)}
        {if $hotel_station.minute == 0}
          すぐ ← {$v->helper->form->strip_tags($hotel_station.route_nm)}{$v->helper->form->strip_tags($hotel_station.station_nm)}<br />
        {else}
          徒歩 {$v->helper->form->strip_tags($hotel_station.minute)}分 ← {$v->helper->form->strip_tags($hotel_station.route_nm)}{$v->helper->form->strip_tags($hotel_station.station_nm)}<br />
        {/if}
      {elseif ($hotel_station.traffic_way == 1)}
          車 {$v->helper->form->strip_tags($hotel_station.minute)}分 ← {$v->helper->form->strip_tags($hotel_station.route_nm)}{$v->helper->form->strip_tags($hotel_station.station_nm)}<br />
      {/if}
    {/foreach}
  {/if}
{else}
<br>
{/if}