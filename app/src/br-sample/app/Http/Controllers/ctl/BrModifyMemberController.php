<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\Member;
use App\Models\Partner;
use App\Models\MemberSendingMail;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\Object_;

class BrModifyMemberController extends _commonController
{
    use Traits;

    public function mailSearch(Request $request)
    {
        // エラーメッセージがあれば取得
        $errors = $request->session()->get('errors', []);

        return view('ctl.brmodifymember.mailsearch', [
            'email' => $request->input('email'),

            'errors' => $errors
        ]);
    }

    public function editMagazine(Request $request)
    {

        if ($this->is_empty($request->input('email'))) {
            $errors[] = 'メールアドレスを入力してください。';
            return redirect()->route('ctl.brmodifymember.mailsearch')->with([
                'errors' => $errors
            ]);
        }

        //会員基盤実装後に実装
        /////////////////////////////////////////////////////////////////////////
        // // ユーザ情報を取得します。
        // $http_client = new Zend_Http_Client();
        // $http_client->setConfig(array('timeout' => 60));
        // $client = new Zend_Rest_Client($this->box->config->rpc_server->protect->host . 'member/');
        // $client->setHttpClient($http_client);

        // $o_response = $client->search_email(trim($request->input('email')), 0)->get();

        // // エラーの場合
        // if (!$o_response->isSuccess()) {
        //     // システムエラーの場合
        //     if ($o_response->error_type() == 'system_error') {
        //         throw new Exception($o_response->messages());
        //     }
        // }
        /////////////////////////////////////////////////////////////////////////

        //会員未実装のため、上記の代わりにテストデータを返す（実装後要修正）
        $o_response = (object)[
            'members' => [
                '0' => [
                    'member_cd' => '01Cuw1Pfo4kkZxbi74vW',
                    'email' => 'test'
                ],
                '1' => [
                    'member_cd' => '01Cuw1kW4PSnWxbZ7ZL6',
                    'email' => 'test2'
                ]
            ]
        ];

        // 有効会員のみ取得
        $a_members = []; //初期化
        for ($n_cnt = 0; $n_cnt < count($o_response->members); $n_cnt++) {
            $member = new Member();
            // $partner = partner::getInstance();
            $partner = new Partner();
            $a_member = $member->selectByKey($o_response->members[$n_cnt]['member_cd']); //find→selectByKeyでいいか？
            $a_member['email'] = $o_response->members[$n_cnt]['email'];
            if (($a_member['member_status'] ?? null) == 1) { //null追記でいいか？
                $a_members[] = $a_member;
                $a_partner[] = $partner->selectByKey($a_member['partner_cd']);
            }
        }

        // 1件以外の場合はエラー
        if (count($a_members) == 0) {
            $errors[] = '該当するメールアドレスがありませんでした。';
            return redirect()->route('ctl.brmodifymember.mailsearch', [
                'email' => $request->input('email')
            ])->with([
                'errors' => $errors
            ]);
        }

        // Memberモデルの取得
        $memberModel = new Member();

        // 会員へのメール送信否の情報取得
        for ($n_cnt = 0; $n_cnt < count($a_members); $n_cnt++) {
            $s_sql =
                <<< SQL
                select	send_mail_type
                from 	member_sending_mail
                where	member_cd = :member_cd
SQL;
            $a_conditions              = [];
            $a_conditions['member_cd'] = $a_members[$n_cnt]['member_cd'];
            $a_tmp_reject_mail_types   = DB::select($s_sql, $a_conditions);

            $a_member_magazine_setting[$n_cnt] = [];
            $a_reject_member_mail_types   = [];
            foreach (($a_tmp_reject_mail_types ?? []) as $a_tmp_reject_mail_type) { //nvl→??で問題ないか？
                $a_reject_member_mail_types[] = $a_tmp_reject_mail_type->send_mail_type;
            }

            // 「宿泊」カテゴリのメルマガ受信状態を設定（※レコードが存在しているものは拒否）
            if (in_array('mailmagazine-v2', $a_reject_member_mail_types) && in_array('mailmagazine-v2-week', $a_reject_member_mail_types)) {
                $a_member_magazine_setting[$n_cnt]['send_magazine_stay'] = 'needless';               // 不要
            } else {
                if (in_array('mailmagazine-v2', $a_reject_member_mail_types)) {
                    $a_member_magazine_setting[$n_cnt]['send_magazine_stay'] = 'mailmagazine-week'; // 週1回程度
                } else {
                    $a_member_magazine_setting[$n_cnt]['send_magazine_stay'] = 'mailmagazine';      // 毎日
                }
            }

            $a_is_forced_stop_mail[$n_cnt] = $memberModel->isForcedStopMail($a_members[$n_cnt]['member_cd']);
            $a_mamber[$n_cnt] = $memberModel->isForcedStopMail($a_members[$n_cnt]['member_cd']);
        }

        return view('ctl.brmodifymember.editmagazine', [
            'is_forced_stop_mail' => $a_is_forced_stop_mail,
            'partner' => $a_partner,
            'member' => $a_members,
            'email' => trim($request->input('email')),
            'magazine_setting' => $a_member_magazine_setting
        ]);
    }

