{{-- MEMO: 移植元 public\app\ctl\views\htllogin\index.tpl --}}

{{-- {include file=$v->env['module_root']|cat:'/views/_common/_htl_login_header.tpl' acceptance_status_flg=false title='予約者へのメール(履歴検索)'} --}}
@extends('ctl.common.htl_login_base')

@section('content')
    <center>
        <p>
            <br>
            管理画面への接続はＩＤ・パスワードを入力の上<br>
            「ログイン」ボタンをクリックください。
        </p>
        <p>※ＩＤ・パスワードがご不明な宿泊施設様は下記サービスセンター宛にご連絡ください。<br>
            <br>
        </p>

        {{-- メッセージ --}}
        @include('ctl.common.message')

        {{ Form::open(['route' => 'ctl.htl.login.authenticate', 'method' => 'post']) }}
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <td>ＩＤ</td>
                <td colspan="2">
                    {{-- <input type="text" name="account_id" value="{{ strip_tags($account_id) }}" size="26" maxlength="10"> --}}
                    {{ Form::text('account_id', old('account_id'), ['size' => '26', 'maxlength' => '10']) }}
                </td>
            </tr>
            <tr>
                <td>パスワード</td>
                <td colspan="2">
                    {{-- <input name="password" type="password" value="" size="26" maxlength="10"> --}}
                    {{ Form::password('password', ['size' => '26', 'maxlength' => '10']) }}
                </td>
            </tr>
        </table>
        {{-- <input type="checkbox" name="keep" value="1" @if (!is_null($keep)) checked @endif id="keep_1"> --}}
        {{ Form::checkbox('keep', '1', old('keep') == '1', ['id' => 'keep_1']) }}
        <label for="keep_1">ログイン情報を持続する</label>
        <br>
        <br>
        <input type="submit" value="ログイン">
        {{ Form::close() }}
        <br>
        <p>
        <table border="0" cellspacing="0" cellpadding="0" bgcolor="#9999ff">
            <tr>
                <td>
                    <table border="0" cellspacing="1" cellpadding="6">
                        <tr>
                            <td align="left" nowrap="" bgcolor="#ffffff">
                                お問い合わせ先<br>
                                MAIL:<a href="mailto:{{ config('settings.contact.email') }}">{{ config('settings.contact.email') }}</a>
                                <br>
                                TEL : {{ config('settings.contact.tel') }}<br>
                                FAX : {{ config('settings.contact.fax') }}<br>
                                受付: 月～金 9:30～18:30<br>
                                （土曜・日曜・祝祭日・弊社休日は除く）
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </p>
    </center>
    <br>
    {{-- {include file=$v->env['module_root']|cat:'/views/_common/_htl_footer.tpl'} --}}
@endsection
