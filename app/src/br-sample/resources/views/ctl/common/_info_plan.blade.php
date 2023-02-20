<!-- {* 引数 $plan *}
{**}
{*---リロ制御-------------------------------------------------------------------*}
{assign var=is_relo_ctl value=false}
{* リロ専用プラン *}
{if $v->assign->sp_pgi.relo|@in_array:$plan.plan_detail.partner_group_id}
  {* NTAログイン *}
  {if $v->user->operator->is_nta()}
    {assign var=is_relo_ctl value=true}
  {/if}
{/if}
{*---/リロ制御------------------------------------------------------------------*}
{**}
{*---販売先制御-----------------------------------------------------------------*}
{* JRコレクション *}
{assign var=is_jrc value=false}
{if $v->assign->sp_pgi.jrc|@in_array:$plan.plan_detail.partner_group_id}
  {assign var=is_jrc value=true}
{/if}
{* リロクラブ *}
{assign var=is_relo value=false}
{if $v->assign->sp_pgi.relo|@in_array:$plan.plan_detail.partner_group_id}
  {assign var=is_relo value=true}
{/if}
{*---/販売先制御----------------------------------------------------------------*}
{**} -->
<div class="info-plan-base">
  <div class="info-plan-base-back">
    <div class="info-plan-base-inline">
      <!-- {* プラン名 *} -->
      <div><span class="font-bold jqs-plan-nm">{{$plan->plan_nm}}</span></div>
      <!-- {* プランラベル *} -->
      <div class="font-normal">
        @if(is_null($plan->partner_cd))
          [<span class="label-cd-text">{{$plan->label_cd}}</span>]
        @endif
        @if($plan->partner_group_id != 0)
          <span class="msg-text-success prev-text">※特定サイト限定販売の為プレビュー不可</span>
        @else
          <span class="prev-text"><a href="{$v->env.source_path}{$v->env.module}/redirect/rsvplan/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}" target="_brank">プレビュー&nbsp;>>&nbsp;</a></span>
        @endif
      </div>
      <div>
        @if(is_null($plan->partner_cd))
          <span class="tag-text-info">リロ専用プラン</span>
        @endif
        <!-- {*====================================================================*}
        {* 連泊                                                               *}
        {*====================================================================*} -->
        @if($plan->stay_limit > 1 && $plan->stay_cap > 1)
          <!-- {* 任意の最小泊数と最大泊数が指定されている（デフォルトでない状態） *} -->
          @if($plan->stay_limit === $plan->stay_cap)
            <!-- {* 最小泊数と最大泊数が同じ値 *} -->
            <span class="tag-text-success">{{$plan->stay_limit}}連泊限定</span>
          @else
            <!-- {* 最小泊数と最大泊数が異なる値 *} -->
            <span class="tag-text-success">{{$plan->stay_limit}}～{{$plan->stay_cap}}連泊まで</span>
          @endif
        @elseif($plan->stay_limit > 1)
          <!-- {* 任意の最小泊数のみが指定されている *} -->
          <span class="tag-text-success">{{$plan->stay_limit}}連泊～</span>
        @elseif($plan->stay_cap > 1)
          <!-- {* 任意の最大泊数のみが指定されている(2泊以上) *} -->
          <span class="tag-text-success">１～{{$plan->stay_cap}}連泊まで</span>
        @elseif($plan->stay_cap > 0)
          <!-- {* 任意の最大泊数のみが指定されている(1泊のみ) *} -->
          <span class="tag-text-success">１泊限定</span>
        @endif
    
        <!-- {* 食事 *} -->
        @if($plan->element_value_id === 0 )<span class="tag-text-success">食事無し</span>
        @elseif($plan->element_value_id === 1 )<span class="tag-text-success">夕食付</span>
        @elseif($plan->element_value_id === 2 )<span class="tag-text-success">朝食付</span>
        @elseif($plan->element_value_id === 3 )<span class="tag-text-success">夕・朝食付</span>
        @endif
        <!-- {* プランタイプ *} -->
        @if($plan->plan_type === 'fss')
          <span class="tag-text-success">金土日</span>
        @endif
        <!-- {* 料金タイプ *} -->
        @if($plan->charge_type === 0)<span class="tag-text-success">１室料金</span>
        @elseif($plan->charge_type === 1)<span class="tag-text-success">１人料金</span>
        @else<span class="tag-text-success">料金タイプ未設定</span>
        @endif
        <!-- {* 支払方法 *} -->
        @if($plan->payment_way == 3)<span class="tag-text-success">事前カード決済&nbsp;/&nbsp;現地決済</span>
        @elseif($plan->payment_way == 2)<span class="tag-text-success">現地決済</span>
        @elseif($plan->payment_way == 1)<span class="tag-text-success">事前カード決済</span>
        @endif
        <!-- {* ポイント利用 *} -->
        @if($plan->point_status === 1)
          <span class="tag-text-success">ポイント利用可能&nbsp;/&nbsp;{{$plan->issue_point_rate}}％付与</span>
        @else
          <span class="tag-text-success">ポイント利用不可</span>
        @endif
        <!-- {* キャンペーン対象 *} -->
        @if(! is_null($plan->camp_cd))
          <span class="tag-text-success">キャンペーン</span>
        @endif
        <!-- {* BR提供 *} -->
        @if($plan->stock_type == 1)
          <span class="tag-text-success">ＢＲ提供</span>
        @endif
        <!-- {* パワー *} -->
        @if($plan->stock_type == 1 && $plan->payment_way == 1)
          <span class="tag-text-success">パワー</span>
        @endif
        <!-- {* 販売チャンネル *} -->
        @if($plan->partner_group_id == 0)
          <span class="tag-text-success">全サイト共通販売</span>
        @else
          <!-- JRセットプランの提携先グループだった場合のデータを取得する分岐処理を記載する。 -->
          @if(false)
            <span class="tag-text-success">特定サイト限定販売</span>
          @endif
        @endif
        @if(false)
          <span class="tag-text-success">ＪＲコレクション</span>
        @endif
        <!-- {* チェックイン *} -->
        @if(! is_null($plan->check_in) || ! is_null($plan->check_in_end))
          @if(is_null($plan->check_in_end))
            <span class="tag-text-success" title="チェックイン：{{$plan->check_in}}&nbsp;～&nbsp;指定無し">チェックイン</span>
          @else
            <span class="tag-text-success" title="チェックイン：{{$plan->check_in}}&nbsp;～&nbsp;{{$plan->check_in_end}}">チェックイン</span>
          @endif
        @endif
        <!-- {* チェックアウト *} -->
        @if(! is_null($plan->check_out))
          <span class="tag-text-success" title="チェックアウト：{{$plan->check_out}}">チェックアウト</span>
        @endif
      </div>
      <div class="margin-spacer"></div>
      <table class="info-plan-base-contents">
        <tr>
          <th class="align-c">プラン情報</th>
          <th class="align-c">販売期間</th>
          <th class="align-c">プラン画像</th>
          <th class="align-c">キャンセルポリシー</th>
          <th class="align-c">プラン休止設定</th>
          <th class="align-c"><br /></th>
        </tr>
        <tr>
          <!-- plan edit -->
          <td class="align-c plan-info-edit">
            <form action="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{$plan.plan_detail.hotel_cd}" />
                <input type="hidden" name="plan_id"   value="{$plan.plan_detail.plan_id}"  />
                <input type="submit" value="プランの編集" />
              </div>
            </form>
          </td>
          <!-- /plan edit -->
          <td class="align-c plan-accept_ymd {if is_empty($plan.plan_detail.accept_s_ymd) or is_empty($plan.plan_detail.accept_e_ymd) or ((($smarty.now|date_format:'%Y-%m-%d'|strtotime) > $plan.plan_detail.accept_e_ymd)  and !is_empty($plan.plan_detail.accept_e_ymd) ) }bk-deactive{/if}">
            <!-- {* 販売期間の状態によって表示を制御 *} -->
            @if(is_null($plan->accept_s_ymd) && is_null($plan->accept_e_ymd))
              <!-- {* 販売期間が未設定 *} -->
              <div class="plan-sale-status-box">
                <div class="plan-sale-status-msg" title="販売期間が未設定です。 クリックで販売期間の設定へ。">
                  <div class="align-c">
                    <a href="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}"><span class="msg-text-error">期間未設定</span>&nbsp;→&nbsp;</a>
                  </div>
                </div>
              </div>
            @else
              @if(date('%Y-%m-%d') > $plan->accept_e_ymd && ! is_null($plan->accept_e_ymd))
                <!-- {* 販売期間が経過 *} -->
                <div class="plan-sale-status-box">
                  <div class="plan-sale-status-msg" title="販売期間切れです。 クリックで販売期間の再設定へ。">
                    <div class="align-c">
                      <a href="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}"><span class="msg-text-error">販売期間切れ</span>&nbsp;→&nbsp;</a>
                    </div>
                  </div>
                </div>
              @else
                <!-- {* 通常の販売期間表示 *} -->
                @if(is_null($plan->accept_s_ymd))
                  <span class="msg-text-error">未設定</span>
                @else
                  @include('ctl.common._date')
                @endif
                ～
                @if(is_null($plan->accept_e_ymd))
                  <span class="msg-text-error">未設定</span>
                @else
                  @include('ctl.common._date')
                @endif
                <!-- {* どちらかが未設定の場合はメッセージを表示 *} -->
                @if(is_null($plan->accept_s_ymd) || is_null($plan->accept_e_ymd))
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="販売期間が未設定です。 クリックで販売期間の設定へ。">
                      <div class="align-c">
                        <a href="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}"><span class="msg-text-error">販売期間の設定へ</span>&nbsp;→&nbsp;</a>
                      </div>
                    </div>
                  </div>
                @endif
              @endif
            @endif
          </td>
          <!-- plan images -->
          <td class="align-c plan-media-edit {if $plan.plan_detail.media_cnt <= 0}bk-deactive{/if}">
            <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/editplan/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{$plan.plan_detail.hotel_cd}" />
                <input type="hidden" name="plan_id"   value="{$plan.plan_detail.plan_id}"  />
                <input type="submit" value="画像の設定" />
              </div>
            </form>
          </td>
          <!--/ plan images -->
          <!-- plan cancel policy -->
          <td class="align-c plan-cancel-policy">
            <form action="{$v->env.source_path}{$v->env.module}/htlroomplancancel/index/" method="post">
              <div>
                <input type="hidden" name="target_cd" value="{$plan.plan_detail.hotel_cd}" />
                <input type="hidden" name="plan_cd"   value="{$plan.plan_detail.parent_plan_cd}"  />
                <input type="hidden" name="room_cd"   value="{$plan.plan_detail.parent_room_cd}"  />
                <input type="hidden" name="plan_id"   value="{$plan.plan_detail.plan_id}"  />
                <input type="submit" value="キャンセルポリシーの設定" />
              </div>
            </form>
          </td>
          <!-- plan cancel policy -->
          <!-- plan accept status -->
          <td class="align-c plan-accept_status {if $plan.plan_detail.plan_accept_status != 1}bk-deactive{/if}">
            <!-- {* プランの販売状態で遷移先を制御 *} -->
            @if($plan->accept_status != 1)
              <span class="msg-text-error">プラン休止中</span><br />
            @endif
            <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/updateplanaccept/" method="post">
              <div>
                <input type="hidden" name="target_cd"     value="{$plan.plan_detail.hotel_cd}" />
                <input type="hidden" name="plan_id"       value="{$plan.plan_detail.plan_id}"  />
                <input type="hidden" name="accept_status" value="{if $plan.plan_detail.plan_accept_status == 1}0{else}1{/if}"  />
                <input type="hidden" name="search_sale_status" value="{$v->assign->form_params.search_sale_status}" />
                @if($plan->accept_status == 1)
                  <!-- ntaログインされているかどうかで分岐処理 -->
                  @if(false)
                    <input type="submit" value="休止中に変更" disabled="disabled" />
                  @else
                    <input type="submit" value="休止中に変更" />
                  @endif
                @else
                  <!-- ntaログインされているかどうかで分岐処理 -->
                  @if(false)
                    <input type="submit" value="休止中を解除" disabled="disabled" />
                  @else
                    <input type="submit" value="休止中を解除" />
                  @endif
                @endif
              </div>
            </form>
          </td>
          <!--/ plan accept status -->
          <!-- plan delete -->
          <td class="align-c plan-info-delete">
            <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/hiddenplan/" method="post">
              <div>
                <input type="hidden" name="target_cd"      value="{$plan.plan_detail.hotel_cd}" />
                <input type="hidden" name="plan_id"        value="{$plan.plan_detail.plan_id}"  />
                <input type="hidden" name="search_sale_status" value="{$v->assign->form_params.search_sale_status}" />
                <input type="submit" class="jqs-btn-plan-del" value="プランの削除" {if $is_relo and !$is_relo_ctl}disabled="disabled"{/if} />
              </div>
            </form>
          </td>
          <!-- /plan delete -->
        </tr>
      </table>
      @php
        $is_sale_cnt = 1;
        $is_not_sale_cnt = 0;
        $is_not_sale = false;

        $plan->rooms = [
          (object)[
            'accept_status'            => 1,
            'is_edit_room_accept'      => true,
            'is_disp_guide'            => false,
            'plan_accept_ymd_status'   => false,
            'max_reg_rooms_ymd'        => false,
            'max_reg_charge_ymd'       => null,
            'accept_status_room_count' => 1,
            'charge_accept_status'     => null,
            'room_nm'                  => 'シングル',
            'room_label_cd'            => '1',
            'roomtype_cd'              => 1,
            'setting_status'           => null,
          ],
        ];
      @endphp
      <div class="plan-room-spacer"></div>
      <div class="plan-room-summary">
        <div class="align-l float-l"><a href="#noact">▼部屋タイプを展開</a></div>
        <div class="align-l float-l plan-room-summary-text">
          <!-- プラン単位で見た時の販売可能な状態の部屋数・販売不可能な状態の部屋数 -->
          <span class="msg-text-info" >設定済：</span>{{$is_sale_cnt}}
          <span class="msg-text-error">非販売：</span>{{$is_not_sale_cnt}}
        </div>
        <div class="clear"></div>
      </div>
      <div class="plan-room-type-list jqs-plan-for-room-type">
        <div class="plan-room-spacer"></div>
        <table class="info-plan-base-contents">
          <tr>
            <th class="align-c">部屋名称</th>
            <th class="align-c">設定状況</th>
            <th class="align-c">
              料金
            </th>
            <th class="align-c">
              <!-- {* 部屋が複数紐づいているときは一括設定ボタンを表示 *} -->
              @if(! is_null($plan->room_id))
                <form action="{$v->env.source_path}{$v->env.module}/htlscharge2/" method="post">
                  <div>
                    <input type="hidden" name="target_cd"  value="{$plan.plan_detail.hotel_cd}" />
                    <input type="hidden" name="plan_id"    value="{$plan.plan_detail.plan_id}"  />
                    <input type="hidden" name="pre_action" value="list"  />
                    <button type="submit" {if ($is_disp_guide or !isset($is_disp_guide)) or ($is_relo and !$is_relo_ctl)}disabled="disabled"{/if} >料金の<br />一括設定</button>
                  </div>
                </form>
              @endif
            </th>
            <th class="align-c" colspan="2">自動延長</th>
          </tr>
          @foreach($plan->rooms as $room) 
            <tr>
              <td class="plan-room-info">
                <div title="{$room.room_detail.room_nl}">{{$room->room_nm}}</div>
                <div>
                  [<span class="label-cd-text">{{$room->room_label_cd}}</span>]
                  @if(!is_null($room->roomtype_cd))
                    &nbsp;[<span class="label-cd-text">{{$room->roomtype_cd}}</span>]
                  @endif
                </div>
                @if($plan->partner_group_id != 0)
                  <div><span class="msg-text-success prev-text">※特定サイト限定販売の為プレビュー不可</span></div>
                @else
                  <div class="prev-text"><a href="{$v->env.source_path}{$v->env.module}/redirect/rsvcalender/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}&amp;room_id={$room.room_detail.room_id}" target="_brank">プレビュー&nbsp;>>&nbsp;</a></div>
                @endif
              </td>
              <td class="plan-room-sale-status {if $is_not_sale}bk-deactive{/if}">
                {$smarty.capture.sale_status}
                @if($plan->accept_status != 1)
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="プランが休止中です。 クリックでプラン休止を解除。">
                      <div class="plan-sale-st-msg-margin">
                        <a href="{$v->env.source_path}{$v->env.module}/htlsroomplan2/updateplanaccept/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}&amp;accept_status=1&amp;search_sale_status={$v->assign->form_params.search_sale_status}"><span class="msg-text-error">プラン休止中</span>→</a>
                      </div>
                    </div>
                  </div>
                @endif
                <!-- {* 部屋販売ステータス *} -->
                @if($room->accept_status != 1)
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="部屋が休止中です。 クリックで部屋休止を解除。">
                      <div class="plan-sale-st-msg-margin">
                        @if($room->is_edit_room_accept)
                          <a href="{$v->env.source_path}{$v->env.module}/htlsroomplan2/updateroomaccept/?target_cd={$plan.plan_detail.hotel_cd}&amp;room_id={$room.room_detail.room_id}&amp;accept_status=1&amp;search_sale_status={$v->assign->form_params.search_sale_status}"><span class="msg-text-error">部屋休止中</span>→</a>
                        @else
                          <span class="msg-text-error">部屋休止中</span>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
                <!-- {* 販売期間が未設定 *} -->
                @if($room->plan_accept_ymd_status == 1)
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="販売期間が未設定です。 クリックで販売期間の設定へ。">
                      <div class="plan-sale-st-msg-margin">
                        <a href="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}"><span class="msg-text-error">期間未設定</span>→</a>
                      </div>
                    </div>
                  </div>
                @else
                  <!-- {* 販売期間が経過 *} -->
                  @if($room->plan_accept_ymd_status == 2)
                    {assign var=is_not_sale value=true}
                    <div class="plan-sale-status-box">
                      <div class="plan-sale-status-msg" title="販売期間切れです。 クリックで販売期間の再設定へ。">
                        <div class="plan-sale-st-msg-margin">
                          <a href="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}"><span class="msg-text-error">販売期間切れ</span>→</a>
                        </div>
                      </div>
                    </div>
                  @else
                    @if($room->is_disp_guide)
                      <div class="plan-sale-status-box">
                        <div class="plan-sale-status-msg" title="販売期間が未設定です。 クリックで販売期間の設定へ。">
                          <div class="plan-sale-st-msg-margin">
                            <a href="{$v->env.source_path}{$v->env.module}/htlsplan2/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}"><span class="msg-text-error">期間未設定</span>→</a>
                          </div>
                        </div>
                      </div>
                    @endif
                  @endif
                @endif
                <!-- {* 部屋在庫無し *} -->
                @if(is_null($room->max_reg_rooms_ymd))
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="部屋数の登録がありません。 クリックで部屋数の登録へ。">
                      <div class="plan-sale-st-msg-margin">
                        {if $is_edit_rooms}
                          <a href="{$v->env.source_path}{$v->env.module}/htlsroomoffer/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;room_id={$room.room_detail.room_id}&amp;ui_type=calender&amp;date_ym={$smarty.now}&amp;current_ymd={$smarty.now}"><span class="msg-text-error">部屋数登録無し</span>→</a>
                        {else}
                          <span class="msg-text-error">部屋数登録無し</span>
                        {/if}
                      </div>
                    </div>
                  </div>
                @elseif($room->accept_status_room_count != 1)
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="登録中の在庫がすべて手仕舞です。">
                      <div class="plan-sale-st-msg-margin">
                        @if($is_edit_rooms)
                          <a href="{$v->env.source_path}{$v->env.module}/htlsroomoffer/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;room_id={$room.room_detail.room_id}&amp;ui_type=accept&amp;date_ymd={$smarty.now}&amp;current_ymd={$smarty.now}"><span class="msg-text-error">部屋手仕舞</span>→</a>
                        @else
                          <span class="msg-text-error">部屋手仕舞</span>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
                <!-- {* 料金登録無し *} -->
                @if(is_null($room->max_reg_charge_ymd))
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="{if $plan_accept_ymd_status == 0 and !$is_disp_guide}料金の登録がありません。クリックで料金登録画面へ。{else}販売期間を設定してください。{/if}">
                      <div class="plan-sale-st-msg-margin">
                        @if($room->plan_accept_ymd_status == 0 and !$room->is_disp_guide)
                          <a href="{$v->env.source_path}{$v->env.module}/htlscharge2/single/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}&amp;room_id={$room.room_detail.room_id}&amp;partner_group_id={$plan.plan_detail.partner_group_id.0}"><span class="msg-text-error">料金登録無し</span>→</a>
                        @else
                          <span class="msg-text-error">料金登録無し</span>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
                <!-- {* 売止 *} -->
                @if(!is_null($room->charge_accept_status)
                    && $room->charge_accept_status == 0
                    && !$room->is_disp_guide)
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-status-msg" title="登録中の料金が全て売止になっています。クリックで売止の変更へ。">
                      <div class="plan-sale-st-msg-margin">
                        @if($plan_accept_ymd_status == 0)
                          <a href="{$v->env.source_path}{$v->env.module}/htlsplanoffer/edit/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}&amp;ui_type=date&amp;target_ymd={$room.room_detail.min_reg_charge_ymd}&amp;current_ymd={$room.room_detail.min_reg_charge_ymd}"><span class="msg-text-error">売止</span>→</a>
                        @else
                          <span class="msg-text-error">売止</span>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif
                <!-- {* 販売終了 *} -->
                @if(!is_null($room->max_reg_charge_ymd) && $room->is_accept_e_dtm === 0)
                  {assign var=is_not_sale value=true}
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-st-msg-margin">
                      <div class="plan-sale-status-msg" title=""><span class="msg-text-error">販売終了</span></div>
                    </div>
                  </div>
                @else
                  <!-- {* 販売開始前 *} -->
                  @if(!is_null($room->max_reg_charge_ymd) && $room->is_accept_s_dtm === 0)
                    {assign var=is_not_sale value=true}
                    <div class="plan-sale-status-box">
                      <div class="plan-sale-st-msg-margin">
                          @if(!is_null($room->min_pre_accept_s_dtm))
                            @include('ctl.common._date'){{date_format($room->min_pre_accept_s_dtm, '%H-%M')}}より販売開始
                          @endif
                          {assign var=pre_sale_title value=$smarty.capture.pre_sale_dtm|strip_tags:true|trim}
                        <div class="plan-sale-status-msg" title="{$pre_sale_title}">
                          <span class="msg-text-error">販売開始前</span>
                        </div>
                      </div>
                    </div>
                  @endif
                @endif
                <!-- {* 設定済み *} -->
                @if(!$is_not_sale)
                  <div class="plan-sale-status-box">
                    <div class="plan-sale-st-msg-margin">
                      <div class="plan-sale-status-msg" title="">設定済</div>
                    </div>
                  </div>
                @endif
              </td>
              <td class="align-c plan-room-charge-ymd {if is_empty($room.room_detail.max_reg_charge_ymd)}bk-deactive{/if}">
                @if(is_null($room->max_reg_charge_ymd))
                  <span class="msg-text-error">料金登録無し</span>
                @else
                  @include('ctl.common._date')まで登録済み
                @endif
              </td>
              <td  class="align-c plan-room-charge-edit {if is_empty($room.room_detail.max_reg_charge_ymd)}bk-deactive{/if}">
                <form action="{$v->env.source_path}{$v->env.module}/htlscharge2/single/" method="post">
                  <div>
                    <input type="hidden" name="target_cd"        value="{$plan.plan_detail.hotel_cd}" />
                    <input type="hidden" name="plan_id"          value="{$plan.plan_detail.plan_id}"  />
                    <input type="hidden" name="room_id"          value="{$room.room_detail.room_id}"  />
                    <input type="hidden" name="partner_group_id" value="{$plan.plan_detail.partner_group_id.0}" />
                    <button type="submit" {if $plan_accept_ymd_status != 0 or $is_disp_guide}disabled="disabled"{/if} >料金の<br />設定</button>
                  </div>
                </form>
              </td>
              <td class="plan-room-extend-status {if $room.room_detail.auto_extend.setting_status != 3}bk-deactive{/if}">
                <!-- {* リロ専用プランでは自動延長は利用できない *} -->
                <!-- {if $is_relo}
                  <div class="msg-text-warning align-c">自動延長は利用できません</div>
                {else} -->
                  @if($room->setting_status === 1)
                    <div class="plan-sale-status-msg" title="クリックで自動延長設定へ">
                      <div class="plan-sale-status-box align-c">
                        <a href="{$v->env.source_path}{$v->env.module}/htlextend/?target_cd={$plan.plan_detail.hotel_cd}&amp;display_type=htls"><span class="msg-text-error">未設定（自動延長不可）</span>&nbsp;→&nbsp;</a>
                      </div>
                    </div>
                  @elseif($room->setting_status === 2)
                    <div class="plan-sale-status-msg" title="クリックで自動延長設定の変更へ">
                      <div class="plan-sale-status-box align-c">
                        <a href="{$v->env.source_path}{$v->env.module}/htlextend/?target_cd={$plan.plan_detail.hotel_cd}&amp;display_type=htls"><span class="msg-text-error">自動延長停止中</span>&nbsp;→&nbsp;</a>
                      </div>
                    </div>
                  @elseif($room->setting_status === 3)
                    自動延長中<br />
                    次回延長日：@include('ctl.common._date')<br />
                    延長内容（予定）：{{ date_format($room->setting_next, '%Y&#24180;%m&#26376;')}}宿泊分
                  @elseif($room->setting_status === 4)
                    <div class="plan-sale-status-msg" title="クリックで自動延長設定の変更へ">
                      <div class="plan-sale-status-box align-c">
                        <a href="{$v->env.source_path}{$v->env.module}/htlsautoextend/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}&amp;room_id={$room.room_detail.room_id}"><span class="msg-text-error">自動延長不可</span>&nbsp;→&nbsp;</a>
                      </div>
                    </div>
                  @else
                    <div class="plan-sale-status-msg" title="クリックで自動延長設定の変更へ">
                      <div class="plan-sale-status-box align-c">
                        <a href="{$v->env.source_path}{$v->env.module}/htlsautoextend/?target_cd={$plan.plan_detail.hotel_cd}&amp;plan_id={$plan.plan_detail.plan_id}&amp;room_id={$room.room_detail.room_id}"><span class="msg-text-error">自動延長不可</span>&nbsp;→&nbsp;</a>
                      </div>
                    </div>
                  @endif
                <!-- {/if} -->
              </td>
              <td class="align-c plan-room-extend-edit {if $room.room_detail.auto_extend.setting_status != 3}bk-deactive{/if}">
                <form action="{$v->env.source_path}{$v->env.module}/htlsautoextend/" method="post">
                  <div>
                    <input type="hidden" name="target_cd" value="{$plan.plan_detail.hotel_cd}" />
                    <input type="hidden" name="plan_id"   value="{$plan.plan_detail.plan_id}"  />
                    <input type="hidden" name="room_id"   value="{$room.room_detail.room_id}"  />
                    <button type="submit" {if $is_relo}disabled="disabled"{/if} >自動延長の<br />設定</button>
                  </div>
                </form>
              </td>
            </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>