    public function modifymagazine(Request $request)
    {

        if ($this->is_empty($request->input('member_cd'))) {
            $errors[] = 'メールアドレスを入力してください。';
            return redirect()->route('ctl.brmodifymember.mailsearch', [ //引数いらない？
            ])->with([
                'errors' => $errors
            ]);
        }

        // 有効会員のみ取得
        $memberModel = new Member();
        $a_member = $memberModel->selectByKey(['member_cd' => $request->input('member_cd')]);
        if ($a_member['member_status'] == 1) {
            $a_member_cd[] = $request->input('member_cd');
        }

        // 1件以外の場合はエラー
        if (count($a_member_cd) == 0) {
            $errors[] = '該当するメールアドレスがありませんでした。';
            return redirect()->route('ctl.brmodifymember.mailsearch', [
                'email' => $request->input('email')
            ])->with([
                'errors' => $errors
            ]);
        }

        // お知らせメール、メールマガジンの受信状態の更新
        $MemberSendingMailModel = new MemberSendingMail($a_member_cd[0]);
        $s_send_magazine_stay    = $request->input('send_magazine_stay');     // メルマガ：宿泊

        //--------------------------------------------------------------
        // 現在のメルマガの受信拒否状態を取得
        //--------------------------------------------------------------
        $s_sql =
            <<< SQL
                select	send_mail_type
                from 	member_sending_mail
                where	member_cd = :member_cd
SQL;
        $a_conditions              = [];
        // user処理実装後に要修正
        // $a_conditions['member_cd'] = $this->box->user->member->member_cd;
        $a_conditions['member_cd'] = '01Cuw1Pfo4kkZxbi74vW'; //テスト用仮実装データ
        $a_tmp_reject_mail_types   = DB::select($s_sql, $a_conditions);

        $a_member_magazine_setting = [];
        $a_reject_member_mail_types   = [];
        foreach (($a_tmp_reject_mail_types ?? []) as $a_tmp_reject_mail_type) { //nvl→??でいいか？
            $a_reject_member_mail_types[] = $a_tmp_reject_mail_type->send_mail_type;
        }

        //--------------------------------------------------------------
        // 「メルマガ：宿泊」に関する設定を更新
        //--------------------------------------------------------------
        switch ($s_send_magazine_stay) {
            case 'mailmagazine': // 毎日
                $MemberSendingMailModel->acceptMagazineType('mailmagazine-v2');      // 許可設定
                $MemberSendingMailModel->rejectMagazineType('mailmagazine-v2-week'); // 拒否設定
                $a_dest_message['stay'] = '毎日';                                  // 設定変更後の状態メッセージ
                break;

            case 'mailmagazine-week': // 週1回程度
                $MemberSendingMailModel->acceptMagazineType('mailmagazine-v2-week'); // 許可設定
                $MemberSendingMailModel->rejectMagazineType('mailmagazine-v2');      // 拒否設定
                $a_dest_message['stay'] = '週1回程度';                             // 設定変更後の状態メッセージ
                break;

            default: // 例外 or 不要
                $MemberSendingMailModel->rejectMagazineType('mailmagazine-v2');      // 拒否設定
                $MemberSendingMailModel->rejectMagazineType('mailmagazine-v2-week'); // 拒否設定
                $a_dest_message['stay'] = '不要';                                  // 設定変更後の状態メッセージ
                break;
        }


        // 更新実行
        if (!$MemberSendingMailModel->execute()) {
            //エラーメッセージはいらない？？
            return redirect()->route('ctl.brmodifymember.editmagazine', [
                'email' => $request->input('email')
            ]);
        }

        $s_message = '宿泊：' . $a_dest_message['stay'];

        return view('ctl.brmodifymember.modifymagazine', [
            'message' => $s_message,
            'email' => $request->input('email')
        ]);
    }

    // Remove の会員メールアドレス入力
    public function mailremove(Request $request)
    {
        return view('ctl.brmodifymember.mailremove', [
            'email' => $request->input('email')
        ]);
    }

