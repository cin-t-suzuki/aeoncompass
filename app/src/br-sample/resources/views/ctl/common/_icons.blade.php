{{-- アイコン表示制御  --}}

{{-- @if (($icons->new != ""))
    <img alt="新着ホテル" src="/images/qi/new.gif" hspace="1" width="38" height="11" />
@endif --}}

{{-- 以下 ?? null追記でいいか？ --}}
@if (($value->icons['power'] ?? null) == true)
    <img alt="パワープラン" src="/images/qi/ph.gif" hspace="1" width="38" height="11" />
@endif
@if (($value->icons['best'] ?? null) == true)
    <img alt="ベストリザーブ提供" src="/images/qi/best.gif" hspace="1" width="38" height="11" />
@endif
@if (($value->icons['stay_limit'] ?? null) == true)
    <img alt="連泊プラン@if ( $value->stay_limit > 1)（{{$value->stay_limit}}泊以上）@endif" src="/images/qi/sl @if ($value->stay_limit > 1){{$value->stay_limit}}@endif.gif" hspace="1" width="38" height="11" title="連泊プラン@if ($value->stay_limit > 1)（{{$value->stay_limit}}泊以上）@endif"/>
@endif
@if (($value->icons['fss'] ?? null) == true)
    <img alt="金土日プラン" src="/images/qi/fss.gif" hspace="1" width="38" height="11" />
@endif
@if (($value->icons['camp'] ?? null) == true)
    <img alt="キャンペーン" src="/images/qi/camp.gif" hspace="1" width="48" height="11" />
@endif

{{-- アイコン表示制御  --}}