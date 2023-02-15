@include('rsv.common._pgh1')
<p>
    {{ 'ログイン: ' . (Auth::guard('web')->check() ? '済' : '未') }}
    @if (Auth::guard('web')->check())
    {{ Auth::guard('web')->user()->name() }}
    @endif
</p>

<h1>予約サイト トップページ</h1>
@if (Auth::guard('web')->check())
<a href="{{ route('rsv.auth.logout') }}">ログアウト</a>
@else
<a href="{{ route('rsv.auth.login') }}">ログイン</a>
@endif
