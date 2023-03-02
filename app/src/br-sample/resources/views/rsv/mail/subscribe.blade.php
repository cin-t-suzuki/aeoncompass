{{-- MEMO: 移植元 public\app\rsv\view2\mail\subscribe.tpl --}}

{{-- {{include file='../_common/_header.tpl' title="電子メールアドレスのテスト送信"}} --}}
@extends('rsv.common.base', [
    'title' => '電子メールアドレスのテスト送信',
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

    <div id="pgc1">
        <div class="pg">
            <div class="pgc1-inner">
            </div>
        </div>
    </div>

    <div id="pgc2">
        <div class="pg">
            <div class="pgc2-inner">
                <div style="text-align:center;">
                    <div style="width:700px; margin:0 auto;text-align:left;">

                        {{-- {{include file=$v->env.module_root|cat:'/views/_common/_message.tpl'}} --}}
                        @include('rsv.common.message')

                        <div class="gi">
                            {{ strip_tags($email) }} にメールを送信いたしました。<br />
                            １・２分程たちましても、メールが届かない場合は電子メールアドレスが間違っています。<br />
                            電子メールアドレスを確認の上、再度入力しなおしてください。
                        </div>
                        <br />
                        <br />
                        <table border="0" cellspacing="0" border="0" bgcolor="#FF9900" width="100%">
                            <tr>
                                <td>
                                    <table border="0" cellspacing="1" border="2" width="100%">
                                        <tr>
                                            <td align="center" bgcolor="#FFFFFF">
                                                前の画面に戻りたい場合は、下記 「もどる」ボタンを押してください。
                                                <form action="{{ $return_pass }}" method="post">
                                                    <input name="Member_Subscribe[account_id]" type="hidden" value="{{ strip_tags($account_id) }}" />
                                                    <input name="Member_Subscribe[password]" type="hidden" value="{{ strip_tags($password) }}" />
                                                    @if (!is_null($j_westid))
                                                        <input name="Member_Subscribe[j_westid]" type="hidden" value="{{ strip_tags($j_westid) }}" />
                                                    @endif
                                                    <input name="Member_Subscribe[partner_cd]" type="hidden" value="{{ strip_tags($partner_cd) }}" />
                                                    <input name="Member_Subscribe[family_nm]" type="hidden" value="{{ strip_tags($family_nm) }}" />
                                                    <input name="Member_Subscribe[given_nm]" type="hidden" value="{{ strip_tags($given_nm) }}" />
                                                    <input name="Member_Subscribe[family_kn]" type="hidden" value="{{ strip_tags($family_kn) }}" />
                                                    <input name="Member_Subscribe[given_kn]" type="hidden" value="{{ strip_tags($given_kn) }}" />
                                                    <input name="Member_Subscribe[email]" type="hidden" value="{{ strip_tags($email) }}" />
                                                    <input name="Member_Subscribe[email_confirmation]" type="hidden" value="{{ strip_tags($email_confirmation) }}" />
                                                    <input name="Member_Subscribe[gender]" type="hidden" value="{{ strip_tags($gender) }}" />
                                                    <input name="Member_Subscribe[year]" type="hidden" value="{{ strip_tags($year) }}" />
                                                    <input name="Member_Subscribe[month]" type="hidden" value="{{ strip_tags($month) }}" />
                                                    <input name="Member_Subscribe[day]" type="hidden" value="{{ strip_tags($day) }}" />
                                                    <input name="Member_Subscribe[mail_magazine]" type="hidden" value="{{ strip_tags($mail_magazine) }}" />
                                                    <input name="Member_Subscribe[contact_type]" type="hidden" value="{{ strip_tags($contact_type) }}" />
                                                    <input name="Member_Subscribe[tel]" type="hidden" value="{{ strip_tags($tel) }}" />
                                                    <input name="Member_Subscribe[optional_tel]" type="hidden" value="{{ strip_tags($optional_tel) }}" />
                                                    <input name="Member_Subscribe[postal_cd]" type="hidden" value="{{ strip_tags($postal_cd) }}" />
                                                    <input name="Member_Subscribe[pref_id]" type="hidden" value="{{ strip_tags($pref_id) }}" />
                                                    <input name="Member_Subscribe[address1]" type="hidden" value="{{ strip_tags($address1) }}" />
                                                    <input name="Member_Subscribe[address2]" type="hidden" value="{{ strip_tags($address2) }}" />
                                                    <input name="Member_Subscribe[member_group]" type="hidden" value="{{ strip_tags($member_group) }}" />
                                                    <input name="Member_Subscribe[birth_ymd]" type="hidden" value="{{ strip_tags($birth_ymd) }}" />

                                                    @for ($i = 1; $i <= 4; $i++)
                                                        @php
                                                            $email = 'email' . $i;
                                                            $email_type = 'email_type' . $i;
                                                            $member_mail_cd = 'member_mail_cd' . $i;
                                                        @endphp
                                                        <input name="Member_Subscribe[{{ $email }}]" type="hidden" value="{{ strip_tags($$email) }}" />
                                                        <input name="Member_Subscribe[{{ $email_type }}]" type="hidden" value="{{ strip_tags($$email_type) }}" />
                                                        <input name="Member_Subscribe[{{ $member_mail_cd }}]" type="hidden" value="{{ strip_tags($$member_mail_cd) }}" />
                                                    @endfor

                                                    <input name="send_magazine_stay" type="hidden" value="{{ strip_tags($send_magazine_stay) }}" />
                                                    <input name="send_magazine_bestcou" type="hidden" value="{{ strip_tags($send_magazine_bestcou) }}" />
                                                    <input name="point_camp_cd" type="hidden" value="{{ strip_tags($point_camp_cd) }}" />
                                                    <input type="submit" value="もどる">
                                                </form>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- {{include file='../_common/_footer.tpl'}} --}}
@endsection
