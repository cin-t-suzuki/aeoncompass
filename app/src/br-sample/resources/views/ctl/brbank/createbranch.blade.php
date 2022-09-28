@extends('ctl.common.base')
@section('title', '銀行支店マスタ 新規登録')


@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<p>
    <table border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td nowrap bgcolor="#EEFFEE">銀行コード</td>
            <td>{{ $views->bank_branch['bank_cd'] }}</td>
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
            <td>{{ $views->bank_branch['bank_branch_cd'] }}</td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEFFEE">支店名称</td>
            <td>{{ $views->bank_branch['bank_branch_nm'] }}</td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEFFEE">支店名称（カナ）</td>
            <td>{{ $views->bank_branch['bank_branch_kn'] }}</td>
        </tr>
    </table>
</p>

<p>
    {!! Form::open(['route' => ['ctl.brbank.newbankbranch'], 'method' => 'post']) !!}
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="hidden" name="bank_cd" value="{{ $views->bank['bank_cd'] }}">
        <input type="submit" value="支店追加">
    {!! Form::close() !!}
</p>

<p>
    {!! Form::open(['route' => ['ctl.brbank.viewbankbranch'], 'method' => 'post']) !!}
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="hidden" name="bank_cd" value="{{ $views->bank['bank_cd'] }}">
        <input type="hidden" name="bank_branch_cd" value="{{ $views->bank_branch['bank_branch_cd'] }}">
        <input type="submit" value="支店詳細表示">
    {!! Form::close() !!}
</p>

<p>
    {!! Form::open(['route' => ['ctl.brbank.index'], 'method' => 'get']) !!}
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="submit" value="一覧">
    {!! Form::close() !!}
</p>
@endsection
