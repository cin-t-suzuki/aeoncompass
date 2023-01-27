<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Common\Traits;
use App\Models\Core;
use App\Models\Voice;
use App\Models\VoiceReply;
use App\Models\VoiceStay;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VoiceReplyRequest;
use App\Http\Requests\VoiceStayRequest;
use Exception;

class BrVoiceController extends _commonController
{
    use Traits;

    // 年の表示数
    private $date_ymd_cnt = '6';
    // 一覧の表示数
    private $list_size = '20';

    // 表示
    public function index()
    {
        $o_date = new DateUtil(); //Br_models_date→DateUtilでいいか？

        // 初期設定　投稿日　日付（本日日付の設定
        $a_after['exp_year']   = $o_date->to_format('Y');
        $a_after['exp_month']  = $o_date->to_format('m');
        $a_after['exp_day']    = $o_date->to_format('d');
        $a_before['exp_year']  = $o_date->to_format('Y');
        $a_before['exp_month'] = $o_date->to_format('m');
        $a_before['exp_day']   = $o_date->to_format('d');

        // 初期設定　返答日　日付（本日日付の設定
        $a_after['rep_year']   = $o_date->to_format('Y');
        $a_after['rep_month']  = $o_date->to_format('m');
        $a_after['rep_day']    = $o_date->to_format('d');
        $a_before['rep_year']  = $o_date->to_format('Y');
        $a_before['rep_month'] = $o_date->to_format('m');
        $a_before['rep_day']   = $o_date->to_format('d');

        // 検索項目のチェックボックスの初期値
        $a_search['exp_check'] = 'no';
        $a_search['rep_check'] = 'no';

        // 一覧へ転送
        return redirect()->route('ctl.brvoice.search', [
            'after' => $a_after,
            'before' => $a_before,
            'search' => $a_search
        ]);
    }

