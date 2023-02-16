{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search.tpl --}}

{strip}
{if !is_empty($v->assign->piece.hotels.0.hotel_cd)}
{assign var=ihotel value=$v->assign->piece.hotels.0}
{assign var=vhotel value=$v->assign->values.hotels[$ihotel.hotel_cd]}
<div style="margin:20px 20px 20px 10px;">
  {assign var=ihotel value=$v->assign->piece.hotels.0}
  {assign var=vhotel value=$v->assign->values.hotels[$ihotel.hotel_cd]}
  <div style="padding: 0 0 0 46px;background:url({$v->env.root_path}img/lhd/lhd-hotel.gif) left top no-repeat;">{$vhotel.hotel_nm}</div>
  {if !is_empty($ihotel.plans.0.plan_id)}
  {assign var=iplan value=$ihotel.plans.0}
  {assign var=iroom value=$iplan.plan_rooms.0}
  {assign var=vplan value=$vhotel.plans[$iplan.plan_id]}
  {assign var=vroom value=$vhotel.rooms[$iroom.room_id]}
  <div class="sfm-plan_nm" style="margin-top:0.5em;padding: 0 0 0 46px;background:url({$v->env.root_path}img/lhd/lhd-plan.gif) left top no-repeat;color:#005e8e">{$vplan.plan_nm}{if !is_empty($vroom.room_nm)}［{$vroom.room_nm}］{/if}</div>
  {/if}
</div>
{/if}
{* -------------------------------------------------------------------------- *}
{* このテンプレートを修正した場合、以下の点に注意してください                 *}
{*                                                                            *}
{* ※深夜時間(23時以降)デザインが切り替わり、今すぐ泊まれる宿の検索フォームが *}
{*   表示されるようになります                                                 *}
{*   ローカル環境(PC)の時間を変更し深夜時間のデザインを確認、ラジオボタンを   *}
{*   クリックし、表示の切り替え確認、この2点を必ず確認してください。          *}
{* -------------------------------------------------------------------------- *}
{if !is_empty($v->assign->piece.hotels.0.hotel_cd)}<div style="margin:0 auto;width:344px;">{/if}
  <div class ="sfm-search">
    <div class ="sfm-cat-box">
      <ul class ="clearfix">
        {* 国内宿泊 *}
        <li><input type="radio" name="search-cat" value="normal" id="sfm-radio01" {if !$v->assign->search_condition.form.midnight.current_status} checked="checked"{/if} class="btnimg jqs-tab tab-normal2{$bgcolor}{if !$v->assign->search_condition.form.midnight.current_status} current{/if}">
        <label for="sfm-radio01">国内宿泊</label></li>
          {* 今すぐ泊まれる宿 *}
          {if $v->assign->search_condition.form.midnight}
          <li><input type="radio" name="search-cat" value="today" id="sfm-radio06" {if $v->assign->search_condition.form.midnight.current_status} checked="checked"{/if} class="btnimg jqs-tab tab-today{$bgcolor}{if $v->assign->search_condition.form.midnight.current_status} current{/if}"{if !$v->assign->search_condition.form.midnight.current_status} style="display: none;"{/if}>
          <label for="sfm-radio06" {if !$v->assign->search_condition.form.midnight.current_status} style="display: none;"{/if}>今すぐ泊まれる宿</label></li>
          {/if}
          {* JR＋宿泊 *}
          {if ($vhotel.jrc_hotel_cd or is_empty($v->assign->piece.hotels.0.hotel_cd))}
          <li class="jqs-jrc"><input type="radio" name="search-cat" value="jrc" id="sfm-radio02" class="btnimg jqs-tab tab-jrc{$bgcolor}">
          <label for="sfm-radio02" name="search-cat">JR＋宿泊</label></li>
          {/if}
          {* ベストプライスルーム *}
          {*{if (!is_empty($isTop) and $isTop == 'true' and !$v->assign->search_condition.form.midnight.current_status)}
          <li><input type="radio" name="search-cat" value="bestprice" id="sfm-radio03" class="btnimg jqs-tab jqs-bestprice tab-bestprice{$bgcolor}">
          <label for="sfm-radio03">ベストプライスルーム</label></li>
          {/if}*}
          {if (!is_empty($isTop) and $isTop == 'true' and !$v->assign->search_condition.form.midnight.current_status)}
          {* レンタカー予約 *}
          {* <li><input type="radio" name="search-cat" value="rentcar" id="sfm-radio05" onclick="window.location.href = '{$v->env.path_base}/rentacar/'">
          <label for="sfm-radio05">レンタカー予約</label></li> *}
          {* 高速バス予約 *}
          <li><input type="radio" name="search-cat" value="bus" id="sfm-radio04" class="jqs-tabLink" onclick="window.open('{$v->env.path_base}/ro/tabiplaza-bus/')">
          <label for="sfm-radio04">高速バス予約</label></li>
          {/if}
        </ul>
      </div>
      {* 国内宿泊 *}
      <div class="sfm-normal2{$bgcolor}{if (!is_empty($v->assign->piece.hotels.0.hotel_cd))}s{/if}" name="search-cat_normal_box"{if $v->assign->search_condition.form.midnight.current_status} style="display: none;"{/if}>
        <div class="sfm-normal2{$bgcolor}{if (!is_empty($v->assign->piece.hotels.0.hotel_cd))}s{/if}-inner">
          {include file='../_common/_form_search_normal.tpl'}
        </div>
      </div>
      {* 今すぐ泊まれる宿 *}
      {if $v->assign->search_condition.form.midnight}
      <div class="sfm-today{$bgcolor}{if (!is_empty($v->assign->piece.hotels.0.hotel_cd))}s{/if}" name="search-cat_today_box"{if !$v->assign->search_condition.form.midnight.current_status} style="display: none;"{/if}>
        <div class="sfm-today{$bgcolor}{if (!is_empty($v->assign->piece.hotels.0.hotel_cd))}s{/if}-inner" >
          {include file='../_common/_form_search_today.tpl'}
        </div>
      </div>
      {/if}
      {* ＪＲ＋宿泊 *}
      {if ($vhotel.jrc_hotel_cd or is_empty($v->assign->piece.hotels.0.hotel_cd))}
      <div class="sfm-jrc{$bgcolor}{if (!is_empty($v->assign->piece.hotels.0.hotel_cd))}s{/if}" style="display: none;" name="search-cat_jrc_box">
        <div class="sfm-jrc{$bgcolor}{if (!is_empty($v->assign->piece.hotels.0.hotel_cd))}s{/if}-inner">
          {include file='../_common/_form_search_jrc.tpl'}
        </div>
      </div>
      {/if}
      {* ベストプライスルーム *}
      {*{if (!is_empty($isTop) and $isTop and !$v->assign->search_condition.form.midnight.current_status)}
      <div class="sfm-bestprice" name="search-cat_bestprice_box" style="display: none;">
        <div class="sfm-bestprice-inner">
          {include file='../_common/_form_search_bestprice.tpl'}
        </div>
      </div>
      {/if}*}
    </div>
  {if !is_empty($v->assign->piece.hotels.0.hotel_cd)}</div>{/if}
  {/strip}
