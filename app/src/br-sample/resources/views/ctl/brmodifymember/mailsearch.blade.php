@extends('ctl.common.base')
@section('title', 'メールマガジン受信状態変更')

@section('page_blade')

{!! Form::open(['route' => ['ctl.brmodifymember.editmagazine'], 'method' => 'get']) !!}
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

<table border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td class="caption">メールアドレス</td>
    <td colspan="2"><input type="text" name="email" value="{{ strip_tags($email) }}" size="50" ></td>
  </tr>
</table>
<p>
<INPUT TYPE="submit" VALUE="検索">
</p>
{!! Form::close() !!}

@endsection
