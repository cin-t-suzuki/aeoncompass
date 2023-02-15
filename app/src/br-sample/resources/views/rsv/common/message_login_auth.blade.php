{{-- MEMO: 移植元 public\app\rsv\view2\_common\_message_login_auth.tpl --}}

@if ($msg1_flg)
    <p>
        <a href="{{ route('rsv.reminder') }}">>> 会員コード・パスワードをお忘れの方はこちら</a>
    </p>
@endif
@if ($msg4_flg)
    <p>
        <a href="{{ route('rsv.member.withdraw4') }}">>> 会員コード・パスワードを お忘れの方はこちら</a>
    </p>
@endif
