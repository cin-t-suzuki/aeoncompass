{{-- MEMO: 移植元 public/app/ctl/view2/htlsmedia/_common_menu.tpl --}}

{{-- HACK: 共通化した先で分岐するなら、共通化する必要ないのではなかろうか --}}

@php
// TODO: 認証関連 (guard: hotel, 施設管理者)
$user = (object)[
    'hotel' => (object)[
        'hotel_nm' => 'TODO: dummy_hotel_name',
    ]
]
@endphp

<br />
<div align="right">
    @if (\Route::CurrentRouteName() != 'ctl.htl.media.list')
        {{-- <form action="/htlsmedia/list/" method="post"> --}}
        {{ Form::open(['route' => 'ctl.htl.media.list', 'method' => 'get']) }}
        <input type="submit" value="「画像一覧管理」{{ strip_tags($user->hotel->hotel_nm) }}へ">
        <input type="hidden" name="target_cd" value="{{ $target_cd }}" />
        {{ Form::close() }}
        {{-- </form> --}}
    @endif
    @if (
        \Route::CurrentRouteName() != 'ctl.htl.medil.edit_hotel'
        && \Route::CurrentRouteName() != 'ctl.htl.medil.update_hotel'
        && \Route::CurrentRouteName() != 'ctl.htl.medil.sort_hotel'
    )
        {{-- <form action="/ctl/htlsmedia/edithotel/" method="post"> --}}
        {{ Form::open(['route' => 'ctl.htl.media.edit_hotel', 'method' => 'get']) }}

            {{-- <input type="submit" value="「施設画像設定」{{ strip_tags($user->hotel->hotel_nm) }}へ"> --}}
            {{ Form::submit('「施設画像設定」' . strip_tags($user->hotel->hotel_nm) . 'へ') }}

            {{-- <input type="hidden" name="target_cd" value="{{ $target_cd }}" /> --}}
            {{ Form::hidden('target_cd', $target_cd) }}

        {{ Form::close() }}
        {{-- </form> --}}
    @endif

    {{-- TODO: 遷移先未実装 --}}
    <form action="/ctl/htlsroomplan2/list/" method="get">
    {{-- {{ Form::open(['action' => '/htlsroomplan2/list/', 'method' => 'post']) }} --}}
    <input type="submit" value="「プランメンテナンス」{{ strip_tags($user->hotel->hotel_nm) }}へ">
    <input type="hidden" name="target_cd" value="{{ $target_cd }}" />
    {{-- {{ Form::close() }} --}}
    </form>
</div>
