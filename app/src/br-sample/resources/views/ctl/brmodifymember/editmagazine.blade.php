@extends('ctl.common.base')
@section('title', 'メールマガジン受信状態変更')

@section('page_blade')

{{-- メッセージbladeの読込 --}}
@include('ctl.common.message')

{{-- giのcssがbaseに記載されていないが、追記していいものか？一旦以下styleにgiで読まれるべき分を直書き --}}
@if (count($member) > 1)<div class="gi" style="margin: 1em 0;padding: 0.8em 1.2em;border: 2px solid #009;color: #009;background-color: #FFF;line-height: 1.25em;">該当する会員は{{ count($member) }} 名です。</div>
@endif

<table border="1" cellpadding="3" cellspacing="0">
  <tr>
    <td bgcolor="#eeffee">メールアドレス</td>
    <td>{{ strip_tags($email) }}</td>
  </tr>
</table>

@foreach ($member as $index => $member)
<div style="margin:1em 0">
{!! Form::open(['route' => ['ctl.brmodifymember.modifymagazine'], 'method' => 'post']) !!}
@if (count($member) > 1 )<div>{{ $index+1 }}名目）</div> @endif
@if ($is_forced_stop_mail[$index])
<div class="ei">システム強制配信停止中<br />メールマガジン受信状態を変更すると強制配信停止が解除されます。</div>
@endif
<table border="1" cellpadding="3" cellspacing="0">
  <tr>
    <td bgcolor="#eeffee">パートナー</td>
    <td>{{ $partner[$index]['partner_ns'] }}（@if ($member['member_type'] == 0) ベストリザーブ会員（2011年12月01日13時までに登録された会員）
         @elseif ($member['member_type'] == 1) 宿ぷらざから移行された会員
         @elseif ($member['member_type'] == 2) ベストリザーブ・宿ぷらざ会員（2011年12月01日13時以降に登録された会員）@endif ）</td>
  </tr>
  <tr>
    <td bgcolor="#eeffee">
      メールアドレス
    </td>
    <td>
      {{ $member['email'] }}
    </td>
  </tr>
  <tr>
    <td bgcolor="#eeffee">
      メールマガジン受信状態
    </td>
    <td>
      <ul style="font-family: Meiryo, sans-serif; margin-top: 0.5em; list-style: none;">
        <li style="float: left;"                  ><span style="background-color: #FF9D14; color: #ffffff; font-weight: bold; padding: 1px 15px;">宿泊</span></li>
        <li style="float: left; margin-left: 1em;"><input type="radio" id="stay_daily_{{ $index }}"    name="send_magazine_stay" value="mailmagazine"      @if ($magazine_setting[$index]['send_magazine_stay'] === 'mailmagazine' )checked="checked"@endif       /><label for="stay_daily_{{ $index}} "   >毎日</label></li>
        <li style="float: left; margin-left: 1em;"><input type="radio" id="stay_week_{{ $index }}"     name="send_magazine_stay" value="mailmagazine-week" @if ($magazine_setting[$index]['send_magazine_stay'] === 'mailmagazine-week' )checked="checked"@endif  /><label for="stay_week_{{ $index}} "    >週１回程度</label></li>
        <li style="float: left; margin-left: 1em;"><input type="radio" id="stay_needless_{{ $index }}" name="send_magazine_stay" value="needless"          @if ($magazine_setting[$index]['send_magazine_stay'] === 'needless' )checked="checked"@endif           /><label for="stay_needless_{{ $index}} ">不要</label></li>
      </ul>
      <div style="clear: both;"></div>
    </td>
  </tr>
</table>
<p>
@if (count($member) > 1 ){{ $index+1 }}名目を受信状態を変更しますか？@endif
<input type="submit" value="変更する" />
</p>
<input type="hidden" name="email" value="{{ strip_tags($email) }}" />
<input type="hidden" name="member_cd" value="{{ strip_tags($member['member_cd']) }}" />
{!! Form::close() !!}
</div>
@endforeach

@endsection
