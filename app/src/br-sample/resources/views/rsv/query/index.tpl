{include file='../_common/_header.tpl'}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

{if $v->assign->map == 'opened'}
{literal}
<script language="javascript"  type="text/javascript">
<!--
$(function() {
  $(document).ready(function () {
      BRJ.Gmap.thumbnail({/literal}{$v->assign->lat}{literal}, {/literal}{$v->assign->lng}{literal}, {/literal}{$v->assign->zoomlevel}{literal});
  });
});
//-->
</script>

{/literal}
{/if}

<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner">
    </div>
{include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
      {if !is_empty($v->hotel.capacitys) or !is_empty($v->plan.capacitys) or !is_empty($v->assign->capacitys)}{* 再検索できない場合は再検索フォームを表示しない *}
      <div id="map_canvas" class="pgc1-gmap" style="display:none;">map</div>{*{if $v->assign->map == 'opened'}*}

      {include file='./_form_search.tpl'}
      {/if}
    </div>
  </div>
</div>

<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">

{* 利用人数パラメータ設定 *}
{capture name='capacity_query'}
senior={ $v->assign->values.booking.senior }
{  section name=n_child start = 1 loop = 6}{assign var=chldnm value='child'|cat:$smarty.section.n_child.index}
{    if $v->assign->values.booking.$chldnm > 0}&{$chldnm}={ $v->assign->values.booking.$chldnm }{/if}
{  /section}
{/capture}

<div class="sch-left">

      {* フィルター *}
{if $v->assign->sales_count != 0}
      {include file='./_filter.tpl'}
{/if}
      {* おすすめホテル *}
      {if $v->assign->hotel_priorities.values|@count != 0}
        {include file='./_hotel_priorities.tpl'}
      {/if}
{* JRコレクション *}
<div style="width:163px;" class="jqs-jrc">
    <a href="{$v->env.base_path}feature/jrc/"><img src="{$v->env.root_path}feature/jrc/img/banner_163-246.gif" width="163" height="246" alt="ＪＲ＋宿泊のお得なセット" /></a>
</div>
</div>


<div class="sch-result">

{* 春のしずおか特集 *}
{if ($smarty.now|date_format:"%Y-%m-%d" <= '2013-03-31')}{if
       nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'p22'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm213'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm214'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm215'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm216'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm217'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm218'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm219'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm220'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm221'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm222'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm223'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm224'
    or nvl($v->assign->conditions.place_js, $v->assign->conditions.retrieval_place) == 'm225'
}<div class="text-center" style="margin:0 0 0.5em;"><a href="http://www.bestrsv.com/feature/shizuoka/" target="_blank"><img src="{$v->env.base_path}feature/shizuoka/img/banner-700-114.gif" width="700" height="114" alt="宿予約でポイント10倍 春のしずおか特集" /></a></div>{
else
}{* 他の地域のキャンペーンバナー出力位置 *}{
/if}{/if}

    {* レンタカーキャンペーン *}
    {*{include file='./_link_cp_b17031.tpl'}*}

  <div class="sch-cmd">
    <div class="btn-b05-110-s"><a class="btnimg jqs-clip-query" href="{$v->env.path_base}/query/?clip_hotel=all&{$v->helper->form->to_query_correct('type,year_month,day,stay,rooms,capacity,charge_min,charge_max,today,senior,child1,child2,child3,child4,child5')}"><img src="/img/btn/b05-search2_disable.gif" width="110" height="34" alt="クリップホテルから空室検索" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a></div>
  </div>
{if $v->assign->type == 'hotel'}
  {if 1 < $v->assign->index.page.total_page}
    <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}施設中{$v->assign->start_record|number_format}～{$v->assign->end_record|number_format}施設を表示</div>
  {elseif 0 < $v->assign->sales_count}
    <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}施設を表示</div>
  {/if}
  {if $v->assign->sales_count == 0}
    {if !is_empty($v->hotel.capacitys) or !is_empty($v->plan.capacitys) or !is_empty($v->assign->capacitys)}
    <div class="sch-msg">条件に一致するプランは{if $v->assign->index.page.total_count > 0}全て満室{else}ありません{/if}でした。</div>
    {else}
    <div class="sch-msg">条件に一致するプランは{if $v->assign->index.page.total_count > 0}全て満室{else}ありません{/if}でした。<br /><br />
    <a href="{$v->env.base_path}query/map/?lat={$v->assign->lat}&lng={$v->assign->lng}&zoomlevel={$v->assign->zoomlevel}&{$v->helper->form->to_query_correct("type,lat,lng,min_lat,min_lng,max_lat,max_lng,zoomlevel,place,hotel_cd", false)}">{$v->assign->conditions.hotel_nm} 近隣の周辺施設を地図から探す。</a>
    </div>
    {/if}
  {/if}
{else}
  {if 1 < $v->assign->index.page.total_page}
    <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}プラン中{$v->assign->start_record|number_format}～{$v->assign->end_record|number_format}プランを表示</div>
  {elseif 0 < $v->assign->sales_count}
    <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}プランを表示</div>
  {/if}
  {if $v->assign->sales_count == 0}
    <div class="sch-msg">条件に一致するプランは{if $v->assign->index.page.total_count > 0}全て満室{else}ありません{/if}でした。</div>
  {/if}
{/if}

