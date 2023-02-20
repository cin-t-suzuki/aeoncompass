<!-- {*  引数：$room_list  *} -->
<script type="text/javascript">
    $(document).ready(function () {
      $('input.jqs-btn-room-del').click(function(){
         return confirm($('.jqs-room-nm').eq($('input.jqs-btn-room-del').index(this)).text() + '\n\nこの部屋を削除します。\nよろしいですか？');
      });

      var ck_path = '{/literal}{$v->env.source_path}{$v->env.module}/{literal}';
      var ck_hide_room = $.cookies.get('HIDE_ROOM');
      if ( ck_hide_room == 'on' ) {
        $('.jqs-room-list').hide();
        $('input.jqs-display-rooms').val('+');
        $('input.jqs-display-rooms').attr('title', 'クリックで部屋一覧を展開できます。');
      } else {
        $('.jqs-room-list').show();
        $('input.jqs-display-rooms').val('-');
        $('input.jqs-display-rooms').attr('title', 'クリックで部屋一覧を収納できます。');
      }

      $('input.jqs-display-rooms').click(function() {
        if ( $('input.jqs-display-rooms').val() == '-' ) {
          $('.jqs-room-list').hide();
          $('input.jqs-display-rooms').val('+');
          $.cookies.set('HIDE_ROOM', 'on', {path: ck_path});
          $('input.jqs-display-rooms').attr('title', 'クリックで部屋一覧を展開できます。');
        } else {
          $('.jqs-room-list').show();
          $('input.jqs-display-rooms').val('-');
          $.cookies.del('HIDE_ROOM', {path: ck_path});
          $('input.jqs-display-rooms').attr('title', 'クリックで部屋一覧を収納できます。');
        }
      });
    });
