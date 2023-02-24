  
  {{-- 部屋スペック --}}
  {{-- 引数：$room => 部屋情報 --}}
  <ul class="gen-list">
  
    {{-- 連携在庫 --}}
    @if($room->is_akafu)
      <li class="rp-specs"><span class="tag-text-error">日本旅行連動在庫</span></li>
    @endif
    
    {{-- 部屋タイプ --}}
    <li class="rp-specs">
      <span class="tag-text-warning">
        @include('ctl.common._room_type',['room_type'  => $room->room_type])

      </span>
    </li>
    
    {{-- 定員数 --}}
    <li class="rp-specs">
      @if(empty($room->capacity_min))
        <span class="tag-text-warning">定員不明</span>
      @else
        {{-- {* 最小定員と最大定員が同値かどうかで表示を変更 *} --}}
        @if($room->capacity_min == $room->capacity_max)
          <span class="tag-text-warning">{{$room->capacity_min}}名</span>
        @else
          <span class="tag-text-warning">{{$room->capacity_min}}～{{$room->capacity_max}}名</span>
        @endif
      @endif
    </li>
    
    {{-- 風呂 --}}
    <li class="rp-specs">
      @if($room->bath === 0)
        <span class="tag-text-warning">風呂なし</span>
      @elseif($room->bath === 1)
        <span class="tag-text-warning">風呂付き</span>
      @elseif($room->bath === 2)
        <span class="tag-text-warning">風呂共同</span>
      @elseif($room->bath === 3)
        <span class="tag-text-warning">シャワーのみ</span>
      @endif
    </li>
    
    {{-- トイレ --}}
    <li class="rp-specs">
      @if($room->toilet === 0)
        <span class="tag-text-warning">トイレなし</span>
      @elseif($room->toilet === 1)
        <span class="tag-text-warning">トイレ付き</span>
      @elseif($room->toilet === 2)
        <span class="tag-text-warning">トイレ共同</span>
      @elseif($room->toilet === 9)
        <span class="tag-text-warning">トイレ不明</span>
      @endif
    </li>
    
    {{-- 禁煙 / 喫煙 --}}
    <li class="rp-specs">
      @if($room->smoke === 0)
        <span class="tag-text-warning">禁煙&nbsp;/&nbsp;喫煙&nbsp;&nbsp;設定なし</span>
      @elseif($room->smoke === 1)
        <span class="tag-text-warning">禁煙</span>
      @elseif($room->smoke === 2)
        <span class="tag-text-warning">喫煙</span>
      @elseif($room->smoke === 3)
        <span class="tag-text-warning">禁煙&nbsp;/&nbsp;喫煙&nbsp;&nbsp;選択</span>
      @endif
    </li>
    
    {{-- ネットワーク環境 --}}

    {{-- ネットワーク環境の文言を作成 *} --}}
    <li class="rp-specs">
      @if($room->network === 0)
        <span class="tag-text-warning">ネット接続不可</span>
      @elseif($room->network === 9)
        <span class="tag-text-warning">ネット接続不明</span>

      @else

        @if($room->network === 1)
          <span class="tag-text-warning">ネット接続可&nbsp;全室無料</span>
        @elseif($room->network === 2)
          <span class="tag-text-warning">ネット接続可&nbsp;一部客室無料</span>
        @elseif($room->network === 3)
          <span class="tag-text-warning">ネット接続可&nbsp;全室有料</span>
        @elseif($room->network === 4)
          <span class="tag-text-warning">ネット接続可&nbsp;一部客室有料</span>
        @endif

        @if(0 < $room->network && $room->network < 9)
        {{-- {* 部屋ネットワーク機器 *} --}}
          @if($room->rental === 1)
            <span class="tag-text-warning">ケーブル類常設</span>
          @elseif($room->rental === 2)
            <span class="tag-text-warning">ケーブル類無料貸出</span>
          @elseif($room->rental === 3)
            <span class="tag-text-warning">ケーブル類有料貸出</span>
          @elseif($room->rental === 4)
            <span class="tag-text-warning">ケーブル類持込</span>
          @endif
          {{-- コネクタ --}}
          @if($room->connector === 1)
            <span class="tag-text-warning">無線接続</span>
          @elseif($room->connector === 2)
            <span class="tag-text-warning">有線接続</span>
          @elseif($room->connector === 3)
            <span class="tag-text-warning">TELコネクタ</span>
          @elseif($room->connector === 4)
            <span class="tag-text-warning">その他コネクタ</span>
          @endif
        @endif
      @endif
    
    {{-- 部屋休止状態 --}}
    @if($room->accept_status != 1)
      <li class="rp-specs">
        <span class="tag-text-deactive">休止中</span>
      </li>
    @endif
  </ul>
  
  <div class="clear"></div>