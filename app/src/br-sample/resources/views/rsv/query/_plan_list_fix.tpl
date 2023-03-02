{strip}
{* 性能アップのためこのファイル内での include の使用禁止 ＆ strip_tags を必要最低限に設定 *}
{assign var=url_query_hotel value=$v->env.root_path|cat:'query/?type=hotel&'|cat:$v->helper->form->to_query_correct('type,place,hotel_cd,hotel_cds,plan_id,room_id,x,y',false)}

{***************************************************************************
    施設の処理
 ***************************************************************************}
{foreach from=$v->assign->index.hotels key=hotel_no item=hotel name=hotels}
  {assign var=vhotel value=$v->assign->values.hotels[$hotel.hotel_cd]}
  <div class="hi-box advance">
    <div class="hi-hotel">
      <div class="hi-name
      {assign var=plan_count value=0}
      {assign var=priority_count value=0}
      {foreach from=$hotel.plans key=plan_no item=plan name=plans}
        {assign var=vplan         value=$v->assign->values.hotels[$hotel.hotel_cd].plans[$plan.plan_id]}
        {if $vplan.icons.highrank and $plan_count==0}
          {assign var=plan_count value=$plan_count+1} bg-bestprice
        {/if}
        {if $vplan.icons.ads and $priority_count==0}
          {assign var=priority_count value=1} bg-ranktop
          {if ($vhotel.hotel_nm|count_characters:true) >= 40} bg-ranktop_h {/if}
        {/if}
      {/foreach}
      "><a href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/">{$v->helper->form->strip_tags($vhotel.hotel_nm)}</a>
      {if $vhotel.icons.camp_goto == 1}　<img src="{$v->env.root_path}img/spc/spc-p0-camp_goto_hotel.gif" width="140" height="20" alt="GoToトラベルキャンペーン" />{/if}
      {if $priority_count > 0}
      {if ($vhotel.hotel_nm|count_characters:true) >= 40}<br>{/if}
      <span class="bg-ranktop_msg">☆☆☆【注目】☆☆☆</span>
      {/if}  
      </div>
      <table class="hi-summary" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td valign="top" width="40%">

            {* 住所 *}
            <div class="hi-address">{$v->helper->form->strip_tags($vhotel.pref_nm)}{$v->helper->form->strip_tags($vhotel.address)}</div>
            {* 口コミ評価 *}
            {if $vhotel.voices.count >= 5}
              <div class="hi-voice">{$vhotel.voices.review.total|number_format:1|mb_convert_kana:'N'}</div>
              <div class="hi-voice-star">
                <img src="{$v->env.path_img}/vic/vic-star-w-{$vhotel.voices.review.total*10}.gif" width="100" height="17" alt="クチコミ総合{$vhotel.voices.review.total}">
              </div>
            {/if}

          </td>
          <td valign="top" width="60%">

            {* 駐車場 *}
            {if $vhotel.parking_status == 1 or !is_empty($vhotel.parking_info)}
              <div class="hi-parking">
                {if $vhotel.parking_status == 1}<strong>あり</strong>&nbsp;&nbsp;{/if}
                {if !is_empty($vhotel.parking_info)}{$vhotel.parking_info}{/if}
              </div>
              {/if}
            </div>
            {* 最寄駅 *}
            {if !is_empty($vhotel.stations)}
              {foreach from=$vhotel.stations item=station name=stations}
                {if $smarty.foreach.stations.first}<div class="hi-station">{/if}
                {$station}<br />
                {if $smarty.foreach.stations.last}</div>{/if}
              {/foreach}
            {/if}
          </td>
        </tr>
        <tr>
          <td align="right" valign="bottom" colspan="2">
            <div class="hi-cmd">
              {* ホテル情報を見る *}
              <div class="btn-b06-098-s"><a class="btnimg" href="{$v->env.path_base}/hotel/{$hotel.hotel_cd}/" title="{$v->helper->form->strip_tags($vhotel.hotel_nm)}の詳細を表示"><img src="{$v->env.path_img}/btn/b06-hotel.gif" width="98" height="23" alt="{$v->helper->form->strip_tags($vhotel.hotel_nm)}の詳細を表示" /></a></div>
              {* プランリストを見る *}
              <div class="btn-b06-098-s"><a class="btnimg" href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/" title="{$v->helper->form->strip_tags($hotel.hotel_nm)}のプランリストを表示"><img src="{$v->env.path_img}/btn/b06-plan.gif" width="98" height="23" alt="{$v->helper->form->strip_tags($vhotel.hotel_nm)}のプランリストを表示" /></a></div>
              {* ストリームは、クリップホテル対象外 *}
              {if $v->user->partner->connect_type == 'reserve'}
                {if $v->assign->params.view != "marker"}
                  {if $vhotel.icons.cliped}
                   <div class="btn-b06-clip2-s"><a class="btnimg jqs-clip jqs-clip-{$hotel.hotel_cd} jqs-on" href="/clip/{$hotel.hotel_cd}/" title="クリップ削除"><img src="{$v->env.path_img}/btn/b06-clip2.gif" width="98" height="23" alt="クリップ中" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a></div>
                  {else}
                   <div class="btn-b06-098-s"><a class="btnimg jqs-clip jqs-clip-{$hotel.hotel_cd}" href="/clip/{$hotel.hotel_cd}/" title="クリップする"><img src="{$v->env.path_img}/btn/b06-clip1.gif" width="98" height="23" alt="クリップする" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a></div>
                  {/if}
                {/if}
              {/if}
            </div>
          </td>
        </tr>
      </table>
    </div>

    {***************************************************************************
        プランの処理
     ***************************************************************************}
    {foreach from=$hotel.plans key=plan_no item=plan name=plans}

      {assign var=vplan value=$v->assign->values.hotels[$hotel.hotel_cd].plans[$plan.plan_id]}
      {assign var=charge_months value=$vplan.charge_months}
      <div class="pi-box
      {if $vplan.icons.highrank} line-bestprice{/if}
      {if !($vplan.icons.highrank) and $priority_count>0} line-priority{/if}
      ">

        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td class="pi-photo">

              {* プランの画像 *}
              {if !is_empty($vplan.medias[0].file_nm)}
                {assign var=media_count value=$vplan.medias|@count}
                {if $media_count == 1}
                  <img src="{$v->env.path_img}/hotel/{$hotel.hotel_cd}/trim_100/{$vplan.medias[0].file_nm}" width="100" height="100" alt="{$vhotel.hotel_nm} {$vplan.plan_nm}" />
                {elseif 4 <= $media_count}
                  <span class="planimg-4"><img src="{$v->env.path_img}/hotel/{$hotel.hotel_cd}/trim_100/{$vplan.medias[0].file_nm}" width="100" height="100" alt="{$vhotel.hotel_nm} {$vplan.plan_nm}" /></span>
                {else}
                  <span class="planimg-{$media_count}"><img src="{$v->env.path_img}/hotel/{$hotel.hotel_cd}/trim_100/{$vplan.medias[0].file_nm}" width="100" height="100" alt="{$vhotel.hotel_nm} {$vplan.plan_nm}" /></span>
                {/if}

              {/if}

            </td>
            <td>

              <div class="pi-head{if $vplan.icons.point_rate > 9} pi-head-p{$vplan.icons.point_rate}{/if}{if $vplan.icons.highrank} pi-head-ph{/if}{if !($vplan.icons.highrank) and $priority_count>0} pi-head-pr{/if}">
                <div class="pi-name">
                  {* ポイント10倍以上アイコン *}
                  {if $vplan.icons.point_rate > 9}
                    <img style="padding-right:5px" src="{$v->env.root_path}img/spc/spc-p3-pointover{$vplan.icons.point_rate}.gif" width="74" height="19" align="top" alt="ポイント{$vplan.icons.point_rate}倍" />
                  {/if}
                  <a href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/{$plan.plan_id}/">{$vplan.plan_nm}</a>
                </div>
                <div class="pi-icons">
                  {* 折り返し以降に表示するアイコンがあるか確認する *}
                  {assign var=new_line value=false}
                  {* チェックイン *}
                  {if !is_empty($vplan.icons.check_in)}{assign var=new_line value=true}{/if}
                  {* チェックアウト *}
                  {if !is_empty($vplan.icons.check_out)}{assign var=new_line value=true}{/if}
                  {* 最低宿泊日数・最大宿泊日数 *}
                  {if $vplan.icons.stay_limit > 1 and $vplan.icons.stay_cap > 1}{assign var=new_line value=true}
                  {elseif $vplan.icons.stay_limit > 1}{assign var=new_line value=true}
                  {elseif $vplan.icons.stay_cap >= 2}{assign var=new_line value=true}
                  {elseif $vplan.icons.stay_cap == 1}{assign var=new_line value=true}{/if}
                  {* ベストリザーブ提供プラン *}
                  {if $vhotel.icons.bestreserve}{assign var=new_line value=true}{/if}
                  {* ハイランク *}
                  {if $vplan.icons.highrank}{assign var=new_line value=true}{/if}
                  {* 新着ホテル *}
                  {if $vhotel.icons.hotel_new}{assign var=new_line value=true}{/if}
                  {* 限定プラン *}
                  {if $vplan.icons.corporate}{assign var=new_line value=true}{/if}
                  {* GoToキャンペーン *}
                  {if $vplan.icons.camp_goto}{assign var=new_line value=true}{/if}

                  {* 食事 *}
                  {if $vplan.icons.meal_nothing         }<img src="{$v->env.path_img}/spc/spc-p1-nothing.gif" width="67" height="20" alt="食事なし" />{/if}
                  {if $vplan.icons.meal_breakfast       }<img src="{$v->env.path_img}/spc/spc-p1-breakfast.gif" width="67" height="20" alt="朝食付き" />{/if}
                  {if $vplan.icons.meal_dinner          }<img src="{$v->env.path_img}/spc/spc-p1-dinner.gif" width="69" height="20" alt="夕食付き" />{/if}
                  {if $vplan.icons.meal_breakfast_dinner}<img src="{$v->env.path_img}/spc/spc-p1-dinnerfast.gif" width="96" height="20" alt="夕食・朝食付き" />{/if}

                  {* 決裁方法 *}
                  {if $vplan.icons.payment_cash       }<img src="{$v->env.path_img}/spc/spc-p2-cash.gif" width="55" height="20" alt="現地決済" />{/if}
                  {if $vplan.icons.payment_online     }<img src="{$v->env.path_img}/spc/spc-p2-online.gif" width="84" height="20" alt="事前カード決済" />{/if}
                  {if $vplan.icons.payment_cash_online}<img src="{$v->env.path_img}/spc/spc-p2-choice.gif" width="134" height="20" alt="現地決済/事前カード決済" />{/if}

                  {* 特別プラン *}
                  {* if $vplan.icons.insurance_status}<img src="{$v->env.path_img}/spc/spc-p0-insurance.gif" width="117" height="20" alt="お天気保険付きプラン" />{/if *}
                  {if $vplan.icons.fss             }<img src="{$v->env.path_img}/spc/spc-p0-fss.gif" width="82" height="20" alt="金土日プラン" />{/if}

                  {* ポイント利用 *}
                  {if $vplan.icons.point_status}<img src="{$v->env.path_img}/spc/spc-p0-point.gif" width="85" height="20" alt="ポイント利用可" />{/if}
                  {if !$new_line}<span class=" tax_included"><span class="arrow down_arrow">表示価格は税込です</span><span class="arrow down_arrow">　　　　　　　</span></span>{/if}

                  <br />

                  {* チェックイン *}
                  {if !is_empty($vplan.icons.check_in)}
                    <img class="continue" src="{$v->env.path_img}/spc/spc-p4-ins{$vplan.icons.check_in|regex_replace:'/^([0-9][0-9])\:([0-9][0-9])(.*)/':'$1$2'}.gif" width="52" height="20" alt="チェックイン {$vplan.icons.check_in}" />
                    {if $vplan.icons.check_in|count_characters > 5}
                      <img src="{$v->env.path_img}/spc/spc-p4-ine{$vplan.icons.check_in|regex_replace:'/^[0-9][0-9]\:[0-9][0-9]-([0-9][0-9])\:([0-9][0-9])/':'$1$2'}.gif" width="44" height="20" alt="チェックイン {$vplan.icons.check_in}" />
                    {else}
                      <img src="{$v->env.path_img}/spc/spc-p4-ine.gif" width="16" height="20" alt="チェックイン {$vplan.icons.check_in}" />
                    {/if}
                  {/if}

                  {* チェックアウト *}
                  {if !is_empty($vplan.icons.check_out)}
                    <img src="{$v->env.path_img}/spc/spc-p5-out{$vplan.icons.check_out|replace:':':''}.gif" width="72" height="20" alt="チェックアウト {$vplan.icons.check_out}" />
                  {/if}

                  {* 最低宿泊日数・最大宿泊日数 *}
                  {if $vplan.icons.stay_limit > 1 and $vplan.icons.stay_cap > 1}
                    {* 任意の最小泊数と最大泊数が指定されている（それぞれ1泊以上） *}
                    {if $vplan.icons.stay_limit === $vplan.icons.stay_cap}
                      {* 最小泊数と最大泊数が同じ値 *}
                      <img src="{$v->env.path_img}/spc/spc-p9-only{$vplan.icons.stay_limit}.gif"  alt="{$vplan.icons.stay_limit}連泊限定" width="68" height="20" />
                    {else}
                      {* 最小泊数と最大泊数が異なる値 *}
                      <img src="{$v->env.path_img}/spc/spc-p6-limit{$vplan.icons.stay_limit}.gif" alt="{$vplan.icons.stay_limit}泊から" width="63" height="20" />
                      <img src="{$v->env.path_img}/spc/spc-p7-cap{$vplan.icons.stay_cap}.gif"     alt="{$vplan.icons.stay_cap}泊まで"   width="62" height="20" />
                    {/if}
                  {elseif $vplan.icons.stay_limit > 1}
                    {* 任意の最小泊数のみが指定されている *}
                    <img src="{$v->env.path_img}/spc/spc-p6-limit{$vplan.icons.stay_limit}.gif" alt="{$vplan.icons.stay_limit}泊から" width="63" height="20" />
                  {elseif $vplan.icons.stay_cap >= 2}
                    {* 任意の最大泊数のみが指定されている(2泊以上) *}
                    <img src="{$v->env.path_img}/spc/spc-p7-cap{$vplan.icons.stay_cap}.gif" alt="{$vplan.icons.stay_cap}泊まで" width="62" height="20" />
                  {elseif $vplan.icons.stay_cap == 1}
                    {* 任意の最大泊数のみが指定されている(1泊のみ) *}
                    <img src="{$v->env.path_img}/spc/spc-p9-only{$vplan.icons.stay_limit}.gif" alt="{$vplan.icons.stay_limit}泊限定" width="63" height="20" />
                  {/if}

                  {* ベストリザーブ提供プラン *}
                  {if $vhotel.icons.bestreserve}<img src="{$v->env.path_img}/spc/spc-h0-best.gif" width="122" height="20" alt="ベストリザーブ提供プラン" />{/if}

                  {* ハイランク *}
                  {if $vplan.icons.highrank}<img src="{$v->env.path_img}/spc/spc-p0-bpr.gif" width="87" height="20" alt="ベストプライスルーム" />{/if}

                  {* 新着ホテル *}
                  {if $vhotel.icons.hotel_new}<img src="{$v->env.path_img}/spc/spc-h0-new.gif" width="53" height="20" alt="新着施設" />{/if}

                  {* 限定プラン *}
                  {if $vplan.icons.corporate}<img src="{$v->env.path_img}/spc/spc-p0-corporate.gif" width="71" height="20" alt="限定プラン" />{/if}

                  {* GoToキャンペーン *}
                  {if $vplan.icons.camp_goto}<img src="{$v->env.path_img}/spc/spc-p0-camp_goto_plan.gif" width="140" height="20" alt="GoToトラベルキャンペーン" />{/if}
                  {if $new_line}<span class=" tax_included"><span class="arrow down_arrow">表示価格は税込です</span><span class="arrow down_arrow">　　　　　　　</span></span>{/if}

                </div>{* class="pi-icon" *}
              </div>{* class="pi-head" *}

              {***************************************************************************
                  部屋の処理
               ***************************************************************************}
              {foreach from=$plan.plan_rooms key=room_no item=room name=rooms}

                {assign var=planroom_id value=$plan.plan_id|cat:$room.room_id}
                {assign var=vroom value=$v->assign->values.hotels[$hotel.hotel_cd].rooms[$room.room_id]}
                {assign var=vplom value=$v->assign->values.hotels[$hotel.hotel_cd].plan_rooms[$planroom_id]}

                <div class="ri-box gi-box">

                  {***************************************************************************
                      利用人数の処理
                   ***************************************************************************}
                  {foreach from=$room.capacities key=capacity_no item=capacity name=capacities}
                    {assign var=charge value=$v->assign->values.hotels[$hotel.hotel_cd].plan_rooms[$planroom_id].capacities[$capacity].charge}

                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                      <tr>

                        {* 部屋名称・部屋面積 *}
                        <td class="gi-expand{if $vplom.icons.plan_new} gi-new{/if}">
                          <div class="gi-name"><a href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/{$plan.plan_id}/{$room.room_id}/{$capacity}/">{$vroom.room_nm}</a></div>
                          <div class="gi-floor">（{$vroom.floorage_min}{if $vroom.floorage_min != $vroom.floorage_max}～{$vroom.floorage_max}{/if}{if $vroom.floor_unit == 0}平米{else}畳{/if}）</div>
                        </td>

                        {* 利用人数 *}
                        <td class="gi-capacity" nowrap="nowrap">
                          <img src="{$v->env.path_img}/spc/spc-q1-capacity{$capacity}.gif" width="40" height="34" alt="{$capacity}名利用" />
                        </td>

                        {* 割引率・ポイント付与 *}
                        <td class="gi-advantage" nowrap="nowrap">
                          {if $vplan.icons.highrank}
                            {if !($v->user->member->is_login()) || $v->user->member->is_free()}
                              <div class="gi-discount">さらに割引</div>
                            {else}
                              {if $charge.rate > 9}
                                <div class="gi-discount">{$charge.rate}%割引</div>
                              {else}
                                <div class="gi-discount-none"></div>
                              {/if}
                            {/if}
                          {else}
                            {if $charge.rate > 9}
                              <div class="gi-discount">{$charge.rate}%割引</div>
                            {else}
                              <div class="gi-discount-none"></div>
                            {/if}
                          {/if}
                        {if !is_empty($vplan.icons.point_rate)}
                          <div class="gi-point"><img src="{$v->env.path_img}/spc/spc-p3-pointrate{$vplan.icons.point_rate|regex_replace:'/^([0-9])$/':'0$1'}.gif" width="{
                          if     $vplan.icons.point_rate ==  1}33{
                          elseif $vplan.icons.point_rate ==  2}34{
                          elseif $vplan.icons.point_rate ==  3}34{
                          elseif $vplan.icons.point_rate ==  4}35{
                          elseif $vplan.icons.point_rate ==  5}35{
                          elseif $vplan.icons.point_rate ==  6}35{
                          elseif $vplan.icons.point_rate ==  7}34{
                          elseif $vplan.icons.point_rate ==  8}34{
                          elseif $vplan.icons.point_rate ==  9}34{
                          elseif $vplan.icons.point_rate == 10}39{
                          elseif $vplan.icons.point_rate == 11}39{
                          elseif $vplan.icons.point_rate == 12}39{
                          elseif $vplan.icons.point_rate == 13}40{
                          elseif $vplan.icons.point_rate == 14}40{
                          elseif $vplan.icons.point_rate == 15}40{
                          elseif $vplan.icons.point_rate == 16}40{
                          elseif $vplan.icons.point_rate == 17}39{
                          elseif $vplan.icons.point_rate == 18}39{
                          elseif $vplan.icons.point_rate == 19}39{
                          elseif $vplan.icons.point_rate == 20}42{
                          /if}" height="15" alt="ポイント{$vplan.icons.point_rate}%">
                             {if $vplan.br_point_plus_rate > 0}
                               <div class="box-br-point-plus-dated">
                                 <span class="icon-br-point-plus">＋<span class="highlight-point">{$vplan.br_point_plus_rate}</span><span class="percent">%</span></span>
                                 <p class="info-br-point-plus">ご検索の宿泊日に宿泊すると<br />{$vplan.br_point_plus_rate}％分のポイントがプラスされます。</p>
                               </div>
                             {/if}
                          </div>
                        {/if}
                        </td>

                        {* 大人１名１泊最低料金・全員全泊合計料金 *}
                        <td class="gi-price gi-pricefix" nowrap="nowrap">
                          {if $v->assign->index.page.charge_type == 1}
                            <span class="gi-currency"><span class="gi-unitprice">{$charge.min_sales_charge|number_format}</span>円</span>/人
                          {elseif $v->assign->index.page.charge_type == 2}
                            <span class="gi-currency"><span class="gi-unitprice">{$charge.max_sales_charge|number_format}</span>円</span>/人
                          {/if}
                          <br />
                          合計<span class="gi-currency">{$charge.total_sales_charge|number_format}円</span>
                        </td>

                        {* 予約申込ボタン *}
                        <td class="gi-action">
                          {if $charge.booking_status == 'soldout'}
                            <div class="btn-b04-098-s"><img src="{$v->env.path_img}/btn/b04-soldout_disable.gif" width="98" height="38" alt="満室" title="満室" /></div>
                          {else}
                            {if ($v->assign->values.booking.rooms == 1 && $charge.vacant < 3)
                             || ($v->assign->values.booking.rooms == 2 && $charge.vacant < 3)
                             || ($v->assign->values.booking.rooms == 3 && $charge.vacant < 5)
                             || ($v->assign->values.booking.rooms == 4 && $charge.vacant < 7)
                             || ($v->assign->values.booking.rooms == 5 && $charge.vacant < 9)}
                              {assign var=src value='b04-booking'|cat:$charge.vacant|cat:'.gif'}
                              {assign var=alt value='予約する（あと'|cat:$charge.vacant|cat:'室）'}
                            {else}
                              {assign var=src value='b04-booking.gif'}
                              {assign var=alt value='予約する'}
                            {/if}
                            <div class="btn-b04-098-s">
                              {if $v->user->partner->connect_type == 'clutch' && !($v->user->member->is_login())}
                                <form method="post" action="{$v->user->partner->request_url}">
                                  <input type="hidden" name="fpcd" value="{$v->user->partner->partner_cd}" />
                                  <input type="hidden" name="fpwd" value="{$v->user->partner->sid}" />
                                  <input type="hidden" name="furl" value="{$v->env.port_https}{$v->env.path_base_module}/booking/step0/" />
                                  <input type="hidden" name="fpms" value="{$hotel.hotel_cd}_{$room.room_id}_{$plan.plan_id}_{$v->assign->values.booking.date|date_format:"%Y/%m/%d"}_{$v->assign->values.booking.stay}_{$v->assign->values.booking.rooms}_{$v->assign->values.booking.senior}_{$v->assign->values.booking.child1}_{$v->assign->values.booking.child2}_{$v->assign->values.booking.child3}_{$v->assign->values.booking.child4}_{$v->assign->values.booking.child5}" />
                                  <input class="btnimg" type="image" alt="再検索" src="{$v->env.path_img}/btn/{$src}" class="ccc" id="query_plan_{$v->assign->page}_{$smarty.foreach.hotels.index+1}:{$hotel.hotel_cd}_{$room.room_id}_{$plan.plan_id}" />
                                </form>
                              {else}
                                <a class="btnimg" href="{$v->env.port_https}{$v->env.path_base}/booking/{$hotel.hotel_cd}/{$room.room_id}/{$plan.plan_id}/{$v->assign->values.booking.date|date_format:"%Y-%m-%d"}/{$v->assign->values.booking.stay}/{$v->assign->values.booking.rooms}/?senior={$v->assign->values.booking.senior}&child1={$v->assign->values.booking.child1}&child2={$v->assign->values.booking.child2}&child3={$v->assign->values.booking.child3}&child4={$v->assign->values.booking.child4}&child5={$v->assign->values.booking.child5}">
                                <img src="{$v->env.path_img}/btn/{$src}" width="98" height="38" alt="{$alt}" class="ccc" id="query_plan_{$v->assign->page}_{$smarty.foreach.hotels.index+1}:{$hotel.hotel_cd}_{$room.room_id}_{$plan.plan_id}" />
                                </a>
                              {/if}
                            </div>
                          {/if}
                        </td>
                      </tr>
                    </table>
                  {/foreach}

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td>
                  <div class="gi-icons">

                    {* 部屋タイプ *}
                    {if $vroom.icons.roomtype_capsule   }<img src="{$v->env.path_img}/spc/spc-r01-capsule.gif" width="54" height="20" alt="カプセル" />{/if}
                    {if $vroom.icons.roomtype_single    }<img src="{$v->env.path_img}/spc/spc-r01-single.gif" width="54" height="20" alt="シングル" />{/if}
                    {if $vroom.icons.roomtype_twin      }<img src="{$v->env.path_img}/spc/spc-r01-twin.gif" width="45" height="20" alt="ツイン" />{/if}
                    {if $vroom.icons.roomtype_semidouble}<img src="{$v->env.path_img}/spc/spc-r01-semidouble.gif" width="65" height="20" alt="セミダブル" />{/if}
                    {if $vroom.icons.roomtype_double    }<img src="{$v->env.path_img}/spc/spc-r01-double.gif" width="45" height="20" alt="ダブル" />{/if}
                    {if $vroom.icons.roomtype_triple    }<img src="{$v->env.path_img}/spc/spc-r01-triple.gif" width="54" height="20" alt="トリプル" />{/if}
                    {if $vroom.icons.roomtype_4bed      }<img src="{$v->env.path_img}/spc/spc-r01-4bed.gif" width="54" height="20" alt="4ベッド" />{/if}
                    {if $vroom.icons.roomtype_suite     }<img src="{$v->env.path_img}/spc/spc-r01-suite.gif" width="50" height="20" alt="スイート" />{/if}
                    {if $vroom.icons.roomtype_maisonette}<img src="{$v->env.path_img}/spc/spc-r01-maisonette.gif" width="65" height="20" alt="メゾネット" />{/if}
                    {if $vroom.icons.roomtype_jstyle    }<img src="{$v->env.path_img}/spc/spc-r01-jstyle.gif" width="35" height="20" alt="和室" />{/if}
                    {if $vroom.icons.roomtype_jmix      }<img src="{$v->env.path_img}/spc/spc-r01-jmix.gif" width="45" height="20" alt="和洋室" />{/if}
                    {if $vroom.icons.roomtype_other     }<img src="{$v->env.path_img}/spc/spc-r01-other.gif" width="45" height="20" alt="その他" />{/if}

                    {* 禁煙・喫煙 *}
                    {if $vroom.icons.tobacco_nosmoke}<img src="{$v->env.path_img}/spc/spc-r04-nosmoke.gif" width="51" height="20" alt="禁煙" />{/if}
                    {if $vroom.icons.tobacco_smoke  }<img src="{$v->env.path_img}/spc/spc-r04-smoke.gif" width="51" height="20" alt="喫煙" />{/if}
                    {if $vroom.icons.tobacco_choice }<img src="{$v->env.path_img}/spc/spc-r04-choice.gif" width="106" height="20" alt="禁煙・喫煙選択可" />{/if}

                    {* ネットワーク *}
                    {if $vroom.icons.network_free       }<img src="{$v->env.path_img}/spc/spc-r05-free.gif" width="75" height="20" alt="ネットワーク 全客室にて無料で対応" />{/if}
                    {if $vroom.icons.network_free_part  }<img src="{$v->env.path_img}/spc/spc-r05-freepart.gif" width="103" height="20" alt="ネットワーク 一部客室にて無料で対応" />{/if}
                    {if $vroom.icons.network_charge     }<img src="{$v->env.path_img}/spc/spc-r05-charge.gif" width="75" height="20" alt="ネットワーク 全客室にて有料で対応" />{/if}
                    {if $vroom.icons.network_charge_part}<img src="{$v->env.path_img}/spc/spc-r05-chargepart.gif" width="103" height="20" alt="ネットワーク 一部客室にて有料で対応" />{/if}

                    {* 風呂 *}
                    {if $vroom.icons.bath       }<img src="{$v->env.path_img}/spc/spc-r02-bath.gif" width="51" height="20" alt="風呂あり" />{/if}
                    {if $vroom.icons.bath_share }<img src="{$v->env.path_img}/spc/spc-r02-share.gif" width="71" height="20" alt="風呂共同" />{/if}
                    {if $vroom.icons.bath_shower}<img src="{$v->env.path_img}/spc/spc-r02-shower.gif" width="76" height="20" alt="シャワーのみ" />{/if}

                    {* トイレ *}
                    {if $vroom.icons.toilet      }<img src="{$v->env.path_img}/spc/spc-r03-toilet.gif" width="52" height="20" alt="トイレあり" />{/if}
                    {if $vroom.icons.toilet_share}<img src="{$v->env.path_img}/spc/spc-r03-share.gif" width="69" height="20" alt="トイレ共同" />{/if}

                    {* 子供受入あり *}
                    {if $vplom.icons.child}<img src="{$v->env.path_img}/spc/spc-q0-child.gif" width="44" height="20" alt="子供受入あり" />{/if}

                    {* 人気 *}
                    {if !is_empty($vplom.icons.rank)}<img src="{$v->env.path_img}/spc/spc-p8-rank{$vplom.icons.rank}.gif" width="44" height="20" alt="人気" />{/if}

                    {* おすすめ *}
                    {if $vplom.icons.recommend}<img src="{$v->env.path_img}/spc/spc-p0-recomend.gif" width="58" height="20" alt="おすすめ" />{/if}

                    {* 早割・当日割 *}
                    {if $vplom.icons.early}<img src="{$v->env.path_img}/spc/spc-p0-ealy.gif" width="43" height="20" alt="早期割引" />{/if}
                    {if $vplom.icons.today}<img src="{$v->env.path_img}/spc/spc-p0-today.gif" width="61" height="20" alt="当日割引" />{/if}

                    {* キャンペーン *}
                    {if $vplom.icons.camp}<img src="{$v->env.path_img}/spc/spc-p0-camp.gif" width="70" height="20" alt="キャンペーン" />{/if}
                    
                  </div>{* class="gi-icons *}
                </td>
                <td align="right" width="280px">
                  {if $vplan.icons.camp_goto}
                    <p style="text-align: center; line-height: 1.15;">
                    <span class="arrow down_arrow"><font color="#000022">Gotoトラベルクーポン適用で</font></span><span class="arrow down_arrow">　</span><br>
                    {if $v->assign->index.page.charge_type == 1}
                      <span class="gi-currency"><span class="gi-unitprice">{$charge.min_goto_charge|number_format}</span>円</span>/人
                    {elseif $v->assign->index.page.charge_type == 2}
                      <span class="gi-currency"><span class="gi-unitprice">{$charge.max_goto_charge|number_format}</span>円</span>/人
                    {/if}
                    <br />
                    合計<span class="gi-currency">{$charge.total_goto_charge|number_format}円</span>
                    </p>
                  {/if}
                </td>
                <td >
             　  
                </td>
              </tr>
            </table>
                </div>{* class="ri-box gi-box" *}
              {/foreach}

            </td>
          </tr>
        </table>
      </div>{* class="pi-box" *}
    {/foreach}

  </div>
{/foreach}
{/strip}
