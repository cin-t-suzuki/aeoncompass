@section('title', 'グループホテル一覧')
@include('ctl.common.base')

<table border="0" cellspacing="0" cellpadding="4">
  <tr>
    {!! Form::open(['route' => ['ctl.brsupervisor.new'], 'method' => 'post']) !!}
      <td>
        <input type="submit" value="グループ登録">
      </td>
    {!! Form::close() !!}
  </tr>	
</table>

<br>
{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)



{{-- グループホテル一覧表示 --}}
@section('brhotelsupervisor_list')
@include('ctl.brsupervisor._list',
    ['a_hotel_supervisor' => $views->a_hotel_supervisor
    ,'supervisor_cd' => $views->supervisor_cd
    ,'supervisor_nm' => $views->supervisor_nm
    ])


@section('title', 'footer')
@include('ctl.common.footer')