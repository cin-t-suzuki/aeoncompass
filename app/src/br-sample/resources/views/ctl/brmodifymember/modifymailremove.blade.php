@extends('ctl.common.base')
@section('title', 'メールアドレスをRemoveへ変更')

@section('page_blade')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
<div style="border-style:solid;border-color:#00f;border-width:1px;padding:6px;background-color:#eef;">
  【remove@bestrsv.com】に変更しました。
</div>
<br/ >
{!! Form::open(['route' => ['ctl.brmodifymember.mailremove'], 'method' => 'get']) !!}
<input type="hidden" name="email" value="{{$email}}">
<INPUT TYPE="submit" VALUE="戻る">
{!! Form::close() !!}
@endsection
