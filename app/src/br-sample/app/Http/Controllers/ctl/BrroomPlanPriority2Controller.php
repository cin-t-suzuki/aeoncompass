<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Common\Traits;
use App\Models\MastPref;
use App\Models\Hotel;
use App\Models\Plan;
use App\Models\Room;
use App\Models\RoomPlan;
use App\Models\RoomPlanPriority;
use Carbon\Carbon;

class BrroomPlanPriority2Controller extends _commonController
{
    use Traits;

    private $set_priority_cnt = 10; // 重点表示順位の登録表示件数
    private $set_wday
        = array(2 => "月曜", 3 => "火曜", 4 => "水曜", 5 => "木曜", 6 => "金曜", 7 => "土曜", 1 => "日曜");
    // tplでは月曜日始まりなので2から始まる ※wday 1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜

    //======================================================================
    // 一覧
    //======================================================================
    public function list()
    {
        //初期化
        $a_select_pref = []; //追記
        $a_priority_list = []; //追記

        // データを取得
        //別アクションからのredirectの場合は渡されたデータを反映する
        if (session()->has('return_data')) {
            $a_priority = session()->pull('return_data');
            if (session()->has('guide')) {
                $guide = session()->pull('guide');
                $this->addGuideMessage($guide);
            }
            if (session()->has('error')) {
                $error = session()->pull('error');
                $this->addErrorMessageArray($error);
            }
        } else {
            //それ以外（初期表示）
            $requestAdvert = Request::all();
            $a_priority = $requestAdvert['priority'] ?? null; //null追記
        }

        // Mast_Pref モデルを取得
        $mastPrefModel = new MastPref();

        // 都道府県の一覧データを配列で取得
        $mastPrefsData = $mastPrefModel->getMastPrefs(array('not_in_by_pref_id' => array(00, 48)));
        // $mastPrefsData = $mastPrefModel->getMastPrefs(array('not_in_by_pref_id'=> array(00,48)));

        // 選択された都道府県名取得
        $a_row = $mastPrefModel->selectByKey($a_priority['pref_id'] ?? null); //null追記

        //オブジェクト取得
        $planModel = new Plan();

        // 検索用の値が空でなければ検索
        if (!$this->is_empty($a_priority)) {
            // プラン一覧の取得用の値を設定
            $a_conditions = array(
                'pref_id' => $a_priority['pref_id'],
                'span' => $a_priority['span']
            );

            // 重点表示プラン一覧の取得
            $a_room_plan_priorities = $planModel->getRoomPlanPriorities($a_conditions);

            // tpl用に整形
            if (is_array($a_room_plan_priorities['values'])) {
                foreach ($a_room_plan_priorities['values'] as $key => $value) {
                    $a_priority_list[$value['priority']][$value['wday']] = $value;
                }
            }

            // tpl用に整形
            $a_select_pref = array(
                'pref_id' => $a_row['pref_id'],
                'pref_nm' => $a_row['pref_nm']
            );

            $hotelModel = new Hotel();
            $roomModel = new Room();
            $roomPlanModel = new RoomPlan();

            // 重点表示プラン一覧が存在すれば
            if (!$this->is_empty($a_priority_list)) {
                // tpl用に整形
                foreach ($a_priority_list as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $hotelData = $hotelModel->selectByKey($value2['hotel_cd']);

                        $roomData = $roomModel->selectByWKey($value2['hotel_cd'], $value2['room_cd']);

                        $planData = $roomPlanModel
                                    ->selectByTripleKey($value2['hotel_cd'], $value2['room_cd'], $value2['plan_cd']);

                        //以下３つnull追記
                        $a_priority_list[$key][$key2]['hotel_nm'] = mb_substr($hotelData['hotel_nm'] ?? null, 0, 12);
                        $a_priority_list[$key][$key2]['room_nm']  = mb_substr($roomData['room_nm'] ?? null, 0, 12);
                        $a_priority_list[$key][$key2]['plan_nm']  = mb_substr($planData['plan_nm'] ?? null, 0, 12);
                    }
                }
            }
        }

        // データを ビューにセット
        $this->addViewData("mast_pref", $mastPrefsData);
        $this->addViewData("select_pref", $a_select_pref);
        $this->addViewData("priority_cd", $a_priority);
        $this->addViewData("priority_list", $a_priority_list);

        $priority_cnt = $this->set_priority_cnt;
        $a_wday = $this->set_wday;
        $this->addViewData("priority_cnt", $priority_cnt); // tpl制御用　登録表示件数を設定
        $this->addViewData("week", $a_wday); // tpl制御用　週の配列

