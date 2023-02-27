        <tr>
          <td nowrap  bgcolor="#EEEEFF" >部屋名称</td>
          <td>
            @if(isset($is_jrset))
              <div>通常販売時の部屋名称</div>
              {$room->room_nm)}
              <br /><br />
              <div>JRコレクション販売時の部屋名称</div>
              @if(is_empty($room_jr->room_nm))
                <br />
              @else
                {$room_jr->room_nm)}
              @endif
            @else
              {{$room->room_nm}}
            @endif
          </td>
          <td><small><font color="#339933">予約ページに表示されます。</small></font></td>
        </tr>
        <tr>
          <td nowrap  bgcolor="#EEEEFF" >適用人数</td>
          <td>
            最小{{$room->capacity_min}}人
            <br />
            最大{{$room->capacity_max}}人
          </td>
          <td><br /></td>
        </tr>
        <tr>
          <td nowrap  bgcolor="#EEEEFF" >部屋タイプ</td>
          <td>@if($room->room_type == 0)カプセル
              @elseif($room->room_type == 1)シングル
              @elseif($room->room_type == 2)ツイン
              @elseif($room->room_type == 3)セミダブル
              @elseif($room->room_type == 4)ダブル
              @elseif($room->room_type == 5)トリプル
              @elseif($room->room_type == 6)４ベッド
              @elseif($room->room_type == 7)スイート
              @elseif($room->room_type == 8)メゾネット
              @elseif($room->room_type == 9)和室
              @elseif($room->room_type == 10)和洋室
              @elseif($room->room_type == 11)その他
              @endif
          </td>
          <td><br></td>
        </tr>
        <tr>
          <td nowrap  bgcolor="#EEEEFF" >広さ <small>最小～最大</small></td>
          <td>{{$room->floorage_min}} ～ {{$room->floorage_max}}
          </td>
          <td><br></td>
        </tr>
        <tr>
          <td nowrap  bgcolor="#EEEEFF" >広さ単位</td>
          <td>
            @if(!is_null($room->floor_unit) && $room->floor_unit == 0)
              平米
            @elseif($room->floor_unit == 1)
              畳
            @else
              予期せぬ値
            @endif
          </td>
          <td><br></td>
        </tr>