    // Remove の会員メールアドレス入力
    public function editmailremove(Request $request)
    {
        if ($this->is_empty($request->input('email'))) {
            $errors[] = 'メールアドレスを入力してください。';
            return redirect()->route('ctl.brmodifymember.mailremove', [])->with([
                'errors' => $errors
            ]);
        }

        //会員基盤実装後に実装
        /////////////////////////////////////////////////////////////////////////
        // // ユーザ情報を取得します。
        // $http_client = new Zend_Http_Client();
        // $http_client->setConfig(array('timeout' => 60));
        // $client = new Zend_Rest_Client($this->box->config->rpc_server->protect->host . 'member/');
        // $client->setHttpClient($http_client);

        // $o_response = $client->search_email(trim($request->input('email')), 0)->get();
        // // エラーの場合
        // if (!$o_response->isSuccess()) {
        //     // システムエラーの場合
        //     if ($o_response->error_type() == 'system_error') {
        //         throw new Exception($o_response->messages());
        //     }
        // }
        /////////////////////////////////////////////////////////////////////////

        //会員未実装のため、上記の代わりにテストデータを返す（実装後要修正）
        $o_response = (object)[
            'members' => [
                '0' => [
                    'member_cd' => '01Cuw1Pfo4kkZxbi74vW',
                    'email' => 'test'
                ],
                '1' => [
                    'member_cd' => '01Cuw1kW4PSnWxbZ7ZL6',
                    'email' => 'test2'
                ]
            ]
        ];

        // 有効会員のみ取得
        for ($n_cnt = 0; $n_cnt < count($o_response->members); $n_cnt++) {
            $memberModel = new Member();
            $partnerModel = new partner();
            $a_member = $memberModel->selectByKey($o_response->members[$n_cnt]['member_cd']); //find→selectByKeyでいいか？
            $a_member['email'] = $o_response->members[$n_cnt]['email'];
            if ($a_member['member_status'] == 1) {
                $a_members[] = $a_member;
                $a_partner[] = $partnerModel->selectByKey($a_member['partner_cd']);
            }
        }

        // 1件以外の場合はエラー
        if (count($a_members) == 0) {
            $errors[] = '該当するメールアドレスがありませんでした。';
            return redirect()->route('ctl.brmodifymember.mailsearch', [
                'email' => trim($request->input('email'))
            ])->with([
                'errors' => $errors
            ]);
        }

        return view('ctl.brmodifymember.editmailremove', [
            'partner' => $a_partner,
            'member' => $a_members,
            'email' => trim($request->input('email'))
        ]);
    }

    public function modifymailremove(Request $request)
    {
        if ($this->is_empty($request->input('member_cd'))) {
            $errors[] = 'メールアドレスを入力してください。';
            return redirect()->route('ctl.brmodifymember.mailremove')->with([
                'errors' => $errors
            ]);
        }

        // 有効会員のみ取得
        $member = new Member();
        $a_member = $member->selectByKey($request->input('member_cd')); //find→selectByKeyでいいか？
        if ($a_member['member_status'] == 1) {
            $a_member_cd[] = $request->input('member_cd');
        }

        // 1件以外の場合はエラー
        if (count($a_member_cd) == 0) {
            $errors[] = '該当するメールアドレスがありませんでした。';
            return redirect()->route('ctl.brmodifymember.mailremove')->with([
                'errors' => $errors
            ]);
        }

        //会員基盤実装後に実装
        /////////////////////////////////////////////////////////////////////////
        // // メールアドレスRemoveへ更新
        // $http_client = new Zend_Http_Client();
        // $http_client->setConfig(array('timeout' => 60));
        // $client = new Zend_Rest_Client($this->box->config->rpc_server->protect->host . 'member/');
        // $client->setHttpClient($http_client);
        // $o_response = $client->update_mail_remove(array('member_cd' => $a_member_cd[0]), false)->get();

        // // エラーの場合
        // if (!$o_response->isSuccess()) {
        //     // システムエラーの場合
        //     if ($o_response->error_type() == 'system_error') {
        //         throw new Exception($o_response->messages());
        //     }
        // }
        /////////////////////////////////////////////////////////////////////////

        // 実際にやりたい処理（remove.bestrsv.comに変える処理）は上記のユーザ処理側内で行っているよう。
        // 実装後に修正するため、ここではそれ以外の処理を実行しておく

        // お知らせメール、メールマガジンの受信しないに更新
        $MemberSendingMailModel = new MemberSendingMail($a_member_cd[0]);
        $MemberSendingMailModel->unsendMailMagazine();
        $MemberSendingMailModel->unsendMailMagazineWeek();
        $MemberSendingMailModel->unsendMailThankyou();
        $MemberSendingMailModel->unsendMailStayconfirm();

        // 更新実行
        if (!$MemberSendingMailModel->execute()) {
            //エラーメッセージはいらない？？
            return redirect()->route('ctl.brmodifymember.mailremove', [
                'email' => $request->input('email')
            ]);
        }

        return view('ctl.brmodifymember.modifymailremove', [
            'email' => $request->input('email')
        ]);
    }
}
