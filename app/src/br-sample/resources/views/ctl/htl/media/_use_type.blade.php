{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_use_type.tpl --}}

{{-- 外観 --}}
@if ($is_use['hotel'])
    外観<br />
@endif
{{-- 地図 --}}
@if ($is_use['map'])
    地図<br />
@endif
{{-- その他 --}}
@if ($is_use['other'])
    フォトギャラリー<br />
@endif
{{-- 部屋 --}}
@if ($is_use['room'])
    部屋<br />
@endif
{{-- プラン --}}
@if ($is_use['plan'])
    プラン<br />
@else
    <br />
@endif
