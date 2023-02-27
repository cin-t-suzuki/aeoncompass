{{-- MEMO: 移植元 public\app\rsv\view2\top\_link_station.tpl --}}

{{-- TODO: レイアウト崩れ。デザイン修正で対応 --}}

<div class="sfm-station">
    <div class="sfm-station-inner">
        <a class="btnimg" href="/station" title="駅・路線図">
            <img src="{{ asset('img/btn/btn-top-station.gif') }}" alt="駅・路線図" />
        </a>
        <dl>
            <dt>●主要な駅</dt>
            <dd>
                {{-- HACK: ハードコーディングhardcoding --}}
                <a href="/station/1110315/">札幌駅</a>
                <a href="/station/1123143/">仙台駅</a>
                <a href="/station/1130101/">東京駅</a>
                <a href="/station/1130208/">新宿駅</a>
                <a href="/station/1130103/">品川駅</a>
                <br />
                <a href="/station/1130205/">渋谷駅</a>
                <a href="/station/1141101/">名古屋駅</a>
                <a href="/station/1160120/">京都駅</a>
                <a href="/station/1160214/">大阪駅</a>
                <a href="/station/1190101/">博多駅</a>
            </dd>
        </dl>
    </div>
</div>