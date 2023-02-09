@extends('ctl.common._htl_base')
@section('title', '満室通知メール設定')
@inject('service', 'App\Http\Controllers\ctl\HtlAlertMailHotelController')
@section('content')

{{-- パンくず --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
<a href="{{ route('ctl.htl_mail_list.list', ['target_cd' =>$target_cd]) }}">各種メール設定</a>&nbsp;&gt;&nbsp;
満室通知メール設定
<br>
<br>
{{-- メッセージ --}}
@include('ctl.common.message')
<br>
{!! Form::open(['route' => ['ctl.htl_alert_mail_hotel.create'], 'method' => 'get']) !!}
    <table border="1" cellspacing="0" cellpadding="4">
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
        電子メールアドレス
        </td>
        <td width="256px">
        <table cellspacing="0" cellpadding="2">
            <tr>
            <td>
                <input type="text" name="AlertMailHotel[email]" value="{{old('AlertMailHotel.email' ,strip_tags($alert_mail_hotel['email']))}}" size="45">
            </td>
            <td>
                <select name="AlertMailHotel[email_type]">
                    <option value= "0" @if(0 == old('AlertMailHotel.email_type')) selected @endif>詳細なメール文章</option>
                    <option value= "1" @if(1 == old('AlertMailHotel.email_type')) selected @endif>簡易なメール文章</option>
                </select>
            </td>
            </tr>
        </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#EEEEFF" nowrap>
            備考
        </td>
        <td width="256px">
            <input type="text" name="AlertMailHotel[note]" value="{{old('AlertMailHotel.note' ,strip_tags($alert_mail_hotel['note']))}}" size="45">
        </td>
    </tr>
    <tr>
        <td bgcolor="#EEEEFF">
            &nbsp;
        </td>
        <td>
            <input type="submit" value="追加登録">
        </td>
    </tr>
    </table>
<input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{{ Form::close() }}

@if(count($a_alert_mail_hotel['values']) == 0)
    <ul>
        <small>
            <li>満室になった際に通知する電子メールアドレスを設定します。
        </small>
    </ul>
@else
    <hr size=1>
    <br>
    登録電子メールアドレス一覧
    <table border="1" cellspacing="0" cellpadding="4">
        <tr>
        <td bgcolor="#EEEEFF">
            電子メールアドレス
        </td>
        <td bgcolor="#EEEEFF">
            電子メールタイプ
        </td>
        <td bgcolor="#EEEEFF">
            備考
        </td>
        <td bgcolor="#EEEEFF">
            &nbsp;
        </td>
        <td bgcolor="#EEEEFF">
            &nbsp;
        </td>
        </tr>
    @foreach($a_alert_mail_hotel['values'] as $alert_mail_hotel)
        <tr>
        <td>
            {{strip_tags($alert_mail_hotel->email)}}
        </td>
        <td>
            @if($alert_mail_hotel->email_type == 0)
                詳細なメール文章
            @elseif($alert_mail_hotel->email_type == 1)
                簡易なメール文章
            @endif
        </td>
        <td>
            {{strip_tags($alert_mail_hotel->note)}}<br />
        </td>
        {!! Form::open(['route' => ['ctl.htl_alert_mail_hotel.update'], 'method' => 'get']) !!}
            <td align="center">
                <table>
                <tr>
                    <td align="center">
                        @if($alert_mail_hotel->email_notify == 1)
                            <font color="#0000FF">通知中</font>
                        @else
                            <input type="submit" value="通知">
                        @endif
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        @if($alert_mail_hotel->email_notify == 1)
                            <input type="submit" value="非通知">
                        @else
                            <font color="#FF0000">非通知</font>
                        @endif
                    </td>
                </tr>
                </table>
            </td>
            <input type="hidden" name="AlertMailHotel[branch_no]" value="{{strip_tags($alert_mail_hotel->branch_no)}}">
            <input type="hidden" name="target_cd" value="{{strip_tags($alert_mail_hotel->hotel_cd)}}">
        {{ Form::close() }}
        {!! Form::open(['route' => ['ctl.htl_alert_mail_hotel.delete'], 'method' => 'get']) !!}
            <td>
                <input type="submit" value="削除">
            </td>
                <input type="hidden" name="AlertMailHotel[branch_no]" value="{{strip_tags($alert_mail_hotel->branch_no)}}">
                <input type="hidden" name="target_cd" value="{{strip_tags($alert_mail_hotel->hotel_cd)}}">
        {{ Form::close() }}
    </tr>
    @endforeach
    </table>
    @endif
<hr size=1>

{!! Form::open(['route' => ['ctl.htl_mail_list.list'], 'method' => 'get']) !!}
    <input type="submit" value="戻る" style="width: 60px;">
    <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}">
{{ Form::close() }}

@endsection