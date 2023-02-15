{{-- MEMO: 移植元 public\app\rsv\view2\auth\login.tpl --}}

{{-- {include file='../_common/_header.tpl' title='会員認証'} --}}
@extends('rsv.common.base')
@section('title', '会員認証')

@section('content')
    
{{-- {include file='../_common/_pgh1.tpl' pgh1_mnv=1} --}}
{{-- TODO: --}}
@include('rsv.common._pgh1')

@php
    $memberAuthenticated = false;
@endphp

<div id="pgh2">
    <div class="pg">
        <div class="pgh2-inner">
        </div>
    </div>
</div>

<div id="pgc1">
    <div class="pg">
        <div class="pgc1-inner">

            @if ($type == 'withdraw')
                <dl class="pgc1-breadcrumbs">
                </dl>
                <div class="snv-text">
                    <ul class="snv-text-l3">
                        <li>
                            <a href="{{ config('app.url') }}/rsv/member/">会員情報 </a>
                        </li>
                        <li>
                            <a href="{{ config('app.url') }}/rsv/member/mail1/">メルマガ受信状態</a>
                        </li>
                        <li>
                            <a href="{{ config('app.url') }}/rsv/reminder/">パスワード照会 </a>
                        </li>
                    </ul>
                </div>
            @endif

        </div>
    </div>
</div>

<div id="pgc2">
    <div class="pg">
        <div class="pgc2-inner">
            <div style="text-align:center;">
                <div style="width:99%; margin:0 auto;text-align:left;">

                    @if ($type == 'withdraw')
                        <div style="padding:1em 0;margin-bottom:1em;">
                            <h1 style="font-size:150%;font-weight:bold;border-left:4px solid #666;padding:4px;">会員退会手続き</h1>
                        </div>
                    @endif

                    <form action="{{ config('app.url') }}/ctl/auth/exelogin/" method="post">
                        <div class="reg-container">

                            {{-- エラーメッセージ --}}
                            {{-- {include file='../_common/_message.tpl'} --}}
                            @include('rsv.common.message')
                            {{-- MEMO: ↓ もとは is_empty() --}}
                            @if (!is_null($banner))
                                <p style="margin-bottom:1em;">
                                    <img src="{{ $banner }}" />
                                </p>
                            @endif
                            {{-- MEMO: ↓ もとは is_empty() --}}
                            @if (is_null($reconfirm) || !$memberAuthenticated)
                                <p>会員コード・パスワードを入力してください。</p>
                            @else
                                <p>ご本人の確認のため、パスワードの入力をお願いします。</p>
                            @endif

                            <div class="reg-box border-f90">

                                {{-- MEMO: ↓ もとは is_empty() --}}
                                @if (is_null($reconfirm) || !$memberAuthenticated)
                                    <div class="form-group">
                                        <div class="lft">
                                            <label class="" for="account_id">会員コード： </label>
                                        </div>
                                        <div class="rgt">
                                            <input class="form-control" name="account_id" type="text" value="{{ strip_tags($account_id) }}" size="20" maxlength="100" />
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <div class="lft">
                                        <label class="" for="password">パスワード：</label>
                                    </div>
                                    <div class="rgt">
                                        <input class="form-control" name="password" type="password" size="20" />
                                    </div>
                                </div>

                                <input class="btn" name="submit" type="submit" value="{{ $button_nm ?? '認  証' }}" />
                            </div>

                            @if ($type == 'withdraw')
                                <div>
                                    <img src="/images/reserve/dot-sr5['gif']" alt="" width="11" height="11" />会員コード・パスワードを お忘れの方は → <a href="{{ config('app.url') }}/rsv/member/withdraw4/">こちら</a>
                                    <img src="/images/reserve/dot-sr5['gif']" alt="" width="11" height="11" />
                                    <br />
                                    <br />※2011年11月28日まで旅ぷらざ会員だった方は会員コードにメールアドレスを入力してください。
                                </div>
                            @else
                                <div>
                                    <img src="/images/reserve/dot-sr5['gif']" alt="" width="11" height="11" />会員コード・パスワードを お忘れの方は → <a href="{{ config('app.url') }}/rsv/reminder/">こちら</a>
                                    <img src="/images/reserve/dot-sr5['gif']" alt="" width="11" height="11" />
                                    <br />
                                    <br />※2011年11月28日まで旅ぷらざ会員だった方は会員コードにメールアドレスを入力してください。
                                </div>
                            @endif

                            <input name="auth_type" type="hidden" value="member" />
                            <input name="check_passwd" type="hidden" value="noneed" />
                            <input name="finger_cd" type="hidden" value="" />
                            <input name="banner" type="hidden" value="{{ $banner }}" />
                            <input name="type" type="hidden" value="{{ $type }}" />
                            <input name="button_nm" type="hidden" value="{{ $button_nm }}" />
                            <input name="reconfirm" type="hidden" value="{{ $reconfirm }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- {include file='../_common/_footer.tpl'} --}}
@endsection
