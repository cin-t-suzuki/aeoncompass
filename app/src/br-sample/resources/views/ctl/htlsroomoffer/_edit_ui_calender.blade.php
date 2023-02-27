
<script language="javascript"  type="text/javascript">
  <!--
    $(document).ready(function () {
  
      $('input[name^="copy_nextweek"]').click(function () {
  
        var click_button = $(this).attr('name');
        $('.jqs-week').each( function() {
          if (click_button == $(':button', $(this)).attr('name')) {
            var week = click_button.split('_');
            parseInt(week[2]++);
            if ($('.jqs-week-0', $(this)).val() != undefined) { $('.jqs-week-0', '.jqs-week-' + week[2]).val($('.jqs-week-0', $(this)).val());}
            if ($('.jqs-week-1', $(this)).val() != undefined) { $('.jqs-week-1', '.jqs-week-' + week[2]).val($('.jqs-week-1', $(this)).val());}
            if ($('.jqs-week-2', $(this)).val() != undefined) { $('.jqs-week-2', '.jqs-week-' + week[2]).val($('.jqs-week-2', $(this)).val());}
            if ($('.jqs-week-3', $(this)).val() != undefined) { $('.jqs-week-3', '.jqs-week-' + week[2]).val($('.jqs-week-3', $(this)).val());}
            if ($('.jqs-week-4', $(this)).val() != undefined) { $('.jqs-week-4', '.jqs-week-' + week[2]).val($('.jqs-week-4', $(this)).val());}
            if ($('.jqs-week-5', $(this)).val() != undefined) { $('.jqs-week-5', '.jqs-week-' + week[2]).val($('.jqs-week-5', $(this)).val());}
            if ($('.jqs-week-6', $(this)).val() != undefined) { $('.jqs-week-6', '.jqs-week-' + week[2]).val($('.jqs-week-6', $(this)).val());}
          }
        });
       });
    });
  //-->
  </script>

<div align="center">
    <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:925;">
      {{$views->room['room_nm']}}
      @if ($views->is_room_akf) &nbsp;<span style="color: #ff0000;">[日本旅行連動在庫]</span> @endif
    </div>
    <p style="color:#ff0000; width:925; text-align:left;">
      <small>
        ※()内は現在の予約数です。<br />
        ※現在の予約数以下には設定できません。
        @if($views->is_room_akf)
        <br />※日本旅行連動在庫の為、在庫数は操作できません。
        @endif
      </small>
    </p>
    <br />
    <table border="1" cellspacing="0" cellpadding="5">
      <tr>
        <th bgcolor="#808080" align="center" colspan="7">
         {!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post', 'style' =>'display:inline; float:center;']) !!}
            <select name="calender_current_year">
                @for($i = $views->start_date['year']; $i < $views->end_date['year']+1; $i++)
                  <option value="{{$i}}" @if($i == $views->form_params['calender_current_year']) selected @endif>{{$i}}</option>
                @endfor
            </select><font color="#ffffff">年</font>
            &nbsp;
            <select name="calender_current_month">
                @for($i=1; $i <= 12; $i++)
                  <option value="{{$i}}" @if($i == $views->form_params['calender_current_month']) selected @endif>{{$i}}</option>
                @endfor
            </select><font color="#ffffff">月</font>
            <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
            <input type="hidden" name="ui_type" value="calender" />
            <input type="hidden" name="room_id" value="{{$views->room['room_id']}}" />
            <input type="hidden" name="date_ym" value="{{$views->form_params['date_ym']}}" />
            <input type="hidden" name="current_ymd" value="{{$views->form_params['current_ymd']}}" />
            <input type="submit" value="切替" />
          {!! Form::close() !!}
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
    {!! Form::open(['route' => ['ctl.htlsroomoffer.confirm'], 'method' => 'post', 'style' => 'display:inline; float:center;', 'name' => 'edit_form']) !!}
        <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
        <input type="hidden" name="ui_type" value="calender" />
        <input type="hidden" name="room_id" value="{{$views->room['room_id']}}" />
        <input type="hidden" name="date_ym" value="{{$views->form_params['date_ym']}}" />
        <input type="hidden" name="current_ymd" value="{{$views->form_params['current_ymd']}}" />

        @php
            $n_week = 0;
        @endphp
        @foreach($views->calender as $key_week => $week)
        @php
            $n_week = $n_week +1;
        @endphp
            <table border="1" cellspacing="0" cellpadding="5" class="jqs-week jqs-week-{{$n_week}}">
                <tr>
                    @foreach($week['days'] as $key_day => $day)
                        <td align="center" bgcolor="@if($key_day == 0)#ffe4e1 @elseif($key_day == 6)#add8e6 @else #dcdcdc @endif" width="120px">
                            @if($week['months'][$key_day] == $views->form_params['calender_current_month'])
                                {{date('m/j',$week['date_ymd'][$key_day])}}
                            @else
                            <font color="#696969">{{date('m/d',$week['date_ymd'][$key_day])}}</font>
                            @endif
                    @endforeach
                </tr>
                <tr>
                    @foreach($week['days'] as $key_day => $day)
                        <td align="center" bgcolor="@if($key_day == 0) #ffe4e1 @elseif($key_day == 6) #add8e6 @else #dcdcdc @endif" width="120px">
                            @if($week['months'][$key_day] == $views->form_params['calender_current_month'])
                                {{-- 編集不可のとき --}}
                                @if(!$week['is_edit'][$key_day] || $views->is_room_akf)
                                    <input type="hidden" name="rooms_{{$week['date_ymd'][$key_day]}}" value="{{$week['edit_rooms'][$key_day]}}" />{{$week['edit_rooms'][$key_day]}}
                                @else
                                    <input type="text" size="4" name="rooms_{{$week['date_ymd'][$key_day]}}" value="{{$week['edit_rooms'][$key_day]}}"  class="jqs-week-{{$key_day}}" maxlength="3" />
                                @endif
                                &nbsp;
                                <b>
                                @if (!is_null($week['reserve_rooms'][$key_day]))
                                  ({{$week['reserve_rooms'][$key_day]}})
                                @elseif(is_null($week['rooms'][$key_day]))
                                  (0)
                                @endif
                                </b>
                            @else
                            　&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </td>
                    @endforeach
                </tr>
                @if(!$loop->last)
                    @if(!$views->is_room_akf)
                    <tr>
                        <td align="center" colspan="7" bgcolor="#cdcdcd">
                            <input type="button" name="copy_nextweek_{{$n_week}}" id = "copy_nextweek" value="上記の週の提供個室数を次の週にコピー▼" />
                        </td>
                    </tr>
                    @endif
                @endif
            </table>
            <br />
        @endforeach
        <br />
    {!! Form::close() !!}
</div>
<div align="center">
    {!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post', 'style' => 'display:inline; float:center;']) !!}
        <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
        <input type="hidden" name="start_ymd[year]"  value="{{date('Y',$views->form_params['current_ymd'])}}" />
        <input type="hidden" name="start_ymd[month]" value="{{date('m',$views->form_params['current_ymd'])}}" />
        <input type="hidden" name="start_ymd[day]"   value="{{date('j',$views->form_params['current_ymd'])}}" />
        <input type="submit" value="戻る" />
    {!! Form::close() !!}

    @if(!$views->is_room_akf)
        <input type="button" value="確認" onClick="select_submit('edit_form');return false;" />
    @endif
    <br clear="all" />
</div>  