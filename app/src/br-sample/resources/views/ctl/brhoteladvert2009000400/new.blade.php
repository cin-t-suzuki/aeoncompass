{{-- acceptance_status_flg=false --}}
@section('title', '迷わずここ！(2009000400 )')
@include('ctl.common.base')

{{-- メッセージ --}}
@section('message')
@include('ctl.common.message')

{{-- 編集用フォーム --}}
{!! Form::open(['route' => ['ctl.brhoteladvert2009000400.create'], 'method' => 'post']) !!} 
@include ('ctl.brhoteladvert2009000400._form')
{!! Form::close() !!}

@section('title', 'footer')
@include('ctl.common.footer')