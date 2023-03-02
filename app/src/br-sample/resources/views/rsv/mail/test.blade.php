{{-- {{include file='../_common/_header.tpl' title='会員情報の確認'}} --}}
@extends('rsv.common.base', [
    'title' => '会員情報の確認',
])

@section('content')
    {{-- {{include file='../_common/_pgh1.tpl' pgh1_mnv=1}} --}}
    @include('rsv.common._pgh1', [
        'pg1_mnv' => 1,
    ])

    <div id="pgh2">
        <div class="pg">
            <div class="pgh2-inner">
            </div>
            {{-- {{include file='../_common/_pgh2_inner.tpl'}} --}}
            @include('rsv.common._pgh2_inner')
        </div>
    </div>


    <div id="pgc2">
        <div class="pg">
            <div class="pgc2-inner">
                <div style="text-align:center;">
                    <div style="width:700px; margin:0 auto;text-align:left;">
                        <div style="padding:1em 0">
                            <h1 style="font-size:150%;font-weight:bold;border-left:4px solid #666;padding:4px;">会員情報の確認</h1>
                        </div>

                        <div style="text-align:center;">
                            <div style="width:500px; margin:0 auto;text-align:left;">
                                <div style="text-align:center;margin:1em 0;border:2px solid #f90;padding:1em 0;">
                                    ご指定の 電子メールアドレス（{{ strip_tags($member_subscribe['email']) }}） に自信のない方、不安な方は、
                                    <br />下記 「送信テスト」ボタンを押してください。
                                    <br />件名「[ベストリザーブ] テスト送信」で メールを送信いたします。

                                    {{ Form::open(['route' => 'rsv.mail.subscribe', 'method' => 'post']) }}
                                    {{-- <form action="/rsv/mail/subscribe/" method="post"> --}}
                                    <input name="Member_Subscribe[account_id]" type="text" value="{{ strip_tags($member_subscribe['account_id']) }}" />
                                    <input name="Member_Subscribe[password]" type="text" value="{{ strip_tags($member_subscribe['password']) }}" />
                                    @if (!is_null($member_subscribe['j_westid']))
                                        <input name="Member_Subscribe[j_westid]" type="text" value="{{ strip_tags($member_subscribe['j_westid']) }}" />
                                    @endif
                                    <input name="Member_Subscribe[family_nm]" type="text" value="{{ strip_tags($member_subscribe['family_nm']) }}" />
                                    <input name="Member_Subscribe[given_nm]" type="text" value="{{ strip_tags($member_subscribe['given_nm']) }}" />
                                    <input name="Member_Subscribe[family_kn]" type="text" value="{{ strip_tags($member_subscribe['family_kn']) }}" />
                                    <input name="Member_Subscribe[given_kn]" type="text" value="{{ strip_tags($member_subscribe['given_kn']) }}" />
                                    <input name="Member_Subscribe[email]" type="text" value="{{ strip_tags($member_subscribe['email']) }}" />
                                    <input name="Member_Subscribe[email_confirmation]" type="text" value="{{ strip_tags($member_subscribe['email_confirmation']) }}" />
                                    <input name="Member_Subscribe[gender]" type="text" value="{{ strip_tags($member_subscribe['gender']) }}" />
                                    <input name="Member_Subscribe[year]" type="text" value="{{ strip_tags($member_subscribe['year']) }}" />
                                    <input name="Member_Subscribe[month]" type="text" value="{{ strip_tags($member_subscribe['month']) }}" />
                                    <input name="Member_Subscribe[day]" type="text" value="{{ strip_tags($member_subscribe['day']) }}" />
                                    <input name="Member_Subscribe[mail_magazine]" type="text" value="{{ strip_tags($member_subscribe['mail_magazine']) }}" />
                                    <input name="Member_Subscribe[contact_type]" type="text" value="{{ strip_tags($member_subscribe['contact_type']) }}" />
                                    <input name="Member_Subscribe[tel]" type="text" value="{{ strip_tags($member_subscribe['tel']) }}" />
                                    <input name="Member_Subscribe[optional_tel]" type="text" value="{{ strip_tags($member_subscribe['optional_tel']) }}" />
                                    <input name="Member_Subscribe[postal_cd]" type="text" value="{{ strip_tags($member_subscribe['postal_cd']) }}" />
                                    <input name="Member_Subscribe[pref_id]" type="text" value="{{ strip_tags($member_subscribe['pref_id']) }}" />
                                    <input name="Member_Subscribe[address1]" type="text" value="{{ strip_tags($member_subscribe['address1']) }}" />
                                    <input name="Member_Subscribe[address2]" type="text" value="{{ strip_tags($member_subscribe['address2']) }}" />
                                    <input name="Member_Subscribe[member_group]" type="text" value="{{ strip_tags($member_subscribe['member_group']) }}" />
                                    <input name="Member_Subscribe[birth_ymd]" type="text" value="{{ strip_tags($member_subscribe['birth_ymd']) }}" />
                                    {{-- {{section name=email start=1 loop=4}} --}}
                                    @for ($i = 1; $i <= 4; $i++)
                                        @php
                                            $email = 'email' . $i;
                                            $email_type = 'email_type' . $i;
                                            $member_mail_cd = 'member_mail_cd' . $i;
                                        @endphp
                                        <input name="Member_Subscribe[{{ $email }}]" type="text" value="{{ strip_tags($member_subscribe[$email]) }}" />
                                        <input name="Member_Subscribe[{{ $email_type }}]" type="text" value="{{ strip_tags($member_subscribe[$email_type]) }}" />

                                        {{-- MEMO: おそらく、登録にはなくて編集にはある。 --}}
                                        <input name="Member_Subscribe[{{ $member_mail_cd }}]" type="text" value="{{ strip_tags($member_subscribe[$member_mail_cd]) }}" />
                                        {{-- {{/section}} --}}
                                    @endfor
                                    <input name="return_pass" type="text" value="{{ strip_tags($return_pass) }}" />
                                    <input name="send_magazine_stay" type="text" value="{{ $send_magazine_stay }}" />
                                    <input name="send_magazine_bestcou" type="text" value="{{ $send_magazine_bestcou }}" />
                                    @if (count($camp) > 0)
                                        <input name="point_camp_cd" type="text" value="{{ $camp['point_camp_cd'] }}" />
                                    @endif

                                    <div style="margin:1em 0;text-align:center;">
                                        <input type="submit" value="送信テスト" />
                                    </div>
                                    {{-- </form> --}}
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
{{-- {{include file='../_common/_footer.tpl'}} --}}
