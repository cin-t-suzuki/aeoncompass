{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search.tpl --}}

@php
    // TODO: 誤魔化し実装 to be deleted
    $vhotel = ['jrc_hotel_cd' => null];
@endphp
@if (!is_null($piece['hotels'][0]['hotel_cd']))
    {{-- MEMO: ↑↑↑ もとは is_empty() --}}
    @php
        $ihotel = $piece['hotels'][0];
        $vhotel = $values['hotels'][$ihotel['hotel_cd']];
    @endphp
    <div style="margin:20px 20px 20px 10px;">
        @php
            $ihotel = $piece['hotels'][0];
            $vhotel = $values['hotels'][$ihotel['hotel_cd']];
        @endphp
        <div style="padding: 0 0 0 46px;background:url(/img/lhd/lhd-hotel.gif) left top no-repeat;">
            {{ $vhotel['hotel_nm'] }}
        </div>
        @if (!is_null($ihotel['plans'][0]['plan_id']))
            {{-- MEMO: ↑↑↑ もとは is_empty() --}}
            @php
                $iplan = $ihotel['plans'][0];
                $iroom = $iplan['plan_rooms'][0];
                $vplan = $vhotel['plans'][$iplan['plan_id']];
                $vroom = $vhotel['rooms'][$iroom['room_id']];
            @endphp
            <div class="sfm-plan_nm" style="margin-top:0.5em;padding: 0 0 0 46px;background:url(/img/lhd/lhd-plan.gif) left top no-repeat;color:#005e8e">
                {{ $vplan['plan_nm'] }}
                @if (!is_null($vroom['room_nm']))
                    {{-- MEMO: ↑↑↑ もとは is_empty() --}}
                    ［{{ $vroom['room_nm'] }}］
                @endif
            </div>
        @endif
    </div>
@endif
{{-- -------------------------------------------------------------------------- --}}
{{-- このテンプレートを修正した場合、以下の点に注意してください --}}
{{-- --}}
{{-- ※深夜時間(23時以降)デザインが切り替わり、今すぐ泊まれる宿の検索フォームが --}}
{{-- 表示されるようになります --}}
{{-- ローカル環境(PC)の時間を変更し深夜時間のデザインを確認、ラジオボタンを --}}
{{-- クリックし、表示の切り替え確認、この2点を必ず確認してください。 --}}
{{-- -------------------------------------------------------------------------- --}}
@if (!is_null($piece['hotels'][0]['hotel_cd']))
    {{-- MEMO: ↑↑↑ もとは is_empty() --}}
    <div style="margin:0 auto;width:344px;">
