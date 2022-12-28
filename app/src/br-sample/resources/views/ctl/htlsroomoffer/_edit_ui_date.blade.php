{!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post', 'style' =>'display:inline;', 'name' =>'back_form']) !!}
  <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
  <input type="hidden" name="start_ymd[year]"  value="{{date("Y", $views->form_params['current_ymd'])}}" />
  <input type="hidden" name="start_ymd[month]" value="{{date("m", $views->form_params['current_ymd'])}}" />
  <input type="hidden" name="start_ymd[day]"   value="{{date("j", $views->form_params['current_ymd'])}}" />
{!! Form::close() !!}

{!! Form::open(['route' => ['ctl.htlsroomoffer.confirm'], 'method' => 'post', 'style' =>'display:inline;', 'name' =>'confirm_form']) !!}
    <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
    <input type="hidden" name="ui_type"   value="date" />
    <input type="hidden" name="date_ymd"  value="{{$views->form_params['date_ymd']}}" />
    <input type="hidden" name="current_ymd" value="{{$views->form_params['current_ymd']}}" />

    <div align="center">
        <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:860px;">
            {{date('Y年m月j日',$views->disp_date['target_date'])}}（{{$views->disp_date['week_day']}}）の提供室数一覧
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
            <input id="none" type="radio" name="accept_status" value="" checked="checked" /><label for="none">ステータスを変更しない</label>
            <input id="batch_sale" type="radio" name="accept_status" value="1" @if($views->form_params['accept_status'] == 1) checked @endif/><label for="batch_sale">一括販売</label>
            <input id="batch_stop" type="radio" name="accept_status" value="0" @if($views->form_params['accept_status'] == 0 && !empty($views->form_params['accept_status'])) checked @endif/><label for="batch_stop">一括売止（キャンセル再販なし）</label>
            &nbsp;
            <input id="batch_zero" type="checkbox" name="remainder_room_zero" value="1" @if($views->form_params['remainder_room_zero'] == 1) checked @endif/><label for="batch_zero">一括残室数0</label>
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
                    {{$room['room_nm']}}@if ($room['is_room_akf'])<span style="color:#ff0000;">[日本旅行連動在庫]</span> @endif
                    <input type="hidden" name="room_id[]" value="{{$room_id}}" />
                </td>
                <td bgcolor="#f5f5f5">
                    @if (!$room['is_room_akf'])
                    変更前&nbsp;<b>{{$room['room_count']['rooms']}}</b>室&nbsp;→&nbsp;<input type="text" name="rooms[{{$room_id}}]" value="{{$views->form_params['rooms'][$room_id]}}"  size="4" maxlength="3" />室
                    @else
                    変更前&nbsp;<b>{{$room['room_count']['rooms']}}</b>室&nbsp;→&nbsp;{{$views->form_params['rooms'][$room_id]}}室<input type="hidden" name="rooms[{{$room_id}}]" value="{{$views->form_params['rooms'][$room_id]}}" />
                    @endif
                </td>
                <td bgcolor="#f5f5f5" align="center"><b>@if($room['room_count']['reserve_rooms']){{$room['room_count']['reserve_rooms']}}@else 0 @endif 室</b></td>
                </tr>
            @endforeach
        </table>
    </div>
{!! Form::close() !!}
<br />
<div align="center">
  <input type="button" value="戻る" onClick="select_submit('back_form');return false;" />
  <input type="button" value="確認" onClick="select_submit('confirm_form');return false;" />
  <br clear="all" />
</div>