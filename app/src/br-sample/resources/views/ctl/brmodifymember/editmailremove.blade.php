@extends('ctl.common.base')
@section('title', 'メールアドレスをRemoveへ変更')

@section('page_blade')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')
@if (count($member) > 1)<div class="gi">該当する会員は{{count($member)}}名です。</div>
@endif

<table border="1" cellpadding="3" cellspacing="0">
  <tr>
    <td bgcolor="#eeffee">メールアドレス</td>
    <td>{{strip_tags($email)}}</td>
  </tr>
</table>

@foreach ($member as $index => $mem)
<div style="margin:1em 0">
{!! Form::open(['route' => ['ctl.brmodifymember.modifymailremove'], 'method' => 'post']) !!}
@if (count($mem) > 1)<div>{{$index+1}}名目）</div>@endif
<table border="1" cellpadding="3" cellspacing="0">
  <tr>
    <td bgcolor="#eeffee">パートナー</td>
    <td>{{$partner[$index]['partner_ns']}}（@if ($mem['member_type'] == 0)ベストリザーブ会員（2011年12月01日13時までに登録された会員）
         @elseif ($mem['member_type'] == 1)宿ぷらざから移行された会員
         @elseif ($mem['member_type'] == 2)ベストリザーブ・宿ぷらざ会員（2011年12月01日13時以降に登録された会員）@endif）</td>
  </tr>
  <tr>
    <td bgcolor="#eeffee">
      メールアドレス
    </td>
    <td>
      {{$mem['email']}}
    </td>
  </tr>
</table>
<p>
<input type="submit" value="「remove@bestrsv.com」へ変更" />
</p>
<input type="hidden" name="email" value="{{strip_tags($email)}}" />
<input type="hidden" name="member_cd" value="{{strip_tags($mem['member_cd'])}}" />
{!! Form::close() !!}
</div>
@endforeach

@endsection