@endif
<div class="sfm-search">
    <div class="sfm-cat-box">
        <ul class="clearfix">
            {{-- 国内宿泊 --}}
            <li>
                <input class="btnimg jqs-tab {{ 'tab-normal2' . $bgcolor . (!$search_condition['form']['midnight']['current_status'] ? 'current' : '') }}" id="sfm-radio01" name="search-cat" type="radio" value="normal" {{ !$search_condition['form']['midnight']['current_status'] ? 'checked' : '' }}>
                <label for="sfm-radio01">国内宿泊</label>
            </li>
            {{-- 今すぐ泊まれる宿 --}}
            @if ($search_condition['form']['midnight'])
                <li>
                    <input class="btnimg jqs-tab {{ 'tab-today' . $bgcolor . ($search_condition['form']['midnight']['current_status'] ? 'current' : '') }}" id="sfm-radio06" name="search-cat" type="radio" value="today" {{ $search_condition['form']['midnight']['current_status'] ? 'checked' : '' }} @if (!$search_condition['form']['midnight']['current_status']) style="display: none;" @endif>
                    <label for="sfm-radio06" @if (!$search_condition['form']['midnight']['current_status']) style="display: none;" @endif>今すぐ泊まれる宿</label>
                </li>
            @endif
            {{-- JR＋宿泊 --}}
            @if ($vhotel['jrc_hotel_cd'] || is_null($piece['hotels'][0]['hotel_cd']))
                {{-- MEMO: ↑↑↑ もとは is_empty() --}}
                <li class="jqs-jrc">
                    <input class="btnimg jqs-tab {{ 'tab-jrc' . $bgcolor }}" id="sfm-radio02" name="search-cat" type="radio" value="jrc">
                    <label name="search-cat" for="sfm-radio02">JR＋宿泊</label>
                </li>
            @endif
            @if (!is_null($isTop) && $isTop && !$search_condition['form']['midnight']['current_status'])
                {{-- MEMO: ↑↑↑ もとは is_empty() --}}
                {{-- 高速バス予約 --}}
                <li>
                    <input class="jqs-tabLink" id="sfm-radio04" name="search-cat" type="radio" value="bus" onclick="window.open('/ro/tabiplaza-bus/')">
                    <label for="sfm-radio04">高速バス予約</label>
                </li>
            @endif
        </ul>
    </div>
    {{-- 国内宿泊 --}}
    <div class="{{ 'sfm-normal2' . $bgcolor . (!is_null($piece['hotels'][0]['hotel_cd']) ? 's' : '') }} " name="search-cat_normal_box" @if ($search_condition['form']['midnight']['current_status']) style="display: none;" @endif>
        {{-- MEMO: ↑↑↑ もとは is_empty() --}}
        <div class="{{ 'sfm-normal2' . $bgcolor . (!is_null($piece['hotels'][0]['hotel_cd']) ? 's' : '') . '-inner' }}">
            {{-- MEMO: ↑↑↑ もとは is_empty() --}}
            {{-- {include file='../_common/_form_search_normal.tpl'} --}}
            @include('rsv.common._form_search_normal')
        </div>
    </div>
    {{-- 今すぐ泊まれる宿 --}}
    @if ($search_condition['form']['midnight'])
        <div class="{{ 'sfm-today' . $bgcolor . (!is_null($piece['hotels'][0]['hotel_cd']) ? 's' : '') }}" name="search-cat_today_box" @if (!$search_condition['form']['midnight']['current_status']) style="display: none;" @endif>
            {{-- MEMO: ↑↑↑ もとは is_empty() --}}
            <div class="{{ 'sfm-today' . $bgcolor . (!is_null($piece['hotels'][0]['hotel_cd']) ? 's' : '') . '-inner' }}">
                {{-- MEMO: ↑↑↑ もとは is_empty() --}}
                {{-- {include file='../_common/_form_search_today.tpl'} --}}
                @include('rsv.common._form_search_today')
            </div>
        </div>
    @endif

    {{-- ＪＲ＋宿泊 --}}
    {{-- TODO: ラジオボタンで表示が切り替わらない。 --}}
    @if ($vhotel['jrc_hotel_cd'] || is_null($piece['hotels'][0]['hotel_cd']))
        {{-- MEMO: ↑↑↑ もとは is_empty() --}}
        <div class="{{ 'sfm-jrc' . $bgcolor . (!is_null($piece['hotels'][0]['hotel_cd']) ? 's' : '') }} " name="search-cat_jrc_box" style="display: none;">
            {{-- MEMO: ↑↑↑ もとは is_empty() --}}
            <div class="{{ 'sfm-jrc' . $bgcolor . (!is_null($piece['hotels'][0]['hotel_cd']) ? 's' : '') . '-inner' }}">
                {{-- MEMO: ↑↑↑ もとは is_empty() --}}
                {{-- {include file='../_common/_form_search_jrc.tpl'} --}}
                @include('rsv.common._form_search_jrc')
            </div>
        </div>
    @endif
</div>
@if (!is_null($piece['hotels'][0]['hotel_cd']))
    {{-- MEMO: ↑↑↑ もとは is_empty() --}}
    </div>
@endif
