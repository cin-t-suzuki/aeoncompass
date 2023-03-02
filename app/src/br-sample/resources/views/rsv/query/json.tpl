{ldelim}
  "hotels":[
  {foreach from=$v->assign->index.hotels key=hotel_cnt item=hotels name=hotels}
  {ldelim}
    "hotel_cd":{$hotels.hotel_cd},
    "hotel_nm":"{$v->assign->values.hotels[$hotels.hotel_cd].hotel_nm}",
    "lat":{$v->assign->values.hotels[$hotels.hotel_cd].wgs_lat_d},
    "lng":{$v->assign->values.hotels[$hotels.hotel_cd].wgs_lng_d},
    {if 0 < $v->assign->values.hotels[$hotels.hotel_cd].plan_count}
      "has_plan":true
    {else}
      "has_plan":false
    {/if}
    {if $smarty.foreach.hotels.last}
      {rdelim}
    {else}
      {rdelim},
    {/if}
  {/foreach}
  ],{include file='./_filter.tpl' type='map' assign=html}
  "filters":[
    {ldelim}
      "html":"{$html|escape|replace:"\n":""}"{*    * & " ' < >    *}
    {rdelim}
  ]
{rdelim}


