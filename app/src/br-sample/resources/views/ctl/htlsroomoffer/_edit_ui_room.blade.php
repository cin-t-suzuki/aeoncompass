{!! Form::open(['route' => ['ctl.htlsroomoffer.list'], 'method' => 'post', 'style' =>'display:inline;','name' => 'back_form']) !!}
    <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
    <input type="hidden" name="start_ymd[year]"  value="{{date('Y',$views->form_params['current_ymd'])}}" />
    <input type="hidden" name="start_ymd[month]" value="{{date('m',$views->form_params['current_ymd'])}}" />
    <input type="hidden" name="start_ymd[day]"   value="{{date('j',$views->form_params['current_ymd'])}}" />
{!! Form::close() !!}

{!! Form::open(['route' => ['ctl.htlsroomoffer.confirm'], 'method' => 'post', 'style' =>'display:inline;' , 'name' => 'confirm_form']) !!}
    <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
    <input type="hidden" name="ui_type" value="{{$views->form_params['ui_type']}}" />
    <input type="hidden" name="room_id" value="{{$views->room['room_id']}}" />
    <input type="hidden" name="date_ymd" value="{{$views->form_params['date_ymd']}}" />
    <input type="hidden" name="current_ymd" value="{{$views->form_params['current_ymd']}}" />
<div align="center">
    <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:860px;">
        {{$views->room['room_nm']}} @if($views->is_room_akf)&nbsp;<span style="color: #ff0000;">[日本旅行連動在庫庫]</span>@endif
    </div>
    <p style="color:#ff0000; width:860px; text-align:left;">
    <small>
        ※現在の予約数以下には設定できません。
        @if($views->is_room_akf) 
        <br />※日本旅行連動在庫庫の為、在庫数は操作できません。
        @endif
    </small>
    </p>
    <div style="width:750px; margin:0 auto;">
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">販売ステータス</span>
    </p>
    <div style="text-align:left;">
        <input id="none" type="radio" name="accept_status" value="" checked="checked" /><label for="none">ステータスを変更しない</label>
        <input id="batch_sale" type="radio" name="accept_status" value="1" @if($views->form_params['accept_status'] == 1) checked @endif/><label for="batch_sale">販売</label>
        <input id="batch_stop" type="radio" name="accept_status" value="0" @if($views->form_params['accept_status'] == 0 && !empty($views->form_params['accept_status'])) checked @endif/><label for="batch_stop">売止（キャンセル再販なし）</label>
        @if(!$views->is_room_akf && $views->form_params['ui_type'] == 'room')
            &nbsp;
            <input id="batch_zero" type="checkbox" name="remainder_room_zero" value="1" @if($views->form_params['remainder_room_zero'] == 1 ) checked @endif/><label for="batch_zero">残室数を0にする</label>
        @endif
    </div>
    <br />
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">期間の設定</span>
    </p>
    <div style="text-align:left;">
        <select name="date_ymd_from_year">
            @for($from_year = $views->start_date['year']; $from_year < $views->end_date['year']+1; $from_year++)
                <option value="{{$from_year}}" @if($from_year == $views->form_params['date_ymd_from_year']) selected @endif>{{$from_year}}</option>
            @endfor
        </select>年
        &nbsp;
        <select name="date_ymd_from_month">
            @for($from_month=1; $from_month <= 12; $from_month++)
                <option value="{{$from_month}}" @if($from_month == $views->form_params['date_ymd_from_month']) selected @endif>{{$from_month}}</option>
            @endfor
        </select>月
        &nbsp;
        <select name="date_ymd_from_day">
            @for($from_day=1; $from_day <= 31; $from_day++)
                <option value="{{$from_day}}" @if($from_day == $views->form_params['date_ymd_from_day']) selected @endif>{{$from_day}}</option>
            @endfor
        </select>日
        &nbsp;～&nbsp;
        <select name="date_ymd_to_year">
            @for($to_year = $views->start_date['year']; $to_year < $views->end_date['year']+1; $to_year++)
                <option value="{{$to_year}}" @if($to_year == $views->form_params['date_ymd_to_year']) selected @endif>{{$to_year}}</option>
            @endfor
        </select>年
        &nbsp;
        <select name="date_ymd_to_month">
            @for($to_month=1; $to_month <= 12; $to_month++)
                <option value="{{$to_month}}" @if($to_month == $views->form_params['date_ymd_to_month']) selected @endif>{{$to_month}}</option>
            @endfor
        </select>月
        &nbsp;
        <select name="date_ymd_to_day">
            @for($to_day=1; $to_day <= 31; $to_day++)
                <option value="{{$to_day}}" @if($to_day == $views->form_params['date_ymd_to_day']) selected @endif>{{$to_day}}</option>
            @endfor
        </select>日
    </div>
    @if($views->form_params['ui_type'] == 'room')
    <br />
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">提供室数</span>
    </p>    
    <div style="text-align:left;">
        <table border="1" cellspacing="0" cellpadding="5" style="table-layout:fixed; width:640px;">
            <tr>
                <td bgcolor="#dcdcdc" align="center">変更前</td>
                <td bgcolor="#f5f5f5" align="center">変更後</td>
            </tr>
            <tr>
            <td bgcolor="" align="center">
                <b>@if($views->room['room_count']['rooms']) {{$views->room['room_count']['rooms']}} @else 0 @endif</b>室
            </td>
            <td align="center">
                @if($views->is_room_akf)
                    <input type="hidden" name="rooms" value="{{$views->room['room_count']['rooms']}}" />{{$views->room['room_count']['rooms']}}室
                @else
                    <input type="text" name="rooms" value="{{$views->room['room_count']['rooms']}}"  size="3" />&ensp;室
                @endif
            </td>
            </tr>
        </table>
    </div>
    @endif
</div>
{!! Form::close() !!}
<br />
<div align="center" style="margin-top:20px;">
<div style="width:190px; margin: 0 auto; text-align: left;">
    <input type="button" value="戻る" onClick="select_submit('back_form');return false;" />
    <input type="button" value="確認" onClick="select_submit('confirm_form');return false;" />
</div>
</div>