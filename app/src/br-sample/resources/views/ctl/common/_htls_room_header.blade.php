  <script type="text/javascript">
    <!--
      $(document).ready(function () {
        // TODO cookie_path書き変え
        var cookie_path = '{/literal}{$v->env.source_path}{$v->env.module}/{literal}';
        var cookie_expert_menu = $.cookies.get('EXPERT');

        if (cookie_expert_menu == 'on') {
          $('#expert_menu_on').hide();
          $('.jqs-expert_menu').show();
        } else {
          $('#expert_menu_off').hide();
          $('.jqs-expert_menu').hide();
        }

        $('#expert_menu_on').click(function () {
          $.cookies.set('EXPERT', 'on', {path: cookie_path});
          $('#expert_menu_off').show();
          $('#expert_menu_on').hide();
          $('.jqs-expert_menu').show();
        })

        $('#expert_menu_off').click(function () {
          $.cookies.del('EXPERT', {path: cookie_path});
          $('#expert_menu_on').show();
          $('#expert_menu_off').hide();
          $('.jqs-expert_menu').hide();
        })

      });
    // -->
  </script>
  <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>

  <br /><br /><br /><br />
<table border="0" style="float:left;">
  <tr>
    <td>
      @if (Route::currentRouteName() === 'ctl.htlsroomoffer')
        【室数・料金・期間の調整】
      @else
        {!! Form::open(['route' => ['ctl.htlsroomoffer.index'], 'method' => 'post','style' => 'display:inline;']) !!}
          <input type="hidden" name="target_cd"        value="{{$target_cd}}" />
          <input type="hidden" name="partner_group_id" value="{{$partner_group_id}}" />
          <input type="submit" value="室数・料金・期間の調整" />
        {!! Form::close() !!}
      @endif
    </td>
    <td>
      @if (Route::currentRouteName() === 'ctl.htlsroomplan2')
        【プランメンテナンス】
      @else
        {!! Form::open(['route' => ['ctl.htlsroomplan2.list'], 'method' => 'post','style' => 'display:inline;']) !!}
          <input type="hidden" name="target_cd"        value="{{$target_cd}}" />
          <input type="hidden" name="partner_group_id" value="{{$partner_group_id}}" />
          <input type="submit" value="プランメンテナンス" />
        {!! Form::close() !!}
      @endif
    </td>
    <td>
      {!! Form::open(['route' => ['ctl.htlreserve'], 'method' => 'post','style' => 'display:inline;']) !!}
        <input type="hidden" name="target_cd"        value="{{$target_cd}}" />
        <input type="hidden" name="partner_group_id" value="{{$partner_group_id}}" />
        <input type="submit" value="予約情報の確認" />
      {!! Form::close() !!}
    </td>
    {{-- JRセットプラン設定の編集可否 --}}
    {{-- TODO  $v->userの書き変え--}}
    @if(empty($v->user->hotel.jrset_status))
      @php
      $is_edit_jrset = false;
      @endphp
    @else
      @if(intval($v->user->hotel.jrset_status) == 0 || intval($v->user->hotel.jrset_status) == 1 || intval($v->user->hotel.jrset_status) == 4)
        @php
        $is_edit_jrset = true;
        @endphp
      @else
        @php
        $is_edit_jrset = false;
        @endphp
      @endif
    @endif
    @if($is_edit_jrset)
    <td>
      {!! Form::open(['route' => ['ctl.htlsroomplandp'], 'method' => 'post','style' => 'display:inline;']) !!}
        <input type="hidden" name="target_cd"        value="{{$target_cd}}" />
        <input type="submit" value="JRコレクション審査状況" />
      {!! Form::close() !!}
    </td>
    @endif
    {{-- エキスパートメニュー --}}
    <td class="jqs-expert_menu">
      @if (Route::currentRouteName() === 'ctl.pmscode')
      【PMSコード】
      @else
        {!! Form::open(['route' => ['ctl.pmscode'], 'method' => 'post','style' => 'display:inline;']) !!}
          <input type="hidden" name="target_cd"        value="{{$target_cd}}" />
          <input type="hidden" name="partner_group_id" value="{{$partner_group_id}}" />
          <input type="submit" value="PMSコード" />
        {!! Form::close() !!}
      @endif
    </td>
		{{-- プラン・部屋登録方式移行ツール --}}
    {{-- TODO  is_migration、v->user、value 書き変え--}}
    @if(!$v->assign->is_migration && $v->user->hotel_system_version.version == 1)
	    <td class="jqs-expert_menu">
        {!! Form::open(['route' => ['ctl.htlmigration'], 'method' => 'post','style' => 'display:inline;']) !!}
          <input type="submit" value="プラン・部屋登録方式移行ツール" />
          <input type="hidden" name="ctl_nm" value="{$v->env.controller}" />
          <input type="hidden" name="act_nm" value="{$v->env.action}" />
          <input type="hidden" name="target_cd" value="{$v->helper->form->strip_tags($v->assign->target_cd)}" />
        {!! Form::close() !!}
			</td>
		@endif
    {{-- /エキスパートメニュー --}}
  </tr>
</table>
<div align="right">
  <input id="expert_menu_on"  type="button" value="エキスパートメニュー　表　示" />
  <input id="expert_menu_off" type="button" value="エキスパートメニュー　非表示" />
</div>
<div style="clear:both; margin-top:0;"> </div>
<hr size="0"><br>