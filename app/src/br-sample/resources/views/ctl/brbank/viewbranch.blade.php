@extends('ctl.common.base')
@section('title', '銀行支店マスタ')


@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<p>
    {!! Form::open(['route' => ['ctl.brbank.updatebankbranch'], 'method' => 'post']) !!}
        <table border="1" cellspacing="0" cellpadding="4">
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行コード</td>
                <td>
                    <span>{{ $views->bank_branch['bank_cd'] }}</span>
                    <input type="hidden" name="bank_branch[bank_cd]" value="{{ $views->bank_branch['bank_cd'] }}">
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
                    <span>{{ $views->bank_branch['bank_branch_cd'] }}</span>
                    <input type="hidden" name="bank_branch[bank_branch_cd]" value="{{ $views->bank_branch['bank_branch_cd'] }}">
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
                    <span>（全角15文字）</span>
                    <div>「ひらがな」は「カタカナ」に変換して登録します。</div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="hidden" name="bank_cd" value="{{ $views->bank_branch['bank_cd'] }}">
        <input type="hidden" name="bank_branch_cd" value="{{ $views->bank_branch['bank_branch_cd'] }}">
        <input type="submit" value="更新">
    {!! Form::close() !!}
</p>

<p>
    {!! Form::open(['route' => ['ctl.brbank.viewbank'], 'method' => 'post']) !!}
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="hidden" name="bank_cd" value="{{ $views->bank['bank_cd'] }}">
        <input type="submit" value="銀行詳細表示">
    {!! Form::close() !!}
</p>

<p>
    {!! Form::open(['route' => ['ctl.brbank.index'], 'method' => 'get']) !!}
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="submit" value="一覧">
    {!! Form::close() !!}
</p>
@endsection
