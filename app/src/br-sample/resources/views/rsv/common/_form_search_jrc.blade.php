{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_jrc.tpl --}}

@php
    // TODO: 会員情報から表示地域を取得している。他のページで読み込まれた時に確認。
    $pref_id = isset($v->user->member->pref_id) ?? null;

    // TODO: トップページでは値がセットされていない変数。他のページで読み込まれた時に確認。
    $hotel_cd = isset($v->hotel['hotel_cd']) ?? null;
    $jrc_hotel_cd = isset($v->hotel['jrc_hotel_cd']) ?? null;
@endphp
<form class="parseForm" method="get" action="/jrc/{{ $pre_uri }}" target="_blank">
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>
                <div>出発地</div>
            </th>
            <td>
                <select name="dept" size="1">
                    {{-- HACK: magic number (おそらく mast_pref テーブルのもの) --}}
                    <option value="1" {{ $pref_id == '1' ? 'selected' : '' }}>北海道</option>
                    <option value="11" {{ $pref_id >= 2 && ($pref_id <= 7 ? 'selected' : '') }}>東北</option>
                    <option value="31" {{ is_null($pref_id) || ($pref_id >= 8 && $pref_id <= 14 ? 'selected' : '') }}>首都圏</option>
                    <option value="41" {{ $pref_id >= 19 && ($pref_id <= 23 ? 'selected' : '') }}>中部</option>
                    <option value="46" {{ $pref_id >= 15 && ($pref_id <= 18 ? 'selected' : '') }}>北陸</option>
                    <option value="56" {{ $pref_id >= 24 && ($pref_id <= 30 ? 'selected' : '') }}>関西</option>
                    <option value="61" {{ $pref_id >= 31 && ($pref_id <= 35 ? 'selected' : '') }}>中国</option>
                    <option value="71" {{ $pref_id >= 36 && ($pref_id <= 39 ? 'selected' : '') }}>四国</option>
                    <option value="81" {{ $pref_id >= 40 && ($pref_id <= 46 ? 'selected' : '') }}>九州</option>
                </select>
            </td>
        </tr>
        <tr>
            <th>
                <div>出発日</div>
            </th>
            <td>
                <select name="year_month" size="1">
                </select>
                <select class="text-right" name="day" size="1">
                </select>&nbsp;
            </td>
        </tr>
        <tr>
            <th>
                <div>１室人数</div>
            </th>
            <td>
                <select class="text-right" name="guest" size="1">
                    @for ($count = 1; $count <= 7; $count++)
                        <option value="{{ $count }}" {{ $count == $senior ? 'selected' : '' }}>
                            {{ $count }}名
                        </option>
                    @endfor
                </select>
            </td>
        </tr>
        @if (is_null($hotel_cd))
            <tr>
                <th>
                    <div>方面</div>
                </th>
                <td>
                    <select name="dict" size="1">
                        <option value="">
                        </option>
                        <option value="l1" {{ $area_id == 'l1' ? 'selected' : '' }}>北海道</option>
                        <option value="l2" {{ $area_id == 'l2' ? 'selected' : '' }}>東北</option>
                        <option value="l3" {{ $area_id == 'l3' ? 'selected' : '' }}>北関東</option>
                        <option value="l4" {{ $area_id == 'l4' ? 'selected' : '' }}>首都圏</option>
                        <option value="l5" {{ $area_id == 'l5' ? 'selected' : '' }}>甲信越</option>
                        <option value="l6" {{ $area_id == 'l6' ? 'selected' : '' }}>北陸</option>
                        <option value="l7" {{ $area_id == 'l7' ? 'selected' : '' }}>東海</option>
                        <option value="l8" {{ $area_id == 'l8' ? 'selected' : '' }}>近畿</option>
                        <option value="l9" {{ $area_id == 'l9' ? 'selected' : '' }}>中国</option>
                        <option value="l10" {{ $area_id == 'l10' ? 'selected' : '' }}>四国</option>
                        <option value="l11" {{ $area_id == 'l11' ? 'selected' : '' }}>九州</option>
                        <option value="l12" {{ $area_id == 'l12' ? 'selected' : '' }}>沖縄</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <div>都道府県</div>
                </th>
                <td>
                    {{-- HACK: ハードコーディングhardcoding --}}
                    <select name="pref" size="1">
                        <option value="">
                        </option>
                        <option value="p01" {{ $place_cd == 'p01' ? 'selected' : '' }}>北海道</option>
                        <option value="p02" {{ $place_cd == 'p02' ? 'selected' : '' }}>青森県</option>
                        <option value="p03" {{ $place_cd == 'p03' ? 'selected' : '' }}>岩手県</option>
                        <option value="p04" {{ $place_cd == 'p04' ? 'selected' : '' }}>宮城県</option>
                        <option value="p05" {{ $place_cd == 'p05' ? 'selected' : '' }}>秋田県</option>
                        <option value="p06" {{ $place_cd == 'p06' ? 'selected' : '' }}>山形県</option>
                        <option value="p07" {{ $place_cd == 'p07' ? 'selected' : '' }}>福島県</option>
                        <option value="p08" {{ $place_cd == 'p08' ? 'selected' : '' }}>茨城県</option>
                        <option value="p09" {{ $place_cd == 'p09' ? 'selected' : '' }}>栃木県</option>
                        <option value="p10" {{ $place_cd == 'p10' ? 'selected' : '' }}>群馬県</option>
                        <option value="p11" {{ $place_cd == 'p11' ? 'selected' : '' }}>埼玉県</option>
                        <option value="p12" {{ $place_cd == 'p12' ? 'selected' : '' }}>千葉県</option>
                        <option value="p13" {{ $place_cd == 'p13' ? 'selected' : '' }}>東京都</option>
                        <option value="p14" {{ $place_cd == 'p14' ? 'selected' : '' }}>神奈川県</option>
                        <option value="p15" {{ $place_cd == 'p15' ? 'selected' : '' }}>新潟県</option>
                        <option value="p16" {{ $place_cd == 'p16' ? 'selected' : '' }}>富山県</option>
                        <option value="p17" {{ $place_cd == 'p17' ? 'selected' : '' }}>石川県</option>
                        <option value="p18" {{ $place_cd == 'p18' ? 'selected' : '' }}>福井県</option>
                        <option value="p19" {{ $place_cd == 'p19' ? 'selected' : '' }}>山梨県</option>
                        <option value="p20" {{ $place_cd == 'p20' ? 'selected' : '' }}>長野県</option>
                        <option value="p21" {{ $place_cd == 'p21' ? 'selected' : '' }}>岐阜県</option>
                        <option value="p22" {{ $place_cd == 'p22' ? 'selected' : '' }}>静岡県</option>
                        <option value="p23" {{ $place_cd == 'p23' ? 'selected' : '' }}>愛知県</option>
                        <option value="p24" {{ $place_cd == 'p24' ? 'selected' : '' }}>三重県</option>
                        <option value="p25" {{ $place_cd == 'p25' ? 'selected' : '' }}>滋賀県</option>
                        <option value="p26" {{ $place_cd == 'p26' ? 'selected' : '' }}>京都府</option>
                        <option value="p27" {{ $place_cd == 'p27' ? 'selected' : '' }}>大阪府</option>
                        <option value="p28" {{ $place_cd == 'p28' ? 'selected' : '' }}>兵庫県</option>
                        <option value="p29" {{ $place_cd == 'p29' ? 'selected' : '' }}>奈良県</option>
                        <option value="p30" {{ $place_cd == 'p30' ? 'selected' : '' }}>和歌山県</option>
                        <option value="p31" {{ $place_cd == 'p31' ? 'selected' : '' }}>鳥取県</option>
                        <option value="p32" {{ $place_cd == 'p32' ? 'selected' : '' }}>島根県</option>
                        <option value="p33" {{ $place_cd == 'p33' ? 'selected' : '' }}>岡山県</option>
                        <option value="p34" {{ $place_cd == 'p34' ? 'selected' : '' }}>広島県</option>
                        <option value="p35" {{ $place_cd == 'p35' ? 'selected' : '' }}>山口県</option>
                        <option value="p36" {{ $place_cd == 'p36' ? 'selected' : '' }}>徳島県</option>
                        <option value="p37" {{ $place_cd == 'p37' ? 'selected' : '' }}>香川県</option>
                        <option value="p38" {{ $place_cd == 'p38' ? 'selected' : '' }}>愛媛県</option>
                        <option value="p39" {{ $place_cd == 'p39' ? 'selected' : '' }}>高知県</option>
                        <option value="p40" {{ $place_cd == 'p40' ? 'selected' : '' }}>福岡県</option>
                        <option value="p41" {{ $place_cd == 'p41' ? 'selected' : '' }}>佐賀県</option>
                        <option value="p42" {{ $place_cd == 'p42' ? 'selected' : '' }}>長崎県</option>
                        <option value="p43" {{ $place_cd == 'p43' ? 'selected' : '' }}>熊本県</option>
                        <option value="p44" {{ $place_cd == 'p44' ? 'selected' : '' }}>大分県</option>
                        <option value="p45" {{ $place_cd == 'p45' ? 'selected' : '' }}>宮崎県</option>
                        <option value="p46" {{ $place_cd == 'p46' ? 'selected' : '' }}>鹿児島県</option>
                        <option value="p47" {{ $place_cd == 'p47' ? 'selected' : '' }}>沖縄県</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <div>エリア</div>
                </th>
                <td>
                    <select name="area" size="1">
                        <option value="">
                        </option>
                        <option value="p01" {{ $place_cd == 'p01' ? 'selected' : '' }}>北海道</option>
                        <option value="p02" {{ $place_cd == 'p02' ? 'selected' : '' }}>東北</option>
                    </select>
                </td>
            </tr>
        @endif
    </table>
    @if (is_null($hotel_cd))
        <div class="sfm-jrc-submit">
            <div class="btn-b06-138-sb" style="margin:0 auto;">
                <input class="btnimg collectBtn collectForce" src="{{ asset('img/btn/b06-jrc1.gif') }}" type="image" alt="ＪＲ＋宿泊検索" />
            </div>
        </div>
        <div style="text-align:center;">※ご予約は日本旅行サイトでのご予約となります。</div>
    @else
        <div class="sfm-jrc-submit">
            <div class="btn-b06-138-sb" style="margin:0 auto;">
                <input class="btnimg collectBtn collectForce" src="{{ asset('img/btn/b06-jrc3.gif') }}" type="image" alt="ＪＲ＋宿泊検索へすすむ" />
            </div>
        </div>
        <div style="text-align:center;">この商品は株式会社日本旅行が企画・実施しております。</div>
        <input name="hotel_cd" type="hidden" value="{{ $jrc_hotel_cd }}" />
    @endif
    <input name="today" type="hidden" value="{{ date('Y-m-d') }}" />
</form>
