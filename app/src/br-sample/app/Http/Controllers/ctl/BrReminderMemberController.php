<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Common\Traits;
use App\Models\Member;
use App\Models\Core;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\SubmitFormCheckRequest;

class BrReminderMemberController extends _commonController
{
    use Traits;

    // インデックス
    public function index(Request $request)
    {
        // エラーメッセージがあれば取得
        $errors = $request->session()->get('errors', []);

        return view('ctl.brremindermember.index', [
            'family_nm' => $request->input('family_nm'),
            'given_nm' => $request->input('given_nm'),
            'birth_ymd' => $request->input('birth_ymd'),
            'tel' => $request->input('tel'),

            'errors' => $errors
        ]);
    }

    public function search(Request $request)
    {
        // 入力内容確認
        $result = $this->checkInput($request);
        if ($result !== true) { //!$this->checkInput()から変更（エラーメッセージを返したいため）
            return redirect()->route('ctl.brremindermember.index', [
                'family_nm' => $request->input('family_nm'),
                'given_nm' => $request->input('given_nm'),
                'birth_ymd' => $request->input('birth_ymd'),
                'tel' => $request->input('tel'),
                ])->with([
                    'errors' => $result
                ]);
        }

        // 検索
        $a_conditions = [
            'family_nm'      => $request->input('family_nm'),
            'given_nm'       => $request->input('given_nm'),
            'tel'            => $request->input('tel'),
            'birth_ymd'      => $request->input('birth_ymd')
        ];
        $a_member = $this->getSearchMember($a_conditions);

        if ($this->is_empty($a_member)) {
            $errors[] = '該当する会員は見つかりませんでした。';
            return redirect()->route('ctl.brremindermember.index', [
                'family_nm' => $request->input('family_nm'),
                'given_nm' => $request->input('given_nm'),
                'birth_ymd' => $request->input('birth_ymd'),
                'tel' => $request->input('tel'),
            ])->with([
                'errors' => $errors
            ]);
        } else {
            $guides[] = count($a_member) . '名の会員が見つかりました。';
        }

        return view('ctl.brremindermember.search', [
            'family_nm' => $request->input('family_nm'),
            'given_nm' => $request->input('given_nm'),
            'birth_ymd' => $request->input('birth_ymd'),
            'tel' => $request->input('tel'),

            'guides' => $guides
        ]);
    }

    // 入力内容の確認
    private function checkInput($request) //リクエストとるために引数追加していいか？
    {
        if (
            $this->is_empty($request->input('family_nm'))
            || $this->is_empty($request->input('given_nm'))
            || $this->is_empty($request->input('birth_ymd'))
            || $this->is_empty($request->input('tel'))
        ) {
            $errors[] = '氏名、生年月日、電話番号 全ての項目を入力してください。';
            return $errors;
        }

        $o_models_date = new DateUtil(); //br_models_date→DateUtilでいいか？
        if (!$o_models_date->is_date($this->getDateFormat($request->input('birth_ymd'))) && !$this->is_empty($request->input('birth_ymd'))) {
            $errors[] = '生年月日の型が不正です。正しく入力してください。';
            return $errors;
        }

        return true;
    }

    private function getDateFormat($as_date)
    {
        // trim
        $s_date = trim(str_replace('　', '', $as_date));

        // 全角数値を半角数値に
        $s_date = mb_convert_kana($s_date, "a", "UTF-8");

        // 区切り文字を「-」
        $s_date = str_replace('/', '-', $s_date);
        $s_date = str_replace('年', '-', $s_date);
        $s_date = str_replace('月', '-', $s_date);
        $s_date = str_replace('日', '', $s_date);

        // 年月日先頭ゼロ埋め
        $a_date = explode('-', $s_date);
        $s_date = sprintf('%04d-%02d-%02d', $a_date[0], $a_date[1] ?? '', $a_date[2] ?? ''); //日付がない時用に ?? ''でいいか

        return ($s_date);
    }


    private function getTelFormat($as_tel)
    {
        // trim
        $s_tel = trim(str_replace('　', '', $as_tel));

        // 全角数値を半角数値に
        $s_tel = mb_convert_kana($s_tel, "a", "UTF-8");

        // 区切り文字を消去
        $s_tel = str_replace('-', '', $s_tel);
        $s_tel = str_replace('(', '', $s_tel);
        $s_tel = str_replace(')', '', $s_tel);

        return ($s_tel);
    }