</script>
<div class="align-l">
  <div class="room-list-header">
    <div class="room-list-header-back">
      <div class="room-list-header-text">
        <input type="button" value="-" class="jqs-display-rooms deploy-btn" title="" />
        <span class="font-bold">部屋一覧</span>
      </div>
    </div>
  </div>
  <div class="jqs-room-list">
  <hr class="bound-line" />
  @if(!(is_countable($room_list) && 0 < count($room_list)))
    <span class="msg-text-error">登録されている部屋がありません。</span>
  @else
    @foreach($room_list as $room)
    <div class="info-room-base">
      <div class="info-room-base-back">
        <div class="info-room-base-inline">
          <!-- {* 部屋名 *} -->
          <div><span class="font-bold jqs-room-nm">{{$room->room_nm}}</span></div>
          <!-- {* 部屋ラベル *} -->
          @if(!is_null($room->roomtype_cd))
            <div>
              [<span class="label-cd-text">{$room.label_cd}</span>]
              @if(!is_empty($room.roomtype_cd))
                &nbsp;[<span class="label-cd-text">{$room.roomtype_cd}</span>]
              @endif
            </div>
          @else
            <br />
          @endif
          <div>
            <!-- {* 部屋スペック *} -->
            @include('ctl.common._zap_room_spec_icon')
          </div>
          <div class="margin-spacer"></div>
          <table class="info-room-base-contents">
            <tr>
              <th class="align-c">部屋情報</th>
              <th class="align-c" colspan="2">部屋数</th>
              <th class="align-c">部屋画像</th>
              <th class="align-c">部屋休止設定</th>
              <th class="align-c"><br /></th>
            </tr>
            <tr>
              <!-- button-edit-room -->
              <td class="align-c">
                <form action="{$v->env.source_path}{$v->env.module}/htlsroom2/edit/" method="post">
                  <div>
                    <input type="hidden" name="target_cd" value="{$room.hotel_cd}" />
                    <input type="hidden" name="room_id"   value="{$room.room_id}" />
                    <input type="submit" value="部屋の編集" />
                  </div>
                </form>
              </td>
              <!-- /button-edit-room -->
              <!-- info-room-stock -->
              @if(is_null($room->rooms))
                <td class="align-c bk-deactive room-stock-status">
                  <span class="msg-text-error">部屋数の登録が有りません</span>
                </td>
                <td class="align-c bk-deactive">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsroomoffer/edit/" method="post">
                    <div>
                      <input type="hidden" name="target_cd"   value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"     value="{$room.room_id}" />
                      <input type="hidden" name="ui_type"     value="calender" />
                      <input type="hidden" name="date_ym"     value="{$smarty.now}" />
                      <input type="hidden" name="current_ymd" value="{$smarty.now}" />
                      <input type="hidden" name="return_path" value="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/" />
                      <!-- ユーザの在庫編集可否判定 ログインがntaという継続的接続クッキーを持っているかで判断している -->
                      @if(true)
                        <input type="submit" value="部屋数の設定" disabled="disabled" />
                      @else
                        <input type="submit" value="部屋数の設定" />
                      @endif
                    </div>
                  </form>
                </td>
              @else
                <td class="align-c room-stock-status">
                  @include('ctl._common._date')
                </td>
                <td class="align-c">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsroomoffer/edit/" method="post">
                    <div>
                      <input type="hidden" name="target_cd"   value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"     value="{$room.room_id}" />
                      <input type="hidden" name="ui_type"     value="calender" />
                      <input type="hidden" name="date_ym"     value="{$smarty.now}" />
                      <input type="hidden" name="current_ymd" value="{$smarty.now}" />
                      <input type="hidden" name="return_path" value="{$v->env.source_path}{$v->env.module}/{$v->env.controller}/{$v->env.action}/" />
                      <!-- ユーザの在庫編集可否判定 ログインがntaという継続的接続クッキーを持っているかで判断している -->
                      @if(true)
                        <input type="submit" value="部屋数の設定" disabled="disabled" />
                      @else
                        <input type="submit" value="部屋数の設定" />
                      @endif
                    </div>
                  </form>
                </td>
              @endif
              <!-- /info-room-stock -->
              <!-- button-edit-room-stock -->
              <!-- /button-edit-room-stock -->
              <!-- button-edit-room-status -->
              @if(is_null($room->media_no))
                <td class="align-c bk-deactive">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/editroom/" method="post">
                    <div>
                      <input type="hidden" name="target_cd" value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"   value="{$room.room_id}" />
                      <input type="submit" value="画像の設定" />
                    </div>
                  </form>
                </td>
              @else
                <td class="align-c">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsmedia/editroom/" method="post">
                    <div>
                      <input type="hidden" name="target_cd" value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"   value="{$room.room_id}" />
                      <input type="submit" value="画像の設定" />
                    </div>
                  </form>
                </td>
              @endif
              <!-- /button-edit-room-status -->
              <!-- info-room-status -->
              @php
                $uri_act = '';
              @endphp
              @if($room->accept_status == 0)
                @php
                  $uri_act = 'roomsale';
                @endphp
                <td class="align-c bk-deactive">
                  <!-- {* 部屋の販売ステータスで遷移先を制御($uri_act) *} -->
                  <span class="msg-text-error">部屋休止中</span><br />
                  <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/updateroomaccept/" method="post">
                    <div>
                      <input type="hidden" name="target_cd"          value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"            value="{$room.room_id}" />
                      <input type="hidden" name="accept_status"      value="{if $is_no_accept_room}1{else}0{/if}" />
                      <input type="hidden" name="search_sale_status" value="{$v->assign->form_params.search_sale_status}" />
                      @if(!$is_edit_room_sales_state_relo)
                        <input type="submit" value="休止中を解除" disabled="disabled" />
                      @else
                        <input type="submit" value="休止中を解除"/>
                      @endif
                    </div>
                  </form>
                </td>
              @else
                @php
                  $uri_act = 'roomstop';
                @endphp
                <td class="align-c">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/updateroomaccept/" method="post">
                    <div>
                      <input type="hidden" name="target_cd"          value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"            value="{$room.room_id}" />
                      <input type="hidden" name="accept_status"      value="{if $is_no_accept_room}1{else}0{/if}" />
                      <input type="hidden" name="search_sale_status" value="{$v->assign->form_params.search_sale_status}" />
                      @if(true)
                        <input type="submit" value="休止中に変更" disabled="disabled" />
                      @else
                        <input type="submit" value="休止中に変更"/>
                      @endif
                    </div>
                  </form>
                </td>
              @endif
              <!-- /info-room-status -->
              <!-- button-room-delete -->
              <!-- 部屋が紐づいているプランが存在するか・ネット在庫・基幹在庫の部屋の削除判定によって分岐している -->
              @if(!(is_null($room->plan_id) or true))
                <td class="align-c bk-deactive">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/hiddenroom/" method="post">
                    <div>
                      <input type="hidden" name="target_cd"          value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"            value="{$room.room_id}" />
                      <input type="hidden" name="search_sale_status" value="{$v->assign->form_params.search_sale_status}" />
                      <input type="submit" value="部屋の削除" class="jqs-btn-room-del" />
                    </div>
                  </form>
                </td>
              @else
                <td class="align-c {if !($is_relation_plan or !$is_delete_room_relo)}bk-deactive{/if}">
                  <form action="{$v->env.source_path}{$v->env.module}/htlsroomplan2/hiddenroom/" method="post">
                    <div>
                      <input type="hidden" name="target_cd"          value="{$room.hotel_cd}" />
                      <input type="hidden" name="room_id"            value="{$room.room_id}" />
                      <input type="hidden" name="search_sale_status" value="{$v->assign->form_params.search_sale_status}" />
                      @if(is_null($room->plan_id))
                        <input type="submit" value="部屋の削除" class="jqs-btn-room-del" disabled="disabled" />
                      @else
                        <input type="submit" value="部屋の削除" class="jqs-btn-room-del" />
                      @endif
                    </div>
                  </form>
                </td>
              @endif
              <!-- /button-room-delete -->
            </tr>
          </table>
        </div>
      </div>
    </div>
    @endforeach
  @endif
  <!-- {if $room_list|@count <= 0}<span class="msg-text-error">登録されている部屋がありません。</span>{/if}
  {foreach from=$room_list item=room name=loop_room_list}
    {* 在庫数の有無を判定($is_reg_room) true:在庫無, false:在庫あり *}
    {assign var=is_reg_room value=false}
    {if is_empty($room.last_reg_ymd)}{assign var=is_reg_room value=false}{else}{assign var=is_reg_room value=true}{/if}
    {* 部屋の販売ステータス判定($is_no_accept_room) *}
    {assign var=is_no_accept_room value=false}
    {if $room.accept_status == 1}{assign var=is_no_accept_room value=false}{else}{assign var=is_no_accept_room value=true}{/if}
    {* 部屋画像が設定済みか判定($is_set_room_media) *}
    {assign var=is_set_room_media value=false}
    {if $room.room_media_cnt > 0}{assign var=is_set_room_media value=true}{else}{assign var=is_set_room_media value=false}{/if}
    {* 部屋が紐づいているプランが存在するか判定($is_relation_plan) *}
    {assign var=is_relation_plan value=false}
    {if $room.rel_plan_cnt > 0}{assign var=is_relation_plan value=true}{else}{assign var=is_relation_plan value=false}{/if}
    {*  ネット在庫・基幹在庫の部屋の販売/休止判定 *}
    {assign var=is_edit_room_sales_state_relo value=true}
    {if ($room.room_stock_type.is_basic_stock) and (!$v->user->operator->is_nta() and !$v->user->operator->is_staff())}{assign var=is_edit_room_sales_state_relo value=false}{/if}
    {*  ネット在庫・基幹在庫の部屋の削除判定 *}
    {assign var=is_delete_room_relo value=true}
    {if $room.room_stock_type.is_basic_stock and !$v->user->operator->is_nta()}{assign var=is_delete_room_relo value=false}{/if}
    {*  ネット在庫 or 基幹在庫のときは（ラベルコード or 部屋ID）を表示しない  *}
    {assign var=is_disp_room_cd value=true}
    {if $room.room_stock_type.is_basic_stock}{assign var=is_disp_room_cd value=false}{/if}
    {* 在庫編集の可否判定 *}
    {assign var=is_edit_rooms value=true}
    {if $room.room_stock_type.is_basic_stock}
      {if !$v->user->operator->is_nta()}
        {assign var=is_edit_rooms value=false}
      {/if}
    {/if} -->
  </div>
    <hr class="bound-line" />
</div>