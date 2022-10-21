@section('title', 'グループホテル一覧')
@include('ctl.common.base')


{{-- メッセージ --}}
@section('message')
@include('ctl.common.message', $messages)

{{-- 入力フォーム --}}
{!! Form::open(['route' => ['ctl.brsupervisor.createhotel'], 'method' => 'post']) !!}

@section('brhotelsupervisor_input_new_hotel')
@include('ctl.brsupervisor._input_new_hotel',
    ['a_hotel_supervisor_hotel' => $views->a_hotel_supervisor_hotel
    ,'supervisor_cd' => $views->supervisor_cd
    ])

  <input type="hidden" name="supervisor_cd" value="{{strip_tags($views->supervisor_cd ??null )}}">
  <input type="submit" value="確認">

{!! Form::close() !!}


<div align="right">
  <small>
    <form method="POST" action="{$v->env.source_path}{$v->env.module}/brsupervisor/listhotel/">
      <input type="submit" value="グループホテル一覧へ">
      <input type="hidden" name="supervisor_cd" value="{$v->helper->form->strip_tags($v->assign->supervisor_cd)}">
    </form>
  </small>
</div>

@section('title', 'footer')
@include('ctl.common.footer')