<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Models\HotelSystemVersion;
use App\Models\Plan;
use App\Models\Charge;
use App\Models\Room2;
use App\Models\RoomPlan;
use App\Models\RoomCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Common\DateUtil;

class HtlsPlanOfferController extends _commonController
{
    protected $_partner_cd = '0000000000'; // デフォルトのパートナーグループCD
    protected $_partner_group_id = null;   // 設定するパートナーグループID

    protected $a_calendar;
    protected $s_from_ymd = null;
    protected $s_to_ymd = null;
    protected $a_define_day_of_week = ['日', '月', '火', '水', '木', '金', '土'];
    protected $a_details; // プラン情報複数（施設の保有するプラン全てを格納）
    protected $s_plan_id;
    // protected $s_hotel_cd;

    // 特殊な扱いになる提携先コードの定義
    const PTN_CD_BR   = '0000000000'; // ベストリザーブ
    const PTN_CD_JRC  = '3015008801'; // JRコレクション
    const PTN_CD_RELO = '3015008796'; // リロクラブ

    // 特殊な提携先コードのリスト
    protected $a_special_partners;

    /**
     * インデックス
     */
    public function index(Request $request)
    {

        try {
            // 一覧画面へ転送
            return $this->list($request);
        } catch (Exception $e) { // 各メソッドで Exception が投げられた場合
            throw $e;
        }
    }

