@extends('ctl.common._htl_base')
@section('title', 'ＩＤ と パスワード の変更')
@inject('service', 'App\Http\Controllers\ctl\HtlChangePassController')
@section('content')

{{-- パンくず --}}
<a href="{{ route('ctl.htl_top.index', ['target_cd' =>$target_cd]) }}">メインメニュー</a>&nbsp;&gt;&nbsp;
ＩＤ と パスワード の変更
<br>
<br>

{{-- メッセージ --}}
@include('ctl.common.message')
<br>
{!! Form::open(['route' => ['ctl.htl_change_pass.update'], 'method' => 'get']) !!}
  <input type="hidden" name="target_cd" value="{{strip_tags($target_cd)}}" />
  <small><font color="#FF3333">※ＩＤとパスワードに同じ内容を入力することは出来ませんのでご注意ください。</font></small><br /><br />
    <table border="0" cellspacing="0" cellpadding="3" >
        <tr>
            <td valign="top">・</td>
            <td><small><font color="#0000FF">ログインＩＤを変更される場合は、新しいログインＩＤを入力して下さい。</font><br />ログインＩＤのみ変更される場合は、下の「ログインＩＤのみ変更する」ボタンを押してください。</small></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <table border="1" cellspacing="0" cellpadding="3">
                <tr>
                    <td bgcolor="#EEEEFF" >新ログインＩＤ</td>
                    <td><input type="text" name="id1" value="{{old('id1' , strip_tags($id1))}}" size="12" maxlength="10">半角英数字（10文字まで）</td>
                </tr>
                <tr>
                    <td  bgcolor="#EEEEFF" >新ログインＩＤ(再入力)</td>
                    <td><input type="text" name="id2" value="{{old('id2' , strip_tags($id2))}}" size="12" maxlength="10">上と同じ内容を入力</td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="id" value="ログインID のみ変更する">
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td valign="top">・</td>
            <td><small><font color="#0000FF">パスワードを変更される場合は、新しいパスワードを入力して下さい。</font><br />パスワードのみ変更される場合は、下の「パスワードのみ変更する」ボタンを押してください。</small></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <table border="1" cellspacing="0" cellpadding="3">
                    <tr>
                        <td bgcolor="#EEEEFF" >新パスワード</td>
                        <td><input type="text" name="pass1" value="{{old('pass1' , strip_tags($pass1))}}" size="12" maxlength="10">半角英数字（10文字まで）</td>
                    </tr>
                    <tr>
                        <td  bgcolor="#EEEEFF" >新パスワード(再入力)</td>
                        <td><input type="text" name="pass2" value="{{old('pass2' , strip_tags($pass2))}}" size="12" maxlength="10">上と同じ内容を入力</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="pass" value="パスワード のみ変更する">
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td valign="top">・</td>
            <td>
                <font color="#0000FF"><small>ID と パスワードを共に変更される場合は、上記 ID と パスワードを共に入力してください。</font><br />入力後、「ＩＤ と パスワード を変更する」ボタンを押してください。</small>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="both" value="ID と パスワード を変更する">
            </td>
        </tr>
    </table>
{{ Form::close() }}
<br />
@endsection