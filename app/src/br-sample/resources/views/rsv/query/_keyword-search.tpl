<div class="keyword-search">


{if !($v->assign->keywords.station == 0)}
<div class="keyword-list jqs-expand" name="open_sesame_hit_station">”{$v->assign->keywords.words|escape:'html'}”が含まれる駅(<span class="keyword-hitcount"> {$v->assign->keywords.station|number_format} </span>)件を見る</div>
<div class="list-keywords" name="open_sesame_hit_station_box" style="display:none;">
<ul>
  {assign var=cnt value=0}
  {foreach from=$v->assign->index.areas key=area_no item=iarea name=areas}
    {foreach from=$iarea.prefs key=pref_no item=ipref name=prefs}
      {foreach from=$ipref.railwaies key=pref_no item=irailway name=railwaies}
        {foreach from=$irailway.routes key=route_no item=iroute name=routes}
          {foreach from=$iroute.stations key=station_no item=istation name=stations}
            {assign var=vstation value=$v->assign->values.stations[$istation.station_id]}
            {assign var=cnt value=$cnt+1}
            <li><a href="{$v->env.path_base}/station/{$istation.station_id}/">{$vstation.station_nm}駅</a>{if $cnt != $v->assign->keywords.station} {/if}</li>
          {/foreach}
        {/foreach}
      {/foreach}
    {/foreach}
  {/foreach}
</ul>
</div>
{/if}

{if !($v->assign->keywords.places == 0)}
<div class="keyword-list jqs-expand" name="open_sesame_hit_area">”{$v->assign->keywords.words|escape:'html'}”が含まれる地域(<span class="keyword-hitcount"> {$v->assign->keywords.places|number_format} </span>)件を見る</div>
<div class="list-keywords" name="open_sesame_hit_area_box" style="display:none;">
<ul>
  {assign var=cnt value=0}
  {foreach from=$v->assign->index.areas key=area_no item=iarea name=areas}
    {assign var=varea value=$v->assign->values.areas[$iarea.area_id]}
    {assign var=cnt value=$cnt+1}
    <li><a href="{$v->env.path_base}/list/{$varea.area_cd}/">{$varea.area_nm}</a>{if $cnt != $v->assign->keywords.places} {/if}</li>
  {/foreach}
</ul>
</div>
{/if}

{* ランドマーク対応まで保留
{if !($v->assign->keywords.landmark == 0)}
<div class="keyword-list jqs-expand" name="open_sesame_hit_landmark">”{$v->assign->keywords.words|escape:'html'}”が含まれるランドマーク(<span class="keyword-hitcount"> {$v->assign->keywords.landmark|number_format} </span>)件を見る</div>
<div class="list-keywords" name="open_sesame_hit_landmark_box" style="display:none;">
<ul>
  {assign var=landmark_cnt value=0}
  {foreach from=$v->assign->index.areas key=area_no item=iarea name=areas}
    {foreach from=$iarea.landmarks key=landmark_no item=ilandmark name=landmarks}
      {assign var=vlandmark value=$v->assign->values.landmarks[$ilandmark.landmark_id]}
      {assign var=landmark_cnt value=$landmark_cnt+1}
      <li><a href="{$v->env.path_base}/query/?landmark_id={$ilandmark.landmark_id}">{$vlandmark.landmark_nm}</a>{if $landmark_cnt != $v->assign->keywords.landmark} {/if}</li>
    {/foreach}
  {/foreach}
</ul>
</div>
{/if}
*}

</div>