    /**
     * 一覧
     */
    public function list(Request $request)
    {
        try {
            // 初期化
            $o_date         = new DateUtil();
            $o_now_date     = new DateUtil();

            // リクエストパラメータ取得
            $a_form_params = $request->all();
            $target_cd = $request->input('target_cd');

            // TODO 認証関連機能ができ次第削除　仮で設定
            $hotel['hotel_cd'] = 999999;

            // 選択可能最小日付取得(2年前まで)
            $a_start_date = [];
            $o_now_date->add('Y', -2);
            $a_start_date['year']  = (int)$o_now_date->to_format('Y');
            $a_start_date['month'] = (int)$o_now_date->to_format('m');
            $a_start_date['day']   = (int)$o_now_date->to_format('d');

            // 選択可能最大日付取得(2年後まで)
            $a_end_date = [];
            $o_now_date->set();
            $o_now_date->add('Y', 2);
            $a_end_date['year']  = (int)$o_now_date->to_format('Y');
            $a_end_date['month'] = (int)$o_now_date->to_format('m');
            $a_end_date['day']   = (int)$o_now_date->to_format('d');

            // 表示日付範囲取得
            if (!empty($a_form_params['start_ymd']['year']) && !empty($a_form_params['start_ymd']['month']) && !empty($a_form_params['start_ymd']['day'])) {
                $o_date->set($a_form_params['start_ymd']['year'] . '-' . sprintf('%02d', $a_form_params['start_ymd']['month']) . '-' . sprintf('%02d', $a_form_params['start_ymd']['day']));
            }
            $a_date_range = $this->setDateRange($o_date->get());

            // 「新部屋プランメンテナンス」メニューの表示・非表示判定
            $this->setDispRoomPlanList($target_cd);

            //----------------------------------------------------------------------------------
            // リクエストパラメータを取得
            //----------------------------------------------------------------------------------
            $a_request_params = $request->all();

            // 旧処理との互換維持のためにパラメータを設定
            $a_request_params['from_year']  = date('Y', $a_date_range['current']['ymd']);
            $a_request_params['from_month'] = date('m', $a_date_range['current']['ymd']);
            $a_request_params['from_day']   = date('d', $a_date_range['current']['ymd']);

            //----------------------------------------------------------------------------------
            // 表示期間の設定
            //----------------------------------------------------------------------------------
            // 日時オブジェクト生成
            $o_models_date = new DateUtil();

            // 表示期間開始日の設定
            if (checkdate((int)$a_request_params['from_month'], (int)$a_request_params['from_day'], (int)$a_request_params['from_year'])) {
                // 指定された開始日時を設定
                $o_models_date->set($a_request_params['from_year'] . '-' . $a_request_params['from_month'] . '-' . $a_request_params['from_day']);
            } else {
                // 初期遷移時は操作日を設定
                $o_models_date->set(date('Y-m-d'));
            }

            // 開始日の加算値が指定されている場合は設定（前の2週、前の日、次の日、次の2週）
            // TODO add_dayの取得先不明のため仮で設定。
            $a_request_params['add_day']  = 0;

            if ($a_request_params['add_day'] == 0) {
                $o_models_date->add('d', 0);
            } else {
                $o_models_date->add('d', $a_request_params['add_day']);
            }

            // カレンダーオブジェクトに開始日を設定
            $this->setFromYmd($o_models_date->to_format('Y-m-d'));

            // 開始日をリクエストパラメータに設定
            $a_request_params['from_year']  = $o_models_date->to_format('Y');
            $a_request_params['from_month'] = $o_models_date->to_format('m');
            $a_request_params['from_day']   = $o_models_date->to_format('d');
            $a_request_params['from_ymd']   = $o_models_date->to_format('Y-m-d');

            // 表示期間終了日の設定（操作日当日を含め2週間分を表示）
            $o_models_date->add('d', 13);

            // 終了日をリクエストパラメータに設定
            $a_request_params['to_ymd']   = $o_models_date->to_format('Y-m-d');

            // カレンダーオブジェクトに終了日を設定
            $this->setToYmd($o_models_date->to_format('Y-m-d'));

            // 表示期間情報を生成
            $this->makeLineCalendar();

            $room_details            = $this->getDetailsRoom3($target_cd);          // 施設の有効なすべての部屋の詳細情報
            $plan_details            = $this->getDetailsPlan3($target_cd);          // 施設の有効なすべてのプランの詳細情報
            $match_plan_rooms_all    = $this->getMatchPlanRoomsAll($target_cd);     // プランから見たときの部屋との組み合わせ情報
            $week_days               = $this->getCalendar();
            $sale_state_plan_room    = $this->getFromToSaleStatePlanRoom($a_request_params['from_ymd'], $a_request_params['to_ymd'], $target_cd);
            $reserve_count_plan_room = $this->getFromToReserveCountPlanRoom($a_request_params['from_ymd'], $a_request_params['to_ymd'], $target_cd);

            return view('ctl.htlsplanoffer.list', [
                'target_cd' => $a_form_params['target_cd'],
                'form_params' => $a_form_params,
                'date_range' => $a_date_range,
                'start_date' => $a_start_date,
                'end_date' => $a_end_date,
                'request_params' => $a_form_params,
                'room_details' => $room_details,
                'plan_details' => $plan_details,
                'match_plan_rooms_all' => $match_plan_rooms_all,
                'week_days' => $week_days,
                'sale_state_plan_room' => $sale_state_plan_room,
                'reserve_count_plan_room' => $reserve_count_plan_room,
                'hotel' => $hotel,  // TODO: 仮で設定 認証関連機能ができ次第削除
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 売/止編集
     */
    public function edit(Request $request)
    {
        try {
            // 初期化
            // $o_now_date  = new Br_Models_Date();
            $o_now_date     = new DateUtil();

            // $o_models_plan2 = new models_Plan2();
            $o_plan = new Plan();

            // リクエストパラメータ取得
            $a_form_params = $request->all();
            $target_cd = $request->input('target_cd');

            // 「新部屋プランメンテナンス」メニューの表示・非表示判定
            $this->setDispRoomPlanList($target_cd);

            // 選択可能最小日付取得(2年前まで)
            $a_start_date = [];
            $o_now_date->add('Y', -2);
            $a_start_date['year']  = (int)$o_now_date->to_format('Y');
            $a_start_date['month'] = (int)$o_now_date->to_format('m');
            $a_start_date['day']   = (int)$o_now_date->to_format('d');

            // 選択可能最大日付取得(2年後まで)
            $a_end_date = [];
            $o_now_date->set();
            $o_now_date->add('Y', 2);
            $a_end_date['year']  = (int)$o_now_date->to_format('Y');
            $a_end_date['month'] = (int)$o_now_date->to_format('m');
            $a_end_date['day']   = (int)$o_now_date->to_format('d');

            if (empty($a_form_params['from_year']) && empty($a_form_params['from_month']) && empty($a_form_params['from_day'])) {
                $o_now_date->set($a_form_params['target_ymd']);
                $o_now_date->set($o_now_date->to_format('Y-m-d'));
                $a_form_params['from_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['from_month'] = (int)$o_now_date->to_format('m');
                $a_form_params['from_day']   = (int)$o_now_date->to_format('d');
                $a_form_params['to_year']    = (int)$o_now_date->to_format('Y');
                $a_form_params['to_month']   = (int)$o_now_date->to_format('m');
                $a_form_params['to_day']     = (int)$o_now_date->to_format('d');
            }

            $o_now_date->set($a_form_params['target_ymd']);
            $o_now_date->set($o_now_date->to_format('Y-m-d'));
            $a_disp_date = [
                'target_date' => $o_now_date->get(),
                'week_day'    => $o_now_date->to_week('j')
            ];

            if ($a_form_params['ui_type'] !== 'date') {
                $a_plan = $o_plan->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'plan_id' => $a_form_params['plan_id'][0]
                    ]
                )->first();
            }

            return view('ctl.htlsplanoffer.edit', [
                'target_cd' => $a_form_params['target_cd'],
                'form_params' => $a_form_params,
                'start_date' => $a_start_date,
                'end_date' => $a_end_date,
                'disp_date' => $a_disp_date,
                'plan_data' => $a_plan ?? null,
                'partner_group_id' => $this->_partner_group_id
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 編集確認
     */
    protected function confirm(Request $request)
    {
        try {
            $o_date         = new DateUtil();
            $o_plan         = new Plan();

            // リクエストパラメータ取得
            $a_form_params = $request->all();
            $target_cd = $request->input('target_cd');

            $actionCd = $this->getActionCd();

            // 「新部屋プランメンテナンス」メニューの表示・非表示判定
            $this->setDispRoomPlanList($target_cd);

            $a_form_params['accept_status'] = $a_form_params['accept_status'] ?? -1;

            // 現在日付取得
            $o_date->set($o_date->to_format('Y-m-d H:i:s'));
            $n_now_date = $o_date->get();

            $o_date->set($a_form_params['target_ymd']);
            $o_date->set($o_date->to_format('Y-m-d'));
            $a_disp_date = array('target_date' => $o_date->get(), 'week_day' => $o_date->to_week('j'));

            // 販売ステータスの変更が行われているとき
            if (0 <= $a_form_params['accept_status']) {
                // 販売ステータスを変更する期間の最終日取得
                $o_date->set($a_form_params['to_year'] . '-' . sprintf('%02d', $a_form_params['to_month']) . '-' . sprintf('%02d', $a_form_params['to_day']));
                $o_date->add('d', 1);
                $n_last_date = $o_date->get();

                $o_date->set($a_form_params['from_year'] . '-' . sprintf('%02d', $a_form_params['from_month']) . '-' . sprintf('%02d', $a_form_params['from_day']));
                $n_start_date = $o_date->get();

                $o_date->set($o_date->to_format('Y-m-d 06:00:00'));
                $o_date->add('d', 1);
                $n_edit_limit = $o_date->get();

                if ($n_edit_limit < $n_now_date) {
                    return $this->edit($request, [
                        'target_cd' => $a_form_params['target_cd'],
                        'form_params' => $a_form_params,
                    ])->with(['errors' => ['設定期間開始日は' . date('Y-m-d', $n_now_date) . '以降で設定してください。']]);
                }

                if ($n_last_date < $n_start_date) {
                    // エラーメッセージ
                    return $this->edit($request, [
                        'target_cd' => $a_form_params['target_cd'],
                        'form_params' => $a_form_params,
                    ])->with(['errors' => ['設定期間開始日と設定期間終了日が逆転しています。']]);
                }

                foreach ($a_form_params['plan_id'] as $plan_id) {
                    // プラン在庫タイプ取得
                    $o_date->set($n_start_date);

                    // 基幹在庫は変更しない
                    // TODO ログインユーザーのステータスを仮で設定。ログイン機能実装後、条件文変更
                    $box_user = 'staff';
                    if ($box_user == 'nta' || $box_user == 'staff') {
                        while ($n_last_date != $o_date->get()) {
                            $charge_data = DB::table('charge')->where([
                                'hotel_cd' => $a_form_params['target_cd'],
                                'plan_id' => $plan_id,
                                'date_ymd' => $o_date->to_format('Y-m-d'),
                            ])->get();
                            // Stdclassを配列に変換
                            $a_charges = json_decode(json_encode($charge_data), true);
                            if (!empty($a_charges)) {
                                foreach ($a_charges as $a_charge) {
                                    $a_entry_modifys = ['modify_cd' => $actionCd, 'modify_ts' => now()];
                                    $a_charge = array_merge($a_charge, $a_entry_modifys);
                                    $a_charge['accept_status'] = $a_form_params['accept_status'];
                                    $a_charge['date_ymd'] = $o_date->to_format('Y-m-d');
                                }
                            }
                            $o_date->add('d', 1);
                        }
                    }
                }
            }

            $a_plan = $o_plan->where(['hotel_cd' => $a_form_params['target_cd'], 'plan_id' => $a_form_params['plan_id'][0]])->first();

            return view('ctl.htlsplanoffer.confirm', [
                'target_cd' => $a_form_params['target_cd'],
                'form_params' => $a_form_params,
                'disp_date' => $a_disp_date,
                'plan_data' => $a_plan,
                'partner_group_id' => $this->_partner_group_id
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 更新
     */
    protected function update(Request $request)
    {

        // 初期化
        $o_date         = new DateUtil();
        $o_plan         = new Plan();
        $o_charge       = new Charge();

        // リクエストパラメータ取得
        $a_form_params = $request->all();
        $target_cd = $request->input('target_cd');

        $actionCd = $this->getActionCd();

        // 現在日付取得
        $o_date->set($o_date->to_format('Y-m-d H:i:s'));
        $n_now_date = $o_date->get();

        $o_date->set($a_form_params['target_ymd']);
        $o_date->set($o_date->to_format('Y-m-d'));
        $a_disp_date = [
            'target_date' => $o_date->get(),
            'week_day'    => $o_date->to_week('j')
        ];

        // 「新部屋プランメンテナンス」メニューの表示・非表示判定
        $this->setDispRoomPlanList($target_cd);

        try {
            DB::beginTransaction();

            if (0 <= $a_form_params['accept_status']) {
                // 販売ステータスを変更する期間の最終日取得
                $o_date->set($a_form_params['to_year'] . '-' . sprintf('%02d', $a_form_params['to_month']) . '-' . sprintf('%02d', $a_form_params['to_day']));
                $o_date->add('d', 1);
                $n_last_date = $o_date->get();
                $o_date->set($a_form_params['from_year'] . '-' . sprintf('%02d', $a_form_params['from_month']) . '-' . sprintf('%02d', $a_form_params['from_day']));
                $n_start_date = $o_date->get();
                $o_date->set($o_date->to_format('Y-m-d 06:00:00'));
                $o_date->add('d', 1);
                $n_edit_limit = $o_date->get();

                if ($n_edit_limit < $n_now_date) {
                    return $this->edit($request, [
                        'target_cd' => $a_form_params['target_cd'],
                        'form_params' => $a_form_params,
                    ])->with(['errors' => ['設定期間開始日は' . date('Y-m-d', $n_now_date) . '以降で設定してください。']]);
                }

                if ($n_last_date < $n_start_date) {
                    return $this->edit($request, [
                        'target_cd' => $a_form_params['target_cd'],
                        'form_params' => $a_form_params,
                    ])->with(['errors' => ['設定期間開始日と設定期間終了日が逆転しています。']]);
                }

                foreach ($a_form_params['plan_id'] as $plan_id) {
                    // 基幹在庫は変更しない
                    // TODO ログインユーザーのステータスを仮で設定。
                    // ログイン機能実装後、条件文変更「if($this->box->user->operator->is_nta() or $this->box->user->operator->is_staff())」
                    $box_user = 'staff';
                    if ($box_user == 'nta' or $box_user == 'staff') {
                        $o_date->set($n_start_date);
                        while ($n_last_date != $o_date->get()) {
                            $charge_data = $o_charge->where([
                                'hotel_cd' => $a_form_params['target_cd'],
                                'plan_id' => $plan_id,
                                'date_ymd' => $o_date->to_format('Y-m-d'),
                            ])->get();
                            // Stdclassを配列に変換
                            $a_charges = json_decode(json_encode($charge_data), true);

                            if (!empty($a_charges)) {
                                foreach ($a_charges as $a_charge) {
                                    $a_entry_modifys = ['modify_cd' => $actionCd, 'modify_ts' => now()];
                                    $a_charge = array_merge($a_charge, $a_entry_modifys);
                                    $a_charge['accept_status'] = $a_form_params['accept_status'];
                                    $a_charge['date_ymd'] = $o_date->to_format('Y-m-d');
                                    if (!$this->chargeUpdate($a_charge)) {
                                        return view('ctl.htlsplanoffer.update', [
                                            'target_cd' => $a_form_params['target_cd'],
                                            'form_params' => $a_form_params,
                                            'disp_date' => $a_disp_date,
                                            'plan_data' => $a_plan ?? null,
                                            'partner_group_id' => $this->_partner_group_id
                                        ])->with(['errors' => ['更新に失敗しました。']]);
                                    }
                                }
                            }
                            $o_date->add('d', 1);
                        }
                    }
                }
            }

            $a_plan = $o_plan->where(array('hotel_cd' => $a_form_params['target_cd'], 'plan_id' => $a_form_params['plan_id'][0]))->first();

            DB::commit();

            //--------------------------------------------------------------
            // 料金のユーザー画面への反映
            //   ※モデル内でトランザクションが実装されているのを可能ならば
            //     コントローラに任せる仕様に変更したい
            //
            // 2022/12/16追記（関）
            // 網さん曰く、if (count($this->params('rooms')) === 1)はバグっぽい。毎回elseに流れるはず。
            // charge_consitionテーブルがある理由は、中間テーブルを作って検索処理を軽くするため。
            // ACホテルサイトはhotel_cdの先頭に都道府県コードをつけて検索する形になるはずなので、Core_ChargeCondition()は必要なくなるかも。
            //--------------------------------------------------------------
            // $o_models_charge_conditions = new Core_ChargeCondition();

            // if (count($this->params('plan_id')) === 1) {
            //     $o_models_charge_conditions->set_charge(array('hotel_cd' => $this->params('target_cd'), 'plan_id' => array_pop($this->params('plan_id'))));
            // } else {
            //     $o_models_charge_conditions->set_charge(array('hotel_cd' => $this->params('target_cd')));
            // }

            return view('ctl.htlsplanoffer.update', [
                'target_cd' => $a_form_params['target_cd'],
                'form_params' => $a_form_params,
                'disp_date' => $a_disp_date,
                'plan_data' => $a_plan,
                'partner_group_id' => $this->_partner_group_id
            ])->with(['guides' => ['更新が完了しました。']]);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function chargeUpdate($a_charge)
    {
        try {
            $a_attributes = $a_charge;
            $o_charge = new Charge();

            $o_charge->where([
                'hotel_cd'         => $a_attributes['hotel_cd'],
                'room_id'          => $a_attributes['room_id'],
                'plan_id'          => $a_attributes['plan_id'],
                'partner_group_id' => $a_attributes['partner_group_id'],
                'capacity'         => $a_attributes['capacity'],
                'date_ymd'         => $a_attributes['date_ymd']
            ])->update([
                'accept_status' => $a_attributes['accept_status'],
                'modify_cd' => $a_attributes['modify_cd'],
                'modify_ts' => now(),
            ]);

            $s_sql =
                <<<SQL
                select	room_cd,
                        plan_cd
                from	zap_room_plan_charge
                where	parent_hotel_cd = :hotel_cd
                    and	parent_room_id  = :room_id
                    and	parent_plan_id  = :plan_id
                    and	parent_capacity = :capacity
SQL;

            $a_row = DB::select($s_sql, ['hotel_cd' => $a_attributes['hotel_cd'], 'room_id' => $a_attributes['room_id'], 'plan_id' => $a_attributes['plan_id'], 'capacity' => $a_attributes['capacity']]);

            $room2 = new Room2();
            $a_room2 = $room2->where(['hotel_cd' => $a_attributes['hotel_cd'], 'room_id' => $a_attributes['room_id']])->first();

            $room_plan = new RoomPlan();
            $a_room_plan = $room_plan->where(['hotel_cd' => $a_attributes['hotel_cd'], 'room_cd' => $a_row[0]->room_cd, 'plan_cd' => $a_row[0]->plan_cd])->first();

            // プランが利用人数範囲外の場合、料金販売を停止
            if ($a_room2['capacity_min'] <= $a_room_plan['capacity'] && $a_room_plan['capacity'] <= $a_room2['capacity_max']) {
                $a_attributes['accept_status'] = $a_attributes['accept_status'];
            } else {
                $a_attributes['accept_status'] = 0;
            }

            $room_charge = new RoomCharge();
            $room_charge->where([
                'hotel_cd'         => $a_attributes['hotel_cd'],
                'room_cd'          => $a_row[0]->room_cd,
                'plan_cd'          => $a_row[0]->plan_cd,
                'partner_group_id' => $a_attributes['partner_group_id'],
                'date_ymd'         => $a_attributes['date_ymd'],
            ])->update([
                'usual_charge'     => $a_attributes['usual_charge'] * $a_attributes['capacity'] + $a_attributes['usual_charge_revise'],
                'sales_charge'     => $a_attributes['sales_charge'] * $a_attributes['capacity'] + $a_attributes['sales_charge_revise'],
                'accept_status'    => $a_attributes['accept_status'],
                'accept_s_dtm'     => $a_attributes['accept_s_dtm'],
                'accept_e_dtm'     => $a_attributes['accept_e_dtm'],
                'low_price_status' => $a_attributes['low_price_status'],
                'modify_cd'        => $a_attributes['modify_cd'],
                'modify_ts'        => $a_attributes['modify_ts']
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 表示期間指定用パラメータセット
     */
    public function setDateRange($an_base_date = null)
    {
        try {
            // 初期化
            $o_date        = new DateUtil($an_base_date);
            $o_date->set($o_date->to_format('Y-m-d'));
            $a_date_ranges = [];

            $n_tmp_base_date = $o_date->get();

            // 当日
            $a_date_ranges['current']['ymd'] = $o_date->get();
            $a_date_ranges['current']['year']  = (int)$o_date->to_format('Y');
            $a_date_ranges['current']['month'] = (int)$o_date->to_format('m');
            $a_date_ranges['current']['day']   = (int)$o_date->to_format('d');

            // 2週間前
            $o_date->add('d', -14);
            $a_date_ranges['week_bfo']['ymd'] = $o_date->get();
            $a_date_ranges['week_bfo']['year']  = (int)$o_date->to_format('Y');
            $a_date_ranges['week_bfo']['month'] = (int)$o_date->to_format('m');
            $a_date_ranges['week_bfo']['day']   = (int)$o_date->to_format('d');

            // 前日
            $o_date->set($n_tmp_base_date);
            $o_date->add('d', -1);
            $a_date_ranges['day_bfo']['ymd'] = $o_date->get();
            $a_date_ranges['day_bfo']['year']  = (int)$o_date->to_format('Y');
            $a_date_ranges['day_bfo']['month'] = (int)$o_date->to_format('m');
            $a_date_ranges['day_bfo']['day']   = (int)$o_date->to_format('d');

            // 翌日
            $o_date->set($n_tmp_base_date);
            $o_date->add('d', 1);
            $a_date_ranges['day_aft']['ymd'] = $o_date->get();
            $a_date_ranges['day_aft']['year']  = (int)$o_date->to_format('Y');
            $a_date_ranges['day_aft']['month'] = (int)$o_date->to_format('m');
            $a_date_ranges['day_aft']['day']   = (int)$o_date->to_format('d');

            // 2週間後
            $o_date->set($n_tmp_base_date);
            $o_date->add('d', 14);
            $a_date_ranges['week_aft']['ymd'] = $o_date->get();
            $a_date_ranges['week_aft']['year']  = (int)$o_date->to_format('Y');
            $a_date_ranges['week_aft']['month'] = (int)$o_date->to_format('m');
            $a_date_ranges['week_aft']['day']   = (int)$o_date->to_format('d');
            $n_last_date = $o_date->get();

            // 期間内の日付と曜日を取得
            $o_date->set($n_tmp_base_date);
            while ($n_last_date != $o_date->get()) {
                $a_date_ranges['days'][$o_date->get()]['date']     = $o_date->get();
                $a_date_ranges['days'][$o_date->get()]['week_day'] = $o_date->to_week('n');
                $a_date_ranges['days'][$o_date->get()]['is_hol']   = $o_date->is_holiday();
                $o_date->add('d', 1);
            }
            return $a_date_ranges;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 「新部屋プランメンテナンス」メニューの表示・非表示判定
     */
    public function setDispRoomPlanList($target_cd)
    {
        try {
            $plan = 'plan';
            $version_cullumn = HotelSystemVersion::where('hotel_cd', $target_cd)->where('system_type', $plan)->first();
            $a_system_versions = $this->toShift($version_cullumn['version'], true);

            return $a_system_versions;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Setter：対象期間の開始日
     */
    public function setFromYmd($as_from_ymd)
    {
        try {
            // エラーチェック
            if (!is_numeric($as_from_ymd) and !is_string($as_from_ymd)) {
                // エラーとする
                throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
            }

            // 文字列の場合
            if (is_string($as_from_ymd)) {
                $as_from_ymd = strtotime($as_from_ymd);
            }

            // 入力された日付が日付として正しくない場合はエラー
            if (!checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd))) {
                throw new Exception('開始日付が日付として正しくありません。');
            }

            // 指定された日付を設定
            $this->s_from_ymd = date('Y-m-d', $as_from_ymd);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Setter：対象期間の終了日
     */
    public function setToYmd($as_to_ymd)
    {
        try {
            // エラーチェック
            if (!is_numeric($as_to_ymd) and !is_string($as_to_ymd)) {
                // エラーとする
                throw new Exception('開始日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
            }

            // 文字列の場合
            if (is_string($as_to_ymd)) {
                $as_to_ymd = strtotime($as_to_ymd);
            }

            // 入力された日付が日付として正しくない場合はエラー
            if (!checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd))) {
                throw new Exception('終了日付が日付として正しくありません。');
            }

            // 指定された日付を設定
            $this->s_to_ymd = date('Y-m-d', $as_to_ymd);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Getter：カレンダー
     *
     * @return array 指定期間のカレンダー表示用データ
     */
    public function getCalendar()
    {
        return $this->a_calendar;
    }

    /**
     * 料金登録用の期間データを作成
     *
     * ※対象期間のデータを生成（第1週、第2週..のインデックスを持たない形）
     */
    public function makeLineCalendar()
    {

        try {
            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $this->a_calendar = [];
            $a_conditions     = [];
            $o_date           = new DateUtil();
            $s_sql            = '';
            $n_week_idx = 1;
            $n_row_idx  = 1;

            // 指定の開始日の週の日曜日を取得(※カレンダーは日曜日から表示)
            $n_from_ymd     = strtotime($this->s_from_ymd);

            // 指定の終了日の週の土曜日を取得(※カレンダーは土曜日まで表示)
            $n_to_ymd     = strtotime($this->s_to_ymd);

            //--------------------------------------------------------------
            // データ取得
            // ※最終日が休前日の場合、判定できない為1日多く取得する
            //--------------------------------------------------------------
            $a_conditions = [
                'from_ymd' => $this->s_from_ymd,
                'to_ymd'   => date('Y-m-d', strtotime('+1 day', strtotime($this->s_to_ymd)))
            ];

            $s_sql =
                <<< SQL
					select	DATE_FORMAT(mc.date_ymd, '%Y-%m-%d') as date_ymd,
							mc.holiday_nm
					from	mast_calendar mc
					where	mc.date_ymd between DATE_FORMAT(:from_ymd, '%Y-%m-%d') and DATE_FORMAT(:to_ymd, '%Y-%m-%d')
					order by	mc.date_ymd
SQL;
            $a_rows = DB::select($s_sql, $a_conditions);
            //--------------------------------------------------------------
            // 必要な情報を追加
            //--------------------------------------------------------------
            foreach ($a_rows as $n_idx => $a_row) {
                $this->a_calendar[$n_idx]['ymd']     = $a_row->date_ymd;
                $this->a_calendar[$n_idx]['ymd_num'] = strtotime($a_row->date_ymd);
                $this->a_calendar[$n_idx]['ymd_str'] = date('Y', $this->a_calendar[$n_idx]['ymd_num']) . '年' . ltrim(date('m', $this->a_calendar[$n_idx]['ymd_num']), '0') . '月' . ltrim(date('d', $this->a_calendar[$n_idx]['ymd_num']), '0') . '日';
                $this->a_calendar[$n_idx]['md_str']  = mb_substr($this->a_calendar[$n_idx]['ymd_str'], 5);
                $this->a_calendar[$n_idx]['dow_num'] = (int)date('w', $this->a_calendar[$n_idx]['ymd_num']);
                $this->a_calendar[$n_idx]['dow_str'] = $this->a_define_day_of_week[$this->a_calendar[$n_idx]['dow_num']];
                $this->a_calendar[$n_idx]['ymd_mn_num'] = strtotime('+30 hour', strtotime($this->a_calendar[$n_idx]['ymd']));

                // 対象日が編集範囲外
                if (!($n_from_ymd <= $this->a_calendar[$n_idx]['ymd_num'] and $this->a_calendar[$n_idx]['ymd_num'] <= $n_to_ymd)) {
                    // 編集不可フラグを設定
                    $this->a_calendar[$n_idx]['is_not_edit'] = true;
                }

                // 対象日が祝日の場合
                if (!empty($a_row->holiday_nm)) {
                    // 祝日フラグを設定
                    $this->a_calendar[$n_idx]['is_hol'] = true;
                    // 対象の前日に休前日フラグを設定
                    if ($n_idx > 0) {
                        $this->a_calendar[$n_idx - 1]['is_bfo'] = true;
                    }
                }
                unset($a_rows[$n_idx]);
            }

            // 1日多く取得した日を削除
            unset($this->a_calendar[$n_idx]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 施設の保持する管理画面上有効な部屋の詳細情報を取得
     */
    public function getDetailsRoom3($s_hotel_cd)
    {
        try {
            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            // 施設コード
            if (empty($s_hotel_cd)) {
                throw new Exception('施設コードを設定してください。');
            }
            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_conditions = [
                'hotel_cd' => $s_hotel_cd
            ];
            $a_result = [];
            //--------------------------------------------------------------
            // データ取得
            //--------------------------------------------------------------
            // 部屋の抽出条件を指定
            $s_where =
                <<< SQL_WHERE
					where	room2.hotel_cd = :hotel_cd
						and	room2.display_status = 1
						and	room2.active_status  = 1
SQL_WHERE;
            $s_sql = $this->getSqlRoomBase($s_where);
            $a_rows = DB::select($s_sql, $a_conditions);

            //--------------------------------------------------------------
            // 配列のキーが部屋IDになるように整形
            //--------------------------------------------------------------
            foreach ($a_rows as $a_row) {
                $a_result[$a_row->room_id] = $a_row;
            }

            return $a_result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 部屋情報（管理画面上でオレンジ枠で表現されるもの）を取得するSQL文を取得。
     *
     * @param string 部屋の抽出条件（WHERE句）
     * @param bool   ORDER BY句を付与するか否か（true:付与する, false:付与しない）
     *
     * @return string 部屋情報を取得する為のSQL文
     */
    public function getSqlRoomBase($as_where, $ab_is_orderby = true)
    {
        try {
            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $s_sql      = '';
            $s_order_by = '';

            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            if (empty($as_where)) {
                throw new Exception('「部屋抽出の為のWHERE句」が指定されていません。');
            }

            //--------------------------------------------------------------
            // 引数によって式を指定
            //--------------------------------------------------------------
            // ORDER BY句の有無
            if ($ab_is_orderby) {
                $s_order_by =
                    <<< SQL_ORDER_BY
						order by	network_add.order_no asc,
									network_add.room_type asc,
									network_add.room_id asc
SQL_ORDER_BY;
            }

            $s_sql =
                <<< SQL
					select	network_add.hotel_cd,
							network_add.room_id,
							network_add.pms_cd,
							network_add.room_nm,
							network_add.room_nm_cut,
							network_add.order_no,
							network_add.capacity_min,
							network_add.capacity_max,
							network_add.def_capacity_max,
							network_add.room_type,
							network_add.accept_status,
							network_add.bath,
							network_add.toilet,
							network_add.smoke,
							network_add.network,
							network_add.rental,
							network_add.connector,
							room_akafu_relation.roomtype_cd,
							substring(room_akafu_relation.roomtype_cd, -6) as akafu_cd,
							case
							 when room_akafu_relation.roomtype_cd
							  	is not null then 1
							 when room_akafu_relation.roomtype_cd
							  	is null then 0	
							end 
							as is_akafu
					from	room_akafu_relation
                    right outer join
							(
								-- 部屋のネット環境情報の取得
								select	room_specs_add.hotel_cd,
										room_specs_add.room_id,
										room_specs_add.pms_cd,
										room_specs_add.room_nm,
										room_specs_add.room_nm_cut,
										room_specs_add.order_no,
										room_specs_add.capacity_min,
										room_specs_add.capacity_max,
										room_specs_add.def_capacity_max,
										room_specs_add.room_type,
										room_specs_add.accept_status,
										room_specs_add.bath,
										room_specs_add.toilet,
										room_specs_add.smoke,
										room_network2.network,
										room_network2.rental,
										room_network2.connector
								from	room_network2,
										(
											-- 部屋スペック情報の取得
											select	rooms.hotel_cd,
													rooms.room_id,
													rooms.pms_cd,
													rooms.room_nm,
													rooms.room_nm_cut,
													rooms.order_no,
													rooms.capacity_min,
													rooms.capacity_max,
													rooms.def_capacity_max,
													rooms.room_type,
													rooms.accept_status,
													max(case when room_spec2.element_id = 1 then room_spec2.element_value_id else -1 end) as bath,
													max(case when room_spec2.element_id = 2 then room_spec2.element_value_id else -1 end) as toilet,
													max(case when room_spec2.element_id = 3 then room_spec2.element_value_id else -1 end) as smoke
											from	room_spec2,
													(
														-- 部屋の情報
														select	room2.hotel_cd,
																room2.room_id,
																ifnull(room2.label_cd, room2.room_id) as pms_cd, 
																room2.room_nl as room_nm,
																room2.room_nm as room_nm_cut,
																room2.order_no,
																room2.capacity_min,
																room2.capacity_max,
																case
																	when room2.capacity_max > 6 then
																		6
																	else
																		room2.capacity_max
																end as def_capacity_max, -- 新画面では6人が定義上最大となるが、旧画面から移行した場合、7人以上のものも存在するので使い分けのできるように追加
																room2.room_type,
																room2.accept_status
														from	room2
														{$as_where}
													) rooms
													where	room_spec2.hotel_cd = rooms.hotel_cd
												and	room_spec2.room_id  = rooms.room_id
											group by
												rooms.hotel_cd,
												rooms.room_id,
												rooms.room_nm,
												rooms.room_nm_cut,
												rooms.pms_cd,
												rooms.order_no,
												rooms.capacity_min,
												rooms.capacity_max,
												rooms.def_capacity_max,
												rooms.room_type,
												rooms.accept_status
										) room_specs_add
								where	room_network2.hotel_cd = room_specs_add.hotel_cd
									and	room_network2.room_id  = room_specs_add.room_id
							) network_add
					on	room_akafu_relation.hotel_cd = network_add.hotel_cd
					and	room_akafu_relation.room_id = network_add.room_id
					{$s_order_by}
SQL;

            return $s_sql;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /**
     * 指定した施設の管理画面に表示されるプラン詳細情報を取得
     *
     * ・プランメンテナンス画面のプラン表示と同フォーマット
     * ・必要な情報のみを取得
     *
     * @return array 管理画面に表示されているプランの詳細情報
     */
    public function getDetailsPlan3($s_hotel_cd)
    {
        $this->a_details = [];

        try {
            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            // 施設コード
            if (empty($s_hotel_cd)) {
                throw new Exception('施設コードを設定してください。');
            }

            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_conditions   = [];
            $s_sql          = '';
            //仮で設定
            $this->s_plan_id = 2011000001;
            $s_temp_plan_id = $this->s_plan_id; // メンバに設定されているプランIDの一時退避先

            //--------------------------------------------------------------
            // データ取得
            //--------------------------------------------------------------
            // バインド変数設定
            $a_conditions['hotel_cd'] = $s_hotel_cd;
            // プランの抽出条件を指定
            $s_where =
                <<< SQL_WHERE
					where	plan.hotel_cd = :hotel_cd
						and	plan.display_status = 1
						and	plan.active_status  = 1
SQL_WHERE;
            $s_sql = $this->getSqlPlanBase($s_where);
            $a_rows = DB::select($s_sql, $a_conditions);

            foreach ($a_rows as $key => $a_row) {
                // キャンペーン対象かどうかを設定
                $this->s_plan_id                           = $a_row->plan_id;
                $this->a_details[$a_row->plan_id]          = $a_row;
                $this->a_details[$a_row->plan_id]->is_camp = $this->isCampaign($s_hotel_cd);

                // プランの販売先チャンネルIDを設定
                $this->a_details[$a_row->plan_id]->partner_groups = $this->getPlanPartnerGroups($s_hotel_cd);
            }

            // 退避していたプランIDを設定しなおす
            $this->s_plan_id = $s_temp_plan_id;

            return $this->a_details;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プラン情報（管理画面上で緑枠で表現されるもの）を取得するSQL文を取得。
     *
     * @param string プランの抽出条件（WHERE句）
     * @param bool   ORDER BY句を付与するか否か（true:付与する, false:付与しない）
     *
     * @return string プラン情報を取得する為のSQL文
     */
    public function getSqlPlanBase($as_where, $ab_is_orderby = true)
    {
        try {
            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $s_select   = '';
            $s_where    = '';
            $s_order_by = '';

            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            if (empty($as_where)) {
                throw new Exception('「プラン抽出の為のWHERE句」が指定されていません。');
            }

            //--------------------------------------------------------------
            // 特殊な提携先の取得方法を設定
            // [0:非対象, 1:対象]
            //--------------------------------------------------------------
            $this->a_special_partners = [
                'br'   => self::PTN_CD_BR,
                'jrc'  => self::PTN_CD_JRC,
                'relo' => self::PTN_CD_RELO
            ];

            foreach ($this->a_special_partners as $s_ptn_nm => $s_ptn_cd) {
                $s_select .= ", max(case partner_group_join.partner_cd when '" . $s_ptn_cd . "' then 1 else 0 end) as is_" . $s_ptn_nm;
            }

            //--------------------------------------------------------------
            // 引数によって式を指定
            //--------------------------------------------------------------
            // ORDER BY句の有無
            if ($ab_is_orderby) {
                $s_order_by =
                    <<< SQL_ORDER_BY
						order by	plan_point_add.plan_order_no asc,
									plan_point_add.modify_ts desc
SQL_ORDER_BY;
            }

            $s_sql =
                <<< SQL
					-- プランのポイント情報取得と整形
					select	plan_point_add.hotel_cd,
							plan_point_add.plan_id,
							plan_point_add.plan_type,
							plan_point_add.plan_nm,
							plan_point_add.charge_type,
							plan_point_add.payment_way,
							plan_point_add.stay_limit,
							plan_point_add.stay_cap,
							plan_point_add.pms_cd,
							concat(DATE_FORMAT(plan_point_add.accept_s_ymd, '%Y%m%d'),' 00:00') as accept_s_ymd,
							concat(DATE_FORMAT(plan_point_add.accept_e_ymd, '%Y%m%d'),' 23:59') as accept_e_ymd,
							plan_point_add.accept_e_day,
							plan_point_add.accept_e_hour,
							plan_point_add.check_in,
							plan_point_add.check_in_end,
							plan_point_add.check_out,
							plan_point_add.accept_status,
							plan_point_add.is_br,		
							plan_point_add.is_jrc,	
							plan_point_add.is_relo,	
							plan_point_add.meal, -- 食事,
							plan_point.issue_point_rate,
							plan_point.point_status
					from	plan_point,
							(
								-- プランのスペック情報取得
								select	plans_attribute_add.hotel_cd,
										plans_attribute_add.plan_id,
										plans_attribute_add.plan_type,
										plans_attribute_add.plan_nm,
										plans_attribute_add.charge_type,
										plans_attribute_add.payment_way,
										plans_attribute_add.stay_limit,
										plans_attribute_add.stay_cap,
										plans_attribute_add.pms_cd,
										plans_attribute_add.accept_s_ymd,
										plans_attribute_add.accept_e_ymd,
										plans_attribute_add.accept_e_day,
										plans_attribute_add.accept_e_hour,
										plans_attribute_add.check_in,
										plans_attribute_add.check_in_end,
										plans_attribute_add.check_out,
										plans_attribute_add.accept_status,
										plans_attribute_add.plan_order_no,
										plans_attribute_add.modify_ts,
										plans_attribute_add.is_br,		
										plans_attribute_add.is_jrc,		
										plans_attribute_add.is_relo,		
										max(case plan_spec.element_id when 4 then element_value_id else -1 end) as meal
								from	plan_spec,
										(
											-- 販売先グループと提携先コードからプラン属性を取得（JRコレクション、リロ、etc）
											select	partner_cd_add.hotel_cd,
													partner_cd_add.plan_id,
													partner_cd_add.plan_type,
													partner_cd_add.plan_nm,
													partner_cd_add.charge_type,
													partner_cd_add.payment_way,
													partner_cd_add.stay_limit,
													partner_cd_add.stay_cap,
													partner_cd_add.pms_cd,
													partner_cd_add.accept_s_ymd,
													partner_cd_add.accept_e_ymd,
													partner_cd_add.accept_e_day,
													partner_cd_add.accept_e_hour,
													partner_cd_add.check_in,
													partner_cd_add.check_in_end,
													partner_cd_add.check_out,
													partner_cd_add.accept_status,
													partner_cd_add.plan_order_no,
													partner_cd_add.modify_ts
													{$s_select}
											from	partner_group_join,
													(
														-- プランの販売先グループの提携先コード取得
														select	plans.hotel_cd,
																plans.plan_id,
																plans.plan_type,
																plans.plan_nm,
																plans.charge_type,
																plans.payment_way,
																plans.stay_limit,
																plans.stay_cap,
																plans.pms_cd,
																plans.accept_s_ymd,
																plans.accept_e_ymd,
																plans.accept_e_day,
																plans.accept_e_hour,
																plans.check_in,
																plans.check_in_end,
																plans.check_out,
																plans.accept_status,
																plans.plan_order_no,
																plans.modify_ts,
																plan_partner_group.partner_group_id
														from	plan_partner_group,
																(
																	-- プランの情報
																	select	plan.hotel_cd,
																			plan.plan_id,
																			plan.plan_type,
																			plan.plan_nm,
																			plan.charge_type,
																			plan.payment_way,
																			plan.stay_limit,
																			plan.stay_cap,
																			ifnull(plan.label_cd, plan.plan_id) as pms_cd,
																			plan.accept_s_ymd,
																			plan.accept_e_ymd,
																			plan.accept_e_day,
																			plan.accept_e_hour,
																			plan.check_in,
																			plan.check_in_end,
																			plan.check_out,
																			plan.accept_status,
																			plan.order_no as plan_order_no,
																			plan.modify_ts -- プランの表示順ソートに使用
																	from	plan
																	{$as_where}
																) plans
														where	plan_partner_group.hotel_cd = plans.hotel_cd
															and	plan_partner_group.plan_id  = plans.plan_id
													) partner_cd_add
											where	partner_group_join.partner_group_id = partner_cd_add.partner_group_id
											group by	partner_cd_add.hotel_cd,
														partner_cd_add.plan_id,
														partner_cd_add.plan_type,
														partner_cd_add.plan_nm,
														partner_cd_add.charge_type,
														partner_cd_add.payment_way,
														partner_cd_add.stay_limit,
														partner_cd_add.stay_cap,
														partner_cd_add.pms_cd,
														partner_cd_add.accept_s_ymd,
														partner_cd_add.accept_e_ymd,
														partner_cd_add.accept_e_day,
														partner_cd_add.accept_e_hour,
														partner_cd_add.check_in,
														partner_cd_add.check_in_end,
														partner_cd_add.check_out,
														partner_cd_add.accept_status,
														partner_cd_add.plan_order_no,
														partner_cd_add.modify_ts
										) plans_attribute_add
								where	plan_spec.hotel_cd = plans_attribute_add.hotel_cd
									and	plan_spec.plan_id  = plans_attribute_add.plan_id
								group by	plans_attribute_add.hotel_cd,
											plans_attribute_add.plan_id,
											plans_attribute_add.plan_type,
											plans_attribute_add.plan_nm,
											plans_attribute_add.charge_type,
											plans_attribute_add.payment_way,
											plans_attribute_add.stay_limit,
											plans_attribute_add.stay_cap,
											plans_attribute_add.pms_cd,
											plans_attribute_add.accept_s_ymd,
											plans_attribute_add.accept_e_ymd,
											plans_attribute_add.accept_e_day,
											plans_attribute_add.accept_e_hour,
											plans_attribute_add.check_in,
											plans_attribute_add.check_in_end,
											plans_attribute_add.check_out,
											plans_attribute_add.accept_status,
											plans_attribute_add.plan_order_no,
											plans_attribute_add.modify_ts,
											plans_attribute_add.is_br,
											plans_attribute_add.is_jrc,
											plans_attribute_add.is_relo
							) plan_point_add
					where	plan_point.hotel_cd = plan_point_add.hotel_cd
						and	plan_point.plan_id  = plan_point_add.plan_id
					{$s_order_by}
SQL;
            return $s_sql;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 対象プランがキャンペーン対象であるかチェック
     *
     * @return bool キャンペーンプラン成否（true:対象, false:非対象）
     */
    public function isCampaign($s_hotel_cd)
    {
        try {
            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            // 施設コード
            if (empty($s_hotel_cd)) {
                throw new Exception('施設コードを設定してください。');
            }

            // プランID
            if (empty($this->s_plan_id)) {
                throw new Exception('プランIDを設定してください。');
            }

            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_conditions = [];
            $s_sql        = '';

            //--------------------------------------------------------------
            // データ取得
            //--------------------------------------------------------------
            // バインド変数設定
            $a_conditions['hotel_cd'] = $s_hotel_cd;
            $a_conditions['plan_id']  = $this->s_plan_id;

            $s_sql =
                <<< SQL
					select	count(*) as camp_count
					from	hotel_camp hc,
							(
								select	hcp2.hotel_cd,
										hcp2.camp_cd
								from	hotel_camp_plan2 hcp2
								where	hcp2.hotel_cd = :hotel_cd
									and	hcp2.plan_id  = :plan_id
							) q1
					where	hc.hotel_cd = q1.hotel_cd
						and	hc.camp_cd  = q1.camp_cd
						and	hc.display_status = 1
						and	(
								hc.accept_s_ymd is null
									or
								truncate(sysdate(), '%d') between hc.accept_s_ymd and ifnull(hc.accept_e_ymd, ifnull(hc.target_e_ymd, truncate(sysdate(), '%d')))
							)
						and	(
								hc.target_s_ymd is null
									or
								truncate(sysdate(), '%d') <= ifnull(hc.target_e_ymd, truncate(sysdate(), '%d'))
							)
SQL;
            $a_rows = DB::select($s_sql, $a_conditions);
            // 有効なキャンペーンが存在しないとき
            if ((int)$a_rows[0]->camp_count < 1) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 販売先の提携先グループIDを取得
     *
     * @return array 対象プランの販売される提携先グループID一覧
     */
    public function getPlanPartnerGroups($s_hotel_cd)
    {
        try {
            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            // 施設コード
            if (empty($s_hotel_cd)) {
                throw new Exception('施設コードを設定してください。');
            }
            // プランID
            if (empty($this->s_plan_id)) {
                throw new Exception('プランIDを設定してください。');
            }

            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_result = [];

            $s_sql = '';

            $a_conditions = [
                'hotel_cd' => $s_hotel_cd,
                'plan_id'  => $this->s_plan_id
            ];

            //--------------------------------------------------------------
            // データ取得
            //--------------------------------------------------------------
            $s_sql =
                <<< SQL
					select	partner_group_id
					from	plan_partner_group
					where	hotel_cd = :hotel_cd
						and	plan_id  = :plan_id
SQL;
            $a_rows = DB::select($s_sql, $a_conditions);
            // 整形
            foreach ($a_rows as $n_key => $a_row) {
                $a_result[$n_key] = $a_row->partner_group_id;
            }
            return $a_result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 施設のすべてのプランに紐づく部屋IDをすべて取得
     */
    public function getMatchPlanRoomsAll($s_hotel_cd)
    {
        try {
            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_conditions = [
                'hotel_cd' => $s_hotel_cd
            ];

            $a_result = [];

            $s_where =
                <<< WHERE
					where	room_plan_match.hotel_cd = :hotel_cd
WHERE;

            $s_sql = $this->getSqlMatchPlanRoomBase($s_where);

            //--------------------------------------------------------------
            // 結果取得
            //--------------------------------------------------------------
            $a_rows = DB::select($s_sql, $a_conditions);
            // 整形
            foreach ($a_rows as $a_row) {
                $a_result[$a_row->room_id][] = $a_row->room_id;
            }

            return $a_result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プランと部屋の組合せ一覧を取得するベースとなるSQL文を取得
     *
     * @param string 抽出条件（WHERE句）
     *
     * @return string SQL文
     */
    public function getSqlMatchPlanRoomBase($as_where)
    {
        try {
            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $s_sql   = '';
            $s_where = '';

            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            if (empty($as_where)) {
                throw new Exception('「抽出条件のWHERE句」が指定されていません。');
            }

            $s_sql =
                <<< SQL
					select	room2.hotel_cd,
							room2.room_id,
							exists_plan.plan_id,
							exists_plan.order_no_plan,
							exists_plan.modify_ts
					from	room2,
							(
								select	plan.hotel_cd,
										plan.plan_id,
										plan.order_no as order_no_plan,
										plan.modify_ts,
										extract_match.room_id
								from	plan,
										(
											select	room_plan_match.hotel_cd,
													room_plan_match.plan_id,
													room_plan_match.room_id
											from	room_plan_match
											{$as_where}
										) extract_match
								where	plan.hotel_cd = extract_match.hotel_cd
									and	plan.plan_id  = extract_match.plan_id
									and	plan.display_status = 1
									and	plan.active_status  = 1
							) exists_plan
					where	room2.hotel_cd = exists_plan.hotel_cd
						and	room2.room_id  = exists_plan.room_id
						and	room2.display_status = 1
						and	room2.active_status  = 1
					order by	exists_plan.order_no_plan asc,
								exists_plan.modify_ts desc,
								room2.order_no asc,
								room2.room_type asc,
								room2.room_id asc
SQL;
            return $s_sql;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プランと部屋の指定期間の販売状況を判断するための情報を取得(プラン基準)
     *
     * ・「在庫数」、「料金」、「手仕舞」の状態
     *
     * @param string 開始日
     * @param string 終了日
     *
     * @return array 指定期間のプランと部屋の販売状況
     */

    public function getFromToSaleStatePlanRoom($as_from_ymd, $as_to_ymd, $target_cd)
    {
        try {
            // 初期化
            $a_base_sale_state = [];
            $a_temp            = [];
            $a_result          = [];

            // 指定期間内の販売状況を判断するための情報を取得
            $a_base_sale_state = $this->getFromToSaleState($as_from_ymd, $as_to_ymd, $target_cd);

            // プラン基準の形に整形
            foreach ($a_base_sale_state ?? [] as $a_sale_state) {
                // キー情報を設定
                $s_room_id = $a_sale_state->room_id;
                $s_plan_id = $a_sale_state->plan_id;
                $n_ymd     = strtotime($a_sale_state->date_ymd);

                // 販売停止フラグ
                $b_is_stop = false;

                if (empty($s_room_id) || empty($s_plan_id)) {
                    continue;
                }

                // プランの手仕舞状態(対象プランの料金が1つでも販売されていれば手仕舞ではないとする)
                $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_without'] = null;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_sale'] = null;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_stop'] = null;
                if (is_null($a_sale_state->accept_status_charge)) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_without'] = true;
                    $b_is_stop = true;
                } elseif ($a_sale_state->accept_status_charge == 1) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_sale'] = true;
                } else {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_stop'] = true;
                    $b_is_stop = true;
                }

                // プランの休止状態
                $a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_plan'] = $a_sale_state->accept_status_plan;

                // 部屋情報のうち必要なものを取得
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['rooms']                    = $a_sale_state->rooms;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['remaining_rooms']          = $a_sale_state->remaining_rooms;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['accept_status_room']       = $a_sale_state->accept_status_room;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['accept_status_room_count'] = $a_sale_state->accept_status_room_count;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sales_charge']             = $a_sale_state->sales_charge;

                //----------------------------------------------------------
                // 部屋が休止
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room'] = null;
                if ($a_sale_state->accept_status_room != 1) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // プランが休止
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_plan'] = null;
                if ($a_sale_state->accept_status_plan != 1) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_plan'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // プランの期間内で販売日時が経過
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_expiration'] = null;
                if (!is_null($a_sale_state->accept_e_dtm) && strtotime($a_sale_state->accept_e_dtm) < strtotime('now')) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_expiration'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 部屋が手仕舞
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room_count'] = null;
                if (!is_null($a_sale_state->accept_status_room_count) && $a_sale_state->accept_status_room_count != 1) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room_count'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 料金が手仕舞
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_charge'] = null;
                if (!is_null($a_sale_state->accept_status_charge) && ($a_sale_state->accept_status_charge != 1)) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_charge'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 満室
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_full'] = null;
                if (($a_sale_state->rooms > 0) && ($a_sale_state->remaining_rooms < 1)) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_full'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 販売がまだ開始されていない
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_sale_still'] = null;
                if (!is_null($a_sale_state->accept_s_dtm) && strtotime($a_sale_state->accept_s_dtm) > strtotime('now')) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_sale_still'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 再販なしの在庫0
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stock_zero'] = null;
                if (!is_null($a_sale_state->rooms) && $a_sale_state->rooms < 1) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stock_zero'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 料金が0
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_zero'] = null;
                if (!is_null($a_sale_state->sales_charge) && ($a_sale_state->sales_charge < 1)) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_zero'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 料金の登録がない
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_without'] = null;
                if (is_null($a_sale_state->sales_charge)) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_without'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 在庫の登録がない
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stock_without'] = null;
                if (is_null($a_sale_state->rooms)) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stock_without'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 再販の可能性
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_resale'] = null;
                if (
                    !is_null($a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_full'])
                    && $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['accept_status_room'] == 1
                    && $a_sale_state['accept_status_charge'] == 1
                    && !$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_zero']
                    && !$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_charge']
                    && !$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room_count']
                ) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_resale'] = true;
                }

                //----------------------------------------------------------
                // 上記条件にあてはまらない場合は販売されているとする
                //----------------------------------------------------------
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop'] = null;
                $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_sale'] = null;
                if ($b_is_stop) {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop'] = true;
                } else {
                    $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_sale'] = true;
                }
            }

            // 日付でループ
            foreach ($a_temp ?? [] as $n_ymd => $a_day_sale_state) {
                // 日別のプランでループ
                foreach ($a_day_sale_state['plan'] ?? [] as $s_plan_id => $a_plan) {
                    $a_result[$n_ymd]['plan'][$s_plan_id] = $a_plan;

                    // 日別・プランの部屋でループ
                    foreach ($a_plan['room'] ?? [] as $s_room_id => $a_room) {
                        // 料金または在庫が登録されていない
                        if ($a_room['sale_status']['is_charge_without'] || $a_room['sale_status']['is_stock_without']) {
                            $a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_without'] = true;
                        } else {
                            // 販売開始前
                            if ($a_room['sale_status']['is_sale_still']) {
                                $a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_sale_still'] = true;
                            }

                            // 販売終了
                            if ($a_room['sale_status']['is_expiration']) {
                                $a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_expiration'] = true;
                            }

                            // 止（再販有）
                            if ($a_room['sale_status']['is_resale']) {
                                $a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_resale'] = true;
                            }

                            // 売
                            if ($a_room['sale_status']['is_sale']) {
                                $a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_sale'] = true;
                            }

                            // 上記条件郡に一致しなかった場合「止」
                            // 止（再販無）
                            if (
                                $a_plan['accept_status_plan'] != 1
                                || $a_room['accept_status_room'] != 1
                                || $a_room['sale_status']['is_stop_plan']
                                || $a_room['sale_status']['is_stop_room']
                                || $a_room['sale_status']['is_expiration']
                                || $a_room['sale_status']['is_stop_room_count']
                                || $a_room['sale_status']['is_stop_charge']
                                || $a_room['sale_status']['is_sale_still']
                                || $a_room['sale_status']['is_stock_zero']
                                || $a_room['sale_status']['is_charge_zero']
                                || $a_room['sale_status']['is_full']
                            ) {
                                $a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_stop'] = true;
                            }
                        }
                    }
                }
            }

            unset($a_temp);

            return $a_result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プランと部屋の指定期間の販売状況を判断するための情報を取得
     *
     * 「在庫数」、「料金」、「手仕舞」の状態
     */
    private function getFromToSaleState($as_from_ymd, $as_to_ymd, $target_cd)
    {
        try {
            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            // 施設コード
            if (empty($target_cd)) {
                throw new Exception('施設コードを設定してください。');
            }

            // 開始日が設定されているか
            if (!is_numeric($as_from_ymd) and !is_string($as_from_ymd)) {
                // エラーとする
                throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
            }

            // 文字列の場合
            if (is_string($as_from_ymd)) {
                $as_from_ymd = strtotime($as_from_ymd);
            }

            // 入力された日付が日付として正しくない場合はエラー
            if (!checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd))) {
                throw new Exception('開始日付が日付として正しくありません。');
            }

            // 指定された日付を設定
            $as_from_ymd = date('Y-m-d', $as_from_ymd);

            // 終了日が設定されているか
            if (!is_numeric($as_to_ymd) and !is_string($as_to_ymd)) {
                // エラーとする
                throw new Exception('終了日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
            }

            // 文字列の場合
            if (is_string($as_to_ymd)) {
                $as_to_ymd = strtotime($as_to_ymd);
            }

            // 入力された日付が日付として正しくない場合はエラー
            if (!checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd))) {
                throw new Exception('終了日付が日付として正しくありません。');
            }

            // 指定された日付を設定
            $as_to_ymd = date('Y-m-d', $as_to_ymd);

            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_conditions = [
                'hotel_cd' => $target_cd,
                'from_ymd' => $as_from_ymd,
                'to_ymd'   => $as_to_ymd
            ];

            $s_sql =
                <<< SQL
					select	q5.hotel_cd,
							q5.room_id,
							q5.accept_status_room,
							q5.plan_id,
							q5.accept_status_plan,
							q5.date_ymd,
							q5.rooms,
							q5.reserve_rooms,
							q5.remaining_rooms,
							q5.accept_status_room_count,
							c.sales_charge,
							c.accept_status as accept_status_charge,
							DATE_FORMAT(c.accept_s_dtm, '%Y-%m-%d %H:%i:%S') as accept_s_dtm,
							DATE_FORMAT(c.accept_e_dtm, '%Y-%m-%d %H:%i:%S') as accept_e_dtm
					from  charge c	
                    right outer join
							(
								select	q4.hotel_cd,
										q4.room_id,
										q4.accept_status_room,
										q4.plan_id,
										q4.accept_status_plan,
										q4.date_ymd,
										room_count2.rooms,
										room_count2.reserve_rooms,
										room_count2.rooms - room_count2.reserve_rooms as remaining_rooms,
										room_count2.accept_status as accept_status_room_count
								from	room_count2
                                right outer join 
										(
											select	q3.hotel_cd,
													q3.room_id,
													q3.accept_status_room,
													q3.plan_id,
													q3.accept_status_plan,
													mc.date_ymd
											from	mast_calendar mc,
													(
														select	q2.hotel_cd,
																q2.room_id,
																q2.accept_status_room,
																p.plan_id,
																p.accept_status as accept_status_plan
														from	plan p
                                                        right outer join 
																(
																	select	q1.hotel_cd,
																			q1.room_id,
																			q1.accept_status_room,
																			rpm.plan_id
																	from	room_plan_match rpm
                                                                    right outer join
																			(
																				select	r2.hotel_cd,
																						r2.room_id,
																						r2.accept_status as accept_status_room
																				from	room2 r2
																				where	r2.hotel_cd = :hotel_cd
																					and	r2.display_status = 1
																					and	r2.active_status  = 1
																			) q1
																			on	rpm.hotel_cd = q1.hotel_cd
																			and	rpm.room_id = q1.room_id
																) q2
															on p.hotel_cd = q2.hotel_cd
															and	p.plan_id = q2.plan_id
															and	p.display_status = 1
															and	p.active_status = 1
													) q3
											where mc.date_ymd between DATE_FORMAT(:from_ymd, '%Y-%m-%d') and DATE_FORMAT(:to_ymd, '%Y-%m-%d')
										) q4
								on room_count2.hotel_cd = q4.hotel_cd
								and	room_count2.room_id = q4.room_id
								and	room_count2.date_ymd = q4.date_ymd
							) q5
						on c.hotel_cd = q5.hotel_cd
						and	c.plan_id = q5.plan_id
						and	c.room_id = q5.room_id
						and	c.date_ymd = q5.date_ymd
					group by	q5.hotel_cd,
								q5.room_id,
								q5.accept_status_room,
								q5.plan_id,
								q5.accept_status_plan,
								q5.date_ymd,
								q5.rooms,
								q5.reserve_rooms,
								q5.remaining_rooms,
								q5.accept_status_room_count,
								c.sales_charge,
								c.accept_status,
								c.accept_s_dtm,
								c.accept_e_dtm
SQL;
            $a_rows = DB::select($s_sql, $a_conditions);
            return $a_rows;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 部屋・プラン別の指定期間の予約数を取得（プラン基準）
     *
     * @params string 開始日
     * @params string 終了日
     *
     * @return array 指定期間の各部屋プラン別の予約数
     */
    public function getFromToReserveCountPlanRoom($as_from_ymd, $as_to_ymd, $target_cd)
    {
        try {
            // 初期化
            $a_base_reserve_count = [];
            $a_result             = [];

            // 指定期間内の販売状況を判断するための情報を取得
            $a_base_reserve_count = $this->getFromToReserveCount($as_from_ymd, $as_to_ymd, $target_cd);
            // 日付基準の形に整形
            foreach ($a_base_reserve_count ?? [] as $a_reserve_count) {
                // キー情報を設定
                $s_room_id = $a_reserve_count->room_id;
                $s_plan_id = $a_reserve_count->plan_id;
                $n_ymd     = strtotime($a_reserve_count->date_ymd);

                // 日別の予約数合計
                $a_result[$n_ymd]['reserve_count_sum'] = ($a_result[$n_ymd]['reserve_count_sum'] ?? 0) + $a_reserve_count->reserve_count;

                // 日別・部屋・プランの予約数
                $a_result[$n_ymd]['plan'][$s_plan_id]['reserve_count'] = $a_reserve_count->reserve_count;
            }
            return $a_result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プランと部屋の指定期間の予約数を取得
     *
     * ・「在庫数」、「料金」、「手仕舞」の状態
     *
     * @param string 開始日
     * @param string 終了日
     *
     * @return array 指定期間の各部屋プラン別の予約数
     */
    private function getFromToReserveCount($as_from_ymd, $as_to_ymd, $target_cd)
    {
        try {
            //--------------------------------------------------------------
            // エラーチェック
            //--------------------------------------------------------------
            // 施設コード
            if (empty($target_cd)) {
                throw new Exception('施設コードを設定してください。');
            }

            // 開始日が設定されているか
            if (!is_numeric($as_from_ymd) and !is_string($as_from_ymd)) {
                // エラーとする
                throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
            }

            // 文字列の場合
            if (is_string($as_from_ymd)) {
                $as_from_ymd = strtotime($as_from_ymd);
            }

            // 入力された日付が日付として正しくない場合はエラー
            if (!checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd))) {
                throw new Exception('開始日付が日付として正しくありません。');
            }

            // 指定された日付を設定
            $as_from_ymd = date('Y-m-d', $as_from_ymd);

            // 終了日が設定されているか
            if (!is_numeric($as_to_ymd) and !is_string($as_to_ymd)) {
                // エラーとする
                throw new Exception('終了日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
            }

            // 文字列の場合
            if (is_string($as_to_ymd)) {
                $as_to_ymd = strtotime($as_to_ymd);
            }

            // 入力された日付が日付として正しくない場合はエラー
            if (!checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd))) {
                throw new Exception('終了日付が日付として正しくありません。');
            }

            // 指定された日付を設定
            $as_to_ymd = date('Y-m-d', $as_to_ymd);

            //--------------------------------------------------------------
            // 初期化
            //--------------------------------------------------------------
            $a_conditions = [
                'hotel_cd' => $target_cd,
                'from_ymd' => $as_from_ymd,
                'to_ymd'   => $as_to_ymd
            ];

            $s_sql =
                <<< SQL
					select	q3.hotel_cd,
							q3.plan_id,
							q3.room_id,
							q3.date_ymd,
							count(*) as reserve_count
					from	(
								select	rp.hotel_cd,
										rp.plan_id,
										rp.room_id,
										q2.date_ymd
								from	reserve_plan rp,
										(
											select	r.reserve_cd,
													r.date_ymd
											from	reserve r,
													(
														select	mc.date_ymd
														from	mast_calendar mc
														where	mc.date_ymd	between DATE_FORMAT(:from_ymd, '%Y-%m-%d')	and	DATE_FORMAT(:to_ymd, '%Y-%m-%d')
													) q1
											where	r.date_ymd = q1.date_ymd
												and	r.hotel_cd = :hotel_cd
												and	r.reserve_status = 0
										) q2
								where	rp.reserve_cd = q2.reserve_cd
							) q3
					group by	q3.hotel_cd,
								q3.plan_id,
								q3.room_id,
								q3.date_ymd
					order by	q3.date_ymd
SQL;
            $a_rows = DB::select($s_sql, $a_conditions);
            return $a_rows;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * ２進数を展開し一致するビットもしくは位に変換します。
     *
     * 旧ソース public\app\_common\models\Core.php のtoShiftメソッド
     * as_value 数字を設定
     * ab_bits  true ビットで返却 false 位で返却
     *
     * example
     *    bits = true
     *      > 30
     *        >> array(2, 4, 8, 16)
     *
     *    bits = false
     *      > 30
     *        >> array(1, 2, 3, 4)
     */
    public function toShift($as_value, $ab_bits = true)
    {
        try {
            if ($as_value <= 0) {
                return null;
            }

            $buf_value = 1;

            $n_cnt = 0;
            while (
                $buf_value <= $as_value
            ) {
                $buf_value <<= 1;
                $bits[] = [$buf_value / 2, $n_cnt];    // ビットと位を保持
                $n_cnt++;
            }
            // ビットで逆順に並び替え
            rsort($bits);

            // 一致するビットと位を取得
            for ($n_cnt = 0; $n_cnt < count($bits); $n_cnt++) {
                if ($bits[$n_cnt][0] <= $as_value) {
                    $a_bits[] = $bits[$n_cnt][0];
                    $a_position[] = $bits[$n_cnt][1];
                    $as_value = $as_value - $bits[$n_cnt][0];
                }
            }

            // ビットを返却
            if ($ab_bits) {
                return $a_bits;

                // 位を返却
            } else {
                return $a_position;
            }

            // 各メソッドで Exception が投げられた場合
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * コントローラ名とアクション名を取得して、ユーザーIDと連結
     * ユーザーID取得は暫定の為、書き換え替えが必要です。
     *
     * MEMO: app/Models/common/CommonDBModel.php から移植したもの
     * HACK: 適切に共通化したいか。
     * @return string
     */
    private function getActionCd()
    {
        $path = explode("@", \Illuminate\Support\Facades\Route::currentRouteAction());
        $pathList = explode('\\', $path[0]);
        $controllerName = str_replace("Controller", "", end($pathList)); // コントローラ名
        $actionName = $path[1]; // アクション名
        $userId = \Illuminate\Support\Facades\Session::get("user_id");   // TODO: ユーザー情報取得のキーは仮です
        $actionCd = $controllerName . "/" . $actionName . "." . $userId;

        return $actionCd;
    }
}
