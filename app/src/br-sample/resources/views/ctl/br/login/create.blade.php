@extends('ctl.common.base')

@section('title', 'スタッフ登録')
@section('page_blade')
    {{ Form::open(['route' => 'register']) }}
    <table>
        <tr>
            <td>
                アカウントID
            </td>
            <td>
                {{ Form::text('account_id', old('account_id')) }}
            </td>
        </tr>
        <tr>
            <td>
                パスワード
            </td>
            <td>
                {{ Form::password('password') }}
            </td>
        </tr>
    </table>
    {{ Form::submit('登録') }}
    {{ Form::close() }}
@endsection
