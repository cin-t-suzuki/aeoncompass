{{-- TODO: --}}

{{-- MEMO: 移植元 public\app\rsv\view2\_common\_form_search_today.tpl --}}

@if (count($search_condition['form']['rooms']) == 0)
    <div style="background:#ffffff;margin:0 auto 10px;width:300px;height:168px;padding:9px 8px 8px;">
        <div>ご指定のプランは満室となりましたので、ご予約いただけなくなりました。 他のプランをご検討ください。</div>
    </div>
@else
    {{-- 検索フォーム --}}
    <form class="jqs-query parseForm" method="get" action="/query/">
        <table border="0" cellpadding="0" cellspacing="0" width="322">
            <tr>
                <td class="info" colspan="2">今夜の宿（最大３０時まで受付）を予約いただけます。</td>
            </tr>
            <tr>
                <th>
                    <div>旅行日程</div>
                </th>
                <td>
                    {{-- @if ($set($search_condition['form']['midnight']['date_ymd'])) @endif --}}
                    <input name="year_month" type="hidden" value="{{ date('Y-m', $search_condition['form']['midnight']['date_ymd']) }}">
                    <input name="day" type="hidden" value="{{ date('d', $search_condition['form']['midnight']['date_ymd']) }}">
                    <span class="font-n" name="year_month_day">
                        {{ date('Y年n月j日', $search_condition['form']['midnight']['date_ymd']) }}（{{ date('j', $search_condition['form']['midnight']['date_ymd']) }}） より
                    </span>
                    <select class="text-right" name="stay" size="1">
                        @foreach ($search_condition['form']['stay'] as $stay)
                            <option value="{{ $stay['days'] }}" {{ $stay['current_status'] ? 'selected' : '' }}>
                                {{ $stay['days'] }}泊
                            </option>
                        @endforeach
                    </select>
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
                    @if ($search_condition['form']['childs']['accept_status'])
                        <a class="panelsw" name="guests-normal" href="">子供
                            <span name="children">
                                {{ $params['child1'] + $params['child2'] + $params['child3'] + $params['child4'] + $params['child5'] }}
                            </span>
                            名
                        </a>
                        {{-- {include file='../_common/_form_capacity.tpl' form_capacity_nm='guests-normal'} --}}
                        @include ('rsv.common._form_capacity', ['form_capacity_nm' => 'guests-normal'])
                    @endif
                </td>
            </tr>
            {{-- 地図 --}}
            @if (!array_key_exists('hotel', $search_condition['form']) || !array_key_exists('hotel_cd', $search_condition['form']['hotel']) && !array_key_exists('hotel', $search_condition['form']) || !array_key_exists('title', $search_condition['form']['hotel']))
                <tr>
                    <th>
                        <div class="div-h">地域</div>
                    </th>
                    <td>
                        {{-- {include file='../_common/_form_select_place.tpl'} --}}
                        @include('rsv.common._form_select_place')
                    </td>
                </tr>
            @endif
            {{--
                MEMO: HACK: 工数次第で対応
                    移植元では、 $search_condition['form']['type'] が unset されている。
                    何かしらの歴史的経緯があるものと思われる。
                    不要であれば、分岐削除できるか？
            --}}
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
        @if (array_key_exists('hotel', $search_condition['form']) && array_key_exists('hotel_cd', $search_condition['form']['hotel']) )
            <input name="hotel_cd" type="hidden" value="{{ $search_condition['form']['hotel']['hotel_cd'] }}" />
        @endif
        @if (array_key_exists('hotel', $search_condition['form']) && array_key_exists('plan_id', $search_condition['form']['hotel']) )
            <input name="plan_id" type="hidden" value="{{ $search_condition['form']['hotel']['plan_id'] }}" />
        @endif
        @if (array_key_exists('hotel', $search_condition['form']) && array_key_exists('room_id', $search_condition['form']['hotel']) )
            <input name="room_id" type="hidden" value="{{ $search_condition['form']['hotel']['room_id'] }}" />
        @endif
    </form>
@endif
