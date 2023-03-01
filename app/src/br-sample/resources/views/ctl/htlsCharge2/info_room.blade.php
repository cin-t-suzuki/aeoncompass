  {{-- 引数：$rooms_idx    ・・・ 部屋IDのインデックス --}}
  {{-- $rooms_detail ・・・ 部屋情報コンテナ --}}
  <div class="gen-container">

    <h2 class="contents-header">部屋情報</h2>

    {{-- 余白 --}}
    <hr class="bound-line" />

    {{-- 移植前 --}}
    {{-- foreach from=$rooms_idx item=room_id_target name=loop_room_detail --}}
    @foreach($rooms_idx as $room_id_target)


      {{-- アサイン：部屋詳細情報 --}}
      {{-- 移植前 --}}
      {{-- assign var=detail_room value=$rooms_detail.$room_id_target --}}

      {{-- 移植後 --}}
      {{-- $detail_room = $rooms_detail->$room_id_target --}}

      @php
      $detail_room = $rooms_detail[$room_id_target]
      @endphp
      
      <div class="info-room-base-sht">
        <div class="info-room-base-sht-back">
          <div class="info-room-base-sht-inline">

            {{-- 部屋名称 --}}
            <div>{{ $detail_room->room_nm }}</div>

            {{-- PMSコード（部屋） --}}
            <span>{{ $detail_room->pms_cd }}</span>


            {{-- 連携在庫コード --}}
            @if ($detail_room->akafu_cd)&nbsp;<span>{{ $detail_room->akafu_cd }}</span>@endif


            {{-- 部屋スペック --}}
            <div>
              {{-- {include file=$v->env['module_root']|cat:'/view2/_common/_room_spec_icons.tpl' room=$detail_room} --}}
              @include('ctl.common._room_spec_icons', ['room' => $detail_room])
            </div>
          </div>
        </div>
      </div>

      {{-- foreachのループが最後出ない限り、空白を挿入する --}}
      @if (!$loop->last)
      <hr class="bound-line" />
      @endif

      {{-- 移植前 --}}
      {{-- @if (!$smarty['foreach']['loop_room_detail']['last']) --}}

     


    @endforeach

  </div>
