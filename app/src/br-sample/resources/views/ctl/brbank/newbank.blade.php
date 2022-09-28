@extends('ctl.common.base')
@section('title', '銀行マスタ 新規登録')


@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<p>
    {!! Form::open(['route' => ['ctl.brbank.createbank'], 'method' => 'post']) !!}
    @csrf
        <table border="1" cellspacing="0" cellpadding="4">
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行コード</td>
                <td>
                    <input type="text" name="bank[bank_cd]" SIZE="5" MAXLENGTH="4" value="{{ $views->bank['bank_cd'] }}">（数字4文字 : 0001）
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行名称</td>
                <td>
                    <input type="text" name="bank[bank_nm]" SIZE="30" MAXLENGTH="30" value="{{ $views->bank['bank_nm'] }}">（全角30文字）
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行名称（カナ）</td>
                <td>
                    <div><input type="text" name="bank[bank_kn]" SIZE="30" MAXLENGTH="15" value="{{ $views->bank['bank_kn'] }}">（全角15文字）</div>
                    <div>「ひらがな」は「カタカナ」に変換して登録します。</div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="submit" value="新規登録">
    {!! Form::close() !!}
</p>
@endsection
