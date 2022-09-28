@extends('ctl.common.base')
@section('title', '銀行支店メンテナンス')

@section('page_blade')
{{-- メッセージbladeの読込 --}}
@include('ctl.common.message', $messages)
<p>
    {!! Form::open(['route' => ['ctl.brbank.index'], 'method' => 'get']) !!}
        <table border="1" cellspacing="0" cellpadding="4">
            <tr>
                <td nowrap bgcolor="#EEFFEE">銀行・支店名称</td>
                <td><input type="text" name="keyword" SIZE="50" MAXLENGTH="100" value="{{ $views->keyword }}"></td>
                <td><input type="submit" value="検索"></td>
            </tr>
        </table>
        <div style="margin-top:0.5em;">
            複数条件のキーワードを設定する場合は、スペースで区切ってください。<br />
            各のキーワード毎に部分一致で銀行・支店名称の漢字・カナを検索します。
        </div>
    {!! Form::close() !!}
</p>

@if (!is_null($views->banks) && count($views->banks) > 0)
<table border="0" cellspacing="0" cellpadding="4">
    @foreach ($views->banks as $banc_cd => $bank)
    <tr>
        <td colspan="7"><hr size="1"></td>
    </tr>
    <tr>
        <td>{{ $bank['bank']['bank_cd'] }}</td>
        <td>:</td>
        <td>{{ $bank['bank']['bank_nm'] }}</td>
        <td>:</td>
        <td>{{ $bank['bank']['bank_kn'] }}</td>
        <td>
            {!! Form::open(['route' => ['ctl.brbank.newbankbranch'], 'method' => 'post']) !!}
                <input type="submit" value="支店追加">
                <input type="hidden" name="bank_cd" value="{{ $banc_cd }}">
                <input type="hidden" name="keyword" value="{{ $views->keyword }}">
            {!! Form::close() !!}
        </td>
        <td>
            {!! Form::open(['route' => ['ctl.brbank.viewbank'], 'method' => 'post']) !!}
                <input type="submit" value="編集">
                <input type="hidden" name="bank_cd" value="{{ $banc_cd }}">
                <input type="hidden" name="keyword" value="{{ $views->keyword }}">
            {!! Form::close() !!}
        </td>
    </tr>
    @if(count($bank['branch']) > 0)
    <tr>
        <td><br /></td>
        <td colspan="6">
            <div>支店</div>
            <div @if(count($bank['branch']) > 5) style='overflow:auto; height:10em;' @else style='overflow:auto;' @endif>
                <table border="0" cellpadding="0" cellspacing="4" width="100%">
                @foreach ($bank['branch'] as $branch)
                    <tr>
                        <td>{{ $branch['bank_branch_cd'] }}</td>
                        <td>:</td>
                        <td>{{ $branch['bank_branch_nm'] }}</td>
                        <td>:</td>
                        <td>{{ $branch['bank_branch_kn'] }}</td>
                        <td align="right">
                            {!! Form::open(['route' => ['ctl.brbank.viewbankbranch'], 'method' => 'post']) !!}
                                <input type="hidden" name="bank_cd" value="{{ $banc_cd }}">
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
    @endif
    @endforeach
</table>
@endif


<p>
    {!! Form::open(['route' => ['ctl.brbank.newbank'], 'method' => 'post']) !!}
        @csrf
        <input type="hidden" name="keyword" value="{{ $views->keyword }}">
        <input type="submit" value="銀行新規登録">
    {!! Form::close() !!}
</p>
@endsection