{if $v->assign->sales_count != 0}
  <div class="sch-nav">
    <div class="sch-order">| {if $v->assign->sort == "trust"}<span class="current">おまかせ順</span>{else}<a href="{$v->env.base_path}query/?sort=trust&{$v->helper->form->to_query_correct('sort', false)}">おまかせ順</a>{/if} | {*
*}{if $v->assign->sort == "rank"}<span class="current">お客様人気順</span>{else}<a href="{$v->env.base_path}query/?sort=rank&{$v->helper->form->to_query_correct('sort', false)}">お客様人気順</a>{/if} | {*
*}{if $v->assign->sort == "charge"}<span class="current">料金が安い順</span>{else}<a href="{$v->env.base_path}query/?sort=charge&{$v->helper->form->to_query_correct('sort', false)}">料金が安い順</a>{/if} | {*
*}{if $v->assign->sort == "charge_desc"}<span class="current">料金が高い順</span>{else}<a href="{$v->env.base_path}query/?sort=charge_desc&{$v->helper->form->to_query_correct('sort', false)}">料金が高い順</a>{/if} | {*
*}{if $v->assign->sort == "rate"}<span class="current">お得感順</span>{else}<a href="{$v->env.base_path}query/?sort=rate&{$v->helper->form->to_query_correct('sort', false)}">お得感順</a>{/if} |</div>
    {include file='./_link_page.tpl'}
  </div>

  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td valign="bottom">
        <div class="sch-tab">{if $v->assign->type == 'hotel'}<img src="/img/btn/b07-byhotel-current.gif" width="106" height="27" alt="施設ごとに表示" />{else}<a class="btnimg" href="{$v->env.base_path}query/?type=hotel&{$v->helper->form->to_query_correct("type", false)}"><img src="/img/btn/b07-byhotel.gif" width="106" height="27" alt="施設ごとに表示" /></a>{/if}
        {if $v->assign->type == 'plan'}<img src="/img/btn/b07-byplan-current.gif" width="106" height="27" alt="プランごとに表示" />{elseif $v->assign->type == 'hotel' and $v->assign->index.page.total_count == 1}<img src="/img/btn/b07-byplan_disable.gif" width="106" height="27" alt="プランごとに表示" />{else}<a class="btnimg" href="{$v->env.base_path}query/?type=plan&{$v->helper->form->to_query_correct("type", false)}"><img src="/img/btn/b07-byplan.gif" width="106" height="27" alt="プランごとに表示" /></a>{/if}
        <a class="btnimg" href="{$v->env.base_path}query/map/?lat={$v->assign->lat}&lng={$v->assign->lng}&min_lat={$v->assign->latlngbounds.min_wgs_lat_d}&min_lng={$v->assign->latlngbounds.min_wgs_lng_d}&max_lat={$v->assign->latlngbounds.max_wgs_lat_d}&max_lng={$v->assign->latlngbounds.max_wgs_lng_d}&zoomlevel={$v->assign->zoomlevel}&{$v->helper->form->to_query_correct("type,lat,lng,min_lat,min_lng,max_lat,max_lng,zoomlevel,place", false)}"><img src="/img/btn/b07-bymap.gif" width="106" height="27" alt="地図で表示" /></a>
        </div>
      </td>


{if $v->assign->conditions.stay == 1}
      <td valign="bottom"><div style="text-align:right;padding:4px 0;">※料金表示は、1泊1部屋あたり大人1名料金です。</div></td>
{else}
      <td valign="bottom"><div style="text-align:right;padding:4px 0;">※料金表示は、1泊1部屋あたり大人1名料金です。<br />（連泊の場合は、一番お安い日程の料金を表示してます）</div></td>
{/if}
    </tr>
  </table>

{/if}

    {* プラン一覧表示 *}
    {if (!is_empty($v->assign->index.hotels))}
      {if $v->assign->type == 'hotel'}
        {include file='./_hotel_list.tpl'}
      {else}
        {include file='./_plan_list.tpl'}
      {/if}
    {/if}

{if $v->assign->sales_count != 0}
  <div class="sch-nav">
    <div class="sch-order">| {if $v->assign->sort == "trust"}<span class="current">おまかせ順</span>{else}<a href="{$v->env.base_path}query/?sort=trust&{$v->helper->form->to_query_correct('sort', false)}">おまかせ順</a>{/if} | {*
*}{if $v->assign->sort == "rank"}<span class="current">お客様人気順</span>{else}<a href="{$v->env.base_path}query/?sort=rank&{$v->helper->form->to_query_correct('sort', false)}">お客様人気順</a>{/if} | {*
*}{if $v->assign->sort == "charge"}<span class="current">料金が安い順</span>{else}<a href="{$v->env.base_path}query/?sort=charge&{$v->helper->form->to_query_correct('sort', false)}">料金が安い順</a>{/if} | {*
*}{if $v->assign->sort == "charge_desc"}<span class="current">料金が高い順</span>{else}<a href="{$v->env.base_path}query/?sort=charge_desc&{$v->helper->form->to_query_correct('sort', false)}">料金が高い順</a>{/if} | {*
*}{if $v->assign->sort == "rate"}<span class="current">お得感順</span>{else}<a href="{$v->env.base_path}query/?sort=rate&{$v->helper->form->to_query_correct('sort', false)}">お得感順</a>{/if} |</div>
    {include file='./_link_page.tpl'}
  </div>
{/if}

{* 表示される予定だった施設一覧 *}
{foreach from=$v->assign->hotel_priorities_all.values key=key item=value name=list}
<img src="/img/nrmv/spacer.gif?id=hotel_priority_all&hotel_cd={$value.hotel_cd}" border="0" width="1" height="1" alt="" />
{/foreach}
{foreach from=$v->assign->room_plan_priority_all key=key item=value name=list}
<img src="/img/nrmv/spacer.gif?id=room_plan_priority_all&hotel_cd={$value.hotel_cd}&room_id={$value.room_id}&plan_id={$value.plan_id}" border="0" width="1" height="1" alt="" />
{/foreach}


</div>

<div class="clear"></div>

    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}

