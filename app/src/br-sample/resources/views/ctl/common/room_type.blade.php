{{-- MEMO: 移植元 public/app/ctl/view2/_common/_room_type.tpl --}}

@if ($room_type === \App\Models\Room2::ROOM_TYPE_CAPSULE)
    カプセル
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_SINGLE)
    シングル
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_TWIN)
    ツイン
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_SEMI_DOUBLE)
    セミダブル
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_DOUBLE)
    ダブル
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_TRIPLE)
    トリプル
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_4_BED)
    ４ベッド
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_SWEET)
    スイート
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_MAISONNETTE)
    メゾネット
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_JAPANESE_STYLE)
    和室
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_JAPANESE_AND_WESTERN_STYLES)
    和洋室
@elseif ($room_type === \App\Models\Room2::ROOM_TYPE_OTHER)
    その他
@elseif ($room_type === "99")
    未選択
@else
    <br />
@endif