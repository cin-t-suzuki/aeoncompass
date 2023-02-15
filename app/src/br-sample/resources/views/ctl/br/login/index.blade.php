{{-- MEMO: 移植元 public\app\ctl\views\brlogin\index.tpl --}}

@extends('ctl.common.base')
@section('title', 'ログイン')

@section('page_blade')
    <center>
        <p>ベストリザーブ社内管理　ログイン画面</p>
        {{-- メッセージ --}}
        @include('ctl.common.message')
        
        {{ Form::open(['route' => 'ctl.br.login.authenticate', 'method' => 'post']) }}
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <td>ＩＤ</td>
                <td colspan="2">
                    {{ Form::text('account_id', old('account_id', $account_id), ['size' => '25', 'maxlength' => '60']) }}
                </td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td colspan="2">
                    {{ Form::password('password', ['size' => '25']) }}
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" value="ログイン">
        </p>
        {{ Form::close() }}
    </center>
@endsection
