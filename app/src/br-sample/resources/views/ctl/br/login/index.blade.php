@extends('ctl.common.base')
@section('title', 'ログイン')

@section('page_blade')
    <center>
        {{ Form::open(['route' => 'ctl.br_login.login', 'method' => 'post']) }}
        {{-- <form action="{$v->env.source_path}{$v->env.module}/brlogin/login/" method="post"> --}}
            <p>ベストリザーブ社内管理　ログイン画面</p>
            {{-- メッセージ --}}
            @include('ctl.common.message')

            <table border="1" cellspacing="0" cellpadding="5">
                <tr>
                    <td>ＩＤ</td>
                    <td colspan="2"><input type="text" name="account_id" value="{{ $account_id }}" size="25" maxlength="60"></td>
                </tr>
                <tr>
                    <td>パスワード</td>
                    <td colspan="2"><input type="password" name="password" value="" size="25"></td>
                </tr>
            </table>
            <p>
                <input type="submit" value="ログイン">
            </p>
        {{-- </form> --}}
        {{ Form::close() }}
    </center>
@endsection
