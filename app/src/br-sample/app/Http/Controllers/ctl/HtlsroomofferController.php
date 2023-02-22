<?php

namespace App\Http\Controllers\ctl;

use App\Http\Controllers\ctl\_commonController;
use App\Models\HotelSystemVersion;
use App\Models\Room2;
use App\Models\RoomCount;
use App\Models\RoomCount2;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Common\DateUtil;

class HtlsRoomOfferController extends _commonController
{
    protected $_partner_cd = '0000000000'; // デフォルトのパートナーグループCD
    protected $_partner_group_id = null;   // 設定するパートナーグループID
    protected $_s_partner_cd       = null;  // 提携先コード
    protected $_n_partner_group_id = null;  // 提携先グループID
    private $_a_get_partner_group_id = [];  // キャッシュ用変数  （旧）public\app\_common\models\Core\Partner.php

    protected $a_calendar;
    protected $s_from_ymd = null;
    protected $s_to_ymd = null;
    protected $o_oracle;
    protected $a_charge_calendar = [];
    protected $a_define_day_of_week = ['日', '月', '火', '水', '木', '金', '土'];
    protected $a_details; // プラン情報複数（施設の保有するプラン全てを格納）
    protected $s_plan_id;
    protected $s_hotel_cd;

    // 特殊な扱いになる提携先コードの定義
    const PTN_CD_BR   = '0000000000'; // ベストリザーブ
    const PTN_CD_JRC  = '3015008801'; // JRコレクション
    const PTN_CD_RELO = '3015008796'; // リロクラブ

    // 特殊な提携先コードのリスト
    protected $a_special_partners;

    // 土曜日
    const SATDAY_NUM = 6;

