{strip}
{*============================================================================*}
{* 利用人数、日付が検索条件に含まれていない場合                               *}
{*============================================================================*}


{* ヘッダー *}
{include file='../_common/_header.tpl' title=$v->assign->head_title js="_gmap.tpl" }


{* グローバルナビゲーション *}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}


{if $v->assign->search_condition.various.map == 'opened'}
  {literal}
    <script language="javascript"  type="text/javascript">
      <!--
        $(function() {
          $(document).ready(function () {
            BRJ.Gmap.thumbnail({/literal}{$v->assign->search_condition.various.lat}{literal}, {/literal}{$v->assign->search_condition.various.lng}{literal}, {/literal}{$v->assign->search_condition.various.zoomlevel}{literal});
          });
        });
      //-->
    </script>
  {/literal}
{/if}

{* ？？？ *}
<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner"></div>
    {* ？中身が空のテンプレート *}
    {include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>
{* 検索フォーム *}
<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
      {if !is_empty($v->assign->search_condition.form)}
        {* 再検索できない場合は再検索フォームを表示しない *}
        <div id="map_canvas" class="pgc1-gmap" style="display:none;">map</div>
        {if !is_empty($v->assign->search_condition.form.station.station_id)}
        {* 検索フォーム：駅検索時 *}
          <div class="jqs-dqs">
            {include file='./_station-detail.tpl'}
          </div>
          <div class="pgc1-hr"></div>
          {assign var=use_expand value="on"}
        {elseif is_empty($v->assign->search_condition.form.hotel) and $v->assign->search_condition.various.map != 'opened'}
          <div class="jqs-dqs">
            {include file='../_common/_area_breadcrumbs.tpl'}
          </div>
          <div class="pgc1-hr"></div>
          {assign var=use_expand value="on"}
        {/if}

        {* 検索フォーム：基本 *}
        {include file='./_form_search.tpl' use_expand=$use_expand }

      {/if}
    </div>
  </div>
</div>


{* 検索結果の一覧 *}
<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">

      {* フィルター *}
      <div class="sch-right">
        <div id="sub">
          <div class="sch-163-1">
            <div class="sch-163-2">
              <div class="sch-163-3">
                {if $v->assign->index.page.sales_count != 0 or
                  0 < $v->assign->search_condition.filter.icp or
                  0 < $v->assign->search_condition.filter.irt or
                  0 < $v->assign->search_condition.filter.iml or
                  0 < $v->assign->search_condition.filter.ism or
                  0 < $v->assign->search_condition.filter.ipw or
                  0 < $v->assign->search_condition.filter.ipt or
                  0 < $v->assign->search_condition.filter.inw or
                  0 < $v->assign->search_condition.filter.icd or
                  0 < $v->assign->search_condition.filter.ihs or
                  0 < $v->assign->search_condition.filter.ist or
                  0 < $v->assign->search_condition.filter.ipc
                }
                  <h3 class="search">検索結果を絞り込み</h3>
                  {include file='./_filter.tpl'}
                {else}
                  {if $v->assign->search_condition.form.hotel.hotel_cd|count_characters == 10}{*_lnaviが表示される際はフィルターとのマージンを空ける*}
                    <div class="clear"></div>
                  {/if}
                  {include file='./_filter_deactive.tpl'}

                {/if}

              </div>
            </div>
          </div>


{* ポイント還元キャンペーン *}
{include file='./_link_cp_b12141.tpl'}

          {* JRコレクション *}
          <div style="width:163px;" class="jqs-jrc">
            <a href="{$v->env.path_base}/feature/jrc/"><img src="{$v->env.path_base}/feature/jrc/img/banner_163-246.gif" width="163" height="246" alt="ＪＲ＋宿泊のお得なセット" /></a>
          </div>

          {* レコメンド *}
          {include file='./_recommend.tpl' spec="pc211"}
        </div>
      </div>

      {* 検索結果一覧 *}
      <div class="sch-result-new">
        {* レンタカーキャンペーン *}
        {*{include file='./_link_cp_b17031.tpl'}*}
        
        {if !is_empty($v->assign->keywords.words)}
          {include file='./_keyword-search.tpl'}
        {/if}

        {* クリップホテル *}
        {if 1 < $v->assign->index.page.total_page or 0 < $v->assign->index.page.sales_count or $v->assign->index.page.total_count == 0}
            <div class="sch-cmd">
               <div class="btn-b05-110-s jqs-dqs">
                    <a class="btnimg jqs-clip-query" href="{$v->env.path_base}/query/?clip_hotel=all&{$v->helper->form->to_query_correct('type,year_month,day,stay,rooms,today,senior,child1,child2,child3,child4,child5,charge_min,charge_max')}"><img src="/img/btn/b05-search2_disable.gif" width="110" height="34" alt="クリップホテルから空室検索" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a>
                </div>
            </div>
        {/if}

        {* 検索結果：メッセージ *}
        {if nvl($v->assign->search_condition.type, 'hotel') == 'hotel'}
          {if 1 < $v->assign->index.page.total_page}
            <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}施設中{$v->assign->index.page.start_record|number_format}～{$v->assign->index.page.end_record|number_format}施設を表示</div>
          {elseif 0 < $v->assign->index.page.sales_count}
            <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}施設を表示</div>
          {/if}
          {if $v->assign->index.page.total_count == 0}
            <div class="sch-msg">条件に一致する施設はありませんでした</div>
          {else}
            {if $v->assign->index.page.sales_count < 1}
              {include file='./_sold_out_all_plan_msg.tpl'}
              {* クリップホテル *}
              <div class="sch-cmd">
                  <div class="btn-b05-110-s jqs-dqs">
                      <a class="btnimg jqs-clip-query" href="{$v->env.path_base}/query/?clip_hotel=all&{$v->helper->form->to_query_correct('type,year_month,day,stay,rooms,today,senior,child1,child2,child3,child4,child5,charge_min,charge_max')}"><img src="/img/btn/b05-search2_disable.gif" width="110" height="34" alt="クリップホテルから空室検索" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a>
                  </div>
              </div>
            {/if}
          {/if}
        {else}
          {if 1 < $v->assign->index.page.total_page}
            <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}プラン中{$v->assign->index.page.start_record|number_format}～{$v->assign->index.page.end_record|number_format}プランを表示</div>
          {elseif 0 < $v->assign->index.page.sales_count}
            <div class="sch-msg">条件に一致した{$v->assign->index.page.total_count|number_format}プランを表示</div>
          {/if}
          {if $v->assign->index.page.total_count == 0}
            <div class="sch-msg">条件に一致するプランはありませんでした</div>
          {else}
            {if $v->assign->index.page.sales_count < 1}
              {include file='./_sold_out_all_plan_msg.tpl'}
              {* クリップホテル *}
              <div class="sch-cmd">
                  <div class="btn-b05-110-s jqs-dqs">
                      <a class="btnimg jqs-clip-query" href="{$v->env.path_base}/query/?clip_hotel=all&{$v->helper->form->to_query_correct('type,year_month,day,stay,rooms,today,senior,child1,child2,child3,child4,child5,charge_min,charge_max')}"><img src="/img/btn/b05-search2_disable.gif" width="110" height="34" alt="クリップホテルから空室検索" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a>
                  </div>
              </div>
            {/if}
          {/if}
        {/if}

        {* ページ上部のページャー、ソート種別 *}
        {if $v->assign->index.page.sales_count != 0}
          <div class="sch-nav">
            {* ソート種別 *}
            {include file='./_sch_order.tpl'}

            {* ページャー *}
            {include file='./_link_page.tpl'}
          </div>
        {/if}

        {* 「施設毎表示」、「プラン毎表示」、「地図検索」タブ *}
        {if 0 < $v->assign->index.page.total_count}
          {include file='./_tab.tpl'}
        {/if}

        {* 検索結果の一覧 *}
          {if ($v->assign->search_condition.form.hotel.hotel_cd|count_characters == 10) or !is_empty($v->assign->search_condition.form.hotel.plan_id)}
            {*----------------------------------------------------------------*}
            {* プラン詳細表示（プラン固定）                                   *}
            {*----------------------------------------------------------------*}
            {if (!is_empty($v->assign->index.hotels))}
                {include file='./_plan_detail.tpl'}
            {/if}
          {else}
            {*----------------------------------------------------------------*}
            {* プラン一覧表示（施設単位）                                     *}
            {*----------------------------------------------------------------*}
            {if (!is_empty($v->assign->index.hotels))}
              {if nvl($v->assign->search_condition.type, 'hotel') == 'hotel'}
                {include file='./_hotel_list.tpl'}
              {else}
                {include file='./_plan_list.tpl'}
              {/if}
            {/if}
          {/if}

          {* ページ下部のページャー、ソート種別 *}
          {if $v->assign->index.page.sales_count != 0 and is_empty($v->assign->search_condition.form.hotel)}
            <div class="sch-nav">
              {* ソート種別 *}
              {include file='./_sch_order.tpl'}

              {* ページャー *}
              {include file='./_link_page.tpl'}
            </div>
          {/if}

      </div>

      <div class="clear"></div>

    </div>
  </div>
</div>


{include file='../_common/_footer.tpl'}


{/strip}