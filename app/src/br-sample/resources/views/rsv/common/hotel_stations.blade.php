<div class="hi-station">
    @if(count($hotel_stations.values) != 0)
        @foreach ($hotel_stations.values as $hotel_station)
            @if($hotel_station->traffic_way == 0)
                {if   $hotel_station.minute == 0}すぐ ← {$hotel_station.route_nm}{$hotel_station.station_nm}<br />
                {else}徒歩 {$hotel_station.minute}分 ← {$hotel_station.route_nm}{$hotel_station.station_nm}<br />
                {/if}
            @elseif($hotel_station->traffic_way == 1)
                車 {{ $hotel_station->minute }}分 ← {{ $hotel_station->route_nm }}{{ $hotel_station->station_nm }}<br />
            @endif
        @endforeach
    @endif
            {if isset($v->hotel.agoda_local.values)}
                {foreach from=$v->hotel.agoda_local.values item=item_local}
                    {if $item_local.type == 1}
                        <span style="display:inline-block; width: 160px; text-align: left;">{$item_local.name}</span>
                        <span>{$item_local.distance}{$item_local.distance_unit}</span>
                        <br/>
                    {/if}
                {/foreach}
            {/if}
</div>
