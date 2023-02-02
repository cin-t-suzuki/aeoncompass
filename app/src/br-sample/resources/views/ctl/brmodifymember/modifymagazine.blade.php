@extends('ctl.common.base')
@section('title', 'メールマガジン受信状態変更')

@section('page_blade')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
<div style="border-style:solid;border-color:#00f;border-width:1px;padding:6px;background-color:#eef;">
  メールマガジンの受信状態【{{$message}}】に変更しました。
</div>
<br/ >
{!! Form::open(['route' => ['ctl.brmodifymember.mailsearch'], 'method' => 'get']) !!}
<input type="hidden" name="email" value="{{$email}}">
<INPUT TYPE="submit" VALUE="戻る">
{!! Form::close() !!}

@endsection
