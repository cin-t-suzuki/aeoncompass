<?php

namespace App\Http\Controllers\ctl;

use App\Common\DateUtil;
use App\Common\Traits;
use App\Models\PartnerCustomer;
use App\Util\Models_Cipher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Demand;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Plan;
use App\Models\ChecksheetFix;
use App\Models\Reserve;
use App\Models\CorePlan;
use Exception;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Requests\ChecksheetFixRequest;

class BrReserveCkController extends _commonController
{
    use Traits;

    // ページング
    private $reserve_list_size = 20;

    // セレクトボックスの年の最小値
    private $reserve_select_year = '';

    // ループカウント
    private $year_loop_cnt = '11';


    // 初期表示
    public function index(Request $request)
    {
        $a_search = $request->input('Search');

        // エラーメッセージの取得
        $errors = $request->session()->get('errors', []);

        // セレクトボックスの初期値を設定
        $this->reserve_select_year = date('Y') - 5 . '-01-01';

        return view('ctl.brReserveCk.index', [
            'reserve_select_year'        => $this->reserve_select_year,
            's_cnt'    => $this->year_loop_cnt,
            'search'         => $a_search,

            'errors'        => $errors,
        ]);
    }

    // 検索
    public function search(Request $request)
    {

        $a_search = $request->input('Search');

        // 検索条件が入力されていなければ
        $errors = []; //初期化
        if ($this->is_empty($a_search['keywords'] ?? null) && $this->is_empty($a_search['keywords'] ?? null)) {
        //バリデーションエラーで戻ってきたときに??null必要
            // エラーメッセージ
            $errorList[] = "施設名か施設コードを入力してください。";
            // エラー時indexへ
            return redirect()->route('ctl.brReserveCk.index', [
                'Search' => $a_search
            ])->with([
                'errors' => $errorList,
            ]);
        }

        // ガイドメッセージの取得
        $guides = $request->session()->get('guides', []);

        // セレクトボックスの初期値を設定
        $this->reserve_select_year = date('Y') - 5 . '-01-01';

        $o_date = new DateUtil($a_search['year'] . '-' . $a_search['month'] . '-01'); //Br_Models_DateはDateUtilでいいか？

        // 2009-07-01 以降に予約手続きが行われた予約は予約日時を元にシステム利用料を算出
        if ($o_date->to_format('Ymd') < '20090701') {
            $a_date['date_ymd']['after'] = $o_date->to_format('Y-m-d');
            $o_date->last_day();
            $a_date['date_ymd']['before'] = $o_date->to_format('Y-m-d');
        } else {
            $o_date->add('d', -1);

            $a_date['date_ymd']['after'] = $o_date->to_format('Y-m-d');

            $o_date->add('m', 1);
            $o_date->last_day();
            $o_date->add('d', -1);
            $a_date['date_ymd']['before'] = $o_date->to_format('Y-m-d');
        }

        // 処理月を取得
        $o_date->add('m', 1);

        // 対象月の送客日を取得
        $o_core_demmand = new Demand();
        $n_send_customers_ymd = ($o_core_demmand->getSendCustomersYmd($o_date->to_format('Y-m')) ?? strtotime($o_date->to_format('Y-m') . '-01')); //nvl→??でいいか

        // 対象月の締日を取得
        $n_dead_line_ymd = ($o_core_demmand->getDeadlineYmd($o_date->to_format('Y-m')) ?? strtotime($o_date->to_format('Y-m') . '-08')); //nvl→??でいいか

        $hotelModel = new Hotel();

        // 検索条件設定
        $a_conditions = [
            'entry_status' => null, //引数が必要なためnull追記でいいか？（下記同様）
            'pref_id'      => null,
            'stock_type'   => null,
            'keywords'     => $a_search['keywords'],
        ];

        // $hotelModel->set_partner_cd('0000000000');

        // Hotelを取得する。
        $errrorArr = []; //第一引数無いとエラーになるので空配列で追記
        $a_hotel_lists = $hotelModel->search($errrorArr, $a_conditions);

        // 送客研修完了テーブル を参照
        $checksheet_fix = new ChecksheetFix();
        for ($n_cnt = 0; $n_cnt < count($a_hotel_lists['values']); $n_cnt++) {
            $s_checksheet_ym = $o_date->to_format('Y-m') . '-01';
            $a_checksheet_fix = $checksheet_fix->selectByWKey($s_checksheet_ym, $a_hotel_lists['values'][$n_cnt]->hotel_cd); // find→selectByWKeyへ変更でいいか？
            $a_hotel_lists['values'][$n_cnt]->fix_status = $a_checksheet_fix['fix_status'] ?? null; //null追記でいいか？
        }

        if (count($a_hotel_lists['values']) == 0) {
            // エラーメッセージ
            $errorList[] = "入力された条件に該当するデータが見つかりませんでした。条件を見直して、再度検索してください。"; //改行とってしまったがいいか？
            // エラー時indexへ
            return redirect()->route('ctl.brReserveCk.index', [
                'Search' => $a_search
            ])->with([
                'errors' => $errorList,
            ]);
        }

        return view('ctl.brReserveCk.search', [
            'reserve_select_year'        => $this->reserve_select_year,
            's_cnt'    => $this->year_loop_cnt,
            'key'   => $request->input('key'),
            'search_word'    => $request->input('search_word'),
            'search'         => $a_search,
            'checksheet_ym' => $s_checksheet_ym,
            'hotel_lists'     => $a_hotel_lists,
            'date_ymd'     => $a_date['date_ymd'],

            'dead_line_ymd' => $n_dead_line_ymd,
            'send_customers_ymd'     => $n_send_customers_ymd,

            'guides'        => $guides,
            'errors'        => $errors,
        ]);
    }

