{{-- MEMO: 移植元 svn_trunk\public\app\ctl\views\_common\_hotel_stations->tpl --}}

{{-- HACK: magic number --}}
@if (count($hotel_stations) != 0)
    @if ($limit != false)
        @for ($limit_cnt = 0; $limit_cnt < $limit && $limit_cnt < count($hotel_stations); $limit_cnt++)
            @if (!$service->is_empty($hotel_stations[$limit_cnt]->traffic_way))
                @if ($hotel_stations[$limit_cnt]->traffic_way == 0)
                    @if ($hotel_stations[$limit_cnt]->minute == 0)
                        すぐ ← {{ strip_tags($hotel_stations[$limit_cnt]->route_nm) }}
                        {{ strip_tags($hotel_stations[$limit_cnt]->station_nm) }}<br />
                    @elseif ($hotel_stations[$limit_cnt]->minute > 0)
                        徒歩 {{ strip_tags($hotel_stations[$limit_cnt]->minute) }}分 ←
                        {{ strip_tags($hotel_stations[$limit_cnt]->route_nm) }}
                        {{ strip_tags($hotel_stations[$limit_cnt]->station_nm) }}<br />
                    @endif
                @elseif ($hotel_stations[$limit_cnt]->traffic_way == 1)
                    車 {{ strip_tags($hotel_stations[$limit_cnt]->minute) }}分 ←
                    {{ strip_tags($hotel_stations[$limit_cnt]->route_nm) }}
                    {{ strip_tags($hotel_stations[$limit_cnt]->station_nm) }}<br />
                @endif
            @endif
        @endfor
    @else
        @foreach ($hotel_stations as $hotel_station)
            @if (($hotel_station->traffic_way == 0))
                @if ($hotel_station->minute == 0)
                    すぐ ← {{ strip_tags($hotel_station->route_nm) }}
                    {{ strip_tags($hotel_station->station_nm) }}<br />
                @else
                    徒歩 {{ strip_tags($hotel_station->minute) }}分 ←
                    {{ strip_tags($hotel_station->route_nm) }}
                    {{ strip_tags($hotel_station->station_nm) }}<br />
                @endif
            @elseif (($hotel_station->traffic_way == 1))
                車 {{ strip_tags($hotel_station->minute) }}分 ←
                {{ strip_tags($hotel_station->route_nm) }}
                {{ strip_tags($hotel_station->station_nm) }}<br />
            @endif
        @endforeach
    @endif
@else
<br>
@endif
