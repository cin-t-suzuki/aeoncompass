@extends('ctl.common.base')
@section('title', '銀行マスタ')


@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)

<p>
    {!! Form::open(['route' => ['ctl.brbank.updatebank'], 'method' => 'post']) !!}
    @csrf
        <table border="1" cellspacing="0" cellpadding="4">
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行コード</td>
                <td>
                    <span>{{ $views->bank['bank_cd'] }}</span>
                    <input type="hidden" name="bank[bank_cd]" value="{{ $views->bank['bank_cd'] }}">
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行名称</td>
                <td>
                    <input type="text" name="bank[bank_nm]" SIZE="30" MAXLENGTH="50" value="{{ $views->bank['bank_nm'] }}">
                    <span>（全角50文字）</span>
                </td>
            </tr>
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行名称（カナ）</td>
                <td>
                    <input type="text" name="bank[bank_kn]" SIZE="30" MAXLENGTH="15" value="{{ $views->bank['bank_kn'] }}">（全角15文字）
                    <div>「ひらがな」は「カタカナ」に変換して登録します。</div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="submit" value="更新">
    {!! Form::close() !!}
</p>

<p>
    <span>支店一覧</span>
    <table border="0" cellpadding="0" cellspacing="4" >
        <tr>
            <td>
                <div @if(count($views->branch) > 5) style='overflow:auto; height:10em;' @else style='overflow:auto;' @endif>
                    <table border="0" cellpadding="0" cellspacing="4" >
                    @foreach ($views->branch as $branch)
                        <tr>
                            <td>{{ $branch['bank_branch_cd'] }}</td>
                            <td>:</td>
                            <td>{{ $branch['bank_branch_nm'] }}</td>
                            <td>:</td>
                            <td>{{ $branch['bank_branch_kn'] }}</td>
                            <td align="right">
                                {!! Form::open(['route' => ['ctl.brbank.viewbankbranch'], 'method' => 'post']) !!}
                                    <input type="hidden" name="bank_cd" value="{{ $branch['bank_cd'] }}">
                                    <input type="hidden" name="bank_branch_cd" value="{{ $branch['bank_branch_cd'] }}">
                                    <input type="hidden" name="keyword" value="{{ $views->keyword }}">
                                    <input type="submit" value="編集">
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </td>
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
    {!! Form::open(['route' => ['ctl.brbank.index'], 'method' => 'get']) !!}
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="submit" value="一覧">
    {!! Form::close() !!}
</p>
@endsection