    public function update(ChecksheetFixRequest $request)
    {

        // リクエストの取得
        $a_checksheet_fix = $request->input('checksheet_fix');
        $Search = $request->input('Search'); // 戻しデータ用に取得

        //ChecksheetFix モデル を取得
        $checksheetFixModel = new ChecksheetFix();

        $checksheet_fix_buf = $checksheetFixModel->selectByWKey($a_checksheet_fix['checksheet_ym'], $a_checksheet_fix['hotel_cd']);
        $requestChecksheetFix = $a_checksheet_fix;

        if ($a_checksheet_fix['fix_status'] == 1) {
            $requestChecksheetFix['fix_dtm'] = now();
        } else {
            $requestChecksheetFix['fix_dtm'] = null;
        }

        // 新規登録の場合のみ
        if (count($checksheet_fix_buf) == 0) {
            // 共通カラム値設定
            $checksheetFixModel->setInsertCommonColumn($requestChecksheetFix);
        // それ以外（更新）
        } else {
            // 共通カラム値設定
            $checksheetFixModel->setUpdateCommonColumn($requestChecksheetFix);
        }

        //Hotel モデルを取得
        $hotelModel = new Hotel();

        // ホテルの情報の取得
        $a_hotel = $hotelModel->selectByKey($a_checksheet_fix['hotel_cd']); // find→selectbyKeyでいいか

        //
        $o_models_date = new DateUtil(); // Br_models_Date→DateUtilでいいか

        $o_models_date->set($a_checksheet_fix['checksheet_ym']);
        $o_models_date->add('m', -1);

        // 登録、更新、エラー時の固定メッセージ作成
        $s_msg = $o_models_date->to_format('Y年m月') . " " . $a_hotel['hotel_nm'] . " ";

        // データがあれば登録、無ければ更新
        if (count($checksheet_fix_buf) == 0) {
            // コネクション
            $errorList = []; // 初期化
            try {
                $con = DB::connection('mysql');
                $dbErr = $con->transaction(function () use ($con, $checksheetFixModel, $requestChecksheetFix) {
                    // DB更新
                    $checksheetFixModel->insert($con, $requestChecksheetFix);
                    //insertでいいか？
                });
            } catch (Exception $e) {
                $errorList[] = '送客実績チェックの登録処理でエラーが発生しました。';
            }
            // 更新エラー
            if (count($errorList) > 0 || !empty($dbErr)) {
                $errorList[] = "送客実績チェックを登録できませんでした。 ";
                return redirect()->route('ctl.brReserveCk.index', [
                    'Search' => $Search
                ])->with([
                    'errors' => $errorList
                ]);
            }
            $guides = $s_msg . '送客実績チェックの登録が完了しました。';
        } else {
            // 更新件数
            $dbCount = 0;
            // コネクション
            $errorList = []; // 初期化
            try {
                $con = DB::connection('mysql');
                $dbErr = $con->transaction(function () use ($con, $checksheetFixModel, $requestChecksheetFix, &$dbCount) {
                    // DB更新
                    $dbCount = $checksheetFixModel->updateByWKey($con, $requestChecksheetFix);
                    //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
                });
            } catch (Exception $e) {
                $errorList[] = '送客実績チェックの更新処理でエラーが発生しました。';
            }
            // 更新エラー
            if (
                $dbCount == 0 || count($errorList) > 0 || !empty($dbErr)
            ) {
                $errorList[] = "送客実績チェックを更新できませんでした。 ";
                return redirect()->route('ctl.brReserveCk.index', [
                    'Search' => $Search
                ])->with([
                    'errors' => $errorList
                ]);
            }

            $guides[] = $s_msg . '送客実績チェックの更新が完了しました。';
        }


        return redirect()->route('ctl.brReserveCk.search', [
            'Search' => $Search
        ])->with([
            'guides' => $guides
        ]);
    }

