@section('title', 'グループホテル登録')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{!! Form::open(['route' => ['ctl.brsupervisor.createhotel'], 'method' => 'post']) !!}
@section('brhotelsupervisor_info_hotel')
@include('ctl.brsupervisor._info_hotel',
    ['hotelData' => $views->hotelData
    ,'a_hotel_supervisor_hotel' => $views->a_hotel_supervisor_hotel
    ,'supervisor_cd' => $views->supervisor_cd
    ])

  <input type="submit" value="登録">
  <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd ??null )}}">
  <input type="hidden" name="hotel_cd" value="{{strip_tags($views->hotelData['hotel_cd'] ??null )}}">
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