    /**
     * インデックス
     */
    public function index()
    {
        try {
            // 一覧画面へ転送
            return $this->list();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 一覧画面
     */
    public function list()
    {
        try {
            // 初期化
            $o_date = new DateUtil();
            $o_now_date = new DateUtil();

            // リクエストパラメータ取得
            $target_cd = Request::input('target_cd');
            $a_form_params = Request::all();

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
            if (!empty($a_form_params['start_ymd']['year']) and !empty($a_form_params['start_ymd']['month']) and !empty($a_form_params['start_ymd']['day'])) {
                $o_date->set($a_form_params['start_ymd']['year'] . '-' . sprintf('%02d', $a_form_params['start_ymd']['month']) . '-' . sprintf('%02d', $a_form_params['start_ymd']['day']));
            }

            $a_date_range = $this->setDateRange($o_date->get());

            //「新部屋プランメンテナンス」メニューの表示・非表示判定
            $a_system_versions = $this->setDispRoomPlanList($target_cd);

            // アサインの登録
            $this->addViewData("target_cd", $target_cd);
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("date_range", $a_date_range);
            $this->addViewData("partner_group_id", $this->_partner_group_id);
            $this->addViewData("start_date", $a_start_date);
            $this->addViewData("end_date", $a_end_date);
            $this->addViewData("system_versions", $a_system_versions);

            // TODO: 仮で設定 認証関連機能ができ次第削除
            $this->addViewData("hotel", $hotel);


            // 以下は部屋手仕舞い対応の処理
            //----------------------------------------------------------------------------------
            // リクエストパラメータを取得
            //----------------------------------------------------------------------------------
            $a_request_params = Request::all();

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
            $match_room_plans_all    = $this->getMatchRoomPlansAll($target_cd);   // 部屋から見たときのプランとの組み合わせ情報
            $week_days               = $this->getCalendar();
            $sale_state_room_plan    = $this->getFromToSaleStateRoomPlan($a_request_params['from_ymd'], $a_request_params['to_ymd'], $target_cd);
            $reserve_count_room_plan = $this->getFromToReserveCountRoomPlan($a_request_params['from_ymd'], $a_request_params['to_ymd'], $target_cd);

            $this->addViewData("request_params", $a_form_params);
            $this->addViewData("room_details", $room_details);
            $this->addViewData("plan_details", $plan_details);
            $this->addViewData("match_room_plans_all", $match_room_plans_all);
            $this->addViewData("week_days", $week_days);
            $this->addViewData("sale_state_room_plan", $sale_state_room_plan);
            $this->addViewData("reserve_count_room_plan", $reserve_count_room_plan);

            return view("ctl.htlsroomoffer.list", $this->getViewData());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 提供部屋数編集
     */
    public function edit()
    {
        try {
            // リクエストパラメータ取得
            $target_cd = Request::input('target_cd');
            $a_form_params = Request::all();
            $a_form_params['accept_status'] = Request::input('accept_status');
            $a_form_params['remainder_room_zero'] = Request::input('remainder_room_zero');

            // 「新部屋プランメンテナンス」メニューの表示・非表示判定
            $a_system_versions = $this->setDispRoomPlanList($target_cd);

            // UIの種類によって処理を変更
            switch ($a_form_params['ui_type']) {
                case 'room':
                case 'accept':
                    $this->editUiTypeRoom($a_form_params);
                    break;

                case 'date':
                    $this->editUiTypeDate($a_form_params);
                    break;

                case 'calender':
                    $this->editUiTypeCalender($a_form_params);
                    break;
            }

            // アサインの登録
            $this->addViewData("target_cd", $target_cd);
            $this->addViewData("partner_group_id", $this->_partner_group_id);
            $this->addViewData("system_versions", $a_system_versions);

            return view("ctl.htlsroomoffer.edit", $this->getViewData());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 提供部屋数編集確認
     */
    public function confirm()
    {
        try {
            // アクションの実行
            if (!$this->confirmMethod()) {
                return $this->edit();
            }

            return view("ctl.htlsroomoffer.confirm", $this->getViewData());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 提供部屋数更新
     */
    public function update()
    {
        // リクエストパラメータ取得
        $a_form_params = Request::all();

        try {
            // トランザクション開始
            DB::beginTransaction();

            // アクションの実行
            if (!$this->updateMethod($a_form_params)) {
                DB::rollback();
                return $this->edit();
            }

            DB::commit();

            // --------------------------------------------------------------
            // TODO 料金のユーザー画面への反映
            //   ※モデル内でトランザクションが実装されているのを可能ならば
            //     コントローラに任せる仕様に変更したい

            // 2022/12/16追記（関）
            // 網さん曰く、if (count($this->params('rooms')) === 1)はバグっぽい。毎回elseに流れるはず。
            // charge_consitionテーブルがある理由は、中間テーブルを作って検索処理を軽くするため。
            // ACホテルサイトはhotel_cdの先頭に都道府県コードをつけて検索する形になるはずなので、Core_ChargeCondition()は必要なくなるかも。
            // --------------------------------------------------------------

            // $o_models_charge_conditions = new Core_ChargeCondition();

            // if (count($this->params('rooms')) === 1) {
            //     $o_models_charge_conditions->set_charge(array('hotel_cd' => $this->params('target_cd'), 'room_id' => $this->params('room_id')));
            // } else {
            //     $o_models_charge_conditions->set_charge(array('hotel_cd' => $this->params('target_cd')));
            // }

            return view("ctl.htlsroomoffer.update", $this->getViewData());
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
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
            // $o_models_room_akafu_relation = new RoomAkafuRelation();
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
        $this->a_details          = [];

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
            // $o_models_plam_point = new PlanPoint();
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
     * 施設のすべての部屋が紐づくプランIDをすべて取得
     */
    public function getMatchRoomPlansAll($s_hotel_cd)
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
                $a_result[$a_row->room_id][] = $a_row->plan_id;
            }

            return $a_result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * プランと部屋の指定期間の販売状況を判断するための情報を取得(部屋基準)
     *
     * ・「在庫数」、「料金」、「手仕舞」の状態
     *
     * @param string 開始日
     * @param string 終了日
     *
     * @return array 指定期間のプランと部屋の販売状況
     */
    public function getFromToSaleStateRoomPlan($as_from_ymd, $as_to_ymd, $target_cd)
    {
        try {
            // 初期化
            $a_base_sale_state = [];
            $a_temp            = [];
            $a_result          = [];

            // 指定期間内の販売状況を判断するための情報を取得
            $a_base_sale_state = $this->getFromToSaleState($as_from_ymd, $as_to_ymd, $target_cd);

            // 部屋基準の形に整形
            foreach ($a_base_sale_state ?? [] as $a_sale_state) {
                // キー情報を設定
                $s_room_id = $a_sale_state->room_id;
                $s_plan_id = $a_sale_state->plan_id;
                $n_ymd     = strtotime($a_sale_state->date_ymd);

                // 販売停止フラグ
                $b_is_stop = false;

                $a_temp[$n_ymd]['room'][$s_room_id]['rooms']                    = $a_sale_state->rooms;
                $a_temp[$n_ymd]['room'][$s_room_id]['reserve_rooms']            = $a_sale_state->reserve_rooms;
                $a_temp[$n_ymd]['room'][$s_room_id]['remaining_rooms']          = $a_sale_state->remaining_rooms;
                $a_temp[$n_ymd]['room'][$s_room_id]['accept_status_room']       = $a_sale_state->accept_status_room;
                $a_temp[$n_ymd]['room'][$s_room_id]['accept_status_room_count'] = $a_sale_state->accept_status_room_count;

                if (empty($s_plan_id)) {
                    continue;
                }

                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_status_plan']   = $a_sale_state->accept_status_plan;
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_status_charge'] = $a_sale_state->accept_status_charge;
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_s_dtm']         = strtotime($a_sale_state->accept_s_dtm);
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_e_dtm']         = strtotime($a_sale_state->accept_e_dtm);
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sales_charge']         = $a_sale_state->sales_charge;

                //----------------------------------------------------------
                // 部屋が休止
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room'] = null;
                if ($a_sale_state->accept_status_room != 1) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // プランが休止
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_plan'] = null;
                if ($a_sale_state->accept_status_plan != 1) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_plan'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 販売日時が経過
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_expiration'] = null;
                if (!is_null($a_sale_state->accept_e_dtm) && strtotime($a_sale_state->accept_e_dtm) < strtotime('now')) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_expiration'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 部屋が手仕舞
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room_count'] = null;
                if (!is_null($a_sale_state->accept_status_room_count) && $a_sale_state->accept_status_room_count != 1) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room_count'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 料金が手仕舞
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_charge'] = null;
                if (!is_null($a_sale_state->accept_status_charge) && ($a_sale_state->accept_status_charge != 1)) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_charge'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 満室かどうか
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_full'] = null;
                if (($a_sale_state->rooms > 0) && ($a_sale_state->remaining_rooms < 1)) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_full'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 販売がまだ開始されていない
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_sale_still'] = null;
                if (!is_null($a_sale_state->accept_s_dtm) && strtotime($a_sale_state->accept_s_dtm) > strtotime('now')) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_sale_still'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 料金が0
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_zero'] = null;
                if (!is_null($a_sale_state->sales_charge) && ($a_sale_state->sales_charge < 1)) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_zero'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 提供室数が0（再販なしの在庫0）
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stock_zero'] = null;
                if (!is_null($a_sale_state->rooms) && $a_sale_state->rooms < 1) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stock_zero'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 料金の登録がない
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_without'] = null;
                if (is_null($a_sale_state->sales_charge)) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_without'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 在庫の登録がない
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stock_without'] = null;
                if (is_null($a_sale_state->rooms)) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stock_without'] = true;
                    $b_is_stop = true;
                }

                //----------------------------------------------------------
                // 再販の可能性
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_resale'] = null;
                if (
                    !is_null($a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_full'])
                    && $a_temp[$n_ymd]['room'][$s_room_id]['accept_status_room'] == 1
                    && $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_status_plan'] == 1
                    && !$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_zero']
                    && !$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_charge']
                    && !$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room_count']
                ) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_resale'] = true;
                }

                //----------------------------------------------------------
                // 上記条件にあてはまらない場合は販売されているとする
                //----------------------------------------------------------
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop'] = null;
                $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_sale'] = null;
                if ($b_is_stop) {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop'] = true;
                } else {
                    $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_sale'] = true;
                }
            }

            // 日付でループ
            foreach ($a_temp ?? [] as $n_ymd => $a_day_sale_state) {
                // 日別の部屋でループ
                foreach ($a_day_sale_state['room'] ?? [] as $s_room_id => $a_room) {
                    // 日別の提供室数合計を取得
                    $a_result[$n_ymd]['rooms_sum'] = ($a_result[$n_ymd]['rooms_sum'] ?? 0) + $a_room['rooms'];

                    // 日別の残室数合計を取得
                    $a_result[$n_ymd]['remaining_rooms_sum'] = ($a_result[$n_ymd]['remaining_rooms_sum'] ?? 0) + $a_room['remaining_rooms'];

                    $a_result[$n_ymd]['room'][$s_room_id] = $a_room;

                    // 日別・部屋・プランでループ

                    foreach ($a_room['plan'] ?? [] as $s_plan_id => $a_plan) {
                        // 料金または在庫が登録されていない
                        if ($a_plan['sale_status']['is_charge_without'] || $a_plan['sale_status']['is_stock_without']) {
                            $a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_without'] = true;
                            $a_result[$n_ymd]['sale_status']['is_without'] = true;
                        } else {
                            // 販売開始前
                            if ($a_plan['sale_status']['is_sale_still']) {
                                $a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_sale_still'] = true;
                                $a_result[$n_ymd]['sale_status']['is_sale_still'] = true;
                            }

                            // 販売終了
                            if ($a_plan['sale_status']['is_expiration']) {
                                $a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_expiration'] = true;
                                $a_result[$n_ymd]['sale_status']['is_expiration'] = true;
                            }

                            // 止（再販有）
                            if ($a_plan['sale_status']['is_resale']) {
                                $a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_resale'] = true;
                                $a_result[$n_ymd]['sale_status']['is_resale'] = true;
                            }

                            // 売
                            if ($a_plan['sale_status']['is_sale']) {
                                $a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_sale'] = true;
                                $a_result[$n_ymd]['sale_status']['is_sale'] = true;
                            }

                            // 止
                            if (
                                $a_room['accept_status_room'] != 1
                                || $a_plan['accept_status_plan'] != 1
                                || $a_plan['sale_status']['is_expiration']
                                || $a_plan['sale_status']['is_stop_room_count']
                                || $a_plan['sale_status']['is_stop_charge']
                                || $a_plan['sale_status']['is_sale_still']
                                || $a_plan['sale_status']['is_stock_zero']
                                || $a_plan['sale_status']['is_charge_zero']
                                || $a_plan['sale_status']['is_full']
                            ) {
                                $a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_stop'] = true;
                                $a_result[$n_ymd]['sale_status']['is_stop'] = true;
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
     * 部屋・プラン別の指定期間の予約数を取得（部屋基準）
     *
     * ・「在庫数」、「料金」、「手仕舞」の状態
     *
     * @param string 開始日
     * @param string 終了日
     *
     * @return array 指定期間の各部屋プラン別の予約数
     */
    public function getFromToReserveCountRoomPlan($as_from_ymd, $as_to_ymd, $target_cd)
    {
        try {
            // 初期化
            $a_base_reserve_count = [];
            $a_result             = [];
            // 指定期間内の販売状況を判断するための情報を取得
            $a_base_reserve_count = $this->getFromToReserveCount($as_from_ymd, $as_to_ymd, $target_cd);
            // 日付基準の形に整形
            foreach ($a_base_reserve_count as $a_reserve_count) {
                // キー情報を設定
                $s_room_id = $a_reserve_count->room_id;
                $s_plan_id = $a_reserve_count->plan_id;
                $n_ymd     = strtotime($a_reserve_count->date_ymd);

                // 日別の予約数合計
                $a_result[$n_ymd]['reserve_count_sum'] = ($a_result[$n_ymd]['reserve_count_sum'] ?? 0) + $a_reserve_count->reserve_count;

                // 日別・部屋・プランの予約数
                $a_result[$n_ymd]['room_plan']['room'][$s_room_id]['plan'][$s_plan_id]['reserve_count'] = $a_reserve_count->reserve_count;
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
     * プランと部屋の指定期間の販売状況を判断するための情報を取得
     *
     * ・「在庫数」、「料金」、「手仕舞」の状態
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
     * Getter：指定の表示期間開始日の週の初日日付を取得
     *
     * @return string 設定された表示期間開始日
     */
    public function getFromYmdWeekFirst()
    {
        // 指定の開始日の週の日曜日を取得(※カレンダーは日曜日から表示)
        $n_from_ymd     = strtotime($this->s_from_ymd);
        $n_from_weekday = (int)date('w', $n_from_ymd);
        $s_from_day_sun = date('Y-m-d', strtotime('-' . $n_from_weekday . ' day', $n_from_ymd));

        return $s_from_day_sun;
    }

    /**
     * Getter：指定の表示期間終了日の週の週末日付を取得
     *
     * @return string 設定された表示期間終了日
     */
    public function getToYmdWeekLast()
    {
        // 指定の終了日の週の土曜日を取得(※カレンダーは土曜日まで表示)
        $n_to_ymd     = strtotime($this->s_to_ymd);
        $n_to_weekday = (self::SATDAY_NUM) - (int)date('w', $n_to_ymd);
        $s_to_day_sat = date('Y-m-d', strtotime('+' . $n_to_weekday . ' day', $n_to_ymd));

        return $s_to_day_sat;
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
     * 部屋指定 - 在庫編集
     *
     * ※edit()ここから
     */
    private function editUiTypeRoom($a_form_params)
    {

        try {
            $o_now_date   = new DateUtil();
            $o_room2      = new Room2();
            $o_room_count2 = new RoomCount2();

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

            // 対象部屋の在庫状況を取得
            $a_room2 = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id']])->first();
            $a_room2['room_count'] = $o_room_count2->where(
                [
                    'hotel_cd' => $a_form_params['target_cd'],
                    'room_id' => $a_form_params['room_id'],
                    'date_ymd' => date('Ymd', $a_form_params['date_ymd'])
                ]
            )->first() ?? [
                'hotel_cd' => null,
                'room_id'  => null,
                'date_ymd' => null,
                'rooms' => null,
                'reserve_rooms' => null,
                'entry_cd' => null,
                'entry_ts' => null,
                'modify_cd' => null,
                'modify_ts' => null,
            ];

            $a_form_params['rooms'] = $a_form_params['rooms'] ?? $a_room2['room_count']['rooms'];

            $o_now_date->set($a_form_params['date_ymd']);
            $a_form_params['date_ymd_from_year']  = $a_form_params['date_ymd_from_year'] ?? $o_now_date->to_format('Y');
            $a_form_params['date_ymd_from_month'] = $a_form_params['date_ymd_from_month'] ?? $o_now_date->to_format('m');
            $a_form_params['date_ymd_from_day']   = $a_form_params['date_ymd_from_day'] ?? $o_now_date->to_format('d');
            $a_form_params['date_ymd_to_year']    = $a_form_params['date_ymd_to_year'] ?? $o_now_date->to_format('Y');
            $a_form_params['date_ymd_to_month']   = $a_form_params['date_ymd_to_month'] ?? $o_now_date->to_format('m');
            $a_form_params['date_ymd_to_day']     = $a_form_params['date_ymd_to_day'] ?? $o_now_date->to_format('d');

            $b_is_room_akf = $this->isRoomAkf($a_form_params['target_cd'], $a_form_params['room_id']);

            // アサインの登録
            $this->addViewData("start_date", $a_start_date);
            $this->addViewData("end_date", $a_end_date);
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("room", $a_room2);
            $this->addViewData("is_room_akf", $b_is_room_akf);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 部屋在庫タイプの取得（部屋別）
     *
     * as_hotel_cd 施設コード
     * as_room_id  部屋ID
     *
     */
    public function isRoomAkf($as_hotel_cd, $as_room_id)
    {
        try {
            // 初期化
            $a_room_stock_type = [];

            // 判定
            $b_is_akf = $this->isAkafu($as_hotel_cd, $as_room_id);
            return $b_is_akf;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 赤い風船在庫
     *
     * as_hotel_cd 施設コード
     * as_room_id  部屋ID
     *
     */
    public function isAkafu($as_hotel_cd, $as_room_id)
    {
        try {
            $s_sql =
                <<< SQL
					select	q1.hotel_cd
					from	room_akafu_relation ra,
						(
							select	r2.hotel_cd,
									r2.room_id
							from	room2 r2
							where	r2.hotel_cd = :hotel_cd
								and	r2.room_id  = :room_id
						) q1
					where	ra.hotel_cd = q1.hotel_cd
						and	ra.room_id  = q1.room_id
SQL;
            $a_rows = DB::select($s_sql, ['hotel_cd' => $as_hotel_cd, 'room_id' => $as_room_id]);

            if (empty($a_rows)) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 日付指定 - 在庫編集
     */
    private function editUiTypeDate($a_form_params)
    {
        try {
            // 初期化
            $o_room2      = new Room2();
            $o_room_count2 = new RoomCount2();
            $o_date   = new DateUtil($a_form_params['date_ymd']);

            $o_date->set($o_date->to_format('Y-m-d'));
            $a_disp_date = ['target_date' => $o_date->get(), 'week_day'    => $o_date->to_week('j')];

            foreach ($a_form_params['room_id'] as $room_id) {
                $a_room2[$room_id] = $o_room2->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'room_id' => $room_id
                    ]
                )->first();
                $a_room2[$room_id]['room_count'] = $o_room_count2->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'room_id' => $room_id,
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? [
                    'hotel_cd' => null,
                    'room_id'  => null,
                    'date_ymd' => null,
                    'rooms' => null,
                    'reserve_rooms' => null,
                    'entry_cd' => null,
                    'entry_ts' => null,
                    'modify_cd' => null,
                    'modify_ts' => null,
                ];
                $a_room2[$room_id]['is_room_akf'] = $this->isRoomAkf($a_form_params['target_cd'], $room_id);
                $a_form_params['rooms'][$room_id] = $a_form_params['rooms'][$room_id] ?? $a_room2[$room_id]['room_count']['rooms'];
            }
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("rooms", $a_room2);
            $this->addViewData("disp_date", $a_disp_date);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * カレンダー - 在庫編集
     */
    private function editUiTypeCalender($a_form_params)
    {
        try {
            // 初期化
            $o_now_date   = new DateUtil();
            $o_room2      = new Room2();

            // 部屋情報取得
            $a_room2 = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id'  => $a_form_params['room_id']])->first();

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

            if (empty($a_form_params['calender_current_year']) && empty($a_form_params['calender_current_month'])) {
                $o_now_date->set($a_form_params['date_ym']);
                $a_form_params['calender_current_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['calender_current_month'] = (int)$o_now_date->to_format('m');
            } else {
                $s_current_date = sprintf('%04d', $a_form_params['calender_current_year']) . '-' . sprintf('%02d', $a_form_params['calender_current_month']) . '-01';
                $o_now_date->set($s_current_date);
                $o_now_date->set($o_now_date->to_format('Y-m-d'));
                $a_form_params['date_ym'] = $o_now_date->get();
                $a_form_params['calender_current_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['calender_current_month'] = (int)$o_now_date->to_format('m');
            }

            $a_calender_values = [];
            foreach ($a_form_params as $key => $value) {
                if (preg_match('/rooms_[0-9]{1}?/', $key)) {
                    $a_tmp_key = explode('_', $key); // [0]カラム名 [1]タイムスタンプ
                    $a_calender_values[$a_tmp_key[1]] = $value;
                }
            }

            // カレンダー表示用の配列を生成
            $a_calender = $this->createCalender(['current_ym' => $a_form_params['date_ym'], 'hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id'], 'calender' => $a_calender_values]);

            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("room", $a_room2);
            $this->addViewData("start_date", $a_start_date);
            $this->addViewData("end_date", $a_end_date);
            $this->addViewData("calender", $a_calender);
            $this->addViewData("is_room_akf", $this->isRoomAkf($a_form_params['target_cd'], $a_form_params['room_id']));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 施設の赤風在庫一覧を取得
     */
    public function getRoomAkfAll($as_hotel_cd)
    {
        try {
            // 初期化
            // $_oracle                  = _Oracle::getInstance();
            $a_conditions             = [];
            $a_conditions['hotel_cd'] = $as_hotel_cd;
            $a_result                 = [];

            $s_sql =
                <<< SQL
					select	rar.room_id
					from	room_akafu_relation rar,
						(
							select	r2.hotel_cd,
									r2.room_id
							from	room2 r2
							where	r2.hotel_cd       = :hotel_cd
								and	r2.display_status = 1
								and	r2.active_status  = 1
						) q1
					where	rar.hotel_cd = q1.hotel_cd
						and	rar.room_id  = q1.room_id
SQL;
            $a_rows = DB::select($s_sql, $a_conditions);

            foreach ($a_rows ?? [] as $a_room) {
                $a_result[$a_room['room_id']] = $a_room['room_id'];
            }

            return $a_result ?? [];
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * カレンダー表示用データ生成
     */
    private function createCalender($aa_conditions)
    {
        try {
            // 初期化
            $o_date   = new DateUtil($aa_conditions['current_ym']);
            $o_date_time = new DateUtil();
            $o_date->set($o_date->to_format('Y-m') . '-01');
            $a_calender = [];
            $n_week_idx = 1;
            $o_room_count2 = new RoomCount2();

            // カレンダー最終週の土曜日の翌日日付を取得
            $o_date->set($o_date->last_day());
            $o_date->week_day(7);
            $o_date->set($o_date->to_format('Y-m-d'));
            $o_date->add('d', 1);
            $n_last_date = $o_date->get();

            // カレンダー開始週の日曜日の日付を取得
            $o_date->set($aa_conditions['current_ym']);
            $o_date->set($o_date->to_format('Y-m') . '-01');
            $o_date->week_day(1);

            // カレンダー配列に整形
            $n_val_cnt = 0;
            while ($n_last_date != $o_date->get()) {
                $a_room_count = $o_room_count2->where(
                    [
                        'hotel_cd' => $aa_conditions['hotel_cd'],
                        'room_id'  => $aa_conditions['room_id'],
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? [
                    'hotel_cd' => null,
                    'room_id'  => null,
                    'date_ymd' => null,
                    'rooms' => null,
                    'reserve_rooms' => null,
                    'entry_cd' => null,
                    'entry_ts' => null,
                    'modify_cd' => null,
                    'modify_ts' => null,
                ];

                // 当日が編集可能かどうか判定（翌日AM-06:00までは編集可能）
                $o_date_time->set();
                $o_date_time->set($o_date_time->to_format('Y-m-d H:i:s'));
                $n_now_date_time = $o_date_time->get(); // 現在日時
                $o_date_time->set($o_date->to_format('Y-m-d H:i:s'));
                $o_date_time->add('d', 1);
                $o_date_time->set($o_date_time->to_format('Y-m-d 06:00:00'));
                $b_is_edit = false;
                if ($n_now_date_time < $o_date_time->get()) {
                    $b_is_edit = true;
                } else {
                    $b_is_edit = false;
                }
                // 日付ごとにデータを格納
                $a_calender['week' . $n_week_idx]['days'][]          = (int)$o_date->to_format('d');
                $a_calender['week' . $n_week_idx]['months'][]        = (int)$o_date->to_format('m');
                $a_calender['week' . $n_week_idx]['date_ymd'][]      = $o_date->get();
                $a_calender['week' . $n_week_idx]['rooms'][]         = $a_room_count['rooms'];
                $a_calender['week' . $n_week_idx]['edit_rooms'][]    = $aa_conditions['calender'][$o_date->get()] ?? $a_room_count['rooms'];
                $a_calender['week' . $n_week_idx]['reserve_rooms'][] = $a_room_count['reserve_rooms'];
                $a_calender['week' . $n_week_idx]['is_edit'][]       = $b_is_edit;

                // 1週間分格納したら行を変更
                if (6 <= $n_val_cnt) {
                    $n_week_idx++;
                    $n_val_cnt = 0;
                } else {
                    $n_val_cnt++;
                }
                $o_date->add('d', 1);
            }
            return $a_calender;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 提供部屋数編集確認
     *
     * ※confirm() ここから
     */
    protected function confirmMethod()
    {
        try {
            // 初期化
            $b_is_success = true;

            // リクエストパラメータ取得
            $target_cd = Request::input('target_cd');
            $a_form_params = Request::all();
            $a_form_params['accept_status'] = $a_form_params['accept_status'] ?? -1;

            // UIの種類によって処理を変更
            switch ($a_form_params['ui_type']) {
                case 'room':
                case 'accept':
                    $b_is_success = $this->confirmUiTypeRoom($a_form_params);
                    break;

                case 'date':
                    $b_is_success = $this->confirmUiTypeDate($a_form_params);
                    break;

                case 'calender':
                    $b_is_success = $this->confirmUiTypeCalender($a_form_params);
                    break;
            }

            // 「新部屋プランメンテナンス」メニューの表示・非表示判定
            $a_system_versions = $this->setDispRoomPlanList($target_cd);

            // アサインの登録
            $this->addViewData("target_cd", $target_cd);
            $this->addViewData("partner_group_id", $this->_partner_group_id);
            $this->addViewData("system_versions", $a_system_versions);

            return $b_is_success;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 部屋指定 - 在庫編集確認
     */
    private function confirmUiTypeRoom($a_form_params)
    {

        try {
            $o_date        = new DateUtil();
            $o_room2       = new Room2();
            $o_room_count2 = new RoomCount2();

            $o_date->set($o_date->to_format('Y-m-d H:i:s'));
            $n_now_date = $o_date->get();

            $o_date->set($a_form_params['date_ymd_from_year'] . '-' . sprintf('%02d', $a_form_params['date_ymd_from_month']) . '-' . sprintf('%02d', $a_form_params['date_ymd_from_day']));
            $a_form_params['from_date'] = $o_date->to_format('Y-m-d');
            $o_date->set($a_form_params['date_ymd_to_year'] . '-' . sprintf('%02d', $a_form_params['date_ymd_to_month']) . '-' . sprintf('%02d', $a_form_params['date_ymd_to_day']));
            $a_form_params['to_date'] = $o_date->to_format('Y-m-d');

            // 対象部屋の在庫状況を取得
            $a_room2 = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id']])->first();

            $o_date->set($a_form_params['date_ymd']);
            $o_date->set($o_date->to_format('Y-m-d'));

            $a_room2['room_count'] = $o_room_count2->where(
                [
                    'hotel_cd' => $a_form_params['target_cd'],
                    'room_id'  => $a_form_params['room_id'],
                    'date_ymd' => $o_date->get()
                ]
            );

            // 在庫数設定期間最終日取得
            $o_date->set($a_form_params['to_date']);
            $o_date->add('d', 1);
            $n_last_date = $o_date->get();

            // 在庫数設定期間開始日設定
            $o_date->set($a_form_params['from_date'] . ' 06:00:00');
            $o_date->add('d', 1);
            $n_start_date_time = $o_date->get();

            $o_date->set($a_form_params['from_date']);
            // 過去日付は編集できないエラーメッセージ(当日30時までは変更可能)
            if ($n_start_date_time < $n_now_date) {
                // エラーメッセージ
                $this->addErrorMessage('設定期間開始日は' . date('Y-m-d', $n_now_date) . '以降で設定してください。');
                return false;
            }

            if ($n_last_date < $o_date->get()) {
                // エラーメッセージ
                $this->addErrorMessage('設定期間開始日と設定期間終了日が逆転しています。');
                return false;
            }

            // 在庫数入力チェック
            if (!empty($a_form_params['remainder_room_zero'])) {
                $a_form_params['rooms'] = 0;
            }
            if ($a_form_params['accept_status'] < -1) {
                $this->addErrorMessage('販売ステータスを正しく選択してください。');
                return false;
            }

            // バリデート実行
            $o_validations = [];
            $o_validations['hotel_cd'] = $a_form_params['target_cd'];
            $o_validations['room_id'] = $a_form_params['room_id'];
            $o_validations['date_ymd'] = date("Ymd", $a_form_params['date_ymd']);

            //販売ステータス変更無の場合にチェックが走らないようにするため
            if (0 <= $a_form_params['accept_status']) {
                $o_validations['accept_status'] = $a_form_params['accept_status'];
            }

            $n_loop_cnt = 0;
            while ($n_last_date != $o_date->get()) {
                $a_room_count = $o_room_count2->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'room_id'  => $a_form_params['room_id'],
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? [
                    'hotel_cd' => null,
                    'room_id'  => null,
                    'date_ymd' => null,
                    'rooms' => null,
                    'reserve_rooms' => null,
                    'entry_cd' => null,
                    'entry_ts' => null,
                    'modify_cd' => null,
                    'modify_ts' => null,
                ];

                if ($a_form_params['ui_type'] === 'room') {
                    //期間設定開始日にもともと在庫が無い場合で、在庫数の設定が無い場合は在庫数登録を促すメッセージを出す。
                    if ($n_loop_cnt == 0 && is_null($a_room_count['rooms'])) {
                        if (empty($a_form_params['rooms'])) {
                            $this->addErrorMessage('在庫数は必ず入力してください。');
                            return false;
                        }
                    } else {
                        //既存の場合は提供室数0とする
                        if (empty($a_form_params['rooms'])) {
                            $a_form_params['rooms'] = 0;
                        }
                    }
                    //Validationを利用してしまうと、予約数との在庫数比較チェックが走ってしまうので、
                    //確認画面では数値かどうかだけのチェックのみ行う。
                    //updateUiTypeRoomで、予約数と比較し適切な在庫数調整を行う。
                    if (!preg_match('/^[0-9]+$/', $a_form_params['rooms'])) {
                        $this->addErrorMessage('在庫数は半角数字のみで入力してください。');
                        return false;
                    }
                }

                $errorList = [];
                $errorList = $o_room_count2->validation($o_validations);
                if (count($errorList) > 0) {
                    $this->addErrorMessageArray($errorList);
                    return false;
                } else {
                    // 独自のバリデーション
                    // 在庫数 ＜ 予約部屋数
                    if (intval($a_form_params['rooms'] ?? null) < $a_room_count['reserve_rooms']) {
                        $this->addErrorMessage('現在の予約数以下には設定できません。');
                        return false;
                    }
                }

                $o_date->add('d', 1);
                $n_loop_cnt++;
            }
            $b_is_room_akf = $this->isRoomAkf($a_form_params['target_cd'], $a_form_params['room_id']);
            $a_form_params['setting_period'] = $n_loop_cnt;

            // アサインの登録
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("room", $a_room2);
            $this->addViewData("is_room_akf", $b_is_room_akf);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 日付指定 - 在庫編集確認
     */
    private function confirmUiTypeDate($a_form_params)
    {
        try {
            // 初期化
            $o_date         = new DateUtil($a_form_params['date_ymd']);
            $o_date->set($o_date->to_format('Y-m-d'));
            $o_room2        = new Room2();
            $o_room_count2  = new RoomCount2();

            // 編集可能な日付かどうか判定（翌日AM-06:00まで編集可能）
            $n_target_ymd     = $o_date->get();
            $o_date->set($o_date->to_format('Y-m-d 06:00:00'));
            $o_date->add('d', 1);
            $n_edit_limit_ymd = $o_date->get();
            $o_date->set();
            if ($n_edit_limit_ymd < $o_date->get()) {
                // エラーメッセージ
                $this->addErrorMessage('過去の在庫数は編集できません。');
                return false;
            }
            $o_date->set($n_target_ymd); // 元に戻す

            $a_disp_date = ['target_date' => $o_date->get(), 'week_day' => $o_date->to_week('j')];
            foreach ($a_form_params['room_id'] as $room_id) {
                $a_room2[$room_id] = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $room_id])->first();
                $a_room2[$room_id]['room_count'] = $o_room_count2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id'  => $room_id, 'date_ymd' => date("Y/m/d G:i:s", $o_date->get())])->first() ?? [];
                $a_room2[$room_id]['is_room_akf'] = $this->isRoomAkf($a_form_params['target_cd'], $room_id);
            }

            foreach ($a_form_params['room_id'] as $room_id) {
                // 部屋在庫情報取得
                $a_room_count = $o_room_count2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id'  => $room_id, 'date_ymd' => date("Y/m/d G:i:s", $o_date->get())])->first();

                // 部屋在庫タイプ取得
                $b_is_room_akf = $this->isRoomAkf($a_form_params['target_cd'], $room_id);
                // 在庫数より予約数の方が多い場合は予約数を設定する
                $a_room_count['reserve_rooms'] = $a_room_count['reserve_rooms'] ?? 0;
                if ($a_form_params['rooms'][$room_id] < $a_room_count['reserve_rooms']) {
                    $n_reserve_rooms = $a_room_count['reserve_rooms'];
                } else {
                    $n_reserve_rooms = $a_form_params['rooms'][$room_id];
                }
                $a_form_params['rooms'][$room_id] = $n_reserve_rooms;

                if (!empty($a_room_count)) {
                    // 全室残室０が指定されているとき
                    if (!empty($a_form_params['remainder_room_zero'])) {
                        $a_form_params['rooms'][$room_id] = 0;
                        if (!$b_is_room_akf) {
                            $a_form_params['rooms'][$room_id] = $a_room_count['reserve_rooms'];
                        }
                    }

                    $o_validations = [];
                    $o_validations['hotel_cd'] = $a_form_params['target_cd'];
                    $o_validations['room_id'] = $room_id;
                    $o_validations['date_ymd'] = date("Ymd", $a_form_params['date_ymd']);
                    $o_validations['rooms'] = $a_form_params['rooms'][$room_id];
                    $o_validations['reserve_rooms'] = $a_room_count['reserve_rooms'];
                    if (0 <= $a_form_params['accept_status']) {
                        // 連携在庫のときは編集しない
                        if (!$b_is_room_akf) {
                            $o_validations['accept_status'] = $a_form_params['accept_status'];
                        }
                    }
                    $errorList = [];
                    $errorList = $o_room_count2->validation($o_validations);
                    if (count($errorList) > 0) {
                        $this->addErrorMessageArray($errorList);
                        return false;
                    } else {
                        // 独自のバリデーション
                        // 在庫数 ＜ 予約部屋数
                        if (intval($a_form_params['rooms'][$room_id]) < $a_room_count['reserve_rooms']) {
                            $this->addErrorMessage('現在の予約数以下には設定できません。');
                            return false;
                        }
                    }
                }
            }

            // _confirm_ui_date.bladeのforeach valueエラー回避のため
            $_confirm_ui_date_back_form = $a_form_params;
            unset($_confirm_ui_date_back_form["room_id"]);
            unset($_confirm_ui_date_back_form["rooms"]);

            // アサインの登録
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("confirm_ui_date_back_form", $_confirm_ui_date_back_form);
            $this->addViewData("rooms", $a_room2);
            $this->addViewData("disp_date", $a_disp_date);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * カレンダー - 在庫編集確認
     */
    private function confirmUiTypeCalender($a_form_params)
    {
        try {
            $o_now_date     = new DateUtil();
            $o_room2        = new Room2();

            // 部屋情報取得
            $a_room2 = $o_room2->where(
                [
                    'hotel_cd' => $a_form_params['target_cd'],
                    'room_id'  => $a_form_params['room_id']
                ]
            )->first();

            if (empty($a_form_params['calender_current_year']) && empty($a_form_params['calender_current_month'])) {
                $o_now_date->set($a_form_params['date_ym']);
                $a_form_params['calender_current_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['calender_current_month'] = (int)$o_now_date->to_format('m');
            } else {
                $s_current_date = sprintf('%04d', $a_form_params['calender_current_year']) . '-' . sprintf('%02d', $a_form_params['calender_current_month']) . '-01';
                $o_now_date->set($s_current_date);
                $o_now_date->set($o_now_date->to_format('Y-m-d'));
                $a_form_params['date_ym'] = $o_now_date->get();
                $a_form_params['calender_current_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['calender_current_month'] = (int)$o_now_date->to_format('m');
            }

            $a_calender_values = [];
            foreach ($a_form_params as $key => $value) {
                if (preg_match('/rooms_[0-9]{1}?/', $key)) {
                    $a_tmp_key = explode('_', $key); // [0]カラム名 [1]タイムスタンプ
                    $a_calender_values[$a_tmp_key[1]] = $value;
                }
            }

            // カレンダー表示用の配列を生成
            $a_calender = $this->confirmCalender(
                [
                    'current_ym' => $a_form_params['date_ym'],
                    'hotel_cd' => $a_form_params['target_cd'],
                    'room_id' => $a_form_params['room_id'], 'calender' => $a_calender_values
                ]
            );
            if (!$a_calender) {
                return false;
            }

            // アサインの登録
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("room", $a_room2);
            $this->addViewData("calender", $a_calender);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * カレンダーデータ更新
     */
    private function confirmCalender($aa_conditions)
    {
        try {
            // 初期化
            $o_date     = new DateUtil($aa_conditions['current_ym']);
            $o_date->set($o_date->to_format('Y-m') . '-01');

            $a_calender     = [];
            $n_week_idx     = 1;
            $o_room_count2   = new RoomCount2();

            // カレンダー最終週の土曜日の翌日日付を取得
            $o_date->set($o_date->last_day());
            $o_date->week_day(7);
            $o_date->set($o_date->to_format('Y-m-d'));
            $o_date->add('d', 1);
            $n_last_date = $o_date->get();

            // カレンダー開始週の日曜日の日付を取得
            $o_date->set($aa_conditions['current_ym']);
            $o_date->set($o_date->to_format('Y-m') . '-01');
            $o_date->week_day(1);

            // カレンダー配列に整形
            $n_val_cnt = 0;
            while ($n_last_date != $o_date->get()) {
                $a_room_count = $o_room_count2->where(
                    [
                        'hotel_cd' => $aa_conditions['hotel_cd'],
                        'room_id'  => $aa_conditions['room_id'],
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? [
                    'hotel_cd' => null,
                    'room_id'  => null,
                    'date_ymd' => null,
                    'rooms' => null,
                    'reserve_rooms' => null,
                    'entry_cd' => null,
                    'entry_ts' => null,
                    'modify_cd' => null,
                    'modify_ts' => null,
                ];

                // 在庫数の入力があるときのみ
                if (!empty($aa_conditions['calender'][$o_date->get()])) {
                    // レコードが存在しないなら登録
                    // 存在すれば更新を行う
                    if (empty($a_room_count)) {
                        $o_validations = [];
                        $o_validations['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $o_validations['room_id'] = $aa_conditions['room_id'];
                        $o_validations['date_ymd'] = date("Ymd", $o_date->get());
                        $o_validations['rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $o_validations['accept_status'] = 1;

                        // バリデート実行
                        $errorList = [];
                        $errorList = $o_room_count2->validation($o_validations);
                        if (count($errorList) > 0) {
                            $this->addErrorMessageArray($errorList);
                            return false;
                        }
                    } else {
                        $o_validations = [];
                        $o_validations['rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $errorList = [];
                        $errorList = $o_room_count2->validation($o_validations);

                        if (count($errorList) > 0) {
                            $this->addErrorMessageArray($errorList);
                            return false;
                        } else {
                            // 独自のバリデーション
                            // 在庫数 ＜ 予約部屋数
                            if (intval($aa_conditions['calender'][$o_date->get()]) < $a_room_count['reserve_rooms']) {
                                $this->addErrorMessage('現在の予約数以下には設定できません。');
                                return false;
                            }
                        }
                    }
                }
                // 日付ごとにデータを格納
                $a_calender['week' . $n_week_idx]['days'][]          = (int)$o_date->to_format('d');
                $a_calender['week' . $n_week_idx]['months'][]        = (int)$o_date->to_format('m');
                $a_calender['week' . $n_week_idx]['date_ymd'][]      = $o_date->get();
                $a_calender['week' . $n_week_idx]['rooms'][]         = $a_room_count['rooms'];
                $a_calender['week' . $n_week_idx]['edit_rooms'][]    = $aa_conditions['calender'][$o_date->get()] ?? $a_room_count['reserve_rooms'];
                $a_calender['week' . $n_week_idx]['reserve_rooms'][] = $a_room_count['reserve_rooms'];

                // 1週間分格納したら行を変更
                if (6 <= $n_val_cnt) {
                    $n_week_idx++;
                    $n_val_cnt = 0;
                } else {
                    $n_val_cnt++;
                }
                $o_date->add('d', 1);
            }
            return $a_calender;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 提供部屋数更新
     *
     * ※update() ここから
     */
    private function updateMethod($a_form_params)
    {
        try {
            // 初期化
            $b_is_success = true;
            $target_cd = $a_form_params['target_cd'];
            $a_form_params['accept_status'] = $a_form_params['accept_status'] ?? -1;

            // UIの種類によって処理を変更
            switch ($a_form_params['ui_type']) {
                case 'room':
                case 'accept':
                    $b_is_success = $this->updateUiTypeRoom($a_form_params);
                    break;

                case 'date':
                    $b_is_success = $this->updateUiTypeDate($a_form_params);
                    break;

                case 'calender':
                    $b_is_success = $b_is_success = $this->updateUiTypeCalender($a_form_params);
                    break;
            }

            // 「新部屋プランメンテナンス」メニューの表示・非表示判定
            $a_system_versions = $this->setDispRoomPlanList($target_cd);

            // アサインの登録
            $this->addViewData("target_cd", $target_cd);
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("partner_group_id", $this->_partner_group_id);
            $this->addViewData("system_versions", $a_system_versions);

            return $b_is_success;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 部屋指定 - 在庫更新
     */
    private function updateUiTypeRoom($a_form_params)
    {

        try {
            $o_date        = new DateUtil();
            $o_room2       = new Room2();
            $o_room_count = new RoomCount();
            $o_room_count2  = new RoomCount2();
            $actionCd = $this->getActionCd();

            $o_date->set($o_date->to_format('Y-m-d'));
            $n_now_date = $o_date->get();

            $o_date->set($a_form_params['date_ymd_from_year'] . '-' . sprintf('%02d', $a_form_params['date_ymd_from_month']) . '-' . sprintf('%02d', $a_form_params['date_ymd_from_day']));
            $a_form_params['from_date'] = $o_date->to_format('Y-m-d');
            $o_date->set($a_form_params['date_ymd_to_year'] . '-' . sprintf('%02d', $a_form_params['date_ymd_to_month']) . '-' . sprintf('%02d', $a_form_params['date_ymd_to_day']));
            $a_form_params['to_date'] = $o_date->to_format('Y-m-d');

            // 対象部屋の在庫状況を取得
            $a_room2 = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id']])->first();
            $a_room2['room_count'] = $o_room_count2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id'], 'date_ymd' => date('Ymd', $a_form_params['date_ymd'])])->first() ?? [];

            // 在庫数設定期間最終日取得
            $o_date->set($a_form_params['to_date']);
            $o_date->add('d', 1);
            $n_last_date = $o_date->get();

            // 在庫数設定期間開始日設定
            $o_date->set($a_form_params['from_date']);

            // 過去日付は編集できないエラーメッセージ
            if ($o_date->get() < $n_now_date) {
                // エラーメッセージ
                $this->addErrorMessage('設定期間開始日は' . date('Y-m-d', $n_now_date) . '以降で設定してください。');
                return false;
            }

            if ($n_last_date < $o_date->get()) {
                // エラーメッセージ
                $this->addErrorMessage('設定期間開始日と設定期間終了日が逆転しています。');
                return false;
            }

            $o_models_room2 = new Room2();

            // バリデーター対象を設定
            $o_validations_room_count2 = [];
            $o_validations_room_count2['hotel_cd'] = $a_form_params['target_cd'];
            $o_validations_room_count2['room_id'] = $a_form_params['room_id'];
            $o_validations_room_count2['date_ymd'] = $o_date->get();

            $o_validations_room_count = [];
            $o_validations_room_count['hotel_cd'] = $a_form_params['target_cd'];
            $o_validations_room_count['room_cd'] = $a_form_params['room_id'];
            $o_validations_room_count['date_ymd'] = $o_date->get();
            if ($a_form_params['ui_type'] === 'room') {
                $o_validations_room_count2['rooms'] = $a_form_params['rooms'];
                $o_validations_room_count['rooms'] = $a_form_params['rooms'];
            }

            $b_is_room_akf = $this->isRoomAkf($a_form_params['target_cd'], $a_form_params['room_id']);
            while ($n_last_date != $o_date->get()) {
                // 在庫数がnullのもの以外に処理をおこなう
                $a_room_count = $o_room_count2->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'room_id' => $a_form_params['room_id'],
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? null;

                if (empty($a_room_count)) {
                    if ($a_form_params['ui_type'] === 'accept') {
                        continue;
                    }

                    // バリデート実行
                    $errorList_room_count2 = [];
                    $errorList = $o_room_count2->validation($o_validations_room_count2);
                    $errorList_room_count = [];
                    $errorList = $o_room_count->validation($o_validations_room_count);
                    if (count($errorList_room_count2) > 0 || count($errorList_room_count) > 0) {
                        $errorListArray = array_merge($errorList_room_count2, $errorList_room_count);
                        $errorList = array_unique($errorListArray);
                        $this->addErrorMessageArray($errorList);
                        return false;
                    }

                    // insert
                    $a_attributes = [];
                    $a_attributes['hotel_cd'] = $a_form_params['target_cd'];
                    $a_attributes['room_id'] = $a_form_params['room_id'];
                    $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                    $a_attributes['edit_rooms'] = $a_form_params['rooms'];
                    $a_attributes['reserve_rooms'] = 0;
                    $a_attributes['accept_status'] = 1;
                    $a_attributes['entry_cd'] = $actionCd;
                    $a_attributes['entry_ts'] = now();
                    $a_attributes['modify_cd'] = $actionCd;
                    $a_attributes['modify_ts'] = now();

                    // 連携在庫ではないとき 画面のaccept_statusで登録する
                    if (!$b_is_room_akf) {
                        if (0 <= $a_form_params['accept_status']) {
                            $a_attributes['accept_status'] = $a_form_params['accept_status'];
                            $a_attributes['modify_cd'] = $actionCd;
                            $a_attributes['modify_ts'] = now();
                        }
                    }

                    if (!$o_room_count2->dataInsert($a_attributes)) {
                        return false;
                    }
                } else {
                    if ($a_form_params['ui_type'] === 'room') {
                        // 在庫数より予約数の方が多い場合は予約数を設定する
                        if ($a_form_params['rooms'] < $a_room_count['reserve_rooms']) {
                            $n_reserve_rooms = $a_room_count['reserve_rooms'];
                        } else {
                            $n_reserve_rooms = $a_form_params['rooms'];
                        }
                        // update
                        $a_attributes = [];
                        $a_attributes['hotel_cd'] = $a_form_params['target_cd'];
                        $a_attributes['room_id'] = $a_form_params['room_id'];
                        $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                        $a_attributes['rooms'] = $n_reserve_rooms;
                        $a_attributes['modify_cd'] = $actionCd;
                        $a_attributes['modify_ts'] = now();
                    }

                    // 連携在庫ではないとき
                    if (!$b_is_room_akf) {
                        if (0 <= $a_form_params['accept_status']) {
                            $a_attributes['hotel_cd'] = $a_form_params['target_cd'];
                            $a_attributes['room_id'] = $a_form_params['room_id'];
                            $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                            $a_attributes['accept_status'] = $a_form_params['accept_status'];
                            $a_attributes['modify_cd'] = $actionCd;
                            $a_attributes['modify_ts'] = now();
                            $a_attributes['ui_type'] = $a_form_params['ui_type'];
                        }
                    }
                    // バリデート実行
                    $errorList_room_count2 = [];
                    $errorList = $o_room_count2->validation($o_validations_room_count2);
                    $errorList_room_count = [];
                    $errorList = $o_room_count->validation($o_validations_room_count);
                    if (count($errorList_room_count2) > 0 || count($errorList_room_count) > 0) {
                        $errorListArray = array_merge($errorList_room_count2, $errorList_room_count);
                        $errorList = array_unique($errorListArray);
                        $this->addErrorMessageArray($errorList);
                        return false;
                    }

                    if (!$o_room_count2->dataUpdate($a_attributes)) {
                        return false;
                    }
                }
                $o_date->add('d', 1);
            }

            // 対象部屋の在庫状況を再取得
            $a_room2 = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id']])->first();
            $a_room2['room_count'] = $o_room_count2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $a_form_params['room_id'], 'date_ymd' => date('Ymd', $a_form_params['date_ymd'])])->first() ?? [];

            // アサインの登録
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("room", $a_room2);
            $this->addViewData("is_room_akf", $b_is_room_akf);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 日付指定 - 在庫更新
     */
    private function updateUiTypeDate($a_form_params)
    {
        try {
            // 初期化
            $o_date        = new DateUtil($a_form_params['date_ymd']);
            $o_date->set($o_date->to_format('Y-m-d'));
            $o_room2       = new Room2();
            $o_room_count = new RoomCount();
            $o_room_count2  = new RoomCount2();
            $a_disp_date = ['target_date' => $o_date->get(), 'week_day' => $o_date->to_week('j')];
            $actionCd = $this->getActionCd();

            foreach ($a_form_params['room_id'] as $room_id) {
                $a_room2[$room_id] = $o_room2->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'room_id' => $room_id
                    ]
                )->first();
                $a_room2[$room_id]['room_count'] = $o_room_count2->where(
                    [
                        'hotel_cd' => $a_form_params['target_cd'],
                        'room_id' => $room_id,
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? [
                    'hotel_cd' => null,
                    'room_id'  => null,
                    'date_ymd' => null,
                    'rooms' => null,
                    'reserve_rooms' => null,
                    'entry_cd' => null,
                    'entry_ts' => null,
                    'modify_cd' => null,
                    'modify_ts' => null,
                ];
            }
            foreach ($a_form_params['room_id'] as $room_id) {
                // 部屋在庫タイプ取得
                $b_is_room_akf = $this->isRoomAkf($a_form_params['target_cd'], $room_id);
                if (!is_null($a_form_params['rooms'][$room_id])) {
                    $a_room_count = $o_room_count2->where(
                        [
                            'hotel_cd' => $a_form_params['target_cd'],
                            'room_id'  => $room_id,
                            'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                        ]
                    )->first() ?? null;
                    // バリデーター対象を設定
                    $o_validations_room_count2 = [];
                    $o_validations_room_count2['hotel_cd'] = $a_form_params['target_cd'];
                    $o_validations_room_count2['room_id'] = $room_id;
                    $o_validations_room_count2['date_ymd'] = $o_date->get();
                    $o_validations_room_count2['rooms'] = $a_form_params['rooms'][$room_id];
                    $o_validations_room_count2['reserve_rooms'] = $a_room_count['reserve_rooms'] ?? null;
                    $o_validations_room_count2['accept_status'] = $a_form_params['accept_status'];

                    $o_validations_room_count = [];
                    $o_validations_room_count['hotel_cd'] = $a_form_params['target_cd'];
                    $o_validations_room_count['room_cd'] = $room_id;
                    $o_validations_room_count['date_ymd'] = $o_date->get();
                    $o_validations_room_count['rooms'] = $a_form_params['rooms'][$room_id];
                    $o_validations_room_count['reserve_rooms'] = $a_room_count['reserve_rooms'] ?? null;
                    $o_validations_room_count['accept_status'] = $a_form_params['accept_status'];

                    // バリデート実行
                    $errorList_room_count2 = [];
                    $errorList = $o_room_count2->validation($o_validations_room_count2);
                    $errorList_room_count = [];
                    $errorList = $o_room_count->validation($o_validations_room_count);
                    if (count($errorList_room_count2) > 0 || count($errorList_room_count) > 0) {
                        $errorListArray = array_merge($errorList_room_count2, $errorList_room_count);
                        $errorList = array_unique($errorListArray);
                        $this->addErrorMessageArray($errorList);
                        return false;
                    }


                    if (empty($a_room_count)) {
                        // insert
                        $a_attributes = [];
                        $a_attributes['hotel_cd'] = $a_form_params['target_cd'];
                        $a_attributes['room_id'] = $room_id;
                        $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                        $a_attributes['edit_rooms'] = $a_form_params['rooms'][$room_id];
                        $a_attributes['reserve_rooms'] = 0;
                        $a_attributes['accept_status'] = 1;
                        $a_attributes['entry_cd'] = $actionCd;
                        $a_attributes['entry_ts'] = now();
                        $a_attributes['modify_cd'] = $actionCd;
                        $a_attributes['modify_ts'] = now();


                        // 連携在庫ではないとき
                        if (!$b_is_room_akf) {
                            if (0 <= $a_form_params['accept_status']) {
                                $a_attributes['accept_status'] = $a_form_params['accept_status'];
                                $a_attributes['modify_cd'] = $actionCd;
                                $a_attributes['modify_ts'] = now();
                            }
                        }

                        if (!$o_room_count2->dataInsert($a_attributes)) {
                            return false;
                        }
                    } else {
                        $o_room_count2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id'  => $room_id, 'date_ymd' => date("Y/m/d G:i:s", $o_date->get())])
                            ->update([
                                'rooms' => $a_form_params['rooms'][$room_id],
                                'modify_cd' => $actionCd,
                                'modify_ts' => now(),
                            ]);

                        $a_attributes = [];
                        $a_attributes['hotel_cd'] = $a_form_params['target_cd'];
                        $a_attributes['room_id'] = $room_id;
                        $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                        $a_attributes['rooms'] = $a_form_params['rooms'][$room_id];
                        $a_attributes['modify_cd'] = $actionCd;
                        $a_attributes['modify_ts'] = now();
                        $a_attributes['ui_type'] = $a_form_params['ui_type'];

                        // 連携在庫ではないとき
                        if (!$b_is_room_akf) {
                            if (0 <= $a_form_params['accept_status']) {
                                $a_attributes['accept_status'] = $a_form_params['accept_status'];
                                $a_attributes['modify_cd'] = $actionCd;
                                $a_attributes['modify_ts'] = now();
                                $a_attributes['ui_type'] = $a_form_params['ui_type'];
                            }
                        }

                        // バリデート実行
                        $errorList_room_count2 = [];
                        $errorList = $o_room_count2->validation($o_validations_room_count2);
                        $errorList_room_count = [];
                        $errorList = $o_room_count->validation($o_validations_room_count);
                        if (count($errorList_room_count2) > 0 || count($errorList_room_count) > 0) {
                            $errorListArray = array_merge($errorList_room_count2, $errorList_room_count);
                            $errorList = array_unique($errorListArray);
                            $this->addErrorMessageArray($errorList);
                            return false;
                        }

                        if (!$o_room_count2->dataUpdate($a_attributes)) {
                            return false;
                        }
                    }
                }
            }

            // 登録した内容の再取得
            foreach ($a_form_params['room_id'] as $room_id) {
                $a_room2[$room_id] = $o_room2->where(['hotel_cd' => $a_form_params['target_cd'], 'room_id' => $room_id])->first();
                $a_room2[$room_id]['is_room_akf'] = $this->isRoomAkf($a_form_params['target_cd'], $room_id);
                $a_room2[$room_id]['room_count'] =
                    $o_room_count2->where(
                        [
                            'hotel_cd' => $a_form_params['target_cd'],
                            'room_id'  => $room_id,
                            'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                        ]
                    )->first() ?? [
                        'hotel_cd' => null,
                        'room_id'  => null,
                        'date_ymd' => null,
                        'rooms' => null,
                        'reserve_rooms' => null,
                        'entry_cd' => null,
                        'entry_ts' => null,
                        'modify_cd' => null,
                        'modify_ts' => null,
                    ];
            }

            // アサインの登録
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("rooms", $a_room2);
            $this->addViewData("disp_date", $a_disp_date);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * カレンダー - 在庫更新
     */
    private function updateUiTypeCalender($a_form_params)
    {

        try {
            $o_now_date     = new DateUtil();
            $o_room2        = new Room2();

            // 部屋情報取得
            $a_room2 = $o_room2->where(
                [
                    'hotel_cd' => $a_form_params['target_cd'],
                    'room_id'  => $a_form_params['room_id']
                ]
            )->first();

            if (empty($a_form_params['calender_current_year']) && empty($a_form_params['calender_current_month'])) {
                $o_now_date->set($a_form_params['date_ym']);
                $a_form_params['calender_current_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['calender_current_month'] = (int)$o_now_date->to_format('m');
            } else {
                $s_current_date = sprintf('%04d', $a_form_params['calender_current_year']) . '-' . sprintf('%02d', $a_form_params['calender_current_month']) . '-01';
                $o_now_date->set($s_current_date);
                $o_now_date->set($o_now_date->to_format('Y-m-d'));
                $a_form_params['date_ym'] = $o_now_date->get();
                $a_form_params['calender_current_year']  = (int)$o_now_date->to_format('Y');
                $a_form_params['calender_current_month'] = (int)$o_now_date->to_format('m');
            }

            $a_calender_values = [];
            foreach ($a_form_params as $key => $value) {
                if (preg_match('/rooms_[0-9]{1}?/', $key)) {
                    $a_tmp_key = explode('_', $key); // [0]カラム名 [1]タイムスタンプ
                    $a_calender_values[$a_tmp_key[1]] = $value;
                }
            }
            $a_calender = $this->updateCalender(
                [
                    'current_ym' => $a_form_params['date_ym'],
                    'hotel_cd'   => $a_form_params['target_cd'],
                    'room_id'    => $a_form_params['room_id'],
                    'calender'   => $a_calender_values
                ]
            );
            if (!$a_calender) {
                return false;
            }

            // アサインの登録
            $this->addViewData("form_params", $a_form_params);
            $this->addViewData("room", $a_room2);
            $this->addViewData("calender", $a_calender);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * カレンダーデータ更新
     */
    private function updateCalender($aa_conditions)
    {
        try {
            // 初期化
            $o_date     = new DateUtil($aa_conditions['current_ym']);
            $o_date->set($o_date->to_format('Y-m') . '-01');
            $a_calender     = [];
            $n_week_idx     = 1;
            $o_room_count2   = new RoomCount2();
            $o_room_count = new RoomCount();
            $actionCd = $this->getActionCd();

            // カレンダー最終週の土曜日の翌日日付を取得
            $o_date->set($o_date->last_day());
            $o_date->week_day(7);
            $o_date->set($o_date->to_format('Y-m-d'));
            $o_date->add('d', 1);
            $n_last_date = $o_date->get();

            // カレンダー開始週の日曜日の日付を取得
            $o_date->set($aa_conditions['current_ym']);
            $o_date->set($o_date->to_format('Y-m') . '-01');
            $o_date->week_day(1);

            // カレンダー配列に整形
            $n_val_cnt = 0;

            while ($n_last_date != $o_date->get()) {
                $a_room_count = $o_room_count2->where(
                    [
                        'hotel_cd' => $aa_conditions['hotel_cd'],
                        'room_id'  => $aa_conditions['room_id'],
                        'date_ymd' => date("Y/m/d G:i:s", $o_date->get())
                    ]
                )->first() ?? null;

                // nullのものは更新しない＆当月分の日だけ更新（カレンダーに表示される前月末日などは更新対象外）
                if (array_key_exists($o_date->get(), $aa_conditions['calender'])) {
                    // レコードが存在しないなら登録
                    // 存在すれば更新を行う
                    if (empty($a_room_count)) {
                        $o_validations_room_count2 = [];
                        $o_validations_room_count2['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $o_validations_room_count2['room_id'] = $aa_conditions['room_id'];
                        $o_validations_room_count2['date_ymd'] = date("Ymd", $o_date->get());
                        $o_validations_room_count2['rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $o_validations_room_count2['reserve_rooms'] = 0;
                        $o_validations_room_count2['accept_status'] = 1;

                        $o_validations_room_count = [];
                        $o_validations_room_count['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $o_validations_room_count['room_cd'] = $aa_conditions['room_id'];
                        $o_validations_room_count['date_ymd'] = date("Ymd", $o_date->get());
                        $o_validations_room_count['rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $o_validations_room_count['reserve_rooms'] = 0;
                        $o_validations_room_count['accept_status'] = 1;

                        // バリデート実行
                        $errorList_room_count2 = [];
                        $errorList = $o_room_count2->validation($o_validations_room_count2);
                        $errorList_room_count = [];
                        $errorList = $o_room_count->validation($o_validations_room_count);
                        if (count($errorList_room_count2) > 0 || count($errorList_room_count) > 0) {
                            $errorListArray = array_merge($errorList_room_count2, $errorList_room_count);
                            $errorList = array_unique($errorListArray);
                            $this->addErrorMessageArray($errorList);
                            return false;
                        }
                        $a_attributes = [];
                        $a_attributes['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $a_attributes['room_id'] = $aa_conditions['room_id'];
                        $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                        $a_attributes['rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $a_attributes['reserve_rooms'] = 0;
                        $a_attributes['accept_status'] = 1;
                        $a_attributes['edit_rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $a_attributes['entry_cd'] = $actionCd;
                        $a_attributes['entry_ts'] = now();
                        $a_attributes['modify_cd'] = $actionCd;
                        $a_attributes['modify_ts'] = now();

                        // insert
                        if (!$o_room_count2->dataInsert($a_attributes)) {
                            return false;
                        }
                    } else {
                        $o_validations_room_count2 = [];
                        $o_validations_room_count2['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $o_validations_room_count2['room_id'] = $aa_conditions['room_id'];
                        $o_validations_room_count2['date_ymd'] = date("Ymd", $o_date->get());
                        $o_validations_room_count2['rooms'] = $aa_conditions['calender'][$o_date->get()];

                        $o_validations_room_count = [];
                        $o_validations_room_count['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $o_validations_room_count['room_cd'] = $aa_conditions['room_id'];
                        $o_validations_room_count['date_ymd'] = date("Ymd", $o_date->get());
                        $o_validations_room_count['rooms'] = $aa_conditions['calender'][$o_date->get()];

                        // バリデート実行
                        $errorList_room_count2 = [];
                        $errorList = $o_room_count2->validation($o_validations_room_count2);
                        $errorList_room_count = [];
                        $errorList = $o_room_count->validation($o_validations_room_count);
                        if (count($errorList_room_count2) > 0 || count($errorList_room_count) > 0) {
                            $errorListArray = array_merge($errorList_room_count2, $errorList_room_count);
                            $errorList = array_unique($errorListArray);
                            $this->addErrorMessageArray($errorList);
                            return false;
                        }

                        // 更新
                        $a_attributes = [];
                        $a_attributes['hotel_cd'] = $aa_conditions['hotel_cd'];
                        $a_attributes['room_id'] = $aa_conditions['room_id'];
                        $a_attributes['date_ymd'] = date("Ymd", $o_date->get());
                        $a_attributes['rooms'] = $aa_conditions['calender'][$o_date->get()];
                        $a_attributes['modify_cd'] = $actionCd;
                        $a_attributes['modify_ts'] = now();

                        if (!$o_room_count2->dataUpdate($a_attributes)) {
                            return false;
                        }
                    }
                }
                // 日付ごとにデータを格納
                $a_calender['week' . $n_week_idx]['days'][]          = (int)$o_date->to_format('d');
                $a_calender['week' . $n_week_idx]['months'][]        = (int)$o_date->to_format('m');
                $a_calender['week' . $n_week_idx]['date_ymd'][]      = $o_date->get();
                $a_calender['week' . $n_week_idx]['rooms'][]         = $a_room_count['rooms'] ?? null;
                if ($a_room_count) {
                    $a_calender['week' . $n_week_idx]['edit_rooms'][] = $aa_conditions['calender'][$o_date->get()] ?? $a_room_count['rooms'];
                } else {
                    $a_calender['week' . $n_week_idx]['edit_rooms'][] = $aa_conditions['calender'][$o_date->get()] ?? $a_attributes['edit_rooms'] ?? null;
                }
                $a_calender['week' . $n_week_idx]['reserve_rooms'][] = $a_room_count['reserve_rooms'] ?? null;

                // 1週間分格納したら行を変更
                if (6 <= $n_val_cnt) {
                    $n_week_idx++;
                    $n_val_cnt = 0;
                } else {
                    $n_val_cnt++;
                }

                $o_date->add('d', 1);
            }
            return $a_calender;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * パートナーグループIDを取得
     */
    public function getPartnerGroupId()
    {
        try {
            // キーが同じ場合キャッシュを利用
            if ($this->_s_partner_cd   == $this->_a_get_partner_group_id['partner_cd']) {
                return $this->_a_get_partner_group_id['values'];
            }

            $s_sql =
                <<<SQL
					select	partner_group_id
					from	partner_group_join
					where	partner_cd      = :partner_cd
SQL;

            // データの取得
            $this->_a_get_partner_group_id['partner_cd'] = $this->_s_partner_cd;
            $a_partner_group_join = DB::select($s_sql, ['partner_cd' => $this->_s_partner_cd]);
            $this->_a_get_partner_group_id['values']   = $a_partner_group_join[0]['partner_group_id'];

            return $this->_a_get_partner_group_id['values'];

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
