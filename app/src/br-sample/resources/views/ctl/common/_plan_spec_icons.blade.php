  {{-- プランスペックを示すアイコン表示 --}}
  {{-- 引数：$plan[0] --}}

  <ul class="gen-list">

    {{-- リロプラン --}}
    @if ($plan[0]['is_relo'])
      <li class="rp-specs">
        <span class="tag-text-info">リロ専用プラン</span>
      </li>
    @endif


    {{-- 泊数限定 --}}
    @if ($plan[0]['stay_limit'] > 1 && $plan[0]['stay_cap'] > 1)
    {{-- 任意の最小泊数と最大泊数が指定されている（デフォルトでない状態） --}}
      @if ($plan[0]['stay_limit'] === $plan[0]['stay_cap'])
        {{-- 最小泊数と最大泊数が同じ値  --}}
        <li class="rp-specs">
          <span class="tag-text-success"> $plan[0]['stay_limit']| mb_convert_kana:'A'  連泊限定</span>
        </li>
      @else
      {{-- 最小泊数と最大泊数が異なる値 --}}
        <li class="rp-specs">
          <span class="tag-text-success"> $plan[0]['stay_limit']|mb_convert_kana:'A' ～ $plan[0]['stay_cap']|mb_convert_kana:'A' 連泊まで</span>
        </li>
      @endif
    @elseif ($plan[0]['stay_limit'] > 1)
    {{-- 任意の最小泊数のみが指定されている --}}
      <li class="rp-specs">
        <span class="tag-text-success"> $plan[0]['stay_limit']|mb_convert_kana:'A' 連泊～</span>
      </li>
    @elseif ($plan[0]['stay_cap'] >= 2)
    {{-- 任意の最大泊数のみが指定されている(2泊以上) --}}
      <li class="rp-specs">
        <span class="tag-text-success">１～ $plan[0]['stay_cap']|mb_convert_kana:'A' 連泊まで</span>
      </li>
    @elseif ($plan[0]['stay_cap'] == 1)
      {{-- 任意の最大泊数のみが指定されている(1泊のみ) --}}
      <li class="rp-specs">
        <span class="tag-text-success"> $plan[0]['stay_cap']|mb_convert_kana:'A' 泊限定</span>
      </li>
    @endif

    {{-- 食事 --}}
    <li class="rp-specs">
      @if (    $plan[0]['meal'] === "0") <span class="tag-text-success">食事無し</span>
      @elseif ($plan[0]['meal'] === "1") <span class="tag-text-success">夕食付</span>
      @elseif ($plan[0]['meal'] === "2") <span class="tag-text-success">朝食付</span>
      @elseif ($plan[0]['meal'] === "3") <span class="tag-text-success">夕・朝食付</span>
      @endif
    </li>

    {{-- プランタイプ --}}
    @if ($plan[0]['plan_type'] === 'fss')
      <li class="rp-specs">
        <span class="tag-text-success">金土日</span>
      </li>
    @endif

    {{-- 料金タイプ --}}
    <li class="rp-specs">
      @if (    $plan[0]['charge_type'] === '0') <span class="tag-text-success">１室料金</span>
      @elseif ($plan[0]['charge_type'] === '1') <span class="tag-text-success">１人料金</span>
      @else <span class="tag-text-success">料金タイプ未設定</span>
      @endif
    </li>


    {{-- 支払方法 --}}
    <li class="rp-specs">
      @if (    $plan[0]['payment_way'] == 3) <span class="tag-text-success">事前カード決済&nbsp;/&nbsp;現地決済</span>
      @elseif ($plan[0]['payment_way'] == 2) <span class="tag-text-success">現地決済</span>
      @elseif ($plan[0]['payment_way'] == 1) <span class="tag-text-success">事前カード決済</span>
      @endif
    </li>

    {{-- ポイント利用 --}}
    {{-- ※基本以上の付与率を設定している場合、付与率を表示する --}}
    {{-- 基礎付与率を判定 --}}
    @php
        $point_rate_default = 0;
    @endphp

    {{-- TODO:について検証環境で確認できていないので、確認でき次第調査 --}}
    {{--@if ($v->user->hotel['premium_status']) --}}
      {{-- プレミアム施設は2%が基本 --}}
    {{--
      @php
          $point_rate_default = 2;
      @endphp
    @else
    --}}
    {{-- 通常施設は1%が基本 --}}
    {{--
      @php
          $point_rate_default = 1;
      @endphp
    @endif --}}

    {{-- 付与率を示す文言を作成 --}}
    {{-- assign var=special_point_rate_msg value='' --}}

    {{-- 付与率が基礎付与率と異なるとき --}}
    {{-- @if ($point_rate_default != $plan[0]['issue_point_rate']|intval) --}}
    {{-- 付与率を文言に含める --}}
      {{-- assign var=special_point_rate_msg value='&nbsp;/&nbsp;'|cat:$plan[0]['issue_point_rate']|cat:'％付与' --}}
    {{-- @endif --}}


    {{--
    @if ($plan[0]['point_status'] == 1)
    --}}
      {{-- ポイント利用可能 --}}
    {{--
      <li class="rp-specs">
        <span class="tag-text-success">ポイント利用可能{{ $special_point_rate_msg }}</span>
      </li>
    @else
    --}}
    {{-- ポイント利用不可 --}}
    {{--
      <li class="rp-specs">
        <span class="tag-text-success">ポイント利用不可{{ $special_point_rate_msg }}</span>
      </li>
    @endif
    --}}


    {{-- キャンペーン対象 --}}
    @if ($plan[0]['is_camp'])
      <li class="rp-specs">
        <span class="tag-text-success">キャンペーン</span>
      </li>
    @endif


    {{-- BR提供 --}}
    {{--
    @if ($v->user->hotel_control['stock_type'] == 1)
      <li class="rp-specs">
        <span class="tag-text-success">ＢＲ提供</span>
      </li>
    @endif
    --}}

    {{-- パワー --}}
    {{--
    @if ($v->user->hotel_control['stock_type'] == 1 && $plan[0]['payment_way'] == 1)
      <li class="rp-specs">
        <span class="tag-text-success">パワー</span>
      </li>
    @endif
    --}}

    {{-- 販売チャンネル --}}
    @if ($plan[0]['is_br'])
      {{-- BRが販売チャンネルに存在する場合、「全サイト共通販売」の扱い --}}
      <li class="rp-specs">
        <span class="tag-text-success">全サイト共通販売</span>
      </li>
    @else
    {{-- BRが販売チャンネルに存在しない場合、「特定サイト限定販売」の扱い --}}
    {{-- （※JRコレクション・リロは除く） --}}
      @if (!$plan[0]['is_jrc'] && !$plan[0]['is_relo'])
        <li class="rp-specs">
          <span class="tag-text-success">特定サイト限定販売</span>
        </li>
      @endif
    @endif

    {{-- JRコレクション --}}
    @if ($plan[0]['is_jrc'])
      <li class="rp-specs">
        <span class="tag-text-success">ＪＲコレクション</span>
      </li>
    @endif

    {{-- チェックイン --}}
    {{-- MEMO: ↓ もとは is_empty() --}}
    @if (!is_null($plan[0]['check_in']) || !is_null($plan[0]['check_in_end']))
      <li class="rp-specs">
        {{-- MEMO: ↓ もとは is_empty() --}}
        <span class="tag-text-success"> title=チェックイン : {{ $plan[0]['check_in'] }}&nbsp;～&nbsp;</span>
        @if (is_null($plan[0]['check_in_end']))
        <span class="tag-text-success">title=指定無し</span>
        @else {{ $plan[0]['check_in_end'] }}
        <span class="tag-text-success">title=チェックイン</span>
        @endif
      </li>
    @endif


    {{-- チェックアウト --}}
    {{-- MEMO: ↓ もとは is_empty() --}}
    @if (!is_null($plan[0]['check_out']))
      <li class="rp-specs">
        <span class="tag-text-success" title="チェックアウト：{{ $plan[0]['check_out'] }}">チェックアウト</span>
      </li>
    @endif

    {{-- 休止 --}}
    @if ($plan[0]['accept_status'] != 1)
      <li class="rp-specs">
        <span class="tag-text-deactive">休止中</span>
      </li>
    @endif

  </ul>

  <div class="clear"></div>

