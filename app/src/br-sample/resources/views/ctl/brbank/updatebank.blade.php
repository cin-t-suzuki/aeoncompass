@extends('ctl.common.base')
@section('title', '銀行マスタ 更新')


@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<p>
    <table border="1" cellspacing="0" cellpadding="4">
        <tr>
            <td nowrap bgcolor="#EEFFEE">銀行コード</td>
            <td>{{ $views->bank['bank_cd'] }}</td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEFFEE">銀行名称</td>
            <td>{{ $views->bank['bank_nm'] }}</td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEFFEE">銀行名称（カナ）</td>
            <td>{{ $views->bank['bank_kn'] }}</td>
        </tr>
    </table>
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
        <input type="hidden" name="bank_cd" value="{{ $views->bank['bank_cd'] }}">
        <input type="submit" value="一覧">
    {!! Form::close() !!}
</p>
@endsection
