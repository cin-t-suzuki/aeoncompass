{{-- MEMO: 移植元 lump.tpl --}}

  {{-- ヘッダー --}}

    {{-- JavaScript指定 --}}
  @section('headScript')
      <script type="text/javascript" src="{{ asset('/js/brj.ctl.js') }}"></script>
      <script type="text/javascript">
        <!--
          $(document).ready(function () {

            $('#add'   ).moveRooms({selecter_src:'.jqs-rooms-select',   selecter_dest:'.jqs-rooms-selected', is_selected:true});
            $('#remove').moveRooms({selecter_src:'.jqs-rooms-selected', selecter_dest:'.jqs-rooms-select',   is_selected:false});

            $('.jqs-rooms-select').previewRoom();
            $('.jqs-rooms-selected').previewRoom();
            $('.jqs-rooms-complete').previewRoom();
          });
        -->
      </script>
      @endsection
  {{-- ヘッダーのテンプレート読み込み --}}
  @extends('ctl.common.base3', [
    'title' => '料金設定を行う部屋の選択',
    'screen_type' => 'htl',
    'js_staff_navi' => 'on',
    'js_htl_navi' => 'on'
    ])

{{-- メイン --}}

@section('content')
  {{-- 余白 --}}
  <hr class="bound-line" />

  {{-- メッセージ --}}
  @include('ctl.common.message2')

  {{-- 余白 --}}
  <hr class="bound-line" />

  {{-- プラン情報 --}}
  @include('ctl.htlsCharge2.info_plan',[
    'plan' => $plan_detail
    ])

  {{-- 余白 --}}
  <hr class="bound-line-l" />

  {{ Form::open(['route' => 'ctl.htlscharge2.edit','method' => 'post']) }}

    {{-- 入力期間 --}}
    <div class="gen-container">
      <h2 class="contents-header">料金の入力を行う期間</h2>

      {{-- 余白 --}}
      <hr class="bound-line" />

      {{-- TODO:古すぎるので消費税告知は要らないはず --}}
      {{-- 2014年4月の消費税告知 --}}
      {{-- {include file=$v->env['module_root']|
        cat:'/view2/_common/_consumption_tax_201404.tpl' type='message'} 
        --}}
      
      <div id="select-ymd">
        {{-- 開始日 --}}
        {!!Form::select('from_year',$plan_accept_ymd_selecter['options']['year'],$plan_accept_ymd_selecter['selected']['accept_s_ymd']['year'])!!}
        
        {!!Form::select('from_month',$plan_accept_ymd_selecter['options']['month'],$plan_accept_ymd_selecter['selected']['accept_s_ymd']['month'])!!}

        {!!Form::select('from_day',$plan_accept_ymd_selecter['options']['day'],$plan_accept_ymd_selecter['selected']['accept_s_ymd']['day'])!!}
        
        &nbsp;～&nbsp;
        {{-- 終了日 --}}
        {!!Form::select('to_year',$plan_accept_ymd_selecter['options']['year'],$plan_accept_ymd_selecter['selected']['accept_e_ymd']['year'])!!}
        
        {!!Form::select('to_month',$plan_accept_ymd_selecter['options']['month'],$plan_accept_ymd_selecter['selected']['accept_e_ymd']['month'])!!}
        
        {!!Form::select('to_day',$plan_accept_ymd_selecter['options']['day'],$plan_accept_ymd_selecter['selected']['accept_e_ymd']['day'])!!}

      </div>

      {{-- 余白 --}}
      <hr class="bound-line-l" />

      {{-- 部屋選択UI --}}
      <h2 class="contents-header">部屋情報</h2>

      {{-- 余白 --}}
      <hr class="bound-line" />

      <div id="room-select">
        <div id="room-select-contents">

          {{-- 2カラム × 2段のうち左辺のBOX --}}
          <div class="group-box-rooms">
            {{-- 設定済みの部屋 --}}
            <div class="box-title box-title-contents">設定済みの部屋タイプ </div>
            <div class="box-rooms box-rooms-contents-low jqs-rooms-complete">
              @foreach ($opration_status_rooms['complete_rooms'] as $room_id_complete)
              {{-- アサイン：部屋詳細情報 --}}
              {{-- 変数名 detail_roomにplan_has_rooms_detail.$room_id_completeを代入している --}}
                {{-- assign var=detail_room value=$plan_has_rooms_detail.$room_id_complete --}}

                {{-- 連携在庫かどうかで処理を分岐 --}}
                {{-- ※連携在庫を示すコードが存在するかどうかで判断 --}}
                @if ($detail_room['akafu_cd'])
                  <p class="room-name-row"  id="jqs-room-{{ $room_id_complete }}">
                    {{ $detail_room['akafu_cd'] }}&nbsp;{{ $detail_room['room_nm'] }}&nbsp;{{ $detail_room['pms_cd'] }}
                    <input type="hidden" name="complete_rooms[]" value="{{ $room_id_complete }}" />
                  </p>
                @else
                  <p class="room-name-row"  id="jqs-room-{{ $room_id_complete }}">
                    {{ $detail_room['room_nm'] }}&nbsp;{{ $detail_room['pms_cd'] }}
                    <input type="hidden" name="complete_rooms[]" value="{{ $room_id_complete }}" />
                  </p>
                @endif
              @endforeach
            </div>

            {{-- 選択対象の部屋 --}}
            <div class="box-title box-title-contents">設定を行う予定の部屋タイプ</div>
            <div class="box-rooms box-rooms-contents jqs-rooms-selected">
              @foreach ($opration_status_rooms['target_rooms'] as $room_id_target)
              {{-- アサイン：部屋詳細情報 --}}
               {{-- assign var=detail_room value=$plan_has_rooms_detail.$room_id_target --}}

              {{-- 連携在庫かどうかで処理を分岐 --}}
                @if ($detail_room['akafu_cd'])
                {{-- 連携在庫 --}}
                  <p class="room-name-row"  id="jqs-room-{{ $room_id_target }}">
                    <input id="select_room_{{ $room_id_target }}" type="checkbox" name="check_on[]" value="{{ $room_id_target }}" />
                    <label for="select_room_{{ $room_id_target }}" class="label-w100">{{ $detail_room['akafu_cd'] }}&nbsp;{{ $detail_room['room_nm'] }}&nbsp;{{ $detail_room['pms_cd'] }}</label>
                    <input type="hidden" name="target_rooms[]" value="{{ $room_id_target }}" />
                  </p>
                @else
                {{-- 連携在庫以外 --}}
                  <p class="room-name-row"  id="jqs-room-{{ $room_id_target }}">
                    <input id="select_room_{{ $room_id_target }}" type="checkbox" name="check_on[]" value="{{ $room_id_target }}" /><label for="select_room_{{ $room_id_target }}" class="label-w100">{{ $detail_room['room_nm'] }}&nbsp;{{ $detail_room['pms_cd'] }}</label>
                    <input type="hidden" name="target_rooms[]" value="{{ $room_id_target }}" />
                  </p>
                @endif
              @endforeach
            </div>

            {{-- 「選択済み」 → 「選択可能」に戻すボタン --}}
            <div>
              <input type="button" id="remove" value="削除" class="submit-m" />
            </div>

          </div>

          {{-- 2カラム × 2段のうち右辺のBOX --}}
            <div class="group-box-rooms">
              <div class="box-title">&nbsp;</div>
              <div class="box-rooms-low">
                {{-- 部屋詳細 --}}
                {{-- ※初期表示では非表示 --}}
                <div class="box-room-detail">
                  @foreach ($plan_has_rooms_detail as $detail_room )
                  <div class="info-room-base-sht default-hide jqs-room-detail-{{ $detail_room['room_id'] }}">
                    <div class="info-room-base-sht-back">
                      <div class="info-room-base-sht-inline">
                        {{-- 部屋名称 --}}
                        <p>{{ $detail_room['room_nm'] }}</p>{{$detail_room['room_id']}}

                        {{-- PMSコード（部屋） --}}
                        <span>&nbsp;{{ $detail_room['pms_cd'] }}</span>

                        {{-- 連携在庫コード --}}
                        @if ($detail_room['akafu_cd'])
                        &nbsp;<span>{{ $detail_room['akafu_cd'] }}&nbsp;</span>
                        @endif
                          {{-- 部屋スペック --}}
                        <div>@include('ctl.common._room_spec_icons', ['room' => $detail_room])</div>
                      </div>
                    </div>
                  </div>
                  @endforeach
              </div>
            </div>

            {{-- 選択可能な部屋 --}}
            <div class="box-title box-title-contents">設定が可能な部屋タイプ </div>
            <div class="box-rooms box-rooms-contents jqs-rooms-select">
              @foreach ($opration_status_rooms['selectable_rooms'] as $room_id_selectable)
              {{-- アサイン：部屋詳細情報 --}}

                {{-- assign var=detail_room value=$plan_has_rooms_detail.$room_id_selectable --}}

                {{-- 連携在庫かどうかで処理を分岐 --}}
                @if ($detail_room['akafu_cd'])
                {{-- 連携在庫 --}}
                  <p class="room-name-row" id="jqs-room-{{$room_id_selectable}}">
                    <input id="select_room_{{$room_id_selectable}}" type="checkbox" name="check_on[]" value="{{ $room_id_selectable }}" />
                    <label for="select_room_{{$room_id_selectable}}" class="label-w100">{{ $detail_room['akafu_cd'] }}&nbsp;{{ $detail_room['room_nm'] }}&nbsp;{{ $detail_room['pms_cd'] }}</label>
                  </p>
                @else
                {{-- 連携在庫以外 --}}
                  <p class="room-name-row" id="jqs-room-{{$room_id_selectable['selectable_rooms']}}">
                    <input id="select_room_{{ $room_id_selectable }}" type="checkbox" name="check_on[]"value="{{ $room_id_selectable }}" />
                    <label for="select_room_{{ $room_id_selectable }}" class="label-w100">{{ $detail_room['room_nm'] }}&nbsp;{{ $detail_room['pms_cd'] }}</label>
                  </p>
                @endif
              @endforeach
            </div>

            {{-- 「選択可能」 → 「選択済み」に戻すボタン --}}
            <div>
              <input type="button" id="add" value="追加" class="submit-m" />
            </div>

          </div>

          {{-- 余白 --}}
          <div class="clear"></div>

        </div>
      </div>

      {{-- 余白 --}}
      <hr class="bound-line" />

      <div>
        <input type="hidden" name="target_cd"  value="{{ $request_params['target_cd'] }}"  />
        <input type="hidden" name="plan_id"    value="{{ $request_params['plan_id'] }}"    />
        <input type="hidden" name="pre_action" value="{{ $request_params['pre_action'] }}" />
        <input type="hidden" name="return_path"    value="{{ $request_params['return_path'] }}"/>
        <input type="hidden" name="current_ymd"    value="{{ $request_params['current_ymd'] }}"/>
        <input type="submit" value="料金の入力へ" class="submit-m" />
      </div>
    </div>

  {{ Form::close() }}

  {{-- 余白 --}}
  <hr class="bound-line" />

  <div class="page-under-menu">
    <p><a href="#" class="move-ptop">▲ページTOPへ</a></p>
  </div>
@endsection

