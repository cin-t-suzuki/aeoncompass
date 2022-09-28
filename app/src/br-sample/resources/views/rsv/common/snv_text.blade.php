<div class="snv-text">
    <ul class="snv-text-l5">
        @if($current == "plan")
            <li class="current">プラン・予約</li>
        @else
            <li><a href="{{-- {$v->env.path_base}/plan/{$v->hotel.hotel_cd}/ --}}">プラン・予約</a></li>
        @endif
        @if($current == "hotel")
            <li class="current">宿泊施設</li>
        @else
            <li><a href="{{-- {$v->env.path_base}/hotel/{$v->hotel.hotel_cd}/ --}}">宿泊施設</a></li>
        @endif
        @if($current == "gallery")
            <li class="current">フォトギャラリー</li>
        @else
            <li><a href="{{-- {$v->env.path_base}/gallery/{$v->hotel.hotel_cd}/ --}}">フォトギャラリー</a></li>
        @endif
        @if($current == "access")
            <li class="current">アクセス</li>
        @else
            <li><a href="{{-- {$v->env.path_base}/access/{$v->hotel.hotel_cd}/ --}}">アクセス</a></li>
        @endif
        @if($current == "voice")
            <li class="current">クチコミ</li>
        @else
            <li><a href="{{-- {$v->env.path_base}/voice/{$v->hotel.hotel_cd}/ --}}">クチコミ</a></li>
        @endif
    </ul>
</div>