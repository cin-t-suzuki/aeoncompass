{!! Form::open(['route' => ['ctl.htlsroomoffer.edit'], 'method' => 'post', 'style' =>'display:inline;', 'name' => 'edit_form']) !!}
  <input type="hidden" name="ui_type" value="room" />
  <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
  @foreach($views->form_params as $name => $value)
    <input type="hidden" name="{{$name}}" value="{{$value}}" />
  @endforeach
{!! Form::close() !!}

{!! Form::open(['route' => ['ctl.htlsroomoffer.update'], 'method' => 'post', 'style' =>'display:inline;', 'name' => 'update_form']) !!}
  <input type="hidden" name="ui_type" value="room" />
  <input type="hidden" name="target_cd" value="{{$views->form_params['target_cd']}}" />
  @foreach($views->form_params as $name => $value)
    <input type="hidden" name="{{$name}}" value="{{$value}}" />
  @endforeach
{!! Form::close() !!}

<div align="center">
  <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:860px;">
    {{$views->room['room_nm']}}@if($views->is_room_akf)&nbsp;<span style="color: #ff0000;">[日本旅行連動在庫]</span>@endif
  </div>
  <p style="color:#ff0000; width:860px; text-align:left;">
    <small>
      ※現在の予約数以下には設定できません。
      @if($views->is_room_akf)
        <br />※日本旅行連動在庫の為、在庫数は操作できません。
      @endif
    </small>
  </p>
  <div style="width:320px; margin:0 auto;">
    <p style="text-align:left; font-size:80%; margin-bottom: 8px; font-weight:bold; color: #ff0000;">
        以下の内容で更新してよろしいですか？
    </p>
    <br />
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">販売ステータス（一括）</span>
    </p>
    <div style="text-align:left;">
      @if($views->form_params['accept_status'] == 0)
        <span style="font-weight:bolder;">売止</span>
        @elseif($views->form_params['accept_status'] == 1)
        <span style="font-weight:bolder;">販売</span>
      @else
        ステータスを変更しない
      @endif
    </div>
    <br/>
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">設定期間</span>
    </p>
    <div style="text-align:left;">
      {{$views->form_params['from_date']}} ～ {{$views->form_params['to_date']}}
         (<b>{{$views->form_params['setting_period']}}</b>日間)
    </div>
    @if($views->form_params['ui_type'] == 'room')
    <br />
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">提供室数</span>
    </p>
    <div style="text-align:left;">
         　　{{$views->form_params['rooms']}}室
    </div>
    @endif
  </div>
  <div style="margin-top: 50px;">
      <div style="width:300px; margin:0 auto;">
        <input type="button" value="修正" onClick="select_submit('edit_form');return false;" />
        <input type="button" value="更新" onClick="select_submit('update_form');return false;" />
        <br clear="all" />
    </div>
  </div>
</div>