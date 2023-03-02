{include file='../_common/_header.tpl' js="_gmap.tpl" title=$v->assign->head_title}
{include file='../_common/_pgh1.tpl' pgh1_mnv=1}

<div id="pgh2">
  <div class="pg">
    <div class="pgh2-inner"></div>
    {include file='../_common/_pgh2_inner.tpl'}
  </div>
</div>

{literal}
  <script language="javascript"  type="text/javascript">
    <!--
      $(function() {
        $(document).ready(function () {
          {/literal}{if !is_empty($v->assign->search_condition.various.min_lat)}{literal}
            BRJ.Gmap.bound({/literal}{$v->assign->search_condition.various.min_lat}{literal}, {/literal}{$v->assign->search_condition.various.max_lat}{literal}, {/literal}{$v->assign->search_condition.various.min_lng}{literal}, {/literal}{$v->assign->search_condition.various.max_lng}{literal});
          {/literal}{elseif ($v->env.action == 'map')}{literal}
            BRJ.Gmap.center({/literal}{$v->assign->search_condition.form.wgs.wgs_lat_d}{literal}, {/literal}{$v->assign->search_condition.form.wgs.wgs_lng_d}{literal}, {/literal}{$v->assign->search_condition.various.zoomlevel}{literal}, {/literal}{$v->assign->search_condition.various.absolute}{literal});
          {/literal}{/if}{literal}
        });
      });
    //-->
  </script>
{/literal}

<div id="pgc1">
  <div class="pg">
    <div class="pgc1-inner">
      {include file='./_form_search.tpl' type='map'}
    </div>
  </div>
</div>


<div id="pgc2">
  <div class="pg">
    <div class="pgc2-inner">
       <div class="sch-right">
          <div>
            {* フィルター *}
            <div class="jqs-filter" style="width:13em;">読み込み中です。</div>
          </div>

{* ポイント還元キャンペーン *}
{include file='./_link_cp_b12141.tpl'}
          <br />
          <div>
            {* おすすめホテル *}
            {if $v->assign->hotel_priorities.values|@count != 0}
              {include file='./_hotel_priorities.tpl'}
            {/if}
          </div>
        </div>

        <div class="sch-result-new">
          {* レンタカーキャンペーン *}
          {*{include file='./_link_cp_b17031.tpl'}*}
          <div class="sch-cmd">
            <div class="btn-b05-110-s">
              <a class="btnimg jqs-clip-query" href="{$v->env.path_base}/query/map/?clip_hotel=all&{$v->helper->form->to_query_correct('type,year_month,day,stay,rooms,capacity,charge_min,charge_max,today,senior,child1,child2,child3,child4,child5')}"><img src="/img/btn/b05-search2_disable.gif" width="110" height="34" alt="クリップホテルから空室検索" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a>
            </div>
          </div>
          <div class="sch-msg">
            <div class="sch-msg">条件に一致した施設を地図に表示</div>
          </div>

          <div class="sch-tab">
            {strip}
            <a class="btnimg" href="{$v->env.path_base}/query/?from=map&type=hotel&{$v->helper->form->to_query_correct("type", false)}">
              <img src="/img/btn/b07-byhotel.gif" width="106" height="27" alt="施設ごとに表示" />
            </a>
            <a class="btnimg" href="{$v->env.path_base}/query/?from=map&type=plan&{$v->helper->form->to_query_correct("type", false)}">
              <img src="/img/btn/b07-byplan.gif" width="106" height="27" alt="プランごとに表示" />
            </a>
            <img src="/img/btn/b07-bymap-current.gif" width="106" height="27" alt="地図で表示" />
            {/strip}
          </div>

          <div class="hi-box-gmap" id="map_canvas"></div>

        </div>

        <div class="clear"></div>
      </div>
    </div>
  </div>



{include file='../_common/_footer.tpl'}

