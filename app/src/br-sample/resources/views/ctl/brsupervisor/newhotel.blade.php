@section('title', 'グループホテル一覧')
@include('ctl.common.base')


{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{{-- 入力フォーム --}}
{!! Form::open(['route' => ['ctl.brsupervisor.cnfhotel'], 'method' => 'post']) !!}

@section('brhotelsupervisor_input_new_hotel')
@include('ctl.brsupervisor._input_new_hotel',
    ['a_hotel_supervisor_hotel' => $views->a_hotel_supervisor_hotel
    ,'supervisor_cd' => $views->supervisor_cd
    ])
  <input type="submit" value="確認">
  <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd)}}">
{!! Form::close() !!}


<div align="right">
  <small>
    {!! Form::open(['route' => ['ctl.brsupervisor.listhotel'], 'method' => 'post']) !!}
      <input type="submit" value="グループホテル一覧へ">
      <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd ??null )}}">
    {!! Form::close() !!}
  </small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')