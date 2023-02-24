<div align="center">
  <div style="background-color:#fffacd; text-align:left; padding: 5px 0px 5px 0px; width:860px;">
    {{$views->room['room_nm']}} @if($views->is_room_akf)&nbsp;<span style="color: #ff0000;">[日本旅行連動在庫]</span>@endif
  </div>
  <p style="color:#ff0000; width:860px; text-align:left;">
    <small>
      @if ($views->is_room_akf)
        <br />※日本旅行連動在庫の為、在庫数は操作できません。
      @endif
    </small>
  </p>
  
  <div style="width:460px; margin:0 auto;">
     <p style="text-align:left; font-size:80%; margin-bottom: 8px; font-weight:bold; color: #1a791f">
        更新が完了しました。
    </p>
    <br />
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
    <font color="#cdcdcd">■</font><span style="margin-left: 3px;">販売ステータス（一括）</span>
    </p>
    <div style="text-align:left;">
      @if($views->form_params['accept_status'] == 0)　売止
      @elseif($views->form_params['accept_status'] == 1)　販売
      @else　ステータスを変更しない
      @endif
    </div>
    <br />
    <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
      <font color="#cdcdcd">■</font><span style="margin-left: 3px;">設定期間</span>
    </p>
    <div style="text-align:left;">
      　　{{$views->form_params['from_date']}}～{{$views->form_params['to_date']}}
        (<b>{{$views->form_params['setting_period']}}</b>日間)
    </div>
    @if ($views->form_params['ui_type'] == 'room')
      <br />
      <p style="text-align:left; font-size:85%; margin-bottom: 8px; font-weight:bold;">
        <font color="#cdcdcd">■</font><span style="margin-left: 3px;">変更後の提供室数</span>
      </p>
      <div style="text-align:left;">
            　　{{$views->form_params['rooms']}}室
              　<br />
               <br />
               @if ($views->form_params['setting_period'] == 1)
                 <p style="font-size: 80%; color:#ff0000">　※予約が存在する場合は予約数以下に提供室数は設定されません。<br />　　「一覧」よりお確かめください。</p>
               @else
                <p style="font-size: 80%; color:#ff0000">　※予約が存在する日については予約数以下に提供室数は設定されません。<br />　　「一覧」よりお確かめください。</p>
               @endif
      </div>
    @endif
  </div>
  <div style="margin-top: 50px;">
     <div style="width:300px; margin:0 auto;">
        {!! Form::open(['route' => ['ctl.htlsroomoffer.index'], 'method' => 'post', 'style' =>'display:inline;']) !!}
          <input type="hidden" name="target_cd"        value="{{$views->form_params['target_cd']}}" />
          <input type="hidden" name="start_ymd[year]"  value="{{date('Y',$views->form_params['current_ymd'])}}" />
          <input type="hidden" name="start_ymd[month]" value="{{date('m',$views->form_params['current_ymd'])}}" />
          <input type="hidden" name="start_ymd[day]"   value="{{date('j',$views->form_params['current_ymd'])}}" />
          <input type="submit" value="一覧へ" />
        {!! Form::close() !!}
     </div>
  </div>
</div>
