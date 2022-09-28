@extends('ctl.common.base')
@section('title', '銀行支店マスタ 新規登録')


@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<p>
    {!! Form::open(['route' => ['ctl.brbank.createbankbranch'], 'method' => 'post']) !!}
        <table border="1" cellspacing="0" cellpadding="4">
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行コード</td>
                <td>
                    <span>{{ $views->bank['bank_cd'] }}</span>
                    <input type="hidden" name="bank_branch[bank_cd]" value="{{ $views->bank['bank_cd'] }}">
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行名称</td>
                <td>{{ $views->bank['bank_nm'] }}</td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行名称（カナ）</td>
                <td>{{ $views->bank['bank_kn'] }}</td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">支店コード</td>
                <td>
                    <input type="text" name="bank_branch[bank_branch_cd]"  SIZE="5" MAXLENGTH="3" value="{{ $views->bank_branch['bank_branch_cd'] }}">
                    <span>（数字3文字 : 001）</span>
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">支店名称</td>
                <td>
                    <input type="text" name="bank_branch[bank_branch_nm]" SIZE="30" MAXLENGTH="30" value="{{ $views->bank_branch['bank_branch_nm'] }}">
                    <span>（全角30文字）</span>
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">支店名称（カナ）</td>
                <td>
                    <input type="text" name="bank_branch[bank_branch_kn]" SIZE="30" MAXLENGTH="15" value="{{ $views->bank_branch['bank_branch_kn'] }}">
                    <span>（全角カナ15文字）</span>
                    <div>「ひらがな」は「カタカナ」に変換して登録します。</div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="hidden" name="bank_cd" value="{{ $views->bank['bank_cd'] }}">
        <input type="submit" value="新規登録">
    {!! Form::close() !!}
</p>
@endsection
