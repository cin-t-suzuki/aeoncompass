{{-- MEMO: 移植元 public\app\rsv\view2\_common\_pgh1.tpl --}}
{{--
--------------------------------------------------------------------------------
パラメータ
pgh1_mnv : メインナビゲーション
1 = 初めての方、ＢＲポイント、予約・キャンセル、ヘルプ
2 = ヘルプ
3 = ヘルプ
--------------------------------------------------------------------------------
--}}
@php
    $pgh1_mnv = $pgh1_mnv ?? 1;
    $isLogin = false;
    $isFree = true;
    $svc_rsv = $svc_rsv ?? 0;
@endphp

<div id="pgh1">
    <div class="pg">
        <div class="pgh1-logo">
            <h1 class="logo">
                <a href="{{ route('rsv.top') }}">
                    <img src="{{ asset('/img/pg/pgh-logo2.gif') }}" title="イオンコンパスホテル予約" alt="旅館・ホテル予約のイオンコンパスホテル" width="170" height="70" />
                </a>
            </h1>
        </div>
        <div class="pgh1-catch">
            <h2 class="catch">レジャー・ビジネスに！国内の宿泊予約サイト。旅館・ホテルの予約はイオンコンパスで。</h2>
            <div class="today">
            </div>
        </div>

        @if (!Auth::guard('web')->check())
            <div class="pgh1-usr pgh1-usr-guest">
                <div class="pgh1-usr2 guest">
                    <div class="pgh1-usr3">
                        <div class="button">
                            <a class="btnimg btn-active" href="{{ route('rsv.auth.login', ['next_url' => Request::fullUrl()]) }}">
                                <img src="{{ asset('/img/usr/usr-login.gif') }}" title="ログイン" alt="ログイン" width="119" height="32" />
                            </a>
                        </div>
                        <div class="welcome">ようこそ</div>
                        <div class="user">会員登録は<a href="{{ route('rsv.subscribe.new') }}" title="会員登録は無料です！" style="color:#9cf;">こちら</a>から</div>
                    </div>
                </div>
            </div>
        @else
            <div class="pgh1-usr pgh1-usr-member">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <div class="pgh1-usr2 member">
                                <a class="user-link" href="{{ route('rsv.point.history') }}" title="ポイントの発行状況はこちら" style="display: block;">
                                    <div class="pgh1-usr3">
                                        <span class="sankaku">
                                        </span>
                                        <span class="welcome">ようこそ</span>
                                        <span class="user username">
                                            {{ Auth::guard('web')->user()->name() }}
                                        </span>
                                    </div>
                                    <div class="clearfix">
                                        <span class="hoyu-point">保有ポイント</span>
                                        <span class="valid_point hoyu-point">
                                            {{-- TODO: ポイント機能実装待ち --}}
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <div>
                            <a class="" href="{{ route('rsv.auth.logout') }}">ログアウト</a>
                        </div>
                    </tr>
                </table>
            </div>
        @endif
        <div class="pgh1-mnv">

            @if ($pgh1_mnv == 1)
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.guide.visitor')
                        <a class="" href="{{ route('rsv.guide.visitor') }}">
                            <img src="{{ asset('/img/mnv/mnv-first2.gif') }}" alt="初めての方へ" width="102" height="33" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-first2_disable.gif') }}" alt="初めての方へ" width="102" height="33" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.point.index')
                        <a class="" href="{{ route('rsv.point.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-point2.gif') }}" alt="ＢＲポイント" width="102" height="33" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-point2_disable.gif') }}" alt="ＢＲポイント" width="102" height="33" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.reserve.index')
                        <a class="" href="{{ route('rsv.reserve.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-rsv2.gif') }}" alt="予約の確認・キャンセル・日程短縮" width="182" height="33" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-rsv2_disable.gif') }}" alt="予約の確認・キャンセル・日程短縮" width="182" height="33" />
                    @endif
                </div>
                <div class="mnv">
                    <a class="" href="{{ route('rsv.help.index') }}">
                        <img src="{{ asset('/img/mnv/mnv-help2.gif') }}" alt="ヘルプ" width="92" height="33" />
                    </a>
                </div>
            @elseif ($pgh1_mnv == 2)
                <div class="mnv">
                    <a class="" href="{{ route('rsv.help.index') }}">
                        <img src="{{ asset('/img/mnv/mnv-help2.gif') }}" alt="ヘルプ" width="92" height="33" />
                    </a>
                </div>
            @elseif ($pgh1_mnv == 3)
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.guide.coupon')
                        <a class="" href="{{ route('rsv.guide.coupon') }}">
                            <img src="{{ asset('/img/mnv/mnv-first2.gif') }}" alt="初めての方へ" width="112" height="40" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-first2_disable.gif') }}" alt="初めての方へ" width="112" height="40" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.point.index')
                        <a class="" href="{{ route('rsv.point.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-point2.gif') }}" alt="ＢＲポイント" width="112" height="40" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-point2_disable.gif') }}" alt="ＢＲポイント" width="112" height="40" />
                    @endif
                </div>
                <div class="mnv">
                    @if (Route::currentRouteName() !== 'rsv.reserve.index')
                        <a class="" href="{{ route('rsv.reserve.index') }}">
                            <img src="{{ asset('/img/mnv/mnv-rsv2.gif') }}" alt="予約の確認・キャンセル・日程短縮" width="112" height="40" />
                        </a>
                    @else
                        <img src="{{ asset('/img/mnv/mnv-rsv2_disable.gif') }}" alt="予約の確認・キャンセル・日程短縮" width="112" height="40" />
                    @endif
                </div>
                <div class="mnv">
                    <a class="" href="{{ route('rsv.help.index') }}">
                        <img src="{{ asset('/img/mnv/mnv-help2.gif') }}" alt="ヘルプ" width="92" height="33" />
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- gnav 追加 -->
<div id="glovalnavi">
    <ul>
        <li>
            <a class="{{ $svc_rsv ? 'btnimg' : '' }}" href="{{ $svc_rsv ? route('rsv.top') : '#' }}">
                <img src="{{ asset('/img/mnv/gnv-tab-rsv' . ($svc_rsv ? '' : '_over') . '.gif') }}" alt="国内宿泊" width="115" height="30" />
            </a>
        </li>
    </ul>
    <hr class="glovalnavi-line" />
</div>
