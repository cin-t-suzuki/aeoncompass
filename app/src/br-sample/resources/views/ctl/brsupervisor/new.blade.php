@section('title', 'グループホテル一覧')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brsupervisor.create'], 'method' => 'post']) !!}
@section('brhotelsupervisor_input_new')
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

@section('title', 'footer')
@include('ctl.common.footer')