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
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/brsupervisor/list/">
      <input type="submit" value="グループ一覧へ">
      <input type="hidden" name="supervisor_cd" value="{$v->helper->form->strip_tags($v->assign->supervisor_cd)}">
    </form>
  </small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')