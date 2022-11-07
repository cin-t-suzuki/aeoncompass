@section('title', 'グループ登録')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brsupervisor.update'], 'method' => 'post']) !!}
@section('brhotelsupervisor_input_edit')
@include('ctl.brsupervisor._input_edit',
    [
    'a_hotel_supervisor' => $views->a_hotel_supervisor
    ,'a_hotel_supervisor_account' => $views->a_hotel_supervisor_account
    ])

  <input type="submit" value="更新">
  <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd)}}">
  <input type="hidden" name="hotel_supervisor[supervisor_nm]" value="{{strip_tags($views->a_hotel_supervisor['supervisor_nm'])}}">
  <input type="hidden" name="hotel_supervisor_account[account_id]" value="{{strip_tags($views->a_hotel_supervisor_account['account_id'])}}">
  <input type="hidden" name="hotel_supervisor_account[accept_status]" value="{{strip_tags($views->a_hotel_supervisor_account['accept_status'])}}">
{!! Form::close() !!}

<div align="right">
  <small>
   {!! Form::open(['route' => ['ctl.brsupervisor.list'], 'method' => 'post']) !!}
      <input type="submit" value="グループ一覧へ">
      <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd)}}">
    {!! Form::close() !!}
  </small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')