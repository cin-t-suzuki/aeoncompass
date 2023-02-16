<div align="center">
  <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:860px;">
    {{date('Y/m/j',$views->disp_date['target_date'])}}（{{$views->disp_date['week_day']}}）の提供室数一覧
  </div>
  <p style="color:#ff0000; width:860px; text-align:left;">
    <small>
      ※現在の予約数以下には設定できません。
       @if(true)
        <br />※日本旅行連動在庫の在庫数は操作できません。
      @endif
    </small>
  </p>
  <p style="width:860px; text-align:left;">
    <font color="#cdcdcd">■</font>販売ステータス（一括）
  </p>
  <div style="width:860px; text-align:left;">
    @if($views->form_params['accept_status'] == 0)一括売止
    @elseif($views->form_params['accept_status'] == 1)一括販売
    @else ステータスを変更しない
    @endif
  </div>
  <br />
  <table border="1" cellspacing="0" cellpadding="5" width="860px;">
    <tr>
      <td bgcolor="#ffdab9">
        <b>部屋タイプ名</b>
        <br />
        現在登録中の部屋タイプ一覧です。
      </td>
      <td bgcolor="#dcdcdc">
        <b>提供室数</b>
      </td>
      <td bgcolor="#dcdcdc">
        <b>予約室数</b>
      </td>
    </tr>
    @foreach($views->rooms as $room_id => $room)
      <tr>
        <td bgcolor="#ffefd5">
          {{$room['room_nm']}}@if($room['is_room_akf'])<span style="color:#ff0000;">[日本旅行連動在庫]</span>@endif
          <input type="hidden" name="room_id[]" value="{{$room_id}}" />
        </td>
        <td bgcolor="#f5f5f5">
          <b>{{$views->form_params['rooms'][$room_id]}}</b>室
        </td>
        <td bgcolor="#f5f5f5" align="center"><b>@if($room['room_count']['reserve_rooms']) {{$room['room_count']['reserve_rooms']}} @else 0 @endif 室</b></td>
      </tr>
    @endforeach
  </table>
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