    // 送客実績・料金変更
    public function reserveck(Request $request)
    {
        // リクエストの取得
        $a_params = $request->all(); // $this->params();の書き換え

        // ページングの初期化
        if ($this->is_empty($a_params['page'] ?? null)) { //null追記でいいか
            $a_params['page'] = 1;
        }

        // 値が存在すれば  ※改ページの日付の持ち回し対応
        if (!$this->is_empty($a_params['date_ymd_before'] ?? null)) { //null追記でいいか
            $a_params['date_ymd']['before'] = $a_params['date_ymd_before'];
        }

        // 値が存在すれば  ※改ページの日付の持ち回し対応
        if (!$this->is_empty($a_params['date_ymd_after'] ?? null)) { //null追記でいいか
            $a_params['date_ymd']['after']  = $a_params['date_ymd_after'];
        }

        // Reserveモデルの取得
        $reserveModel = new Reserve();

        // 検索用の予約コードが渡ってくれば検索条件へ
        $a_conditions['reserve_cd'] = null; //初期化、nullでいいか？
        if (!$this->is_empty($a_params['search_reserve_cd'] ?? null)) { //null追記でいいか
            $a_conditions['reserve_cd'] = $a_params['search_reserve_cd'];
        }

        // 検索条件のセット
        $a_conditions['hotel_cd']           = $a_params['target_cd'];
        $a_conditions['date_ymd']['after']  = $a_params['date_ymd']['after'];
        $a_conditions['date_ymd']['before'] = $a_params['date_ymd']['before'];

        $a_order = ['date_ymd' => 'asc'];

        // offsetのセット
        $a_offsets = ['page' => $a_params['page'], 'size' => $this->reserve_list_size];

        // 予約情報の取得
        $reserveModel->reserves($a_conditions);
        $a_reserve_data = $reserveModel->getReserveDays(['include_member' => false], $a_order, $a_offsets);

        // powerの判定
        $core_plan   = new CorePlan();
        //書き換え以下でいいか？set~cdは使わないでいい気がする
        // $core_plan->set_hotel_cd($a_reserve_data['values'][0]['hotel_cd']);
        // $core_plan->set_room_cd($a_reserve_data['values'][0]['room_cd']);
        // $core_plan->set_plan_cd($a_reserve_data['values'][0]['plan_cd']);
        $hotel_cd = $a_reserve_data['values'][0]->hotel_cd;
        $room_cd = $a_reserve_data['values'][0]->room_cd;
        $plan_cd = $a_reserve_data['values'][0]->plan_cd;
        $is_power = $core_plan->isPower($hotel_cd, $room_cd, $plan_cd);

        // ホテル情報の取得
        $hotelModel = new Hotel();
        $a_hotel_data = $hotelModel->selectByKey($a_params['target_cd']); //find→selectByKeyでいいか

        // システム利用料（税別）合計 の取得
        $o_date = new DateUtil($a_params['date_ymd']['before']); //Br_Models_DateはDateUtilでいいか
        $n_system_charge = $reserveModel->getBillChargeTotal($a_params['target_cd'], ['after' => $a_params['date_ymd']['after'], 'before' => $a_params['date_ymd']['before']]);

        // 表示制御用  ※未来日の更新対応
        if (count($a_reserve_data['values']) > 1) {
            $d_last_date = $a_reserve_data['values'][count($a_reserve_data['values']) - 1]->date_ymd;
        } else {
            $d_last_date = null;
        } //if文追記でいいか？nullでいいか？

        //ページャー設定追加
            $per_page = 10; // 1ページ当りの表示数
            // ページ番号が指定されていなかったら１ページ目
            $page_num = isset($a_params['page']) ? $a_params['page'] : 1;
            // ページ番号に従い、表示するレコードを切り出す
            $disp_rec = array_slice($a_reserve_data['values'], ($page_num - 1) * $per_page, $per_page, true);
            // ページャーオブジェクトを生成
            $pager = new \Illuminate\Pagination\LengthAwarePaginator(
                $disp_rec, // ページ番号で指定された表示するレコード配列
                count($a_reserve_data['values']), // 検索結果の全レコード総数
                $per_page, // 1ページ当りの表示数
                $page_num, // 表示するページ
                ['path' => ''] // ページャーのリンク先のURLを指定
            );

        return view('ctl.brReserveCk.reserveck', [
            'pager' => $pager,

            'is_power'    => $is_power,
            'page'   => $a_params['page'],
            'target_cd'    => $a_params['target_cd'],
            'date_ymd'     => $a_params['date_ymd'],
            'system_charge' => $n_system_charge,
            'conditions' => $a_conditions,
            'reserve_data'     => $a_reserve_data,
            'hotel_data' => $a_hotel_data,
            'search_reserve_cd'     => $a_params['search_reserve_cd'] ?? null, //null追記でいいか？
            'last_date'     => $d_last_date,
            'return_pass' => $request->input('controller'),
        ]);
    }

