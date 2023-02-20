<div align="center">
  <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:925px;">
    {{$views->room['room_nm']}}&nbsp;   
  
  </div>
  <p style="color:#ff0000; width:925px; text-align:left;">
    <small>
      ※()内は現在の予約数です。現在の予約数以下には設定できません。
    </small>
  </p>
  <br />
  <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th bgcolor="#808080" align="center" colspan="7">
        <font color="#ffffff">{{$views->form_params['calender_current_year']}}年{{$views->form_params['calender_current_month']}}月</font>
      </th>
    </tr>
    <tr align="center">
      <td bgcolor="#ffc0cb" width="120px">日</td>
      <td bgcolor="#c0c0c0" width="120px">月</td>
      <td bgcolor="#c0c0c0" width="120px">火</td>
      <td bgcolor="#c0c0c0" width="120px">水</td>
      <td bgcolor="#c0c0c0" width="120px">木</td>
      <td bgcolor="#c0c0c0" width="120px">金</td>
      <td bgcolor="#87cefa" width="120px">土</td>
    </tr>
  </table>
  <br />
    @foreach($views->calender as $key_week => $week)
      <table border="1" cellspacing="0" cellpadding="5">
        <tr>
            @foreach($week['days'] as $key_day => $day)
            <td align="center" bgcolor="@if($key_day == 0) #ffe4e1 @elseif($key_day == 6) #add8e6 @else #dcdcdc @endif" width="120px">
                @if($week['months'][$key_day] == $views->form_params['calender_current_month'])
                    {{date('m/j',$week['date_ymd'][$key_day])}}
                @else
                    <font color="#696969">{{date('m/j',$week['date_ymd'][$key_day])}}</font>
                @endif
            </td>
            @endforeach
        </tr>
        <tr>
            @foreach($week['days'] as $key_day => $day)
            <td align="center" bgcolor="@if($key_day == 0) #ffe4e1 @elseif($key_day == 6) #add8e6 @else #dcdcdc @endif" width="120px">
                @if($week['months'][$key_day] == $views->form_params['calender_current_month'])
                    {{$week['rooms'][$key_day]}}&nbsp;→&nbsp;{{$week['edit_rooms'][$key_day]}}
                    <input type="hidden" name="rooms_{{$week['date_ymd'][$key_day]}}" value="{{$week['edit_rooms'][$key_day]}}" />
                    <b>
                    @if(!is_null($week['reserve_rooms'][$key_day]))
                        ({{$week['reserve_rooms'][$key_day]}})
                    @elseif(is_null($week['rooms'][$key_day]))
                        (0)
                    @endif
                </b>
                @else
                　<br />
                @endif
            </td>
            @endforeach
        </tr>
      </table>
      <br />
    @endforeach
  <br />
  <div align="center">
    {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post', 'style' =>'display:inline;']) !!}
      <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
      <input type="hidden" name="start_ymd[year]"  value="{{date('Y',$views->form_params['current_ymd'])}}" />
      <input type="hidden" name="start_ymd[month]" value="{{date('m',$views->form_params['current_ymd'])}}" />
      <input type="hidden" name="start_ymd[day]"   value="{{date('j',$views->form_params['current_ymd'])}}" />
      <input type="submit" value="一覧へ" />
    {!! Form::close() !!}
  </div>
</div>
