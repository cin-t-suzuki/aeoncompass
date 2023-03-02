<?php

namespace App\Http\Controllers\rsv;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MailController extends Controller
{
    /**
     * 動作確認用のダミーデータ
     *
     * @var array
     */
    private $dummyDataForTest = [
        'member_subscribe' => [
            'email' => 'test@mail.com',
            'account_id' => '',
            'password' => '',
            'j_westid' => '',
            'family_nm' => '',
            'given_nm' => '',
            'family_kn' => '',
            'given_kn' => '',
            'email_confirmation' => '',
            'gender' => '',
            'year' => '',
            'month' => '',
            'day' => '',
            'mail_magazine' => '',
            'contact_type' => '',
            'tel' => '',
            'optional_tel' => '',
            'postal_cd' => '',
            'pref_id' => '',
            'address1' => '',
            'address2' => '',
            'member_group' => '',
            'birth_ymd' => '',
            'email1' => '',
            'email2' => '',
            'email3' => '',
            'email4' => '',
            'email_type1' => '',
            'email_type2' => '',
            'email_type3' => '',
            'email_type4' => '',
            'member_mail_cd1' => '',
            'member_mail_cd2' => '',
            'member_mail_cd3' => '',
            'member_mail_cd4' => '',
        ],
        'return_pass' => '',
        'send_magazine_stay' => '',
        'send_magazine_bestcou' => '',
        'camp' => [
            'point_camp_cd' => '',
        ],
    ];
    public function test()
    {
        return view('rsv.mail.test', $this->dummyDataForTest);
    }

    public function subscribe(Request $request)
    {
        // 情報取得
        $a_member_subscribe         = $request->input('Member_Subscribe');
        $s_return_pass              = $request->input('return_pass');
        $s_send_magazine_stay       = $request->input('send_magazine_stay');     // メルマガ：宿泊
        $s_send_magazine_bestcou    = $request->input('send_magazine_bestcou');  // メルマガ：ベストク

        return view('rsv.mail.subscribe', [
            'mail' => [
                'email' => $a_member_subscribe['email'],
                'info'  => 'info@bestrsv.com',
            ],
            'account_id'            => $a_member_subscribe['account_id'],
            'password'              => $a_member_subscribe['password'],
            'j_westid'              => $a_member_subscribe['j_westid'],
            'partner_cd'            => $a_member_subscribe['partner_cd'] ?? '',
            'family_nm'             => $a_member_subscribe['family_nm'],
            'given_nm'              => $a_member_subscribe['given_nm'],
            'family_kn'             => $a_member_subscribe['family_kn'],
            'given_kn'              => $a_member_subscribe['given_kn'],
            'email'                 => $a_member_subscribe['email'],
            'email_confirmation'    => $a_member_subscribe['email_confirmation'],
            'gender'                => $a_member_subscribe['gender'],
            'year'                  => $a_member_subscribe['year'],
            'month'                 => $a_member_subscribe['month'],
            'day'                   => $a_member_subscribe['day'],
            'mail_magazine'         => $a_member_subscribe['mail_magazine'],
            'contact_type'          => $a_member_subscribe['contact_type'],
            'tel'                   => $a_member_subscribe['tel'],
            'optional_tel'          => $a_member_subscribe['optional_tel'],
            'postal_cd'             => $a_member_subscribe['postal_cd'],
            'pref_id'               => $a_member_subscribe['pref_id'],
            'address1'              => $a_member_subscribe['address1'],
            'address2'              => $a_member_subscribe['address2'],
            'member_group'          => $a_member_subscribe['member_group'],
            'birth_ymd'             => $a_member_subscribe['birth_ymd'],
            'email1'                => $a_member_subscribe['email1'],
            'email_type1'           => $a_member_subscribe['email_type1'],
            'member_mail_cd1'       => $a_member_subscribe['member_mail_cd1'],
            'email2'                => $a_member_subscribe['email2'],
            'email_type2'           => $a_member_subscribe['email_type2'],
            'member_mail_cd2'       => $a_member_subscribe['member_mail_cd2'],
            'email3'                => $a_member_subscribe['email3'],
            'email_type3'           => $a_member_subscribe['email_type3'],
            'member_mail_cd3'       => $a_member_subscribe['member_mail_cd3'],
            'email4'                => $a_member_subscribe['email4'],
            'email_type4'           => $a_member_subscribe['email_type4'],
            'member_mail_cd4'       => $a_member_subscribe['member_mail_cd4'],
            'return_pass'           => $s_return_pass,
            'send_magazine_stay'    => $s_send_magazine_stay,
            'send_magazine_bestcou' => $s_send_magazine_bestcou,
            'point_camp_cd'         => $request->input('point_camp_cd'),
        ]);
    }
}
