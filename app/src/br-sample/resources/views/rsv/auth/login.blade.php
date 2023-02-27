{{-- MEMO: 移植元 public\app\rsv\view2\auth\login2.tpl --}}

{{-- {include file='../_common/_header.tpl' title="会員認証"} --}}
@extends('rsv.common.base')
@section('title', '会員認証')

@section('content')
    {{-- {include file='../_common/_pgh1.tpl' pgh1_mnv=1 svc_rsv=true} --}}
    @include('rsv.common._pgh1', [
        'pgh_mnv' => 1,
        'svc_rsv' => true,
    ])

    <div id="pgh2v2">
        <div class="pg">
            <div class="pgh2-inner">
            </div>
            {{-- {include file='../_common/_pgh2_inner.tpl'} --}}
            @include('rsv.common._pgh2_inner')
        </div>
    </div>
    <div id="pgc1v2">
        <div class="pg">
            <div class="pgc1-inner">
            </div>
        </div>
    </div>
    <div id="pgc2">
        <div class="pg">
            <div class="pgc2-inner">
                <!-- Content Start -->
                <div style="padding:1em 0;">
                    <div class="reserve_top">
                        {{-- メッセージ --}}
                        {{-- {include file='../_common/_message_org.tpl'} --}}
                        @include('rsv.common.message_org')
                        <!-- 宿泊予約確認 -->
                        <div class="reserve_top_lodging">
                            <h2>会員認証</h2>
                            <div class="reserve_box reserve_stay_box clearfix">
                                <div class="reserve_stay_box_inner" style="padding-bottom: 320px;">

                                    {{ Form::open(['route' => 'rsv.auth.eplogin.authenticate', 'method' => 'post']) }}
                                    <div style=" width: 50%; float: left;">
                                        <h3>EPARK IDでログイン</h3>
                                        <p>EPARKのアカウントでログインを行います。</p>
                                        <button class="login_button_epk" name="login_button_epk" type="submit">EPARK IDでログイン</button>
                                    </div>
                                    {{ Form::close() }}

                                    {{ Form::open(['route' => 'rsv.auth.login.authenticate', 'method' => 'post']) }}

                                    <div style=" width: 50%; float: left;">
                                        <h3>ベストリザーブ会員IDでログインする</h3>
                                        @if (!$errors->any())
                                            <p>ベストリザーブのアカウントログインを行います。<br>
                                            </p>
                                        @endif
                                        <dl>
                                            <dt>会員コード(メールアドレス)：</dt>
                                            <dd>
                                                <input name="email" type="email" value="{{ old('email') }}" size="25" />
                                            </dd>
                                            <dt>パスワード：</dt>
                                            <dd>
                                                <input name="password" type="password" size="25" />
                                            </dd>
                                        </dl>

                                        <button class="login_button login_button_rsv" name="login_button_rsv" type="submit">ベストリザーブ IDでログイン</button>
                                        <div style="margin: 13px 60px;">
                                            {{-- {include file='../_common/_message_login_auth.tpl' msg1_flg=true msg2_flg=false msg3_flg=false} --}}
                                            @include('rsv.common.message_login_auth', [
                                                'msg1_flg' => true,
                                                'msg2_flg' => false,
                                                'msg3_flg' => false,
                                                'msg4_flg' => false,
                                            ])
                                        </div>
                                    </div>
                                    {{ Form::close() }}

                                </div>
                                <div style=" clear: left;">
                                </div>

                            </div>
                        </div>

                        <style type="text/css">
                            .login_button_rsv {
                                background: #f5bf56;
                                width: 290px;
                            }

                            .login_button_rsv:hover {
                                background: #f5bf56;
                                opacity: 0.7;
                            }

                            .login_button_epk {
                                background: #8fc31f;
                            }

                            .login_button_epk:hover {
                                background: #8fc31f;
                                opacity: 0.7;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- {include file='../_common/_footer.tpl'} --}}
@endsection
