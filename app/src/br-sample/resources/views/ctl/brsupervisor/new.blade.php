@section('title', 'グループホテル一覧')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{{-- TODO createメソッド作成後、↓のコメントアウト外す --}}
{!! Form::open(['route' => ['ctl.brsupervisor.create'], 'method' => 'post']) !!}
@section('brhotelsupervisor_input_new')
@include('ctl.brsupervisor._input_new',
    [
    // 'a_hotel_supervisor' => $views->a_hotel_supervisor
    // ,'a_hotel_supervisor_account' => $views->a_hotel_supervisor_account,
    'supervisor_cd' => $views->supervisor_cd
    ,'supervisor_nm' => $views->supervisor_nm
    ,'account_id' => $views->account_id
    ,'password' => $views->password
    ,'accept_status' => $views->accept_status
    ])

  <input type="submit" value="登録">
{!! Form::close() !!}

<div align="right">
  <small>
    {{-- TODO書き換え↓ --}}
    {!! Form::open(['route' => ['ctl.brsupervisor.list'], 'method' => 'post']) !!}
      <input type="submit" value="グループ一覧へ">
    {!! Form::close() !!}
  </small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')