@section('title', 'グループホテル一覧')
@include('ctl.common.base')


{!! Form::open(['route' => ['ctl.brsupervisor.newhotel'], 'method' => 'post']) !!}
        <input type="submit" value="グループホテル登録">
        <input type="hidden" name="supervisor_cd" value="{{$views->supervisor_cd}}">
{!! Form::close() !!}

<br>
{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{{-- グループホテル一覧表示 --}}
@section('brhotelsupervisor_listhotel')
@include('ctl.brsupervisor._list_hotel',
    ['a_hotel_supervisor_hotel' => $views->a_hotel_supervisor_hotel
    ,'id' => $views->id
    ,'supervisor_cd' => $views->supervisor_cd
    ])

<div align="right">
  <small>
    {!! Form::open(['route' => ['ctl.brsupervisor.list'], 'method' => 'post']) !!}
      <input type="submit" value="グループ一覧へ">
      <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd ??null )}}">
    {!! Form::close() !!}
  </small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')