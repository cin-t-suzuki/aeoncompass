<!-- <!-- {* 引数：$room, $display_row *} -->
<!-- {assign var=room_icon_color_class value='warning'} -->
<ul class="room-spec-list">
  <!-- {* 連携在庫 *} -->
  @if(!is_null($room->roomtype_cd))
    <li><span class="tag-text-error">日本旅行連動在庫</span></li>
  @endif
  <!-- {* 部屋タイプ *} -->
  <li>
    <span class="tag-text-warning">
      @if($room->room_type === 0)カプセル
      @elseif($room->room_type === 1)シングル
      @elseif($room->room_type === 2)ツイン
      @elseif($room->room_type === 3)セミダブル
      @elseif($room->room_type === 4)ダブル
      @elseif($room->room_type === 5)トリプル
      @elseif($room->room_type === 6)４ベッド
      @elseif($room->room_type === 7)スイート
      @elseif($room->room_type === 8)メゾネット
      @elseif($room->room_type === 9)和室
      @elseif($room->room_type === 10)和洋室
      @elseif($room->room_type === 11)その他
      @elseif($room->room_type === 99)未選択
      @else<br />
      @endif
    </span>
  </li>
  <!-- {* 定員範囲 *} -->
  <li> 
    <span class="tag-text-warning">
      @if(is_null($room->capacity_min) || is_null($room->capacity_max))
        不明
      @else
        {{$room->capacity_min}}名～{{$room->capacity_max}}名
      @endif
    </span>
  </li>
  <!-- {* 部屋スペック -風呂 *} -->
  <li>
    @if($room->element_id === 1 && $room->element_value_id === 0)<span class="tag-text-warning">風呂なし</span>
    @elseif($room->element_id === 1 && $room->element_value_id === 1)<span class="tag-text-warning">風呂付き</span>
    @elseif($room->element_id === 1 && $room->element_value_id === 2)<span class="tag-text-warning">風呂共同</span>
    @elseif($room->element_id === 1 && $room->element_value_id === 3)<span class="tag-text-warning">シャワーのみ</span>
    @endif
  </li>
  <!-- {* 部屋スペック -トイレ *} -->
  <li>
    @if($room->element_id === 2 && $room->element_value_id === 0)<span class="tag-text-warning">トイレなし</span>
    @elseif($room->element_id === 2 && $room->element_value_id === 1)<span class="tag-text-warning">トイレ付き</span>
    @elseif($room->element_id === 2 && $room->element_value_id === 2)<span class="tag-text-warning">トイレ共同</span>
    @elseif($room->element_id === 2 && $room->element_value_id === 3)<span class="tag-text-warning">トイレ不明</span>
    @endif
  </li>
  <!-- {* 部屋スペック - 禁煙・喫煙 *} -->
  <li>
    @if($room->element_id === 3 && $room->element_value_id === 0)<span class="tag-text-warning">禁煙&nbsp;/&nbsp;喫煙　設定なし</span>
    @elseif($room->element_id === 3 && $room->element_value_id === 1)<span class="tag-text-warning">禁煙</span>
    @elseif($room->element_id === 3 && $room->element_value_id === 2)<span class="tag-text-warning">喫煙</span>
    @elseif($room->element_id === 3 && $room->element_value_id === 3)<span class="tag-text-warning">禁煙&nbsp;/&nbsp;喫煙　選択</span>
    @endif
  </li>
  <!-- {* 部屋ネットワーク環境 *} -->
  <li>
    <span class="tag-text-warning">
      @if($room->network === 0)
        ネット接続不可
      @elseif($room->network === 9)
        ネット接続不明
      @else
        ネット接続可
        @if($room->network === 1)全室無料
        @elseif($room->network === 2)一部客室無料
        @elseif($room->network === 3)全室有料
        @elseif($room->network === 4)一部客室有料
        @endif

        @if(0 < $room->network && $room->network < 9)
          <!-- {* 部屋ネットワーク機器 *} -->
          @if($room->rental === 1)ケーブル類常設
          @elseif($room->rental === 2)ケーブル類無料貸出
          @elseif($room->rental === 3)ケーブル類有料貸出
          @elseif($room->rental === 4)ケーブル類持込
          @endif
          <!-- {* コネクタ *} -->
          @if($room->connector === 1)無線接続
          @elseif($room->connector === 2)有線接続
          @elseif($room->connector === 3)TELコネクタ
          @elseif($room->connector === 4)その他コネクタ
          @endif
        @endif
      @endif
    </span>
  </li>
  @if($room->accept_status != 1)
    <li><span class="tag-text-deactive">休止中</span></li>
  @endif
</ul>
<div class="clear"></div>