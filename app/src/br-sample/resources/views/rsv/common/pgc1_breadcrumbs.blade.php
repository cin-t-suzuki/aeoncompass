<dl class="pgc1-breadcrumbs">
    <dd>
        @if (!empty($hotel))
            <span>
                <a href="{{-- {$v->env.path_base}/list/{$v->hotel.pref_cd}/ --}}">{{ $hotel->pref_nm }}</a>
            </span> &gt;
            <span>
                <a href="{{-- {$v->env.path_base}/list/{$v->hotel.pref_cd}/{$v->hotel.city_cd}/ --}}">{{ $hotel->city_nm }}</a>
            </span> &gt;
            @if (!empty($hotel->ward_cd))
            <span>
                <a href="{{-- {$v->env.path_base}/list/{$v->hotel.pref_cd}/{$v->hotel.city_cd}/{$v->hotel.ward_cd}/ --}}">{{ $hotel->ward_nm }}</a>
            </span> &gt;
            @endif
            <span class="current">{{ strip_tags($hotel->hotel_nm) }}</span>
        @endif
    </dd>
</dl>