    // 検索
    public function search(Request $request)
    {
        // リクエストの取得
        $a_params               = $request->all();
        $a_params['target_cd']  = $request->input('target_cd');
        $a_params['page']       = $request->input('page');
        $a_params['after']      = $request->input('after');
        $a_params['before']     = $request->input('before');
        $a_params['after_dtm']  = $request->input('after_dtm');
        $a_params['before_dtm'] = $request->input('before_dtm');
        $a_params['search']     = $request->input('search');

        // 初期設定
        $_reserve_select_year = date('Y') - 5 . '-01-01';

        // インスタンスの生成
        $voiceModel = new Voice();

        // // 一覧の取得
        // // パートナーコードのセット(仮セット)
        // $voiceModel->setPartnerCd('0000000000'); //Voiceモデル側に記述でいいか？

        // 検索処理でページ遷移してきた場合に検索用データを成型　※データの更新時、改ページ時しかパラメータでpageが渡ってこないので検索判断
        if (!$this->is_empty($a_params['page'])) { //元ソースzap_is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）
            // 投稿日を表示用に解体
            list($a_params['after']['exp_year'], $a_params['after']['exp_month'], $a_params['after']['exp_day']) = explode('-', $a_params['exp_after_dtm']);
            list($a_params['before']['exp_year'], $a_params['before']['exp_month'], $a_params['before']['exp_day']) = explode('-', $a_params['exp_before_dtm']);

            // 返答日を表示用に解体
            list($a_params['after']['rep_year'], $a_params['after']['rep_month'], $a_params['after']['rep_day']) = explode('-', $a_params['rep_after_dtm']);
            list($a_params['before']['rep_year'], $a_params['before']['rep_month'], $a_params['before']['rep_day']) = explode('-', $a_params['rep_before_dtm']);

            $a_params['search']['hotel_cd']  = $request->input('hotel_cd');
            $a_params['search']['keywords']  = $request->input('keywords');
            $a_params['search']['exp_check'] = $request->input('exp_check');
            $a_params['search']['rep_check'] = $request->input('rep_check');
        } else {
            // リクエストから日付の作成
            $a_params['exp_after_dtm']  = $a_params['after']['exp_year'] . '-' . $a_params['after']['exp_month'] . '-' . $a_params['after']['exp_day'];
            $a_params['exp_before_dtm'] = $a_params['before']['exp_year'] . '-' . $a_params['before']['exp_month'] . '-' . $a_params['before']['exp_day'];

            $a_params['rep_after_dtm']  = $a_params['after']['rep_year'] . '-' . $a_params['after']['rep_month'] . '-' . $a_params['after']['rep_day'];
            $a_params['rep_before_dtm'] = $a_params['before']['rep_year'] . '-' . $a_params['before']['rep_month'] . '-' . $a_params['before']['rep_day'];

            // 初期値設定
            $a_params['page'] = 1;
        }

        $a_conditions['hotel_cd']                 = $a_params['search']['hotel_cd'] ?? null; // null追記でいいか？
        $a_conditions['keywords']                 = $a_params['search']['keywords'] ?? null; // null追記でいいか？
        $a_conditions['not_limit_dtm']            = false;    // 有効期限外も取得
        $a_conditions['not_status']               = false;    // 削除状態も取得

        // 投稿日のチェックがあれば投稿日で検索
        if ((!$this->is_empty($a_params['search']['exp_check'] ?? null))) { //元ソース$this->is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
            // 日付の設定
            $o_exp_after_date = new DateUtil($a_params['exp_after_dtm']); //Br_models_date→DateUtilでいいか？
            $o_exp_before_date = new DateUtil($a_params['exp_before_dtm']); //Br_models_date→DateUtilでいいか？

            // 日付を１日足して１秒を引きbeforeに23：59：59をセット
            $o_exp_before_date->add('d', 1);
            $o_exp_before_date->add('s', -1);

            $a_conditions['experience_dtm']['after']  = $o_exp_after_date->to_format('Y-m-d H:i:s');
            $a_conditions['experience_dtm']['before'] = $o_exp_before_date->to_format('Y-m-d H:i:s');
        }

        // 返答日のチェックがあれば返答日で検索
        if ((!$this->is_empty($a_params['search']['rep_check'] ?? null))) { //元ソース$this->is_emptyは使用しない関数と記載アリ→is_emptyでいいか？（0の扱いが違うよう）,null追記でいいか
            // 日付の設定
            $o_rep_after_date = new DateUtil($a_params['rep_after_dtm']); //Br_models_date→DateUtilでいいか？
            $o_rep_before_date = new DateUtil($a_params['rep_before_dtm']); //Br_models_date→DateUtilでいいか？

            // 日付を１日足して１秒を引きbeforeに23：59：59をセット
            $o_rep_before_date->add('d', 1);
            $o_rep_before_date->add('s', -1);

            $a_conditions['reply_dtm']['after']  = $o_rep_after_date->to_format('Y-m-d H:i:s');
            $a_conditions['reply_dtm']['before'] = $o_rep_before_date->to_format('Y-m-d H:i:s');
        }

        $a_offsets = [
            'page' => $a_params['page'],
            'size' => $this->list_size,
        ];

        // 宿泊体験の一覧を取得
        $a_voice_data = $voiceModel->voiceLists($a_conditions, $a_offsets);

        return view('ctl.brvoice.search', [
            'search' => $a_params['search'],
            'target_cd' => $a_params['target_cd'],
            'page' => $a_params['page'],
            'year' => $_reserve_select_year,
            'date_ymd_cnt' => $this->date_ymd_cnt,
            'after' => $a_params['after'],
            'before' => $a_params['before'],
            'exp_after_dtm' => $a_params['exp_after_dtm'],
            'exp_before_dtm' => $a_params['exp_before_dtm'],
            'rep_after_dtm' => $a_params['rep_after_dtm'],
            'rep_before_dtm' => $a_params['rep_before_dtm'],
            'voice_data' => $a_voice_data,
            'conditions' => $a_conditions
        ]);
    }
    public function create(VoiceReplyRequest $request)
    {
        // リクエストの取得
        $a_params['hotel_cd']  = $request->input('target_cd'); //insert用にtarget_cd→hotel_cdへ入れなおす
        $a_params['answer']     = $request->input('answer');
        $a_params['voice_cd']   = $request->input('voice_cd');
        $a_params['reply_type'] = $request->input('reply_type'); // 0:施設 1:運用

        // 追加項目
        $a_params['reply_dtm'] = now();

        // voiceReplyモデルの取得
        $voiceReplyModel = new VoiceReply();

        // 共通カラム値設定
        $voiceReplyModel->setInsertCommonColumn($a_params);

        // コネクション
        $errorList = []; //初期化
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $voiceReplyModel, $a_params) {
                // DB更新
                $voiceReplyModel->insert($con, $a_params);
                //insertでいいか？特有のsaveメソッドがありそう？？
            });
        } catch (Exception $e) {
            $errorList[] = '返信の登録処理でエラーが発生しました。';
        }
        // 更新エラー
        if (
            count($errorList) > 0 || !empty($dbErr)
        ) {
            $errorList[] = "返信を登録できませんでした。 ";
            // search アクションに転送します
            return redirect()->route('ctl.brvoice.search', [
                'target_cd'  => $request->input('target_cd'), //元のtarget_cdで戻すでいいか？
                'page'       => $request->input('page'),
                'exp_after_dtm' => $request->input('exp_after_dtm'),
                'exp_before_dtm' => $request->input('exp_before_dtm'),
                'rep_after_dtm' => $request->input('rep_after_dtm'),
                'rep_before_dtm' => $request->input('rep_before_dtm'),
                'exp_check' => $request->input('exp_check'),
                'rep_check' => $request->input('rep_check'),
                'hotel_cd' => $request->input('hotel_cd'),
                'keywords' => $request->input('keywords'),
            ])->with([
                'errors' => $errorList
            ]);
        }

        //登録完了→検索処理を実行
        $guides[] = "返答の登録を完了しました。";
        return redirect()->route('ctl.brvoice.search', [
            'target_cd'  => $request->input('target_cd'), //元のtarget_cdで戻すでいいか？
            'page'       => $request->input('page'),
            'exp_after_dtm' => $request->input('exp_after_dtm'),
            'exp_before_dtm' => $request->input('exp_before_dtm'),
            'rep_after_dtm' => $request->input('rep_after_dtm'),
            'rep_before_dtm' => $request->input('rep_before_dtm'),
            'exp_check' => $request->input('exp_check'),
            'rep_check' => $request->input('rep_check'),
            'hotel_cd' => $request->input('hotel_cd'),
            'keywords' => $request->input('keywords'),
        ])->with([
            'guides' => $guides
        ]);
    }

    // 変更
    public function update(VoiceReplyRequest $request)
    {

        // リクエストの取得
        $a_params['target_cd'] = $request->input('target_cd');
        $a_params['voice_cd']   = $request->input('voice_cd');

        // voiceReplyモデルの取得
        $voiceReplyModel = new VoiceReply();

        // 更新データの作成
        $voiceReplyData = $voiceReplyModel->selectByWKey($a_params['target_cd'], $a_params['voice_cd']);
        $voiceReplyData['reply_type'] = $request->input('reply_type');
        $voiceReplyData['answer']     = $request->input('answer');
        //更新した場合、返答日は作成日時のままでいいのか？（元ソースでは作成日ままのよう）

        // 共通カラム値設定
        $voiceReplyModel->setUpdateCommonColumn($voiceReplyData);

        // 更新件数
        $dbCount = 0;
        // コネクション
        $errorList = []; //初期化
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $voiceReplyModel, $voiceReplyData, &$dbCount) {
                // DB更新
                $dbCount = $voiceReplyModel->updateByWKey($con, $voiceReplyData);
                //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
            });
        } catch (Exception $e) {
            $errorList[] = '返答の更新処理でエラーが発生しました。';
        }
        // 更新エラー
        if (
            $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
        ) {
            //改行いらないのでは？ $errorList[] = "更新エラー<br>トップページよりやり直してください ";
            $errorList[] = "更新エラー：トップページよりやり直してください ";
            // search アクションに転送します
            return redirect()->route('ctl.brvoice.search', [
                'target_cd'  => $a_params['target_cd'],
                'page'       => $request->input('page'),
                'exp_after_dtm' => $request->input('exp_after_dtm'),
                'exp_before_dtm' => $request->input('exp_before_dtm'),
                'rep_after_dtm' => $request->input('rep_after_dtm'),
                'rep_before_dtm' => $request->input('rep_before_dtm'),
                'exp_check' => $request->input('exp_check'),
                'rep_check' => $request->input('rep_check'),
                'hotel_cd' => $request->input('hotel_cd'),
                'keywords' => $request->input('keywords'),
            ])->with([
                'errors' => $errorList
            ]);
        }

        //登録完了→検索処理を実行
        $guides[] = "返答の更新を完了しました。";
        return redirect()->route('ctl.brvoice.search', [
            'target_cd'  => $a_params['target_cd'],
            'page'       => $request->input('page'),
            'exp_after_dtm' => $request->input('exp_after_dtm'),
            'exp_before_dtm' => $request->input('exp_before_dtm'),
            'rep_after_dtm' => $request->input('rep_after_dtm'),
            'rep_before_dtm' => $request->input('rep_before_dtm'),
            'exp_check' => $request->input('exp_check'),
            'rep_check' => $request->input('rep_check'),
            'hotel_cd' => $request->input('hotel_cd'),
            'keywords' => $request->input('keywords'),
        ])->with([
            'guides' => $guides
        ]);
    }

    // 削除と削除取り消しの切り替え
    public function switch(VoiceStayRequest $request)
    {
        //要確認：VoiceStayRequest側のコメント
        //むしろこっちではVoiceStayRequest使わない方がいい？？（その場合バリデーションどうする？不要？）

        // リクエストの取得
        $a_params['voice_cd']   = $request->input('voice_cd');

        // voiceStayモデルの作成
        $voiceStayModel = new VoiceStay();

        // 更新情報の取得
        $a_voice_stay = $voiceStayModel->selectByKey($a_params['voice_cd']); //find→selectbykeyでいいか？

        // 削除と削除取り消しの切り替え
        if ($a_voice_stay['status'] == 1 || $a_voice_stay['status'] == 2) {
            // 削除状態から有効へ
            $n_status = 0;
            $s_mes = "削除取消";
        } else {
            // 管理側からの操作なので強制
            $n_status = 2;
            $s_mes = "削除";
        }

        // 更新データの作成
        $a_voice_stay['status']     = $n_status; // 0:有効 1:本人取り消し 2:強制取り消し

        // 共通カラム値設定
        $voiceStayModel->setUpdateCommonColumn($a_voice_stay);

        // 更新件数
        $dbCount = 0;
        // コネクション
        $errorList = []; //初期化
        try {
            $con = DB::connection('mysql');
            $dbErr = $con->transaction(function () use ($con, $voiceStayModel, $a_voice_stay, &$dbCount) {
                // DB更新
                $dbCount = $voiceStayModel->updateByKey($con, $a_voice_stay);
                //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
            });
        } catch (Exception $e) {
            $errorList[] = '返答の更新処理でエラーが発生しました。';
        }
        // 更新エラー
        if (
            $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
        ) {
            //改行いらないのでは？ $errorList[] = "削除エラー<br>トップページよりやり直してください";
            $errorList[] = "削除エラー：トップページよりやり直してください ";
            // search アクションに転送します
            return redirect()->route('ctl.brvoice.search', [
                'target_cd'  => $request->input('target_cd'),
                'page'       => $request->input('page'),
                'exp_after_dtm' => $request->input('exp_after_dtm'),
                'exp_before_dtm' => $request->input('exp_before_dtm'),
                'rep_after_dtm' => $request->input('rep_after_dtm'),
                'rep_before_dtm' => $request->input('rep_before_dtm'),
                'exp_check' => $request->input('exp_check'),
                'rep_check' => $request->input('rep_check'),
                'hotel_cd' => $request->input('hotel_cd'),
                'keywords' => $request->input('keywords'),
            ])->with([
                'errors' => $errorList
            ]);
        }

        //登録完了→検索処理を実行
        $guides[] = "宿泊体験の' . $s_mes . 'を完了しました。";
        return redirect()->route('ctl.brvoice.search', [
            'target_cd'  => $request->input('target_cd'),
            'page'       => $request->input('page'),
            'exp_after_dtm' => $request->input('exp_after_dtm'),
            'exp_before_dtm' => $request->input('exp_before_dtm'),
            'rep_after_dtm' => $request->input('rep_after_dtm'),
            'rep_before_dtm' => $request->input('rep_before_dtm'),
            'exp_check' => $request->input('exp_check'),
            'rep_check' => $request->input('rep_check'),
            'hotel_cd' => $request->input('hotel_cd'),
            'keywords' => $request->input('keywords'),
        ])->with([
            'guides' => $guides
        ]);
    }
}