        // ビューを表示
        return view("ctl.brroomplanpriority2.list", $this->getViewData());
    }

    //======================================================================
    // 登録処理
    // バリデーション
    //======================================================================
    private function validateRoomPlanPriorityFromScreen(&$roomPlanPriorityData, $request, $roomPlanPriorityModel)
    {

        // 登録情報
        $roomPlanPriorityData = [];
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_PREF_ID] = $request["pref_id"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_SPAN] = $request["span"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_WDAY] = $request["wday"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_PRIORITY] = $request["priority"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_HOTEL_CD] = $request["hotel_cd"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_ROOM_CD] = $request["room_cd"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_PLAN_CD] = $request["plan_cd"] ?? null;
        $roomPlanPriorityData[$roomPlanPriorityModel->COL_DISPLAY_STATUS] = $request["display_status"] ?? null;

        // バリデーション
        $errorList = $roomPlanPriorityModel->validation($roomPlanPriorityData);

        return $errorList;
    }

    // 登録処理
    // レコードが無ければ新規登録
    // レコードが存在すれば更新
    //施設コード、部屋コード、プランコードが全て入力されていなければ削除
    public function registration()
    {
        // データを取得
        $requestroomPlanPriority = Request::all();
        $a_priority = $requestroomPlanPriority['priority'] ?? null; //null追記

        $roomPlanPriorityModel = new RoomPlanPriority();

        // set_wday 1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜
        // tplでは月曜日始まりなので2から始まる
        foreach ($this->set_wday as $wday => $week_nm) {
            // 施設コード、部屋コード、プランコードの何れかが存在する場合はエラーチェック。
            // チェック後に登録or更新を行う
            //一旦下記3か所をis_emptyに変えてみる＆null追記（zap~は今後使わないと元ソースで追記アリ）
            if (
                !$this->is_empty($a_priority['hotel_cd' . $wday . "_" . $a_priority['priority']] ?? null)
                || !$this->is_empty($a_priority['room_cd' . $wday . "_" . $a_priority['priority']] ?? null)
                || !$this->is_empty($a_priority['plan_cd' . $wday . "_" . $a_priority['priority']] ?? null)
            ) {
                // 重点プラン用存在チェック
                $res = $this->existenceCheck($wday, $week_nm, $a_priority);

                // バリデート結果を判断
                if ($res !== true) {
                    $errorList = $res;
                    $errorList[] = "広告掲載施設の登録ができませんでした。";
                    session()->put('error', $errorList);
                    session()->put('return_data', $a_priority);
                    return redirect()->route('ctl.brroomplanpriority2.list');
                }

                //一旦非表示
                // // エラー時の文言変更
                // $roomPlanPriorityModel->set_logical_nm('hotel_cd', $week_nm."の施設コード");
                // $roomPlanPriorityModel->set_logical_nm('room_cd', $week_nm."の部屋コード");
                // $roomPlanPriorityModel->set_logical_nm('plan_cd', $week_nm."のプランコード");
                // $roomPlanPriorityModel->set_logical_nm('display_status', $week_nm."の重点表示フラグ");

                // 重点表示部屋プランの取得＆設定
                $a_room_plan_priority
                    = $roomPlanPriorityModel
                        ->selectBy4Key($a_priority['pref_id'], $a_priority['span'], $wday, $a_priority['priority']);

                // 重点表示部屋プランが存在しなければ登録処理
                if ($this->is_empty($a_room_plan_priority)) {
                    // 登録するデータを作成
                    $a_room_plan_priority['pref_id']  = $a_priority['pref_id'];
                    $a_room_plan_priority['span']  = $a_priority['span'];
                    $a_room_plan_priority['wday']  = $wday;
                    $a_room_plan_priority['priority']  = $a_priority['priority'];
                    $a_room_plan_priority['hotel_cd']
                        = $a_priority['hotel_cd' . $wday . "_" . $a_priority['priority']];
                    $a_room_plan_priority['room_cd']  = $a_priority['room_cd' . $wday . "_" . $a_priority['priority']];
                    $a_room_plan_priority['plan_cd']  = $a_priority['plan_cd' . $wday . "_" . $a_priority['priority']];
                    $a_room_plan_priority['display_status']
                        = $a_priority['display_status' . $wday . "_" . $a_priority['priority']];

                    // 画面入力を変換
                    $errorList
                        = $this->validateRoomPlanPriorityFromScreen($roomPlanPriorityData, $a_room_plan_priority, $roomPlanPriorityModel);

                    if (count($errorList) > 0) {
                        $errorList[] = "広告掲載施設の登録ができませんでした。";
                        session()->put('error', $errorList);
                        session()->put('return_data', $a_priority);
                        return redirect()->route('ctl.brroomplanpriority2.list');
                    }

                    //登録用の共通の値をセット
                    $roomPlanPriorityModel->setInsertCommonColumn($roomPlanPriorityData);

                    // 登録
                    // コネクション
                    try {
                        $con = DB::connection('mysql');
                        $dbErr = $con->transaction(function () use ($con, $roomPlanPriorityModel, $roomPlanPriorityData) {
                            // DB更新
                            $roomPlanPriorityModel->insert($con, $roomPlanPriorityData);
                            //insertでいいか？
                        });
                    } catch (Exception $e) {
                        $errorList[] = '広告掲載施設の登録ができませんでした。';
                    }

                    // 重点表示部屋プランが存在すれば更新処理
                } else {
                    // 登録するデータを作成
                    $a_room_plan_priority['pref_id']  = $a_priority['pref_id'];
                    $a_room_plan_priority['span']  = $a_priority['span'];
                    $a_room_plan_priority['wday']  = $wday;
                    $a_room_plan_priority['priority']  = $a_priority['priority'];
                    $a_room_plan_priority['hotel_cd']
                        = $a_priority['hotel_cd' . $wday . "_" . $a_priority['priority']];
                    $a_room_plan_priority['room_cd']  = $a_priority['room_cd' . $wday . "_" . $a_priority['priority']];
                    $a_room_plan_priority['plan_cd']  = $a_priority['plan_cd' . $wday . "_" . $a_priority['priority']];
                    $a_room_plan_priority['display_status']
                        = $a_priority['display_status' . $wday . "_" . $a_priority['priority']];

                    // 画面入力を変換
                    $errorList = $this->validateRoomPlanPriorityFromScreen($roomPlanPriorityData, $a_room_plan_priority, $roomPlanPriorityModel);

                    if (count($errorList) > 0) {
                        $errorList[] = "広告掲載施設の登録ができませんでした。";
                        session()->put('error', $errorList);
                        session()->put('return_data', $a_priority);
                        return redirect()->route('ctl.brroomplanpriority2.list');
                    }

                    // 重点表示部屋プランが存在していれば更新用の共通値をセット
                    $roomPlanPriorityModel->setUpdateCommonColumn($roomPlanPriorityData);

                    // 更新件数
                    $dbCount = 0;
                    // コネクション
                    try {
                        $con = DB::connection('mysql');
                        $dbErr = $con->transaction(function () use ($con, $roomPlanPriorityModel, $roomPlanPriorityData, &$dbCount) {
                            // DB更新
                            $dbCount = $roomPlanPriorityModel->updateBy4Key($con, $roomPlanPriorityData);
                            //TODO 更新件数0件でも1で戻る気がする,modify_tsがあるからでは？（共通カラム設定消すと想定通りになる）
                        });
                    } catch (Exception $e) {
                        $errorList[] = '広告掲載施設の登録ができませんでした。';
                    }

                    // 更新エラー
                    if ($dbCount == 0) {
                        $errorList[] = "広告掲載施設の登録ができませんでした。";
                        session()->put('error', $errorList);
                        session()->put('return_data', $a_priority);
                        return redirect()->route('ctl.brroomplanpriority2.list');
                    }
                }

                // 登録、更新エラー
                if (count($errorList) > 0 || !empty($dbErr)) {
                    $errorList[] = "広告掲載施設の登録ができませんでした。";
                    session()->put('error', $errorList);
                    session()->put('return_data', $a_priority);
                    return redirect()->route('ctl.brroomplanpriority2.list');
                }

                session()->put('return_data', $roomPlanPriorityData); //渡す値合っている？

                // 施設コード、部屋コード、プランコードが存在しない場合はレコードを削除
            } else {
                // 削除
                $roomPlanPriorityModel
                    ->deleteBy4Key($a_priority['pref_id'], $a_priority['span'], $wday, $a_priority['priority']);
                session()->put('return_data', $a_priority); //渡す値合っている？
            }
        }

        // 正常に完了、一覧へ戻る
        // 更新後の結果表示
        session()->put('guide', '重点表示プランが更新されました。');
        return redirect()->route('ctl.brroomplanpriority2.list');
    }

    //
    // 存在チェック 都道府県、施設、部屋、プランのチェック
    //
    // wday 1:日曜 2:月曜 3:火曜 4:水曜 5:木曜 6:金曜 7:土曜
    // week_nm 曜日名
    // aa_priority array(
    //                  hotel_cd
    //                  room_cd
    //                  plan_cd
    //                  pref_id
    //                  priority
    //                  )
    //
    // return           true or false
    //
    private function existenceCheck($wday, $week_nm, $aa_priority)
    {

        //施設コード・部屋コード・プランコードの存在チェック
        $hotelModel = new Hotel();
        $roomModel = new Room();
        $roomPlanModel = new RoomPlan();

        // Mast_Pref モデルを取得
        $mastPrefModel = new MastPref();

        //null追記
        $s_hotel_cd = $aa_priority['hotel_cd' . $wday . "_" . $aa_priority['priority']] ?? null;
        $s_room_cd = $aa_priority['room_cd' . $wday . "_" . $aa_priority['priority']] ?? null;
        $s_plan_cd = $aa_priority['plan_cd' . $wday . "_" . $aa_priority['priority']] ?? null;

        //
        // 施設チェック
        //

        //施設コードの入力チェック
        if ($this->is_empty($s_hotel_cd)) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . 'の施設コードは必ず入力してください。';
            return $errorList;
        }

        // ホテルの取得
        $a_hotel = $hotelModel->selectByKey($s_hotel_cd);

        //都道府県内の施設チェック
        //一旦下記をis_emptyに変えてみる＆null追記（zap~は今後使わないと元ソースで追記アリ）
        if ($this->is_empty($a_hotel)) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . '、' . $s_hotel_cd . 'の施設は存在しません。';

            return $errorList;
        }

        // 選択された都道府県名取得
        $a_plef = $mastPrefModel->selectByKey($aa_priority['pref_id']);

        //都道府県内の施設チェック
        if ($a_hotel['pref_id'] != $aa_priority['pref_id']) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . '、' . $s_hotel_cd . 'は' . $a_plef['pref_nm'] . '内の施設ではありません。';

            return $errorList;
        }

        //
        // 部屋チェック
        //

        // 部屋コードの入力チェック
        if ($this->is_empty($s_room_cd)) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . 'の部屋コードは必ず入力してください。';
            return $errorList;
        }

        // 部屋情報の取得
        $a_room = $roomModel->selectByWKey($s_hotel_cd, $s_room_cd);

        //部屋チェック
        //一旦下記をis_emptyに変えてみる＆null追記（zap~は今後使わないと元ソースで追記アリ）
        if ($this->is_empty($a_room)) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . '、' . $s_hotel_cd . '、' . $s_room_cd . 'は存在する部屋ではありません。';

            return $errorList;
        }

        //
        // プランチェック
        //

        // プランコードの入力チェック
        if ($this->is_empty($s_plan_cd)) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . 'のプランコードは必ず入力してください。';
            return $errorList;
        }

        // プラン情報の取得
        $a_plan = $roomPlanModel->selectByTripleKey($s_hotel_cd, $s_room_cd, $s_plan_cd);

        //プランチェック
        //一旦下記をis_emptyに変えてみる＆null追記（zap~は今後使わないと元ソースで追記アリ）
        if ($this->is_empty($a_plan)) {
            // エラーメッセージ
            $errorList[] = '表示順位' . $aa_priority['priority'] . '、' . $week_nm . '、' . $s_hotel_cd . '、' . $s_room_cd . '、' . $s_plan_cd . 'は存在するプランではありません。';

            return $errorList;
        }

        return true;
    }


    //======================================================================
    // 表示順序入れ替え
    //======================================================================
    public function sort()
    {
        // データを取得
        $requestroomPlanPriority = Request::all();
        $a_priority = $requestroomPlanPriority['priority'] ?? null; //null追記


        $pref_id = $a_priority['pref_id'];
        $span     = $a_priority['span'];
        $priority = $a_priority['priority'];
        $other_priority = $a_priority['other_priority'];

        //modify設定追記
        $roomPlanPriorityModel = new RoomPlanPriority();
        $roomPlanPriorityModel->setUpdateCommonColumn($a_priority);
        $modify_cd = $a_priority['modify_cd'];
        $modify_ts = $a_priority['modify_ts'];

        $this->sortList($pref_id, $span, $priority, "0", $modify_cd, $modify_ts);
        $this->sortList($pref_id, $span, $other_priority, $priority, $modify_cd, $modify_ts);
        $this->sortList($pref_id, $span, "0", $other_priority, $modify_cd, $modify_ts);

        // 正常に完了、一覧へ戻る
        // 更新後の結果表示

        session()->put('return_data', $a_priority); //渡す値合っている？
        return redirect()->route('ctl.brroomplanpriority2.list');
    }

    //=====================================================================
    // 並べ替え登録
    //=====================================================================
    protected function sortList($pref_id, $span, $priority, $other_priority, $modify_cd, $modify_ts)
    {
        try {
            $s_sql = //modify関係書き換え
                <<< SQL
					update	room_plan_priority
					set	priority = :other_priority,
						modify_cd = :modify_cd,
						modify_ts = :modify_ts
					where	pref_id  = :pref_id
					and	span   = :span
					and	priority = :priority
SQL;

            $a_conditions = [];
            $a_conditions['pref_id']      = $pref_id;
            $a_conditions['span']      = $span;
            $a_conditions['priority']      = $priority;
            $a_conditions['other_priority']    = $other_priority;
            $a_conditions['modify_cd']    = $modify_cd; //追記
            $a_conditions['modify_ts']    = $modify_ts; //追記

            DB::update($s_sql, $a_conditions);

            session()->put('guide', '並べ替えが完了しました。');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
