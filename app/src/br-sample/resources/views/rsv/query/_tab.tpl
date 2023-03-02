{strip}
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td valign="bottom">
        <div class="sch-tab">
          {if nvl($v->assign->search_condition.type, 'hotel') == 'hotel'}
            <img src="{$v->env.path_img}/btn/b07-byhotel-current.gif" width="106" height="27" alt="施設ごとに表示" />
          {else}
            <a class="btnimg" href="{$v->env.path_base}{$v->env.path_x_uri}/?type=hotel&{$v->helper->form->to_query_correct("type,page", false)}"><img src="{$v->env.path_img}/btn/b07-byhotel.gif" width="106" height="27" alt="施設ごとに表示" /></a>
          {/if}
          {if $v->assign->search_condition.type == 'plan'}
            <img src="{$v->env.path_img}/btn/b07-byplan-current.gif" width="106" height="27" alt="プランごとに表示" />
          {elseif nvl($v->assign->search_condition.type, 'hotel') == 'hotel' and $v->assign->index.page.total_count == 1}
            <img src="{$v->env.path_img}/btn/b07-byplan.gif" width="106" height="27" alt="プランごとに表示" />
          {else}
            <a class="btnimg" href="{$v->env.path_base}{$v->env.path_x_uri}/?type=plan&{$v->helper->form->to_query_correct("type,page", false)}"><img src="{$v->env.path_img}/btn/b07-byplan.gif" width="106" height="27" alt="プランごとに表示" /></a>
          {/if}
          {if is_empty($v->assign->keywords.words)}
            <a class="btnimg" href="{$v->env.path_base}/query/map/?lat={$v->assign->search_condition.various.lat}&lng={$v->assign->search_condition.various.lng}&min_lat={$v->assign->search_condition.various.latlngbounds.min_wgs_lat_d}&min_lng={$v->assign->search_condition.various.latlngbounds.min_wgs_lng_d}&max_lat={$v->assign->search_condition.various.latlngbounds.max_wgs_lat_d}&max_lng={$v->assign->search_condition.various.latlngbounds.max_wgs_lng_d}&zoomlevel={$v->assign->search_condition.various.zoomlevel}&{$v->helper->form->to_query_correct("type,lat,lng,min_lat,min_lng,max_lat,max_lng,zoomlevel,page", false)}"><img src="{$v->env.path_img}/btn/b07-bymap.gif" width="106" height="27" alt="地図で表示" /></a>
          {/if}
        </div>
      </td>

      {if $v->assign->params.stay == 1}
        <td valign="bottom">
          <div style="text-align:right;padding:4px 0;">
            ※料金表示は、1泊1部屋あたり大人1名料金です。
          </div>
        </td>
      {else}
        <td valign="bottom">
          <div style="text-align:right;padding:4px 0;">
            ※料金表示は、1泊1部屋あたり大人1名料金です。<br />（連泊の場合は、一番お安い日程の料金を表示してます）
          </div>
        </td>
      {/if}
    </tr>
  </table>
{/strip}
