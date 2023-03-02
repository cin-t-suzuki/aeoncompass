{* 施設固定での検索             *}{if $v->assign->search_condition.form.hotel.hotel_cd|count_characters == 10}
{* 複数ホテル                   *}{elseif 10 < $v->assign->search_condition.form.hotel.hotel_cd|count_characters}
{* ホテルのタイトル指定         *}{elseif !is_empty($v->assign->search_condition.form.hotel.title)}
{* クリップホテル               *}{elseif !is_empty($v->assign->search_condition.clip_hotel)}
{* 地図オープン後に遷移した場合 *}{elseif $v->assign->search_condition.various.map == 'opened'}
{* 地図表示の場合               *}{elseif nvl($v->assign->search_condition.type, 'hotel') == 'map'}
{* ランドマーク                 *}{elseif !is_empty($v->assign->piece.areas.landmarks)}
{* 指定されたラベルの場合       *}{elseif !is_empty($v->assign->area_label)}
{* 上記以外の場合表示 都道府県・区域・駅 *}
{else}
  {if !is_empty($v->assign->params.area_id)}        <div class="jqs-include" name="{$v->env.path_base_module}/recommend/?type={$spec}_a{$v->assign->params.area_id}"></div>
  {elseif !is_empty($v->assign->params.ward_id)}    <div class="jqs-include" name="{$v->env.path_base_module}/recommend/?type={$spec}_w{$v->assign->params.ward_id}"></div>
  {elseif !is_empty($v->assign->params.city_id)}    <div class="jqs-include" name="{$v->env.path_base_module}/recommend/?type={$spec}_c{$v->assign->params.city_id}"></div>
  {elseif !is_empty($v->assign->params.pref_id)}    <div class="jqs-include" name="{$v->env.path_base_module}/recommend/?type={$spec}_p{$v->assign->params.pref_id}"></div>
  {elseif !is_empty($v->assign->params.station_id)} <div class="jqs-include" name="{$v->env.path_base_module}/recommend/?type={$spec}_s{$v->assign->params.station_id}"></div>
  {/if}
{/if}

