{strip}
{* 性能アップのためこのファイル内での include の使用禁止 ＆ strip_tags を必要最低限に設定 *}

{***************************************************************************
    施設の処理
 ***************************************************************************}
{foreach from=$v->assign->index.hotels key=hotel_no item=hotel name=hotels}
  {assign var=vhotel value=$v->assign->values.hotels[$hotel.hotel_cd]}
  <div class="hi-box advance">
    {if is_empty($v->assign->search_condition.form.hotel) }
    <div class="hi-hotel">
      <div class="hi-name
      {assign var=plan_count value=0}
      {foreach from=$hotel.plans key=plan_no item=plan name=plans}
        {assign var=vplan         value=$v->assign->values.hotels[$hotel.hotel_cd].plans[$plan.plan_id]}
        {if $vplan.icons.highrank and $plan_count==0}
          {assign var=plan_count value=$plan_count+1} bg-bestprice
        {/if}
      {/foreach}
      "><a href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/">{$v->helper->form->strip_tags($vhotel.hotel_nm)}</a>
          {if $vhotel.icons.camp_goto == 1}　<img style="position: absolute;" src="{$v->env.root_path}img/spc/spc-p0-camp_goto_hotel.gif" width="140" height="20" alt="GoToトラベルキャンペーン" />{/if}
      </div>
      <table class="hi-summary" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="hi-photo" rowspan="2" valign="top">

            {* 施設外観写真 *}
            {if is_empty($vhotel.media)}
              <div class="hi-photo-none"></div>
            {else}
              <img src="{$v->env.path_img}/hotel/{$hotel.hotel_cd}/trim_138/{$vhotel.media.outside.file_nm|escape:'url'}" width="138" height="138" alt="{$v->helper->form->strip_tags($vhotel.hotel_nm)}" />          {/if}
          </td>
          <td valign="top">

            {* 施設特色 *}
            <div class="hi-info">{$v->helper->form->strip_tags($vhotel.info_short)}</div>
            {* 住所 *}
            <div class="hi-address">{$v->helper->form->strip_tags($vhotel.pref_nm)}{$v->helper->form->strip_tags($vhotel.address)}</div>
            {* 口コミ評価 *}
            {if $vhotel.voices.count >= 5}
              <div class="hi-voice">{$vhotel.voices.review.total|number_format:1|mb_convert_kana:'N'}</div>
              <div class="hi-voice-star">
                <img src="{$v->env.path_img}/vic/vic-star-w-{$vhotel.voices.review.total*10}.gif" width="100" height="17" alt="クチコミ総合{$vhotel.voices.review.total}">
              </div>
            {/if}
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
          <td align="right" valign="bottom">
            <div class="hi-cmd">
              {* ホテル情報を見る *}
              <div class="btn-b06-098-s"><a class="btnimg" href="{$v->env.path_base}/hotel/{$hotel.hotel_cd}/" title="{$v->helper->form->strip_tags($vhotel.hotel_nm)}の詳細を表示"><img src="{$v->env.path_img}/btn/b06-hotel.gif" width="98" height="23" alt="{$v->helper->form->strip_tags($vhotel.hotel_nm)}の詳細を表示" /></a></div>
              {* プランリストを見る *}
              <div class="btn-b06-098-s"><a class="btnimg" href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/" title="{$v->helper->form->strip_tags($hotel.hotel_nm)}のプランリストを表示"><img src="{$v->env.path_img}/btn/b06-plan.gif" width="98" height="23" alt="{$v->helper->form->strip_tags($vhotel.hotel_nm)}のプランリストを表示" /></a></div>
              {* クリップ *}
              {if $v->user->partner->connect_type == 'reserve'}
                {if $v->assign->params.view != "marker"}
                  {if $vhotel.icons.cliped}
                   <div class="btn-b06-clip2-s"><a class="btnimg jqs-clip jqs-clip-{$hotel.hotel_cd} jqs-on" href="/clip/{$hotel.hotel_cd}/" title="クリップ削除"><img src="{$v->env.path_img}/btn/b06-clip2.gif" width="98" height="23" alt="クリップ中" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a></div>
                  {else}
                   <div class="btn-b06-098-s"><a class="btnimg jqs-clip jqs-clip-{$hotel.hotel_cd}" href="/clip/{$hotel.hotel_cd}/" title="クリップする"><img src="{$v->env.path_img}/btn/b06-clip1.gif" width="98" height="23" alt="クリップする" title="「クリップ」とは、よく泊まる宿泊施設や、いつか泊まってみたい気になる宿泊施設を保存できる機能です。サイトに会員ログインいただき「クリップ」すると、その宿泊施設はベストリザーブ・宿ぷらざの画面を閉じても、あらためてサイトに会員ログインいただくと「クリップ」されたまま保存されています。「クリップ」された宿泊施設だけの空室を簡単に探すことができるようになります。" /></a></div>
                  {/if}
                {/if}
              {/if}
              {* ＪＲコレクション プラン一覧へのリンク *}
              {if $vhotel.jrc_hotel_cd}
                <div class="btn-b06-138-s jqs-jrc">
                  <a class="btnimg" href="{$v->env.base_path}jrc/?SiteCode=00574251&PageType=hotel&ListMode=Plan&HotelCD={$vhotel.jrc_hotel_cd}{
                    if $vhotel.pref_nm == '茨城県'
                    or $vhotel.pref_nm == '栃木県'
                    or $vhotel.pref_nm == '群馬県'
                    or $vhotel.pref_nm == '埼玉県'
                    or $vhotel.pref_nm == '千葉県'
                    or $vhotel.pref_nm == '東京都'
                    or $vhotel.pref_nm == '神奈川県'}&Departure=56{/if}" title="出発日などを指定して{$v->helper->form->strip_tags($vhotel.hotel_nm)}の「ＪＲ＋宿泊検索」へすすみます。" target="_blank">
                    <img src="{$v->env.path_img}/btn/b06-jrc1.gif" width="138" height="23" alt="ＪＲ＋宿泊検索へすすむ" />
                  </a>
                </div>
              {/if}
            </div>
          </td>
        </tr>
      </table>
    </div>
    {/if}
    {***************************************************************************
        プランの処理
     ***************************************************************************}
    {assign var=plan_count value=0}
    {foreach from=$hotel.plans key=plan_no item=plan name=plans}

      {assign var=vplan         value=$v->assign->values.hotels[$hotel.hotel_cd].plans[$plan.plan_id]}
      {assign var=charge        value=$vplan.charge}
      {assign var=charge_months value=$vplan.charge_months}
      {assign var=plan_count value=$plan_count+1}

      <div class="pi-box
      {if $vplan.icons.highrank} line-bestprice{/if}
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

              <div class="pi-head{if $vplan.icons.point_rate > 9} pi-head-p{$vplan.icons.point_rate}{/if}{if $vplan.icons.highrank} pi-head-ph{/if}">
                <div class="pi-name">
                  {* ポイント10倍以上アイコン *}
                  {if $vplan.icons.point_rate > 9}
                    <img style="padding-right:5px" src="{$v->env.root_path}img/spc/spc-p3-pointover{$vplan.icons.point_rate}.gif" width="74" height="19" align="top" alt="ポイント{$vplan.icons.point_rate}倍" />
                  {/if}
                  <a href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/{$plan.plan_id}/">{$vplan.plan_nm}</a>
                </div>
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
                  {* if $vplan.icons.insurance_status}<img src="{$v->env.path_img}/spc/spc-p0-insurance.gif" width="117" height="20" alt="お天気保険付きプラン" />{/if *}
                  {if $vplan.icons.fss             }<img src="{$v->env.path_img}/spc/spc-p0-fss.gif" width="82" height="20" alt="金土日プラン" />{/if}

                  {* ポイント利用 *}
                  {if $vplan.icons.point_status}<a href="{$v->env.path_base}/point/"><img src="{$v->env.path_img}/spc/spc-p0-point.gif" width="85" height="20" alt="ポイント利用可" /></a>{/if}

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

              {***************************************************************************
                  部屋の処理
               ***************************************************************************}
              <div class="ri-box gi-box">

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
                  <span class="tax_included"><span class="arrow down_arrow">表示価格は税込です</span><span class="arrow down_arrow">　　　　　　　　　　　　　　　　</span></span>
                </div>


                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                  <tr>

                    {* 利用人数 *}
                    {* 5種類以上の利用人数が存在するときは、表示領域が足りなくなるので範囲で表現するアイコンで表示します。 *}
                    <td class="gi-expand gi-capacity" nowrap="nowrap">
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
                      <div class="gi-point rsv-gi-point"><img src="{$v->env.path_img}/spc/spc-p3-pointrate{$vplan.icons.point_rate|regex_replace:'/^([0-9])$/':'0$1'}.gif" width="{
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
                        {if $vplan.br_point_plus_flg == 1 and $vplan.br_point_plus_total > 0}
                          <div class="box-br-point-plus-undated box-br-point-plus-mt4">
                          <span class="icon-br-point-plus">さらにポイント<br />最大<span class="highlight-plus">+</span><span class="highlight-point">{$vplan.br_point_plus_total}</span>&nbsp;%</span>
                          <p class="info-br-point-plus">特定の宿泊日に宿泊された場合は<br />さらにポイントがプラスされます。</p>
                          </div>
                        {/if}
                      </div>

                      </div>
                      {/if}
                    </td>

                    {* 大人１名１泊最低料金・全員全泊合計料金 *}
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
                        <a class="btnimg" href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/{$plan.plan_id}/">
                          <img src="{$v->env.path_img}/btn/b04-plan.gif" width="98" height="38" alt="詳細を見る" />
                        </a>
                      </div>
                    </td>

                    {* 空室検索ボタン *}
                    <td class="gi-action">
                      <div class="btn-b04-098-s">
                        {if  0 < $charge.vacant_max}
                          <a class="btnimg" href="{$v->env.path_base_module}/plan/reserve/?hotel_cd={$hotel.hotel_cd}&plan_id={$plan.plan_id}">
                            <img src="{$v->env.path_img}/btn/b04-search.gif" width="98" height="38" alt="空室を検索して予約する" />
                          </a>
                        {else}
                          {if 0 == $charge.vacant_max}
                            <img src="{$v->env.path_img}/btn/b04-soldout_disable.gif" width="98" height="38" alt="満室" />
                          {else}
                            <img src="{$v->env.path_img}/btn/b04-prepare_disable.gif" width="98" height="38" alt="販売なし" />
                          {/if}
                        {/if}
                      </div>
                    </td>

                  </tr>
                </table>

              </div>{* class="ri-box gi-box" *}

            </td>
          </tr>
        </table>
      </div>{* class="pi-box" *}
    {/foreach}

    {* すべてのプランを表示するリンク *}
    {if $plan_count < $vhotel.plan_count}
      <div class="hi-foot">
        <div class="hi-link"><a href="{$v->env.path_base}/plan/{$hotel.hotel_cd}/">この施設のすべてのプランを見る</a></div>
      </div>
    {/if}

  </div>
{/foreach}
{/strip}