@include('ctl.common.base')

{{-- メッセージ --}}
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brsupervisor.create'], 'method' => 'post']) !!}
@include('ctl.brsupervisor._input_new',
    ['supervisor_cd' => $views->supervisor_cd
    ,'supervisor_nm' => $views->supervisor_nm
    ,'account_id' => $views->account_id
    ,'password' => $views->password
    ,'accept_status' => $views->accept_status
    ])

  <input type="submit" value="登録">
{!! Form::close() !!}

<div align="right">
  <small>
    {!! Form::open(['route' => ['ctl.brsupervisor.list'], 'method' => 'post']) !!}
      <input type="submit" value="グループ一覧へ">
    {!! Form::close() !!}
  </small>
</div>

@include('ctl.common.footer')