    // csvダウンロード
    public function csv(Request $request)
    {

        // リクエストの取得
        $a_params['target_cd']  = $request->input('target_cd');
        $a_params['date_ymd']   = $request->input('date_ymd');
        $a_params['reserve_cd'] = $request->input('reserve_cd');
        $a_params               = $request->input();

        // Reserveモデルの取得
        $reserveModel = new Reserve();

        // 検索条件のセット
        $a_conditions['hotel_cd']           = $a_params['target_cd'];
        $a_conditions['reserve_cd']         = $a_params['reserve_cd'];
        $a_conditions['date_ymd']['after']  = $a_params['date_ymd']['after'];
        $a_conditions['date_ymd']['before'] = $a_params['date_ymd']['before'];

        $a_order = ['date_ymd' => 'asc'];

        // 予約情報の取得
        $reserveModel->reserves($a_conditions);
        $a_reserve_data = $reserveModel->getReserveDays(['include_member' => true], $a_order);

        $header = $reserveModel->setCsvHeader($a_reserve_data); //renderでbladeからではなく、モデルからの取得に変更
        $data = $reserveModel->setCsvData($a_reserve_data);
        $csvList = array_merge([$header], $data);

        $response = new StreamedResponse(function () use ($request, $csvList) {
            $stream = fopen('php://output', 'w');

            //　文字化け回避
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');

            // CSVデータ
            foreach ($csvList as $key => $value) {
                fputcsv($stream, $value);
            }
            fclose($stream);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="reserve_ck.csv"');

        // print $s_response;
        return $response;
        exit;
    }
}
