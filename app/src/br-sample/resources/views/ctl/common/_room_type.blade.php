@if($room_type === 0)カプセル
@elseif($room_type === 1)シングル
@elseif($room_type === 2)ツイン
@elseif($room_type === 3)セミダブル
@elseif($room_type === 4)ダブル
@elseif($room_type === 5)トリプル
@elseif($room_type === 6)４ベッド
@elseif($room_type === 7)スイート
@elseif($room_type === 8)メゾネット
@elseif($room_type === 9)和室
@elseif($room_type === 10)和洋室
@elseif($room_type === 11)その他
@elseif($room_type === 99)未選択
@else<br />
@endif