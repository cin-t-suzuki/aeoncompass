{{-- MEMO: 移植元 public\app\ctl\views\brchangepass\index.tpl --}}

{{-- {include file=$v->env.module_root|cat:'/views/_common/_br_header.tpl' title="管理画面用パスワード変更"} --}}
@extends('ctl.common.base')

@section('title', '管理画面用パスワード変更')

@section('page_blade')
    {{-- メッセージ --}}
    <br />
    @include('ctl.common.message')

    {{ Form::open(['route' => 'ctl.br.change.password.update', 'method' => 'post']) }}
    <table border="0" cellspacing="0" cellpadding="3">
        <tr>
            <td>&nbsp;</td>
            <td>
                <table border="1" cellspacing="0" cellpadding="3">
                    <tr>
                        <td style="background-color: #EEFFEE;">
                            新パスワード
                        </td>
                        <td>
                            {{ Form::password('password') }}
                        </td>
                        <td>
                            半角英数字（20文字まで）
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #EEFFEE;">
                            新パスワード(確認)
                        </td>
                        <td>
                            {{ Form::password('password_confirmation') }}
                        </td>
                        <td>
                            上と同じ内容を入力
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="pass" value="パスワード を変更する">
            </td>
        </tr>
    </table>
    {{ Form::close() }}
    <br />
@endsection
