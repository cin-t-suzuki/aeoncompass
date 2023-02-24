
  {{-- プランスペックを示すアイコン表示 --}}
  <ul class="gen-list">
    
    {{-- リロプラン --}}
    @if($plan->is_relo)
      <li class="rp-specs">
        <span class="tag-text-info">リロ専用プラン</span>
      </li>
    @endif
    
    {{-- 泊数限定 --}}
    @if($plan->stay_limit > 1 && $plan->stay_cap > 1)
    {{-- 任意の最小泊数と最大泊数が指定されている（デフォルトでない状態） --}}
      @if($plan->stay_limit === $plan->stay_cap)
        {{-- 最小泊数と最大泊数が同じ値 --}}
        <li class="rp-specs">
          <span class="tag-text-success">{{mb_convert_kana($plan->stay_limit,'A')}}連泊限定</span>
        </li>
      @else
      {{-- 最小泊数と最大泊数が異なる値 --}}
        <li class="rp-specs">
          <span class="tag-text-success">{{mb_convert_kana($plan->stay_limit,'A')}}～{{mb_convert_kana($plan['stay_cap'],'A')}}連泊まで</span>
        </li>
      @endif
    @elseif($plan->stay_limit > 1)
      {{-- 任意の最小泊数のみが指定されている --}}
      <li class="rp-specs">
        <span class="tag-text-success">{{mb_convert_kana($plan->stay_limit,'A')}}連泊～</span>
      </li>
    @elseif($plan->stay_cap >= 2)
      {{-- 任意の最大泊数のみが指定されている(2泊以上) --}}
      <li class="rp-specs">
        <span class="tag-text-success">１～{{mb_convert_kana($plan->stay_cap,'A')}}連泊まで</span>
      </li>
    @elseif($plan->stay_cap == 1)
      {{-- 任意の最大泊数のみが指定されている(1泊のみ) --}}
      <li class="rp-specs">
        <span class="tag-text-success">{{mb_convert_kana($plan->stay_cap,'A')}}泊限定</span>
      </li>
    @endif
    
    {{-- 食事 --}}
    <li class="rp-specs">
      @if($plan->meal === 0)
        <span class="tag-text-success">食事無し</span>
      @elseif($plan->meal === 1)
        <span class="tag-text-success">夕食付</span>
      @elseif($plan->meal === 2)
        <span class="tag-text-success">朝食付</span>
      @elseif($plan->meal === 3)
        <span class="tag-text-success">夕・朝食付</span>
      @endif
    </li>
    
    {{-- プランタイプ --}}
    @if($plan->plan_type === 'fss')
      <li class="rp-specs">
        <span class="tag-text-success">金土日</span>
      </li>
    @endif
    
    {{-- 料金タイプ --}}
    <li class="rp-specs">
      @if($plan->charge_type === 0)
        <span class="tag-text-success">１室料金</span>
      @elseif($plan->charge_type === 1)
        <span class="tag-text-success">１人料金</span>
      @else
        <span class="tag-text-success">料金タイプ未設定</span>
      @endif
    </li>
    
    {{-- 支払方法 --}}
    <li class="rp-specs">
      @if($plan->payment_way == 3)
        <span class="tag-text-success">事前カード決済&nbsp;/&nbsp;現地決済</span>
      @elseif($plan->payment_way == 2)
        <span class="tag-text-success">現地決済</span>
      @elseif($plan->payment_way == 1)
        <span class="tag-text-success">事前カード決済</span>
      @endif
    </li>
    
    {{-- ポイント利用 --}}
    {{-- ※基本以上の付与率を設定している場合、付与率を表示する --}}
    {{-- 基礎付与率を判定 --}}

    
    {{-- TODO  $v->user-> 書き変え--}}
    @if($v->user->hotel->premium_status)
      {{-- プレミアム施設は2%が基本 --}}
      @php
      $point_rate_default = 2;
      @endphp
    @else
      {{-- 通常施設は1%が基本 --}}
      @php
      $point_rate_default = 1;
      @endphp
    @endif
    
    {{-- 付与率を示す文言を作成 --}}
    @if($point_rate_default != intval($plan->issue_point_rate))
      {{-- 付与率を文言に含める --}}
      @php
      $special_point_rate_msg = $plan->issue_point_rate . '％付与';
      @endphp
    @endif

    @php
    $special_point_rate_msg = $plan->issue_point_rate . '％付与';
    @endphp
    
    @if($plan->point_status == 1)
      {{-- ポイント利用可能 --}}
      <li class="rp-specs">
        <span class="tag-text-success">ポイント利用可能{{$special_point_rate_msg}}</span>
      </li>
    @else
      {{-- ポイント利用不可 --}}
      <li class="rp-specs">
        <span class="tag-text-success">ポイント利用不可{{$special_point_rate_msg}}</span>
      </li>
    @endif
    
    {{-- キャンペーン対象 --}}
    @if($plan->is_camp)
      <li class="rp-specs">
        <span class="tag-text-success">キャンペーン</span>
      </li>
    @endif
    
    {{-- BR提供 --}}
    {{-- TODO $v->user-> 書き変え --}}
    @if($v->user->hotel_control->stock_type == 1)
      <li class="rp-specs">
        <span class="tag-text-success">ＢＲ提供</span>
      </li>
    @endif
    
    {{-- パワー --}}
    {{-- TODO $v->user-> 書き変え --}}
    @if($v->user->hotel_control->stock_type == 1 && $plan->payment_way == 1)
      <li class="rp-specs">
        <span class="tag-text-success">パワー</span>
      </li>
    @endif
    
    {{-- 販売チャンネル --}}
    @if($plan->is_br)
      {{-- BRが販売チャンネルに存在する場合、「全サイト共通販売」の扱い --}}
      <li class="rp-specs">
        <span class="tag-text-success">全サイト共通販売</span>
      </li>
    @else
      {{-- BRが販売チャンネルに存在しない場合、「特定サイト限定販売」の扱い --}}
      {{-- （※JRコレクション・リロは除く） --}}
      @if(!$plan->is_jrc && $plan->is_relo)
        <li class="rp-specs">
          <span class="tag-text-success">特定サイト限定販売</span>
        </li>
      @endif
    @endif
    
    {{-- JRコレクション --}}
    @if($plan->is_jrc)
      <li class="rp-specs">
        <span class="tag-text-success">ＪＲコレクション</span>
      </li>
    @endif
    
    {{-- チェックイン --}}
    @if(!empty($plan->check_in) || !empty($plan->check_in_end))
      <li class="rp-specs">
        <span class="tag-text-success" title="チェックイン：{{$plan->check_in}}&nbsp;～&nbsp; @if(empty($plan->check_in_end)) 指定無し @else {{$plan->check_in_end}} @endif">チェックイン</span>
      </li>
    @endif
    
    {{-- チェックアウト --}}
    @if(!empty($plan->check_out))
      <li class="rp-specs">
        <span class="tag-text-success" title="チェックアウト：{{$plan->check_out}}">チェックアウト</span>
      </li>
    @endif
    
    {{-- 休止 --}}
    @if($plan->accept_status != 1)
      <li class="rp-specs">
        <span class="tag-text-deactive">休止中</span>
      </li>
    @endif
    
  </ul>

  <div class="clear"></div>