    // ベストリザーブ会員を検索し、該当する会員情報を返す
    //   aa_condition
    //     family_nm
    //     given_nm
    //     tel
    //     birth_ymd
    //
    //   return array();
    private function getSearchMember($aa_conditions)
    {
        try {
            $detail = "";
            $a_conditions = [];
            // family_nmを設定
            $detail .= "	and	trim(replace(family_nm, '　', '')) = trim(:family_nm)";
            $a_conditions['family_nm'] = trim(str_replace('　', '', $aa_conditions['family_nm']));

            // given_nmを設定
            $detail .= "	and	trim(replace(given_nm, '　', '')) = :given_nm";
            $a_conditions['given_nm'] = trim(str_replace('　', '', $aa_conditions['given_nm']));

            // telを設定
            $detail .= "	and	(replace(replace(replace(tel, '-', ''), '(', ''), ')', '') = :tel1 || replace(replace(replace(optional_tel, '-', ''), '(', ''), ')', '') = :tel2)";
            $a_conditions['tel1'] = $this->getTelFormat($aa_conditions['tel']);
            $a_conditions['tel2'] = $this->getTelFormat($aa_conditions['tel']);

            // birth_ymdを設定
            $detail .= "	and	birth_ymd = date_format(:birth_ymd, '%Y-%m-%d')"; //to_date(:birth_ymd, 'YYYY-MM-DD')
            $a_conditions['birth_ymd'] = $this->getDateFormat($aa_conditions['birth_ymd']);//$request->input('birth_ymd')→$aa_conditionsではだめ？

            $s_sql =
            <<< SQL
						select	q3.member_cd,
								q3.account_id,
								q3.family_nm,
								q3.given_nm,
								q3.email,
								q1.entry_dtm as entry_dtm, -- to_charは削除
								q1.member_type,
								q1.reserve_system,
								q2.system_nm,
								q2.partner_cd
						from	member q1
                        left outer join
								partner q2
                                on q1.partner_cd = q2.partner_cd,
							(
								select	member_cd,
										account_id,
										family_nm,
										given_nm,
										email
								from	member_detail
								where	null is null
									{$detail}
							) q3
						where	q3.member_cd = q1.member_cd
							-- and	q1.partner_cd = q2.partner_cd(+) 書き替えは↑のleftouterjoinであっているか？
							and	q1.member_status = 1
SQL;

            $a_member = Db::select($s_sql, $a_conditions);
            // 取得した値の保管
            // $o_member = new Member(); ※下記会員実装後に使用する想定
            $a_members = [];
            for ($n_cnt = 0; $n_cnt < count($a_member); $n_cnt++) {
                // 登録年月
                $o_date = new DateUtil($a_member[$n_cnt]->entry_dtm); //br_models_date→DateUtilでいいか？
                $a_member[$n_cnt]->entry_ym = $o_date->to_format('Y年n月');

                // 登録サイト //不要？？
                if ($a_member[$n_cnt]->reserve_system == 'dash') {
                    $a_member[$n_cnt]->site_nm = $a_member[$n_cnt]->system_nm;
                } else {
                    $a_member[$n_cnt]->site_nm = 'ベストリザーブ・宿ぷらざ';
                    if ($a_member[$n_cnt]->member_type == 0) {
                        $a_member[$n_cnt]->site_nm = 'ベストリザーブ';
                    } elseif ($a_member[$n_cnt]->member_type == 1) {
                        $a_member[$n_cnt]->site_nm = '宿ぷらざ';
                    }
                }

                // メールアドレス暗号化解除
                // $o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->protect->key);
                $cipher = new Models_Cipher(config('settings.cipher_key'));
                $a_member[$n_cnt]->email   = $cipher->decrypt($a_member[$n_cnt]->email);

                // パスワード照会
                //会員機能未実装のため、テストデータを返す ※実装後に要修正
                // $a_pwd = $o_member->getPassword($a_member[$n_cnt]->member_cd);
                $a_pwd =  [
                    'member_cd' => '01Cuw1Pfo4kkZxbi74vW',
                    'password' => 'test'
                ];

                if (!$this->is_empty($a_pwd['password'] ?? null)) {
                    $a_member[$n_cnt]->password = $a_pwd['password'];
                    $a_members[] = $a_member[$n_cnt];
                } else {
                    // パスワードが見つからない場合は、基本的にないけど検証環境で確認した場合見つからない場合があるのでエラーをクリアーしておく。
                    // $this->box->item->error->clear();
                    //エラーメッセージはこのメソッド内では定義されていないので不要でいいか？
                }
            }

            return $a_members;

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function send(SubmitFormCheckRequest $request)
    {
        // 入力内容確認
        $result = $this->checkInput($request);
        if ($result !== true) { //!$this->checkInput()から変更（エラーメッセージを返したい他メソッドに合わせて）
            return redirect()->route('ctl.brremindermember.index', [
                'family_nm' => $request->input('family_nm'),
                'given_nm' => $request->input('given_nm'),
                'birth_ymd' => $request->input('birth_ymd'),
                'tel' => $request->input('tel'),
            ]);
        }

        // 検索
        $a_conditions = [
                                'family_nm'      => $request->input('family_nm'),
                                'given_nm'       => $request->input('given_nm'),
                                'tel'            => $request->input('tel'),
                                'birth_ymd'      => $request->input('birth_ymd')
        ];
        $a_member = $this->getSearchMember($a_conditions);

        if ($this->is_empty($a_member)) {
            $errors[] = '氏名、生年月日、電話番号 全ての項目を入力してください。';
            return redirect()->route('ctl.brremindermember.index', [
                'family_nm' => $request->input('family_nm'),
                'given_nm' => $request->input('given_nm'),
                'birth_ymd' => $request->input('birth_ymd'),
                'tel' => $request->input('tel'),
            ])->with([
                'errors' => $errors
            ]);
        }

        // 重複登録を防止します。
        $o_core = new Core();
        $b_ret = $o_core->isDuplicate($request->input('sfck'));
        if (!$b_ret) {
            // $this->box->item->error->clear();
            $errors[] = null; //↑書き替えあっているか？
            $errors[] = 'ダブルクリックやリロードなどで重複実行されました。';
            $errors[] = 'すでに該当する登録会員（' . count($a_member) . '名）全てに対し１通ずつ通知済みです。 '; //number_format(count($a_member))→countは数字返すのでnumber_formatはいらない？
            $errors[] = '再度送信する必要がある場合は検索から操作しなおしてください。';
            $errors[] = '氏名、生年月日、電話番号 全ての項目を入力してください。';
            return redirect()->route('ctl.brremindermember.index', [
                'family_nm' => $request->input('family_nm'),
                'given_nm' => $request->input('given_nm'),
                'birth_ymd' => $request->input('birth_ymd'),
                'tel' => $request->input('tel'),
            ])->with([
                'errors' => $errors
            ]);
        }

        // // Mail モデルインスタンスの生成
        // $sendMailModel = new SendMail(); ※メール処理実装時に使用

        // アサインの登録
        for ($n_cnt = 0; $n_cnt < count($a_member); $n_cnt++) {
            $mail = [
                'account_id'     => $a_member[$n_cnt]->account_id,
                'password'       => $a_member[$n_cnt]->password,
                'family_nm'      => $a_member[$n_cnt]->family_nm,
                'given_nm'       => $a_member[$n_cnt]->given_nm,
                'entry_ym'       => $a_member[$n_cnt]->entry_ym,
                'site_nm'        => $a_member[$n_cnt]->site_nm,
                'reserve_system' => $a_member[$n_cnt]->reserve_system,
                'partner_cd'     => $a_member[$n_cnt]->partner_cd
            ];

            // メール送信
            // if (!$sendMailModel->reminderPtn0($a_member[$n_cnt]->email)) {　//メール送信処理未実装のため以下で仮実装 ※実装後要修正
            if (!true) { //テスト用、成功時
            // if (!false) { //テスト用、失敗時
                $errors[] = '案内通知の登録に失敗しましたので、該当する登録会員（' . number_format(count($a_member)) . '名）全てに対しての通知ができませんでした。';
                // $this->oracle->rollback();
                return redirect()->route('ctl.brremindermember.index', [
                    'family_nm' => $request->input('family_nm'),
                    'given_nm' => $request->input('given_nm'),
                    'birth_ymd' => $request->input('birth_ymd'),
                    'tel' => $request->input('tel'),
                    'members' => $a_member,
                    'mail' => $mail
                ])->with([
                    'errors' => $errors
                ]);
            }
        }

        $guides[] = '全ての項目が完全一致で該当する登録会員（' . count($a_member) . '名）全てに対して、登録メールアドレス宛てに会員コード・パスワードを案内いたしました。'; //number_format(count($a_member))→countは数字返すのでnumber_formatはいらない？

        return view('ctl.brremindermember.send', [
            'family_nm' => $request->input('family_nm'),
            'given_nm' => $request->input('given_nm'),
            'birth_ymd' => $request->input('birth_ymd'),
            'tel' => $request->input('tel'),
            'members' => $a_member,
            'mail' => $mail,

            'guides' => $guides
        ]);
    }
}
