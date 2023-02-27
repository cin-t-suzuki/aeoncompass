{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_normal.tpl --}}

@if (count($search_condition['form']['rooms']) == 0)
    <div style="background:#ffffff;margin:0 auto 10px;width:300px;height:168px;padding:9px 8px 8px;">
        <div>ご指定のプランは満室となりましたので、ご予約いただけなくなりました。 他のプランをご検討ください。</div>
    </div>
@else
    {{-- 検索フォーム --}}
    <form class="jqs-query parseForm" method="get" action="/query/">
        <table border="0" cellpadding="0" cellspacing="0" width="322">
            <tr>
                <th>
                    <div class="div-h">旅行日程</div>
                </th>
                <td>
                    {{-- 年月表示（13月まで表示） --}}
                    <select name="year_month" size="1">
                        @foreach ($search_condition['form']['year_month'] as $months)
                            <option value="{{ $months['date_ym'] }}" {{ $months['current_status'] ? 'selected' : '' }}>
                                {{ substr($months['date_ym'], 0, 4) }}年{{ number_format(substr($months['date_ym'], 5, 2)) }}月
                            </option>
                        @endforeach
                    </select>
                    {{-- 日表示のための31回ループ --}}
                    <select class="text-right" name="day" size="1">
                        @foreach ($search_condition['form']['days'] as $days)
                            <option value="{{ $days['date_ymd'] }}" {{ $days['current_status'] ? 'selected' : '' }}>
                                {{ $days['date_ymd'] }}日
                            </option>
                        @endforeach
                    </select>
                    &nbsp;&nbsp;
                    <a class="jqs-calendar" href="#calendar">
                        <img src="{{ asset('img/lhd/lhd-calendar.gif') }}" alt="" />
                    </a>
                    <br />
                    <select class="text-right" name="stay" size="1">
                        @foreach ($search_condition['form']['stay'] as $stay)
                            <option value="{{ $stay['days'] }}" {{ $stay['current_status'] ? 'selected' : '' }}>
                                {{ $stay['days'] }}泊
                            </option>
                        @endforeach
                    </select>
                    &nbsp;&nbsp;
                    @if (!array_key_exists('hotel', $search_condition['form']) || !array_key_exists('room_id', $search_condition['form']['hotel']))
                        <input id="date_status" name="date_status" type="checkbox" value="on" {{ $search_condition['form']['date_status'] == 'on' ? 'checked' : '' }} />
                        <label class="checkbox" for="date_status">日程未定</label>
                    @endif
                </td>
            </tr>
            <tr>
                <th>
                    <div>部屋数</div>
                </th>
                <td>
                    <select class="text-right" name="rooms" size="1">
                        @foreach ($search_condition['form']['rooms'] as $rooms)
                            <option value="{{ $rooms['room_count'] }}" {{ $rooms['current_status'] ? 'selected' : '' }}>
                                {{ $rooms['room_count'] }}室
                            </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>
                    <div>利用人数／室</div>
                </th>
                <td>
                    大人<select class="text-right" name="senior" size="1">
                        @foreach ($search_condition['form']['senior']['capacities'] as $senior)
                            <option value="{{ $senior['capacity'] }}" {{ $senior['current_status'] ? 'selected' : '' }}>
                                {{ $senior['capacity'] }}名
                            </option>
                        @endforeach
                    </select>
                    @if ($search_condition['form']['children']['accept_status'])
                        <a class="panelsw" name="guests-normal" href="">
                            子供
                            <span name="children">
                                {{-- MEMO: js で動的に書き換えられる。 --}}
                                {{ $params['child1'] + $params['child2'] + $params['child3'] + $params['child4'] + $params['child5'] }}
                            </span>名
                        </a>
                        {{-- {{include file='../_common/_form_capacity.tpl' form_capacity_nm='guests-normal'}} --}}
                        @include('rsv.common._form_capacity', [
                            'form_capacity_nm' => 'guests-normal',
                        ])
                    @endif
                </td>
            </tr>
            {{-- @if (is_null($search_condition['form']['hotel']['hotel_cd'])) --}}
            @if (!array_key_exists('hotel', $search_condition['form']) || !array_key_exists('hotel_cd', $search_condition['form']['hotel']))
                <tr>
                    <th>
                        <div class="div-h">予算</div>
                    </th>
                    <td>1泊1部屋1人あたり<br />
                        {{-- HACK: ネストが壊れている --}}
                        @foreach ($search_condition['form']['charges']['min'] as $charge_min)
                            @if ($loop->count == 1)
                                {{ $charge_min['name'] }}
                                <input name="charge_min" type="hidden" value="{{ $charge_min['charge'] }}" />
                            @else
                                @if ($loop->first)
                                    <select class="text-right" name="charge_min" size="1">
                                @endif
                                <option value="{{ $charge_min['charge'] }}" {{ $charge_min['current_status'] ? 'selected' : '' }}>
                                    {{ $charge_min['name'] }}
                                </option>
                                @if ($loop->last)
                                    </select>
                                @endif
                            @endif
                        @endforeach
                        @foreach ($search_condition['form']['charges']['max'] as $charge_max)
                            @if ($loop->count == 1)
                                @if ($loop->count == 1 && $charge_max['charge'] == $search_condition['form']['charges']['min'][0]['charge'])
                                @else
                                    ～{{ $charge_max['name'] }}
                                @endif
                                <input name="charge_max" type="hidden" value="{{ $charge_max['charge'] }}" />
                            @else
                                @if ($loop->first)
                                    ～<select class="text-right" name="charge_max" size="1">
                                @endif
                                <option value="{{ $charge_max['charge'] }}" {{ $charge_max['current_status'] ? 'selected' : '' }}>
                                    {{ $charge_max['name'] }}
                                </option>
                                @if ($loop->last)
                                    </select>
                                @endif
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endif
            {{-- 地図 --}}
            {{-- @if (is_null($search_condition['form']['hotel']['hotel_cd']) && is_null($search_condition['form']['hotel']['title'])) --}}
            @if ((!array_key_exists('hotel', $search_condition['form']) || !array_key_exists('hotel_cd', $search_condition['form']['hotel'])) && (!array_key_exists('hotel', $search_condition['form']) || !array_key_exists('title', $search_condition['form']['hotel'])))
                <tr>
                    <th>
                        <div class="div-h">地域</div>
                    </th>
                    <td>
                        {{-- {include file = '../_common/_form_select_place.tpl'} --}}
                        @include('rsv.common._form_select_place')
                    </td>
                </tr>
            @endif
            @if (array_key_exists('type', $search_condition['form']))
                <tr>
                    <th>
                        <div>表示方法</div>
                    </th>
                    <td>
                        <input id="list" name="type" type="radio" value=""{{ $search_condition['form']['type'] == 'list' ? 'checked' : '' }} />
                        <label for="list">テキストで表示</label>&nbsp;&nbsp;
                        <input id="map" name="type" type="radio" value="map"{{ $search_condition['form']['type'] == 'map' ? 'checked' : '' }} />
                        <label for="map">地図で表示</label>
                    </td>
                </tr>
            @endif
            <tr>
                <th>
                    <div>GoToトラベル<br>キャンペーン</div>
                </th>
                <td>
                    対象プランのみ表示<input id="goto" name="goto" type="checkbox" value="1" {{ array_key_exists('goto', $search_condition['form']) && $search_condition['form']['goto'] == '1' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        @if (!array_key_exists('hotel', $search_condition['form']) || !array_key_exists('hotel_cd', $search_condition['form']['hotel']))
            <div class="btn-b01-143-sb" style="margin:0 auto;">
                <input class="btnimg collectBtn" src="{{ asset('img/btn/b01-search1.gif') }}" type="image" alt="空室検索" />
            </div>
        @else
            <div class="btn-b06-138-sc" style="margin:0 auto;">
                <input class="btnimg collectBtn" src="{{ asset('img/btn/b06-booking.gif') }}" type="image" alt="予約へすすむ" />
            </div>
        @endif
        <input name="today" type="hidden" value="{{ date('Y-m-d') }}" />

        {{-- 施設・プラン --}}
        @if (array_key_exists('hotel', $search_condition['form']) && array_key_exists('hotel_cd', $search_condition['form']['hotel']))
            <input name="hotel_cd" type="hidden" value="{{ $search_condition['form']['hotel']['hotel_cd'] }}" />
        @endif
        @if (array_key_exists('hotel', $search_condition['form']) && array_key_exists('plan_id', $search_condition['form']['hotel']))
            <input name="plan_id" type="hidden" value="{{ $search_condition['form']['hotel']['plan_id'] }}" />
        @endif
        @if (array_key_exists('hotel', $search_condition['form']) && array_key_exists('room_id', $search_condition['form']['hotel']))
            <input name="room_id" type="hidden" value="{{ $search_condition['form']['hotel']['room_id'] }}" />
        @endif
    </form>
@endif
