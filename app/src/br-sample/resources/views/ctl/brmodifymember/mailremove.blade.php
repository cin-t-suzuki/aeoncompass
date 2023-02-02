@extends('ctl.common.base')
@section('title', 'メールアドレスをRemoveへ変更')

@section('page_blade')
{!! Form::open(['route' => ['ctl.brmodifymember.editmailremove'], 'method' => 'get']) !!}

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

「remove@bestrsv.com」へ変更するメールアドレスを入力してください。
<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td class="caption">メールアドレス</td>
    <td colspan="2"><input type="text" name="email" value="{{strip_tags($email)}}" size="50"></td>
  </tr>
</table>
<p>
<INPUT TYPE="submit" VALUE="変更">
</p>
{!! Form::close() !!}
@endsection
