{strip}
 {assign var=station_id value=$v->assign->search_condition.form.station.station_id}
  {assign var=vstation   value=$v->assign->values.stations[$station_id]}
  <div class="station-detail">
    <h2>{$vstation.station_nm}駅周辺の駅・路線</h2>
    <div class="rail">
      <div class="rail-l"></div>
      <div class="rail-station">●</div>
      <div class="rail-station">●</div>
      <div class="station-active"><img src="{$v->env.root_path}img/pg/station-active.gif" /></div>
      <div class="rail-station">●</div>
      <div class="rail-station">●</div>
      <div class="rail-r"></div>
    </div>
    {foreach from=$v->assign->piece.routes key=cnt item=route name=route}
      {if $smarty.foreach.route.index == 1}
        <div name="open_sesame_station_detail_box" style="display:none;">
          <div name="open_sesame_station_detail_msg_open" style="display:none;">路線を折りたたむ</div>
          <div name="open_sesame_station_detail_msg_close" style="display:none;">すべての路線を見る</div>
      {/if}
      {assign var=route_id value=$route.route_id}
      {assign var=vroute   value=$v->assign->values.routes[$route_id]}
      {foreach from=$route.stations key=cnt2 item=station name=station}
        {assign var=station_id value=$station.station_id}
        {assign var=vstation   value=$v->assign->values.stations[$station_id]}
        {assign var=query_string   value=$v->helper->form->to_query_correct('station_id', false)}
        {if $smarty.foreach.station.first == true}
          <div class="station-unit">
            <div class="station-unit-inner">
        {/if}
        {if $cnt2 == 2}
              {if ($station.start_station and $station.end_station)}
                <div class="stationunit-active-0">
              {elseif ($station.start_station)}
                <div class="stationunit-active-l0">
              {elseif ($station.end_station)}
                <div class="stationunit-active-r0">
              {else}
                <div class="stationunit-active">
              {/if}
                {if is_empty($query_string)}
                  <a href="{$v->env.path_base}/station/{$station_id}/">{$vstation.station_nm}</a>
                {else}
                  <a href="{$v->env.path_base}/station/{$station_id}/?{$query_string}">{$vstation.station_nm}</a>
                {/if}
              {if !is_empty($station.from_time)}
                  <div class="st-minute1">{$station.from_time}分</div>
              {else}
                  <div class="st-minute1">&nbsp;</div>
              {/if}

              {if !is_empty($station.to_time)}
                  <div class="st-minute2">{$station.to_time}分</div>
              {else}
                  <div class="st-minute2">&nbsp;</div>
              {/if}
                {if !is_empty($vroute.route_nm)}
                  <div class="link-railwayline-active">
                    <a href="{$v->env.path_base}/station/r{$route_id}/">{$vroute.route_nm}</a>
                  </div>
                {/if}
                </div>
        {else}
            {if is_empty($station_id)}
              <div class="stationunit0"></div>
            {elseif ($station.start_station)}
              <div class="stationunit1">
                {if is_empty($query_string)}
                  <a href="{$v->env.path_base}/station/{$station_id}/">{$vstation.station_nm}</a>
                {else}
                  <a href="{$v->env.path_base}/station/{$station_id}/?{$query_string}">{$vstation.station_nm}</a>
                {/if}
              </div>
            {elseif ($station.end_station)}
              <div class="stationunit4">
                {if is_empty($query_string)}
                  <a href="{$v->env.path_base}/station/{$station_id}/">{$vstation.station_nm}</a>
                {else}
                  <a href="{$v->env.path_base}/station/{$station_id}/?{$query_string}">{$vstation.station_nm}</a>
                {/if}
              </div>
            {else}
              <div class="stationunit2">
                {if is_empty($query_string)}
                  <a href="{$v->env.path_base}/station/{$station_id}/">{$vstation.station_nm}</a>
                {else}
                  <a href="{$v->env.path_base}/station/{$station_id}/?{$query_string}">{$vstation.station_nm}</a>
                {/if}
              </div>
            {/if}
        {/if}
        {if $smarty.foreach.station.last == true}
            </div>
          </div>
          <br clear="all" />
        {/if}
      {/foreach}
      {if $smarty.foreach.route.last == false}
          <hr>
      {else}
        {if 1 <= $smarty.foreach.route.index}
          </div>
        <div class="follow-railwayline"><p name="open_sesame_station_detail" class="accordion_head2 jqs-expand">すべての路線を見る</p></div>
        {/if}
      {/if}
    {/foreach}
  </div>
{/strip}
