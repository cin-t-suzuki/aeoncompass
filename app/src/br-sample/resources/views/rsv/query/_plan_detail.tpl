{strip}
{* 性能アップのためこのファイル内での include の使用禁止 ＆ strip_tags を必要最低限に設定 *}
{assign var=params value=$v->assign->conditions}

{***************************************************************************
    施設の処理
 ***************************************************************************}
{foreach from=$v->assign->index.hotels key=hotel_no item=hotel name=hotels}
  {assign var=vhotel value=$v->assign->values.hotels[$hotel.hotel_cd]}
  {if !is_empty($hotel.plans)}
  <div class="hi-box advance">

    {***************************************************************************
        プランの処理
     ***************************************************************************}
    {assign var=plan_count value=0}
    {foreach from=$hotel.plans key=plan_no item=plan name=plans}

      {assign var=vplan         value=$v->assign->values.hotels[$hotel.hotel_cd].plans[$plan.plan_id]}

      <div class="pi-box" style="margin-top:0px;">

        <div class="border-bd" style="padding-bottom:10px;">
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
                <div class="pi-head">
                  <div class="pi-name"><a href="{$v->env.path_base_module}/query/?hotel_cd={$hotel.hotel_cd}&plan_id={$plan.plan_id}">{$vplan.plan_nm}</a></div>
                  <div class="pi-icons">

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
                    {*{if $vplan.icons.insurance_status}<img src="{$v->env.path_img}/spc/spc-p0-insurance.gif" width="117" height="20" alt="お天気保険付きプラン" />{/if}*}
                    {if $vplan.icons.fss             }<img src="{$v->env.path_img}/spc/spc-p0-fss.gif" width="82" height="20" alt="金土日プラン" />{/if}

                    {* ポイント利用 *}
                    {if $vplan.icons.point_status}<img src="{$v->env.path_img}/spc/spc-p0-point.gif" width="85" height="20" alt="ポイント利用可" />{/if}

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

                  </div>{* class="pi-icon" *}
                </div>{* class="pi-head" *}

                <div class="gi-box">
                  <div class="gi-icons">

                    {* 部屋タイプ *}
                    {if $vplan.rooms_icons.roomtype_capsule   }<img src="{$v->env.path_img}/spc/spc-r01-capsule.gif" width="54" height="20" alt="カプセル" />{/if}
                    {if $vplan.rooms_icons.roomtype_single    }<img src="{$v->env.path_img}/spc/spc-r01-single.gif" width="54" height="20" alt="シングル" />{/if}
                    {if $vplan.rooms_icons.roomtype_twin      }<img src="{$v->env.path_img}/spc/spc-r01-twin.gif" width="45" height="20" alt="ツイン" />{/if}
                    {if $vplan.rooms_icons.roomtype_semidouble}<img src="{$v->env.path_img}/spc/spc-r01-semidouble.gif" width="65" height="20" alt="セミダブル" />{/if}
                    {if $vplan.rooms_icons.roomtype_double    }<img src="{$v->env.path_img}/spc/spc-r01-double.gif" width="45" height="20" alt="ダブル" />{/if}
                    {if $vplan.rooms_icons.roomtype_triple    }<img src="{$v->env.path_img}/spc/spc-r01-triple.gif" width="54" height="20" alt="トリプル" />{/if}
                    {if $vplan.rooms_icons.roomtype_4bed      }<img src="{$v->env.path_img}/spc/spc-r01-4bed.gif" width="54" height="20" alt="4ベッド" />{/if}
                    {if $vplan.rooms_icons.roomtype_suite     }<img src="{$v->env.path_img}/spc/spc-r01-suite.gif" width="50" height="20" alt="スイート" />{/if}
                    {if $vplan.rooms_icons.roomtype_maisonette}<img src="{$v->env.path_img}/spc/spc-r01-maisonette.gif" width="65" height="20" alt="メゾネット" />{/if}
                    {if $vplan.rooms_icons.roomtype_jstyle    }<img src="{$v->env.path_img}/spc/spc-r01-jstyle.gif" width="35" height="20" alt="和室" />{/if}
                    {if $vplan.rooms_icons.roomtype_jmix      }<img src="{$v->env.path_img}/spc/spc-r01-jmix.gif" width="45" height="20" alt="和洋室" />{/if}
                    {if $vplan.rooms_icons.roomtype_other     }<img src="{$v->env.path_img}/spc/spc-r01-other.gif" width="45" height="20" alt="その他" />{/if}

                  </div>

                  <div class="gi-icons">
                    {* 利用人数 *}
                    {* 5種類以上の利用人数が存在するときは、表示領域が足りなくなるので範囲で表現するアイコンで表示します。 *}
                    {assign var=capacity value=''}
                    {if $vplan.plan_rooms_icons.capacity_1}{assign var=capacity value=$capacity|cat:'1'}{/if}
                    {if $vplan.plan_rooms_icons.capacity_2}{assign var=capacity value=$capacity|cat:'2'}{/if}
                    {if $vplan.plan_rooms_icons.capacity_3}{assign var=capacity value=$capacity|cat:'3'}{/if}
                    {if $vplan.plan_rooms_icons.capacity_4}{assign var=capacity value=$capacity|cat:'4'}{/if}
                    {if $vplan.plan_rooms_icons.capacity_5}{assign var=capacity value=$capacity|cat:'5'}{/if}
                    {if $vplan.plan_rooms_icons.capacity_6}{assign var=capacity value=$capacity|cat:'6'}{/if}
                    {    if $capacity == '123456'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity1to6.gif width="68" height="34" alt="1～6名利用" />
                    {elseif $capacity == '12345'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity1to5.gif width="68" height="34" alt="1～5名利用" />
                    {elseif $capacity == '12346'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity1to4.gif width="68" height="34" alt="1～4名利用" />
                      <img src={$v->env.path_img}/spc/spc-q1-capacity6.gif width="43" height="34" alt="6名利用" />
                    {elseif $capacity == '12356'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity1to3.gif width="68" height="34" alt="1～3名利用" />
                      <img src={$v->env.path_img}/spc/spc-q1-capacity5.gif width="40" height="34" alt="5名利用" />
                      <img src={$v->env.path_img}/spc/spc-q1-capacity6.gif width="40" height="34" alt="6名利用" />
                    {elseif $capacity == '23456'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity2to6.gif width="68" height="34" alt="2～6名利用" />
                    {elseif $capacity == '13456'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity1.gif width="40" height="34" alt="1名利用" />
                      <img src={$v->env.path_img}/spc/spc-q1-capacity3to6.gif width="68" height="34" alt="1～6名利用" />
                    {elseif $capacity == '12456'}
                      <img src={$v->env.path_img}/spc/spc-q1-capacity1.gif width="40" height="34" alt="1名利用" />
                      <img src={$v->env.path_img}/spc/spc-q1-capacity2.gif width="40" height="34" alt="2名利用" />
                      <img src={$v->env.path_img}/spc/spc-q1-capacity4to6.gif width="68" height="34" alt="4～6名利用" />
                    {else}
                      {if $vplan.plan_rooms_icons.capacity_1}<img src="{$v->env.path_img}/spc/spc-q1-capacity1.gif" width="40" height="34" alt="1名利用" />{/if}
                      {if $vplan.plan_rooms_icons.capacity_2}<img src="{$v->env.path_img}/spc/spc-q1-capacity2.gif" width="40" height="34" alt="2名利用" />{/if}
                      {if $vplan.plan_rooms_icons.capacity_3}<img src="{$v->env.path_img}/spc/spc-q1-capacity3.gif" width="40" height="34" alt="3名利用" />{/if}
                      {if $vplan.plan_rooms_icons.capacity_4}<img src="{$v->env.path_img}/spc/spc-q1-capacity4.gif" width="40" height="34" alt="4名利用" />{/if}
                      {if $vplan.plan_rooms_icons.capacity_5}<img src="{$v->env.path_img}/spc/spc-q1-capacity5.gif" width="40" height="34" alt="5名利用" />{/if}
                      {if $vplan.plan_rooms_icons.capacity_6}<img src="{$v->env.path_img}/spc/spc-q1-capacity6.gif" width="40" height="34" alt="6名利用" />{/if}
                    {/if}
                  </div>

                </div>

                {* プラン特色 *}
                {if !is_empty($vplan.info_full)}
                  <div class="pi-body">
                    {$v->helper->form->strip_tags($vplan.info_full)|replace:"\n":'<br />'}
                  </div>{* class="pi-body" *}
                {/if}

              </td>
            </tr>
          </table>
        </div>

        {***************************************************************************
            部屋の処理
         ***************************************************************************}
        {assign var=room_capacity_count value=0}
        {foreach from=$plan.plan_rooms key=room_no item=room name=rooms}

          {assign var=planroom_id value=$plan.plan_id|cat:$room.room_id}
          {assign var=vroom value=$v->assign->values.hotels[$hotel.hotel_cd].rooms[$room.room_id]}
          {assign var=vplom value=$v->assign->values.hotels[$hotel.hotel_cd].plan_rooms[$planroom_id]}

          <div class="ri-box{if !$smarty.foreach.rooms.last} border-bd{/if}" style="margin-top:10px">

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td class="ri-photo">
                  {* 部屋の画像 *}
                  {if !is_empty($vroom.medias[0].file_nm)}
                    <img src="{$v->env.path_img}/hotel/{$hotel.hotel_cd}/trim_100/{$vroom.medias[0].file_nm}" width="100" height="100" alt="{$vhotel.hotel_nm} {$vplan.plan_nm} {$vroom.room_nm}" />
                  {/if}
                </td>

                <td>

                  <div class="ri-body">
                    {if $vplom.icons.plan_new}<div class="ri-new">{/if}
                    <div class="ri-name"><a href="{$v->env.path_base_module}/plan/vacant/?hotel_cd={$hotel.hotel_cd}&plan_id={$plan.plan_id}&room_id={$room.room_id}">{$vroom.room_nm}</a></div>
                    <div class="ri-floor">（{$vroom.floorage_min}{if $vroom.floorage_min != $vroom.floorage_max}～{$vroom.floorage_max}{/if}{if $vroom.floor_unit == 0}平米{else}畳{/if}）</div>
                    {if $vplom.icons.plan_new}</div>{/if}

                    <div class="ri-icons">

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
                      {* ダッシュ・クローンキャンペーンサイトないのでコメント
                        {if $vplom.icons.camp}<img src="{$v->env.path_img}/spc/spc-p0-camp.gif" width="70" height="20" alt="キャンペーン" />{/if}
                      *}

                    </div>{* class="ri-icons *}
                  </div>

                  {***************************************************************************
                      利用人数の処理
                   ***************************************************************************}
                  {*foreach from=$room.capacities key=capacity_no item=capacity name=capacities*}
                    {assign var=capacity value=''}
                    {if $vplom.icons.capacity_1}{assign var=capacity value=$capacity|cat:'1'}{/if}
                    {if $vplom.icons.capacity_2}{assign var=capacity value=$capacity|cat:'2'}{/if}
                    {if $vplom.icons.capacity_3}{assign var=capacity value=$capacity|cat:'3'}{/if}
                    {if $vplom.icons.capacity_4}{assign var=capacity value=$capacity|cat:'4'}{/if}
                    {if $vplom.icons.capacity_5}{assign var=capacity value=$capacity|cat:'5'}{/if}
                    {if $vplom.icons.capacity_6}{assign var=capacity value=$capacity|cat:'6'}{/if}

                    {assign var=charge        value=$vplom.charge}
                    {assign var=room_capacity_count value=$room_capacity_count+1}

                    <div class="gi-box{if !$smarty.foreach.capacities.last} border-bd{/if}">

                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>

                          {* 利用人数 *}
                          <td class="gi-expand gi-capacity" nowrap="nowrap">
                            {    if $capacity == '123456'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity1to6.gif width="68" height="34" alt="1～6名利用" />
                            {elseif $capacity == '12345'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity1to5.gif width="68" height="34" alt="1～5名利用" />
                            {elseif $capacity == '12346'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity1to4.gif width="68" height="34" alt="1～4名利用" />
                              <img src={$v->env.path_img}/spc/spc-q1-capacity6.gif width="43" height="34" alt="6名利用" />
                            {elseif $capacity == '12356'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity1to3.gif width="68" height="34" alt="1～3名利用" />
                              <img src={$v->env.path_img}/spc/spc-q1-capacity5.gif width="40" height="34" alt="5名利用" />
                              <img src={$v->env.path_img}/spc/spc-q1-capacity6.gif width="40" height="34" alt="6名利用" />
                            {elseif $capacity == '23456'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity2to6.gif width="68" height="34" alt="2～6名利用" />
                            {elseif $capacity == '13456'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity1.gif width="40" height="34" alt="1名利用" />
                              <img src={$v->env.path_img}/spc/spc-q1-capacity3to6.gif width="68" height="34" alt="1～6名利用" />
                            {elseif $capacity == '12456'}
                              <img src={$v->env.path_img}/spc/spc-q1-capacity1.gif width="40" height="34" alt="1名利用" />
                              <img src={$v->env.path_img}/spc/spc-q1-capacity2.gif width="40" height="34" alt="2名利用" />
                              <img src={$v->env.path_img}/spc/spc-q1-capacity4to6.gif width="68" height="34" alt="4～6名利用" />
                            {else}
                              {if $vplom.icons.capacity_1}<img src="{$v->env.path_img}/spc/spc-q1-capacity1.gif" width="40" height="34" alt="1名利用" />{/if}
                              {if $vplom.icons.capacity_2}<img src="{$v->env.path_img}/spc/spc-q1-capacity2.gif" width="40" height="34" alt="2名利用" />{/if}
                              {if $vplom.icons.capacity_3}<img src="{$v->env.path_img}/spc/spc-q1-capacity3.gif" width="40" height="34" alt="3名利用" />{/if}
                              {if $vplom.icons.capacity_4}<img src="{$v->env.path_img}/spc/spc-q1-capacity4.gif" width="40" height="34" alt="4名利用" />{/if}
                              {if $vplom.icons.capacity_5}<img src="{$v->env.path_img}/spc/spc-q1-capacity5.gif" width="40" height="34" alt="5名利用" />{/if}
                              {if $vplom.icons.capacity_6}<img src="{$v->env.path_img}/spc/spc-q1-capacity6.gif" width="40" height="34" alt="6名利用" />{/if}
                            {/if}
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
                              /if}" height="15" alt="ポイント{$vplan.icons.point_rate}%"></div>
                            {/if}
                          </td>

                          {* 大人１名１泊最低料金 *}
                          <td class="gi-price gi-pricefrom" nowrap="nowrap">
                            {if $v->assign->index.page.charge_type == 1}
                              <span class="gi-currency"><span class="gi-unitprice">{$charge.min_sales_charge|number_format}</span>円～</span>/人
                            {elseif $v->assign->index.page.charge_type == 2}
                              <span class="gi-currency"><span class="gi-unitprice">～{$charge.max_sales_charge|number_format}</span>円</span>/人
                            {/if}
                          </td>

                          {* 詳細表示ボタン *}
                          <td class="gi-action">
                            <div class="btn-b04-098-s">
                              <a class="btnimg" href="{$v->env.path_base_module}/plan/vacant/?hotel_cd={$hotel.hotel_cd}&plan_id={$plan.plan_id}&room_id={$room.room_id}&capacity={$capacity}">
                                <img src="{$v->env.path_img}/btn/b04-vacancy.gif" width="98" height="38" alt="日別の空室状況を見る" />
                              </a>
                            </div>
                          </td>

                          {* 空室検索ボタン *}
                          <td class="gi-action">
                            <div class="btn-b04-098-s">
                              {if 0 < $charge.vacant_max}
                                <a class="btnimg" href="{$v->env.path_base_module}/plan/reserve/?hotel_cd={$hotel.hotel_cd}&plan_id={$plan.plan_id}&room_id={$room.room_id}&adult[0]={$capacity}">
                                  <img src="{$v->env.path_img}/btn/b04-search.gif" width="98" height="38" alt="空室を検索して予約する" />
                                </a>
                              {else}
                                <img src="{$v->env.path_img}/btn/b04-soldout_disable.gif" width="98" height="38" alt="満室" />
                              {/if}
                            </div>
                          </td>
                        </tr>
                      </table>

                    </div>{* class="gi-box" *}

                  {*/foreach*}

                </td>
              </tr>
            </table>

          </div>{* class="ri-box" *}
        {/foreach}

      </div>{* class="pi-box" *}
    {/foreach}
  </div>
  {else}
    <div class="sch-msg">ご提供できる宿泊プランがございません。</div>
  {/if}
{/foreach}
{/strip}