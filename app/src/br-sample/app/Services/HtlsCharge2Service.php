<?php
namespace App\Services;
	// require_once '../lib/Controllers/Action2.php';
	use App\Models\Room3;
	// require_once '../models/Hotel/Room3.php';
	use App\Models\Plan3;
	// require_once '../models/Hotel/Room/Plan3.php';
	use App\Models\Charge3;
	// require_once '../models/Hotel/Room/Plan/Charge3.php';
	use App\Models\Calendar;
	// require_once '../models/Calendar.php';
	use App\Models\MatchPlanRoom;

	use Illuminate\Support\Facades\DB;
	// require_once '../models/MatchPlanRoom.php';
	// class HtlsCharge2Model extends lib_Controllers_Action2

	class HtlsCharge2Service
	{
		// アサイン
		protected $_assign;
		
		// リクエストパラメータ
		protected $a_request_params;
		
		// 当該コントローラでのみ使用する変数
		protected $n_count_plan_has_room;
		protected $a_plan_has_rooms_detail;
		protected $a_plan_detail;
		protected $a_operation_status_rooms;
		protected $a_range_capacity_rooms;
		protected $a_plan_partner_group_id;
		protected $a_day_of_week;
		protected $a_low_price_setting;
		protected $a_plan_accept_ymd;
		protected $a_updated_charge_ymdc;
		protected $b_exists_charge_today;
		
		// 使用するビジネスロジックモデル
		protected $o_models_plan3;
		protected $o_models_room3;
		protected $o_models_charge3;
		protected $o_models_calendar;
		protected $o_models_match_plan_room;
		
		// アクティブレコード
		protected $o_plan;
		protected $o_room_plan_child;
		protected $o_charge_remind;
		protected $o_charge;
		protected $o_charge_today;
		
		// バリデートオブジェクト
		protected $o_validations;
		
		// Oracle
		protected $o_oracle;

		//移植の際に追加
		protected $s_hotel_cd;

		// 特殊な扱いになる提携先コードの定義
		const PTN_CD_BR   = '0000000000'; // ベストリザーブ
		const PTN_CD_JRC  = '3015008801'; // JRコレクション
		const PTN_CD_RELO = '3015008796'; // リロクラブ

		protected $a_detail;
		
		//======================================================================
		// 初期化
		//======================================================================
		public function init() 
		{
			try {
				//--------------------------------------------------------------
				// 事前処理
				//--------------------------------------------------------------
				// parent::init();
				
				$this->_assign               = new stdClass();
				$this->_assign->is_migration = $this->box->user->migration_status;
				
				// リクエストパラメータを取得
				$this->a_request_params      = $this->params();
				
				$this->a_plan_detail            = array(); // 対象プランの詳細情報
				$this->n_count_plan_has_room    = 0;       // 対象プランに紐づく部屋タイプ数
				$this->a_plan_has_rooms_detail  = array(); // 対象プランに紐づく部屋情報詳細
				$this->a_operation_status_rooms = array(); // 一括操作時の部屋の操作状態
				$this->a_range_capacity_rooms   = array(); // 対象プランに紐づく部屋の定員数の幅（最小～最大）
				$this->a_plan_partner_group_id  = array(); // 対象プランの販売される提携先グループIDの一覧
				$this->a_low_price_setting      = array(); // 1人利用あたり1000円未満が設定された日付、人数を記憶
				$this->a_plan_accept_ymd        = array(); // プランの表示期間に関するデータ
				$this->a_updated_charge_ymdc    = array(); // 販売料金が変更された日付・人数を保持
				$this->b_exists_charge_today    = false;   // 対象プランに当日割料金が設定されているかどうか
				
				// 曜日インデックス定義
				$this->a_day_of_week = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'bfo', 'hol');
				
				// ビジネスロジックモデルの生成
				$this->o_models_plan3           = new Plan3();
				$this->o_models_room3           = new Models_Room3();
				$this->o_models_charge3         = new Models_Charge3();
				$this->o_models_calendar        = new Models_calendar();
				$this->o_models_match_plan_room = new Models_MatchPlanRoom();
				
				// バリデーションオブジェクト生成
				$this->o_validations     = Validations::getInstance($this->box);
				
				// Oracleオブジェクト生成
				$this->o_oracle = _Oracle::getInstance();
				
				// テーブルオブジェクト生成
				$this->o_room_plan_child = Room_Plan_Child::getInstance(); // 子供料金
				$this->o_charge_remind   = Charge_Remind::getInstance();   // 曜日別の正規料金、販売料金
				$this->o_charge          = Charge::getInstance();          // 料金
				$this->o_charge_today    = Charge_Today::getInstance();    // 当日割料金
				
				// メッセージ用のフィールド名の設定
				$this->o_charge_remind->set_logical_nm('usual_charge_sun', '正規料金(日曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_mon', '正規料金(月曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_tue', '正規料金(火曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_wed', '正規料金(水曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_thu', '正規料金(木曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_fri', '正規料金(金曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_sat', '正規料金(土曜)');
				$this->o_charge_remind->set_logical_nm('usual_charge_bfo', '正規料金(休前日)');
				$this->o_charge_remind->set_logical_nm('usual_charge_hol', '正規料金(祝日)');
				$this->o_charge_remind->set_logical_nm('sales_charge_sun', '販売料金(日曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_mon', '販売料金(月曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_tue', '販売料金(火曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_wed', '販売料金(水曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_thu', '販売料金(木曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_fri', '販売料金(金曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_sat', '販売料金(土曜)');
				$this->o_charge_remind->set_logical_nm('sales_charge_bfo', '販売料金(休前日)');
				$this->o_charge_remind->set_logical_nm('sales_charge_hol', '販売料金(祝日)');
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		//======================================================================
		// 部屋単体選択
		//======================================================================
		protected function singleMethod()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_temp_accept_s_ymd = array();
				$a_temp_accept_e_ymd = array();
				$a_room_detail       = array();
				
				//--------------------------------------------------------------
				// プラン詳細データ作成
				//--------------------------------------------------------------
				$this->_make_plan_detail();
				
				//--------------------------------------------------------------
				// 部屋詳細データ取得
				//--------------------------------------------------------------
				$this->o_models_room3->set_hotel_cd($this->a_request_params['target_cd']);
				$this->o_models_room3->set_room_id($this->a_request_params['room_id']);
				
				$a_room_detail = $this->o_models_room3->get_detail();
				$this->a_plan_has_rooms_detail = array($a_room_detail['room_id'] => $a_room_detail);
				
				// 部屋操作情報データを作成
				$this->a_operation_status_rooms = array(
					'selectable_rooms' => array(),
					'target_rooms'     => array($this->a_request_params['room_id']),
					'complete_roos'    => array()
				);
				
				//--------------------------------------------------------------
				// 料金登録対象の部屋の最小・最大定員数データを作成
				//--------------------------------------------------------------
				if ( !$this->_make_range_capacity_rooms() ) {
					$b_is_error = true;
				}
				
				//--------------------------------------------------------------
				// プランの表示期間データ作成
				//--------------------------------------------------------------
				$this->_make_plan_accept_ymd($this->a_plan_detail['accept_s_ymd'], $this->a_plan_detail['accept_e_ymd']);
				
				// 料金確認用のカレンダー表示期間を設定
				$a_temp_accept_s_ymd = $this->a_plan_accept_ymd['selected']['accept_s_ymd'];
				$a_temp_accept_e_ymd = $this->a_plan_accept_ymd['selected']['accept_e_ymd'];
				
				//--------------------------------------------------------------
				// 期間のカレンダー作成
				//--------------------------------------------------------------
				// 作成の為の情報を設定
				$this->o_models_calendar->set_from_ymd($a_temp_accept_s_ymd['year'] . '-' . $a_temp_accept_s_ymd['month'] . '-' . $a_temp_accept_s_ymd['day']);
				$this->o_models_calendar->set_to_ymd($a_temp_accept_e_ymd['year']   . '-' . $a_temp_accept_e_ymd['month'] . '-' . $a_temp_accept_e_ymd['day']);
				
				// 作成
				$this->o_models_calendar->make_calendar();
				
				//--------------------------------------------------------------
				// 期間の料金を取得
				//--------------------------------------------------------------
				// 取得の為の情報を設定
				$this->o_models_charge3->set_hotel_cd($this->a_request_params['target_cd']);
				$this->o_models_charge3->set_plan_id($this->a_request_params['plan_id']);
				$this->o_models_charge3->set_room_id($this->a_request_params['room_id']);
				$this->o_models_charge3->set_from_ymd($this->o_models_calendar->get_from_ymd_week_first());
				$this->o_models_charge3->set_to_ymd($this->o_models_calendar->get_to_ymd_week_last());
				
				// 期間内の料金作成
				$this->o_models_charge3->make_from_to_ymd_charges();
				
				// リクエストパラメータに料金を設定
				$this->_set_charges_to_request_param();
				
				//--------------------------------------------------------------
				// 子供料金情報を取得
				//--------------------------------------------------------------
				// リクエストパラメータに料金を設定
				$this->_set_child_charge_to_request_param();
				
				//--------------------------------------------------------------
				// アサイン
				//--------------------------------------------------------------
				// リクエスト
				$this->_assign->request_params = $this->a_request_params;
				
				// プランの詳細情報を取得
				$this->_assign->plan_detail = $this->a_plan_detail;
				
				// 部屋の詳細情報を取得
				$this->_assign->plan_has_rooms_detail = $this->a_plan_has_rooms_detail;
				
				// 部屋の操作状況
				$this->_assign->opration_status_rooms = $this->a_operation_status_rooms;
				
				// 料金登録対象の部屋の定員幅の配列
				$this->_assign->target_capacities = $this->a_range_capacity_rooms;
				
				// 表示期間データ
				$this->_assign->plan_accept_ymd_selecter = $this->a_plan_accept_ymd;
				
				// カレンダー表示用のデータ
				$this->_assign->calendar = $this->o_models_calendar->get_calendar();
				
				// 低価格設定の日程データ
				$this->_assign->low_price_info = $this->a_low_price_setting;
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 編集
		//======================================================================
		protected function editMethod()
		{
			try {
				// エラーフラグ
				$b_is_error = false;
				
				// 日付処理モデル
				$o_models_date = new Br_Models_Date();
				
				//--------------------------------------------------------------
				// リクエストパラメータのエラーチェック
				//--------------------------------------------------------------
				if ( !$this->_is_error_params() ) {
					return false;
				}
				
				//--------------------------------------------------------------
				// プラン詳細データ作成
				//--------------------------------------------------------------
				$this->_make_plan_detail();
				
				//--------------------------------------------------------------
				// プランに紐づく部屋詳細データ作成
				//--------------------------------------------------------------
				$this->_make_plan_has_rooms_detail();
				
				//--------------------------------------------------------------
				// 部屋の操作状態データを作成
				//--------------------------------------------------------------
				$this->_make_operation_status_rooms();
				
				//--------------------------------------------------------------
				// 料金登録対象の部屋の最小・最大定員数データを作成
				//--------------------------------------------------------------
				if ( !$this->_make_range_capacity_rooms() ) {
					$b_is_error = true;
				}
				
				//--------------------------------------------------------------
				// 表示期間のエラーチェック
				//--------------------------------------------------------------
				if ( !$this->_is_error_from_to_ymd() ) {
					$b_is_error = true;
				}
				
				// エラーが発生していたら終了
				if ( $b_is_error ) {
					return false;
				}
				
				//--------------------------------------------------------------
				// カレンダーの設定
				//--------------------------------------------------------------
				$o_models_date->set($this->a_request_params['from_year'] . '-' . $this->a_request_params['from_month'] . '-' . $this->a_request_params['from_day']);
				$this->o_models_calendar->set_from_ymd($o_models_date->to_format('Y-m-d'));
				$o_models_date->set($this->a_request_params['to_year']     . '-' . $this->a_request_params['to_month']   . '-' . $this->a_request_params['to_day']);
				$this->o_models_calendar->set_to_ymd($o_models_date->to_format('Y-m-d'));
				
				// カレンダーを作成
				$this->o_models_calendar->make_calendar();
				
				//--------------------------------------------------------------
				// 1部屋で指定された画面から遷移した時
				//--------------------------------------------------------------
				if ( $this->a_request_params['pre_action'] === 'single' and !$this->a_request_params['is_fix_params'] ) {
					// 取得の為の情報を設定
					$this->o_models_charge3->set_hotel_cd($this->a_request_params['target_cd']);
					$this->o_models_charge3->set_plan_id($this->a_request_params['plan_id']);
					$this->o_models_charge3->set_room_id($this->a_request_params['room_id']);
					$this->o_models_charge3->set_from_ymd($this->o_models_calendar->get_from_ymd_week_first());
					$this->o_models_charge3->set_to_ymd($this->o_models_calendar->get_to_ymd_week_last());
					
					// 期間内の料金作成
					$this->o_models_charge3->make_from_to_ymd_charges();
					
					// リクエストパラメータに料金情報を設定
					$this->_set_charges_to_request_param();
					
					// リクエストパラメータに子供料金情報を設定
					$this->_set_child_charge_to_request_param();
					
					// リクエストパラメータに正規料金・販売料金を設定
					$this->_set_charge_remind_to_request_param();
				}
				
				//--------------------------------------------------------------
				// 複数部屋一括設定のとき
				//--------------------------------------------------------------
				if ( $this->a_request_params['pre_action'] === 'lump' and !$this->a_request_params['is_fix_params'] ) {
					// リクエストパラメータに子供料金情報を設定
					$a_default_child_person = array(
						'child1' => 1, 
						'child2' => 1,
						'child3' => 1,
						'child4' => 0,
						'child5' => 0
					);
					
					for ($ii = 1; $ii <= 5; $ii++) {
						$this->a_request_params['child' . $ii . '_accept']         = 0;
						$this->a_request_params['child' . $ii . '_charge_include'] = null;
						$this->a_request_params['child' . $ii . '_person']         = $a_default_child_person['child' . $ii];
						$this->a_request_params['child' . $ii . '_charge_unit']    = 0;
						$this->a_request_params['child' . $ii . '_charge']         = null;
					}
					
				}
				
				$a_calendar = $this->o_models_calendar->get_calendar();
				
				// 金土日プランの場合、編集不可の日程情報を追加する
				if ( $this->a_plan_detail['plan_type'] === 'fss' ) {
					// 金土日を表す配列を生成
					$a_allow_day_of_week = array(0, 5, 6);
					
					foreach ( nvl($a_calendar, array()) as $key_week => $a_week ) {
						// 金土日以外は編集不可フラグ追加
						foreach ($a_week['values'] as $key_day => $a_day) {
							if ( !in_array($a_day['dow_num'], $a_allow_day_of_week) ) {
								$a_calendar[$key_week]['values'][$key_day]['is_not_edit'] = true;
							}
						}
					}
				}
				
				//--------------------------------------------------------------
				// アサイン
				//--------------------------------------------------------------
				// リクエスト
				$this->_assign->request_params = $this->a_request_params;
				
				// プランの詳細情報を取得
				$this->_assign->plan_detail = $this->a_plan_detail;
				
				// プランに紐づいた部屋の詳細情報を取得
				$this->_assign->plan_has_rooms_detail = $this->a_plan_has_rooms_detail;
				
				// 部屋の操作状況
				$this->_assign->opration_status_rooms = $this->a_operation_status_rooms;
				
				// 料金登録対象の部屋の定員幅の配列
				$this->_assign->target_capacities = $this->a_range_capacity_rooms;
				
				// カレンダー表示用のデータ
				$this->_assign->calendar = $a_calendar;
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 編集
		//======================================================================
		protected function updateMethod()
		{
			try {
				// エラーフラグ
				$b_is_error = false;
				
				// 日付処理モデル
				$o_models_date = new Br_Models_Date();
				
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				if ( !$this->_is_error_params() ) {
					return false;
				}
				
				//--------------------------------------------------------------
				// プラン詳細データ作成
				//--------------------------------------------------------------
				$this->_make_plan_detail();
				
				// プランの販売される提携先グループIDを取得
				$this->a_plan_partner_group_id = $this->o_models_plan3->get_plan_partner_groups();
				
				// 当日割の設定されたプランかどうかを判定
				$this->b_exists_charge_today = $this->o_models_plan3->exists_charge_today();
				
				//--------------------------------------------------------------
				// プランに紐づく部屋詳細データ作成
				//--------------------------------------------------------------
				$this->_make_plan_has_rooms_detail();
				
				//--------------------------------------------------------------
				// 部屋の操作状態データを作成
				//--------------------------------------------------------------
				$this->_make_operation_status_rooms();
				
				//--------------------------------------------------------------
				// 料金登録対象の部屋の最小・最大定員数データを作成
				//--------------------------------------------------------------
				if ( !$this->_make_range_capacity_rooms() ) {
					$b_is_error = true;
				}
				
				//--------------------------------------------------------------
				// 表示期間のエラーチェック
				//--------------------------------------------------------------
				if ( !$this->_is_error_from_to_ymd() ) {
					$b_is_error = true;
				}
				
				// エラーが発生していたら終了
				if ( $b_is_error ) {
					return false;
				}
				
				//--------------------------------------------------------------
				// 入力された料金パラメータの整形
				//--------------------------------------------------------------
				$this->_filter_charge();
				
				//--------------------------------------------------------------
				// 子供料金の処理
				//--------------------------------------------------------------
				// プランメンテとプランの更新からの遷移時は表示しない
				if ( $this->a_request_params['pre_action'] !== 'list' and $this->a_request_params['pre_action'] !== 'update' ) {
					if ( !$this->_update_child_charge() ) {
						return false;
					}
				}
				
				//--------------------------------------------------------------
				// 曜日別の「正規料金」、「販売料金」の処理
				//--------------------------------------------------------------
				// プランメンテとプランの更新からの遷移時は表示しない
				if ( $this->a_request_params['pre_action'] !== 'list' and $this->a_request_params['pre_action'] !== 'update' ) {
					if ( !$this->_update_charge_remind() ) {
						return false;
					}
				}
				
				//--------------------------------------------------------------
				// 料金の登録
				//--------------------------------------------------------------
				if ( !$this->_update_charge() ) {
					return false;
				}
				
				//--------------------------------------------------------------
				// 当日割の削除（※料金変更が発生した場合）
				//--------------------------------------------------------------
				if ( !$this->_update_charge_today() ) {
					$b_is_error = true;
				}
				
				//--------------------------------------------------------------
				// 料金カレンダーの設定
				//--------------------------------------------------------------
				$o_models_date->set($this->a_request_params['from_year'] . '-' . $this->a_request_params['from_month'] . '-' . $this->a_request_params['from_day']);
				$this->o_models_calendar->set_from_ymd($o_models_date->to_format('Y-m-d'));
				$o_models_date->set($this->a_request_params['to_year']     . '-' . $this->a_request_params['to_month']   . '-' . $this->a_request_params['to_day']);
				$this->o_models_calendar->set_to_ymd($o_models_date->to_format('Y-m-d'));
				
				// カレンダーを作成
				$this->o_models_calendar->make_calendar();
				
				//--------------------------------------------------------------
				// 1部屋で指定された画面から遷移した時
				//--------------------------------------------------------------
				if ( $this->a_request_params['pre_action'] === 'single') {
					// 期間内の料金取得の為の情報を設定
					$this->o_models_charge3->set_hotel_cd($this->a_request_params['target_cd']);
					$this->o_models_charge3->set_plan_id($this->a_request_params['plan_id']);
					$this->o_models_charge3->set_room_id($this->a_request_params['room_id']);
					$this->o_models_charge3->set_from_ymd($this->o_models_calendar->get_from_ymd_week_first());
					$this->o_models_charge3->set_to_ymd($this->o_models_calendar->get_to_ymd_week_last());
					
					// 期間内の表示用料金データ作成
					$this->o_models_charge3->make_from_to_ymd_charges();
					
					// リクエストパラメータに料金情報を設定
					$this->_set_charges_to_request_param();
					
					// リクエストパラメータに子供料金情報を設定
					$this->_set_child_charge_to_request_param();
					
					// リクエストパラメータに正規料金・販売料金を設定
					$this->_set_charge_remind_to_request_param();
				}
				
				$a_calendar = $this->o_models_calendar->get_calendar();
				
				// 金土日プランの場合、編集不可の日程情報を追加する
				if ( $this->a_plan_detail['plan_type'] === 'fss' ) {
					// 金土日を表す配列を生成
					$a_allow_day_of_week = array(0, 5, 6);
					
					foreach ( nvl($a_calendar, array()) as $key_week => $a_week ) {
						// 金土日以外は編集不可フラグ追加
						foreach ($a_week['values'] as $key_day => $a_day) {
							if ( !in_array($a_day['dow_num'], $a_allow_day_of_week) ) {
								$a_calendar[$key_week]['values'][$key_day]['is_not_edit'] = true;
							}
						}
					}
				}
				
				//--------------------------------------------------------------
				// アサイン
				//--------------------------------------------------------------
				// リクエスト
				$this->_assign->request_params = $this->a_request_params;
				
				// プランの詳細情報を取得
				$this->_assign->plan_detail = $this->a_plan_detail;
				
				// プランに紐づいた部屋の詳細情報を取得
				$this->_assign->plan_has_rooms_detail = $this->a_plan_has_rooms_detail;
				
				// 部屋の操作状況
				$this->_assign->opration_status_rooms = $this->a_operation_status_rooms;
				
				// 料金登録対象の部屋の定員幅の配列
				$this->_assign->target_capacities = $this->a_range_capacity_rooms;
				
				// カレンダー表示用のデータ
				$this->_assign->calendar = $a_calendar;
				
				// 1000円未満料金の日程
				$this->_assign->low_price_info = $this->a_low_price_setting;
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// リクエストパラメータのエラーチェック
		//======================================================================
		private function _is_error_params() {
			try {
				// 初期化
				$b_is_error    = false;
				$o_models_date = new Br_Models_Date();
				
				// 部屋が選択されているかどうかのエラーチェック
				if ( is_empty($this->a_request_params['target_rooms']) ) {
					$this->box->item->error->add('料金を登録する部屋が選択されていません。');
					$b_is_error = true;
				}
				
				// 表示期間のエラーチェック
				$n_from_ymd = strtotime($this->a_request_params['from_year'] . $this->a_request_params['from_month'] . $this->a_request_params['from_day']);
				$n_to_ymd   = strtotime($this->a_request_params['to_year']   . $this->a_request_params['to_month']   . $this->a_request_params['to_day']);
				
				if ( $n_from_ymd > $n_to_ymd ) {
					$this->box->item->error->add('料金を編集する期間の開始日と終了日が逆転しています。');
					$b_is_error = true;
				}
				
				if ( $b_is_error ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 表示期間開始日のエラーチェック
		//======================================================================
		private function _is_error_from_to_ymd()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$s_from_ymd      = $this->a_request_params['from_year'] . '-' . $this->a_request_params['from_month'] . '-' . $this->a_request_params['from_day'];
				$s_to_ymd        = $this->a_request_params['to_year']   . '-' . $this->a_request_params['to_month']   . '-' . $this->a_request_params['to_day'];
				$s_accept_e_hour = '30';
				$s_accept_e_min  = '00';
				$o_models_date   = new Br_Models_Date($s_from_ymd);
				$b_is_error      = false;
				
				//--------------------------------------------------------------
				// 表示期間開始日のエラーチェック
				//--------------------------------------------------------------
				// プラン情報が取得できている場合は販売終了時間を考慮する
				if ( !is_empty($this->a_plan_detail['accept_e_hour']) ) {
					list($s_accept_e_hour, $s_accept_e_min) = explode(':', $this->a_plan_detail['accept_e_hour']);
				}
				
				$o_models_date->add('H', $s_accept_e_hour);
				$o_models_date->add('i', $s_accept_e_min);
				
				// プランの販売終了時間を考慮した表示期間開始日が現在日時より過去の場合、エラー
				if ( strtotime(date('Y-m-d')) > $o_models_date->get() ) {
					$this->box->item->error->add('過去の日程への料金設定は行えません。');
					$b_is_error = true;
				}
				
				// プランの料金設定が可能な期間開始日より過去の場合はエラー
				if ( strtotime($this->a_plan_detail['accept_s_ymd']) > strtotime($s_from_ymd) ) {
					$s_error_msg  = substr($this->a_plan_detail['accept_s_ymd'], 0, 4) . '年';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_s_ymd'], 5, 2), 0) . '月';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_s_ymd'], 8, 2), 0) . '日';
					$s_error_msg .= '～';
					$s_error_msg .= substr($this->a_plan_detail['accept_e_ymd'], 0, 4) . '年';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_e_ymd'], 5, 2), 0) . '月';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_e_ymd'], 8, 2), 0) . '日';
					$this->box->item->error->add('料金の入力を行う期間は「' . $s_error_msg . '」より指定してください。');
					$b_is_error = true;
				}
				
				//--------------------------------------------------------------
				// 表示期間終了日のエラーチェック
				//--------------------------------------------------------------
				// プランの料金設定が可能な期間終了日より未来の場合はエラー
				if ( strtotime($this->a_plan_detail['accept_e_ymd']) < strtotime($s_to_ymd) ) {
					$s_error_msg  = substr($this->a_plan_detail['accept_s_ymd'], 0, 4) . '年';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_s_ymd'], 5, 2), 0) . '月';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_s_ymd'], 8, 2), 0) . '日';
					$s_error_msg .= '～';
					$s_error_msg .= substr($this->a_plan_detail['accept_e_ymd'], 0, 4) . '年';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_e_ymd'], 5, 2), 0) . '月';
					$s_error_msg .= ltrim(substr($this->a_plan_detail['accept_e_ymd'], 8, 2), 0) . '日';
					$this->box->item->error->add('料金の入力を行う期間は「' . $s_error_msg . '」より指定してください。');
					$b_is_error = true;
				}
				
				// いずれかのエラーチェックでヒットすればエラーとして返す
				if ( $b_is_error ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		/**
		 * 部屋の操作状況データを作成する（主に一括操作時）
		 * 
		 * @param 	$a_detail_room	部屋詳細情報
		 * 			$request リクエストパラメータ
		 * 
		 * @return	部屋の操作状況(それぞれ部屋IDが保持されている配列)
		 * 			・selectable_rooms	選択可能な部屋
		 * 			・target_rooms		設定対象としている部屋
		 * 			・complete_rooms	既に設定を終えた部屋	
		 */
		public function _make_operation_status_rooms($a_detail_room, $request)
		{
			try {
				// 初期化
				$a_selectable_rooms = array(); // 選択可能な部屋
				$a_targets_rooms    = ($request->input('target_	rooms') ??   array()); // 選択対象の部屋
				$a_complete_rooms   = ($request->input('complete_rooms') ?? array()); // 設定済みの部屋

				
				// 料金設定の対象として「選択可能」な部屋の一覧を作成する
				foreach ( $a_detail_room ?? array() as $s_room_id => $a_room_detail ) {
					// foreach ( nvl($this->a_plan_has_rooms_detail, array()) as $s_room_id => $a_room_detail ) {
					// 「設定済み」か判定
					if ( in_array($s_room_id, $a_complete_rooms) ) {
						continue;
					}
					
					// 「設定対象」か判定
					if ( in_array($s_room_id, $a_targets_rooms) ) {
						continue;
					}
					
					// 部屋が決まっているときは設定しない
					if ( $request['pre_action'] !== 'single' ) {
					// if ( $this->a_request_params['pre_action'] !== 'single' ) {
						// 「選択可能な対象」の配列に格納
						$a_selectable_rooms[] = $s_room_id;
					}
				}
				//宣言と初期化
				$a_operation_status_rooms =array(); 
				$a_operation_status_rooms['selectable_rooms'] = $a_selectable_rooms;
				$a_operation_status_rooms['target_rooms']     = $a_targets_rooms;
				$a_operation_status_rooms['complete_rooms']   = $a_complete_rooms;

				return $a_operation_status_rooms;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プランの表示期間に関するデータを作成
		//
		// @param String 表示期間開始日
		//        String 表示期間終了日
		//======================================================================
		/**
		 * プランの表示期間に関するデータを作成
		 * @param 	$as_accept_s_ymd 表示期間開始日付
		 * 		　	$as_accept_e_ymd　表示期間終了日付
		 * 		　	$request リクエストパラメータ
		 * @return 	セレクトボックスに格納するために作成した日付
		 * 
		 */

		public function _make_plan_accept_ymd($as_accept_s_ymd, $as_accept_e_ymd, $request)
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$this->a_plan_accept_ymd = array();
				$a_plan_accept_ymd = array();
				
				$n_accept_s_ymd = strtotime($as_accept_s_ymd);//表示期間開始日

				$n_accept_e_ymd = strtotime($as_accept_e_ymd);//表示期間終了日
				$n_today        = strtotime(date('Y-m-d'));//操作日時
				
				$s_start_year = '';
				$s_end_year   = date('Y', $n_accept_e_ymd);
				
				// プランの「表示期間開始日」が「操作日時」より過去の場合、「操作日時」を優先
				if ( $n_accept_s_ymd < $n_today ) {
					$s_start_year = (int)date('Y', $n_today);
					$n_accept_s_ymd = $n_today;
				} else {
					$s_start_year = (int)date('Y', $n_accept_s_ymd);
				}
				
				// セレクトボックスの選択値となる日付
				$n_selected_s_ymd = null;
				$n_selected_e_ymd = null;
				
				// 初期遷移とエラーで戻された場合で設定値を変更する：開始日
				if ( checkdate($request['from_month'], $request['from_day'], $request['from_year']) ) {
				// if ( checkdate($request->input('from_month'), $request->input('from_day'), $request->input('from_year')) ) {
					// エラー等で画面が戻された場合、変更された日付を設定する
					//  $request->input('from_day')
					$n_selected_s_ymd = strtotime($request['from_year'] . $request['from_month'] . $request['from_day']);
				} else {
					// プランの「表示期間開始日」 or 「操作日」を設定
					$n_selected_s_ymd = $n_accept_s_ymd;
				}
				
				// 初期遷移とエラーで戻された場合で設定値を変更する：終了日
				if ( checkdate($request['to_month'], $request['to_day'], $request['to_year']) ) {
					// エラー等で画面が戻された場合、変更された日付を設定する
					$n_selected_e_ymd = strtotime($request['to_year'] . $request['to_month'] . $request['to_day']);
				} else {
					// プランの「表示期間終了日」を設定
					$n_selected_e_ymd = $n_accept_e_ymd;
				}
				
				//--------------------------------------------------------------
				// 開始日の年～終了日の年
				//--------------------------------------------------------------
				do {
					$this->a_plan_accept_ymd['options']['year'][ (int)$s_start_year ] = $s_start_year . '年';
					$s_start_year++;
				} while ( (int)$s_start_year <= (int)$s_end_year );
				
				//--------------------------------------------------------------
				// 月
				//--------------------------------------------------------------
				for ( $ii = 1; $ii <= 12; $ii++) {
					$this->a_plan_accept_ymd['options']['month'][ sprintf('%02d', $ii) ] = $ii . '月';
				}
				
				//--------------------------------------------------------------
				// 日
				//--------------------------------------------------------------
				for ( $ii = 1; $ii <= 31; $ii++) {
					$this->a_plan_accept_ymd['options']['day'][ sprintf('%02d', $ii) ] = $ii . '日';
				}
				
				//--------------------------------------------------------------
				// 初期選択値
				//--------------------------------------------------------------
				// 開始日
				$this->a_plan_accept_ymd['selected']['accept_s_ymd']['year']  = date('Y', $n_selected_s_ymd);
				$this->a_plan_accept_ymd['selected']['accept_s_ymd']['month'] = date('m', $n_selected_s_ymd);
				$this->a_plan_accept_ymd['selected']['accept_s_ymd']['day']   = date('d', $n_selected_s_ymd);
				
				// 終了日
				$this->a_plan_accept_ymd['selected']['accept_e_ymd']['year']  = date('Y', $n_selected_e_ymd);
				$this->a_plan_accept_ymd['selected']['accept_e_ymd']['month'] = date('m', $n_selected_e_ymd);
				$this->a_plan_accept_ymd['selected']['accept_e_ymd']['day']   = date('d', $n_selected_e_ymd);
				
				//作成日を返却
				return $this->a_plan_accept_ymd;

			} catch(Exception $e) {
				throw $e;
			}
		}
		
		/**
		 * プランに紐づく部屋詳細情報の取得
		 * @param 	$request	リクエストパラメータ
		 * 
		 * @return	プラン情報に紐づいた部屋の詳細情報
		 */
		public function _make_plan_has_rooms_detail($request)
		{
			try {
				//o_models_room3の意味
				$room3 = new Room3();
				// 部屋情報を取得する為のキーを設定
				// $this->o_models_room3->set_hotel_cd($request->input('target_cd'));
				$room3->set_hotel_cd($request->input('target_cd'));
				// $a_room_details = $this->o_models_room3->get_details();
				// $a_room_details = $room3->get_details();
				$a_room_details = $room3->get_details();

				
				// プランに紐づく部屋IDを取得
				$matchPlanRoom = new MatchPlanRoom();
				$matchPlanRoom->set_hotel_cd($request->input('target_cd'));
				$matchPlanRoom->set_plan_id($request->input('plan_id'));
				$a_match_plan_rooms = $matchPlanRoom->get_match_plan_rooms();
				
				// プランに紐づく部屋の詳細情報を取得
				
				$a_plan_has_rooms_detail = array();
				
				foreach ( $a_match_plan_rooms ?? array() as $a_match_plan_room) {
					// $a_plan_has_rooms_detail[$a_match_plan_room['room_id']] = $a_room_details[ $a_match_plan_room['room_id']];
					$a_plan_has_rooms_detail[$a_match_plan_room['room_id']] = $a_room_details[ $a_match_plan_room['room_id']];
				}
				return $a_plan_has_rooms_detail;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 処理対象（複数可）の部屋の最小～最大の定員数データを作成
		//======================================================================
		private function _make_range_capacity_rooms()
		{
			try {
				// 初期化
				$a_tmp_capacities_min = array();
				$a_tmp_capacities_max = array();
				$b_is_success = true;
				
				// 料金の設定対象の部屋分ループ
				foreach ( nvl($this->a_operation_status_rooms['target_rooms'], array()) as $s_target_room_id ) {
					// 定員０は不正なデータでエラーとする
					if ( $this->a_plan_has_rooms_detail[$s_target_room_id]['capacity_min'] < 0 or $this->a_plan_has_rooms_detail[ $s_target_room_id ]['capacity_max'] < 0 ) {
						$this->box->item->error->add('「' . $this->a_plan_has_rooms_detail[ $s_target_room_id ]['room_nm'] . '」（'. $this->a_plan_has_rooms_detail[ $s_target_room_id ]['pms_cd'] . '）の定員数が入力されていません。');
						$this->box->item->error->add('部屋プランメンテナンス画面の部屋編集より修正を行ってください。');
						$b_is_success = false;
					}
					
					// 最小定員数・最大定員数が共に7名以上
					if ( $this->a_plan_has_rooms_detail[$s_target_room_id]['capacity_min'] > 6 and $this->a_plan_has_rooms_detail[ $s_target_room_id ]['capacity_max'] > 6 ) {
						$this->box->item->error->add('「' . $this->a_plan_has_rooms_detail[ $s_target_room_id ]['room_nm'] . '」（'. $this->a_plan_has_rooms_detail[ $s_target_room_id ]['pms_cd'] . '）の定員数が7名以上の為、料金を設定できません。');
						$this->box->item->error->add('部屋プランメンテナンス画面の部屋編集より修正を行ってください。');
						$b_is_success = false;
					}
					
					$a_tmp_capacities_min[] = nvl($this->a_plan_has_rooms_detail[ $s_target_room_id ]['capacity_min'], 0);
					
					// 最大定員数だけが7名以上の場合、6名までの入力は受け入れるように補正する
					if ( $this->a_plan_has_rooms_detail[ $s_target_room_id ]['capacity_max'] > 6 ) {
						$a_tmp_capacities_max[] = 6;
					} else {
						$a_tmp_capacities_max[] = nvl($this->a_plan_has_rooms_detail[ $s_target_room_id ]['capacity_max'], 0);
					}
					
				}
				
				$this->a_range_capacity_rooms = range(min($a_tmp_capacities_min), max($a_tmp_capacities_max));
				
				if ( !$b_is_success ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 取得した料金情報を整形してリクエストパラメータに設定する
		//======================================================================
		private function _set_charges_to_request_param()
		{
			try {
				// 初期化
				$n_ymd = 0;
				
				// 画面で使用可能な形式に整形
				foreach ( $this->o_models_charge3->get_charges() as $a_charge ) {
					$n_ymd = strtotime($a_charge['date_ymd']);
					
					$this->a_request_params['sales_charge_' . $n_ymd . '_' . $a_charge['capacity']] = $a_charge['sales_charge'];
					
					// 1000円以下の時はフラグを設定する
					if ( $a_charge['is_low_price'] == 1 ) {
						$this->a_low_price_setting['ymdc'][$n_ymd . '_' . $a_charge['capacity']] = true;
					}
				}
				
				// カレンダーを消去
				$this->o_models_charge3->clear();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 料金入力補助情報を整形してリクエストパラメータに設定する
		//======================================================================
		private function _set_charge_remind_to_request_param()
		{
			try {
				// 初期化
				$a_conditions = array(
					'hotel_cd' => $this->a_request_params['target_cd'],
					'plan_id'  => $this->a_request_params['plan_id'],
					'room_id'  => $this->a_request_params['room_id']
				);
				
				$s_sql =
<<< SQL
					select	cr.capacity,
							cr.usual_charge_sun,
							cr.usual_charge_mon,
							cr.usual_charge_tue,
							cr.usual_charge_wed,
							cr.usual_charge_thu,
							cr.usual_charge_fri,
							cr.usual_charge_sat,
							cr.usual_charge_bfo,
							cr.usual_charge_hol,
							cr.sales_charge_sun,
							cr.sales_charge_mon,
							cr.sales_charge_tue,
							cr.sales_charge_wed,
							cr.sales_charge_thu,
							cr.sales_charge_fri,
							cr.sales_charge_sat,
							cr.sales_charge_bfo,
							cr.sales_charge_hol
					from	charge_remind cr,
							(
								select	ppg.hotel_cd,
										ppg.plan_id,
										min(ppg.partner_group_id) as partner_group_id
								from	plan_partner_group ppg
								where	ppg.hotel_cd = :hotel_cd
									and	ppg.plan_id  = :plan_id
								group by	ppg.hotel_cd,
											ppg.plan_id
							) q1
					where	cr.hotel_cd         = q1.hotel_cd
						and	cr.plan_id          = q1.plan_id
						and	cr.room_id          = :room_id
						and	cr.partner_group_id = q1.partner_group_id
					order by	cr.capacity
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				$a_rows = nvl($a_rows, array());
				
				// 整形してリクエストパラメータに設定
				foreach ( $a_rows as $n_idx => $a_row ) {
					// 曜日でループ
					foreach ( $this->a_day_of_week as $s_day_of_week ) {
						$this->a_request_params['usual_charge_' . $s_day_of_week . '_' . $a_row['capacity']] = $a_row['usual_charge_' . $s_day_of_week];
						$this->a_request_params['sales_charge_' . $s_day_of_week . '_' . $a_row['capacity']] = $a_row['sales_charge_' . $s_day_of_week];
					}
					
					unset($a_rows[$n_idx]);
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 子供料金情報を整形してリクエストパラメータに設定する
		//======================================================================
		private function _set_child_charge_to_request_param()
		{
			try {
				// 子供料金情報を取得
				$a_room_plan_child_find = $this->o_room_plan_child->find(
					array(
						'hotel_cd' => $this->a_request_params['target_cd'],
						'plan_id'  => $this->a_request_params['plan_id'],
						'room_id'  => $this->a_request_params['room_id']
					)
				);
				
				// 整形してリクエストパラメータに設定
				for ( $ii = 1; $ii <= 5; $ii++ ) {
					$this->a_request_params['child' . $ii . '_accept']         = $a_room_plan_child_find['child' . $ii . '_accept'];
					$this->a_request_params['child' . $ii . '_person']         = $a_room_plan_child_find['child' . $ii . '_person'];
					$this->a_request_params['child' . $ii . '_charge_include'] = $a_room_plan_child_find['child' . $ii . '_charge_include'];
					$this->a_request_params['child' . $ii . '_charge_unit']    = $a_room_plan_child_find['child' . $ii . '_charge_unit'];
					
					if ( $a_room_plan_child_find['child' . $ii . '_charge_unit'] > 0 ) {
						$this->a_request_params['child' . $ii . '_charge'] = $a_room_plan_child_find['child' . $ii . '_charge'];
					} else {
						$this->a_request_params['child' . $ii . '_charge'] = $a_room_plan_child_find['child' . $ii . '_rate'];
					}
				}
				
				unset($a_room_plan_child_find);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 子供料金の設定処理
		//======================================================================
		protected function _update_child_charge()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_child_idx            = array(-1, 1, 2, 4, 3, 5); // 子供料金の3と4が逆になっている為、ループで回すための配列
				$a_child_person_default = array(-1, 1, 1, 0, 1, 0); // ループ用配列に対応した「部屋人数係数」のデフォルト値配列
				
				$a_room_plan_child_work = array(
					'hotel_cd' => $this->a_request_params['target_cd'],
					'plan_id'  => $this->a_request_params['plan_id']
				);
				
				$a_room_plan_child_find = array();
				
				$b_is_record_create  = false; // レコードを新規作成するかどうか
				$b_is_update_success = true;  // 更新の成功可否フラグ
				$b_is_error          = false; // 更新エラーフラグ
				$n_temp_child_unit   = -1;
				
				//--------------------------------------------------------------
				// バリデート設定
				//--------------------------------------------------------------
				// テーブルを設定
				$this->o_validations->set_table($this->o_room_plan_child);
				
				// 対象項目を定義
				$this->o_validations->set_validate(array('Room_Plan_Child' => 'hotel_cd'));
				$this->o_validations->set_validate(array('Room_Plan_Child' => 'room_id'));
				$this->o_validations->set_validate(array('Room_Plan_Child' => 'plan_id'));
				
				for ( $ii = 1; $ii <= 5; $ii++ ) {
					$this->o_validations->set_validate(array('Room_Plan_Child' => 'child' . $a_child_idx[$ii] . '_accept'));
					$this->o_validations->set_validate(array('Room_Plan_Child' => 'child' . $a_child_idx[$ii] . '_person'));
					$this->o_validations->set_validate(array('Room_Plan_Child' => 'child' . $a_child_idx[$ii] . '_charge_include'));
					$this->o_validations->set_validate(array('Room_Plan_Child' => 'child' . $a_child_idx[$ii] . '_charge_unit'));
					$this->o_validations->set_validate(array('Room_Plan_Child' => 'child' . $a_child_idx[$ii] . '_rate'));
					$this->o_validations->set_validate(array('Room_Plan_Child' => 'child' . $a_child_idx[$ii] . '_charge'));
				}
				
				//--------------------------------------------------------------
				// 登録 / 更新
				//--------------------------------------------------------------
				// 対象の部屋でループ
				foreach ( nvl($this->a_request_params['target_rooms'], array()) as $s_room_id ) {
					// データ登録/更新成功フラグを初期化
					$b_is_update_success = true;
					
					// レコードを取得
					$a_room_plan_child_work['room_id'] = $s_room_id;
					$a_room_plan_child_find            = $this->o_room_plan_child->find($a_room_plan_child_work);
					
					// レコードの有無で処理を分岐
					if ( is_empty($a_room_plan_child_find) ) {
						// レコードの新規作成フラグをON
						$b_is_record_create = true;
						
						// 新規作成に必要な項目を設定
						$a_room_plan_child_find['hotel_cd'] = $this->a_request_params['target_cd'];
						$a_room_plan_child_find['room_id']  = $s_room_id;
						$a_room_plan_child_find['plan_id']  = $this->a_request_params['plan_id'];
						$a_room_plan_child_find['entry_cd'] = $this->box->info->env->action_cd;
						$a_room_plan_child_find['entry_ts'] = 'sysdate';
					} else {
						// レコードの新規作成フラグをOFF
						$b_is_record_create = false;
					}
					
					// 登録 / 更新で共通部分を設定
					for ( $ii = 1; $ii <= 5; $ii++ ) {
						$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_accept']         = $this->a_request_params['child' . $a_child_idx[$ii] . '_accept'];
						$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_person']         = $a_child_person_default[$ii];
						$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_charge_include'] = nvl($this->a_request_params['child' . $a_child_idx[$ii] . '_charge_include'], 0);
						$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_charge_unit']    = $this->a_request_params['child' . $a_child_idx[$ii] . '_charge_unit'];
						
						$n_temp_child_unit = nvl2($this->a_request_params['child' . $a_child_idx[$ii] . '_charge_unit'], (int)$this->a_request_params['child' . $a_child_idx[$ii] . '_charge_unit'], -1);
						
						// 単位によって処理を分岐
						switch ($n_temp_child_unit) {
						case 0: //率
							$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_rate']   = $this->a_request_params['child' . $a_child_idx[$ii] . '_charge'];
							break;
							
						case 1: // 円
						case 2: // 円引き
							$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_charge'] = $this->a_request_params['child' . $a_child_idx[$ii] . '_charge'];
							break;
							
						default: // 子供の受け入れ「なし」に変更されたとき
							$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_rate']   = null;
							$a_room_plan_child_find['child' . $a_child_idx[$ii] . '_charge'] = null;
							break;
						}
					}
					
					$a_room_plan_child_find['modify_cd'] = $this->box->info->env->action_cd;
					$a_room_plan_child_find['modify_ts'] = 'sysdate';
					
					// 登録内容をアクティブレコードに設定
					$this->o_room_plan_child->attributes($a_room_plan_child_find);
					
					// バリデート実行
					$this->o_validations->valid('Room_Plan_Child');
					
					// バリデート結果を判定
					if ( !$this->o_validations->is_valid('Room_Plan_Child') ) {
						// データ登録/更新成功フラグをOFF
						$b_is_update_success = false;
					} else {
						// レコードを作成するかどうかで処理を分岐
						if ( $b_is_record_create ) {
							// 新規作成
							$b_is_update_success = $this->o_room_plan_child->save();
						} else {
							// 更新
							$b_is_update_success = $this->o_room_plan_child->update();
						}
					}
					
					// エラー時
					if ( !$b_is_update_success ) {
						// エラーフラグをON
						$b_is_error = true;
						
						// エラーメッセージを格納
						$this->box->item->error->add('子供料金の設定に失敗しました。');
					}
				}
				
				// エラーメッセージが存在した場合エラーとする
				if ( $b_is_error ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 曜日別の「正規料金」、「販売料金」の処理
		//======================================================================
		private function _update_charge_remind()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_charge_remind_work = array(
					'hotel_cd' => $this->a_request_params['target_cd'],
					'plan_id'  => $this->a_request_params['plan_id']
				);
				
				$a_charge_remind_find = array();
				
				$s_temp_key_nm    = ''; // 配列のキーを動的に作時の一時保存用
				
				$b_is_record_create  = false; // レコードを新規作成するかどうか
				$b_is_update_success = true;  // 登録/更新の成功可否フラグ
				$b_is_error          = false; // エラーフラグ
				
				//--------------------------------------------------------------
				// バリデート設定
				//--------------------------------------------------------------
				// テーブルを設定
				$this->o_validations->set_table($this->o_charge_remind);
				
				// 対象項目を定義
				$this->o_validations->set_validate(array('Charge_Remind' => 'hotel_cd'));
				$this->o_validations->set_validate(array('Charge_Remind' => 'room_id'));
				$this->o_validations->set_validate(array('Charge_Remind' => 'plan_id'));
				$this->o_validations->set_validate(array('Charge_Remind' => 'partner_group_id'));
				$this->o_validations->set_validate(array('Charge_Remind' => 'capacity'));
				
				foreach ( $this->a_day_of_week as $s_day_of_week) {
					$this->o_validations->set_validate(array('Charge_Remind' => 'usual_charge_' . $s_day_of_week));
					$this->o_validations->set_validate(array('Charge_Remind' => 'sales_charge_' . $s_day_of_week));
				}
				
				//--------------------------------------------------------------
				// 登録 / 更新
				//--------------------------------------------------------------
				//********************************
				// 対象の部屋でループ
				//********************************
				foreach ( nvl($this->a_request_params['target_rooms'], array()) as $s_room_id ) {
					
					// 部屋IDを設定
					$a_charge_remind_work['room_id'] = $s_room_id;
					
					//********************************
					// 処理対象の人数分ループ
					//********************************
					for ( $ii = $this->a_plan_has_rooms_detail[$s_room_id]['capacity_min']; $ii <= $this->a_plan_has_rooms_detail[$s_room_id]['capacity_max']; $ii++ ) {
						
						// 人数を設定
						$a_charge_remind_work['capacity'] = $ii;
						
						//********************************
						// 提携先グループIDでループ
						//********************************
						foreach ( $this->a_plan_partner_group_id as $s_partner_group_id ) {
							
							// 提携先グループIDを設定
							$a_charge_remind_work['partner_group_id'] = $s_partner_group_id;
						
							// データ登録更新エラーフラグを初期化
							$b_is_update_success = true;
							
							// レコードを取得
							$a_charge_remind_find = $this->o_charge_remind->find($a_charge_remind_work);
							
							// レコードの有無で処理を分岐
							if ( is_empty($a_charge_remind_find) ) {
								// レコード作成フラグON
								$b_is_record_create = true;
								
								// 新規作成に必要な項目を設定
								$a_charge_remind_find['hotel_cd']         = $this->a_request_params['target_cd'];
								$a_charge_remind_find['room_id']          = $s_room_id;
								$a_charge_remind_find['plan_id']          = $this->a_request_params['plan_id'];
								$a_charge_remind_find['partner_group_id'] = $s_partner_group_id;
								$a_charge_remind_find['capacity']         = $ii;
								$a_charge_remind_find['entry_cd']         = $this->box->info->env->action_cd;
								$a_charge_remind_find['entry_ts']         = 'sysdate';
							} else {
								// レコード作成フラグOFF
								$b_is_record_create = false;
							}
							
							// 曜日でループ
							foreach ( $this->a_day_of_week as $s_day_of_week ) {
								// 正規料金
								$s_temp_key_nm = 'usual_charge_' . $s_day_of_week;
								$a_charge_remind_find[$s_temp_key_nm] = $this->a_request_params[$s_temp_key_nm . '_' . $ii];
								
								// 販売料金
								$s_temp_key_nm = 'sales_charge_' . $s_day_of_week;
								$a_charge_remind_find[$s_temp_key_nm] = $this->a_request_params[$s_temp_key_nm . '_' . $ii];
							}
							
							$a_charge_remind_find['modify_cd'] = $this->box->info->env->action_cd;
							$a_charge_remind_find['modify_ts'] = 'sysdate';
							
							// 登録内容をアクティブレコードに設定
							$this->o_charge_remind->attributes($a_charge_remind_find);
							
							// バリデート実行
							$this->o_validations->valid('Charge_Remind');
							
							// バリデート結果を判定
							if ( !$this->o_validations->is_valid('Charge_Remind') ) {
								// エラーメッセージ追加
								$this->box->item->error->add('正規料金の設定または販売料金の設定(曜日一括入)でエラーが発生しました。(' . $ii . '人利用)');
								
								return false;
							}

							// レコードを作成するかどうかで処理を分岐
							if ( $b_is_record_create ) {
								// 新規作成
								$b_is_update_success = $this->o_charge_remind->save();
							} else {
								// 更新
								$b_is_update_success = $this->o_charge_remind->update();
							}
							
							// エラー時
							if ( !$b_is_update_success ) {
								// エラーメッセージ追加
								$this->box->item->error->add('正規料金の設定または販売料金の設定(曜日一括入)でエラーが発生しました。(' . $ii . '人利用)');
								
								return false;
							}
							
						} // 提携先グループID
					} // 人数
				} // 部屋
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 料金の登録 / 更新
		//======================================================================
		private function _update_charge()
		{
			try {
				$a_charge_work = array(
					'hotel_cd' => $this->a_request_params['target_cd'],
					'plan_id'  => $this->a_request_params['plan_id']
				);
				
				$a_charge_find = array();
				
				$b_is_record_create  = false; // レコードを新規作成するかどうか
				$b_is_update_success = false; // 更新の成功可否フラグ
				
				$n_low_price_calculate = null; // 低価格警告表示用の金額
				
				$n_temp_usual_charge   = null;
				$n_usual_charge        = null;
				$n_usual_charge_revise = null;
				
				$n_temp_sales_charge   = null;
				$n_sales_charge        = null;
				$n_sales_charge_revise = null;
				
				$o_models_date = new Br_Models_Date();
				
				// プランの販売終了日時を取得
				$s_accept_e_hour = null;
				$s_accept_e_min  = null;
				list($s_accept_e_hour, $s_accept_e_min) = nvl2($this->a_plan_detail['accept_e_hour'], explode(':', $this->a_plan_detail['accept_e_hour']), null);
				
				// 金土日を表す配列を生成
				$a_allow_day_of_week = array(0, 5, 6);
				
				//--------------------------------------------------------------
				// バリデート設定
				//--------------------------------------------------------------
				// テーブルを設定
				$this->o_validations->set_table($this->o_charge);
				
				// 対象項目を定義
				$this->o_validations->set_validate(array('Charge' => 'hotel_cd'));
				$this->o_validations->set_validate(array('Charge' => 'room_id'));
				$this->o_validations->set_validate(array('Charge' => 'plan_id'));
				$this->o_validations->set_validate(array('Charge' => 'partner_group_id'));
				$this->o_validations->set_validate(array('Charge' => 'capacity'));
				$this->o_validations->set_validate(array('Charge' => 'date_ymd'));
				$this->o_validations->set_validate(array('Charge' => 'usual_charge'));
				$this->o_validations->set_validate(array('Charge' => 'usual_charge_revise'));
				$this->o_validations->set_validate(array('Charge' => 'sales_charge'));
				$this->o_validations->set_validate(array('Charge' => 'sales_charge_revise'));
				$this->o_validations->set_validate(array('Charge' => 'accept_status'));
				$this->o_validations->set_validate(array('Charge' => 'accept_s_dtm'));
				$this->o_validations->set_validate(array('Charge' => 'accept_e_dtm'));
				$this->o_validations->set_validate(array('Charge' => 'low_price_status'));
				
				//--------------------------------------------------------------
				// カレンダーの取得
				//--------------------------------------------------------------
				$this->o_models_calendar->set_from_ymd($this->a_request_params['from_year'] . '-' . $this->a_request_params['from_month'] . '-' . $this->a_request_params['from_day']);
				$this->o_models_calendar->set_to_ymd($this->a_request_params['to_year']     . '-' . $this->a_request_params['to_month']   . '-' . $this->a_request_params['to_day']);
				$this->o_models_calendar->make_update_charge_calendar();
				
				$a_calendar = $this->o_models_calendar->get_calendar();
				
				// シティホテル、旅館は5000円以下の料金登録でアラート、ビジネスホテル、カプセルホテルは1000円
				if ($this->box->user->hotel['hotel_category'] == 'c' or $this->box->user->hotel['hotel_category'] == 'j') {
					$n_alert_charge = 5000;
				} else {
					$n_alert_charge = 1000;
				}

				//--------------------------------------------------------------
				// 登録 / 更新
				//--------------------------------------------------------------
				//********************************
				// 対象の部屋でループ
				//********************************
				foreach ( nvl($this->a_request_params['target_rooms'], array()) as $s_room_id ) {
					
					// 部屋IDを設定
					$a_charge_work['room_id'] = $s_room_id;
					
					//********************************
					// 処理対象の人数分ループ
					//********************************
					for ( $ii = $this->a_plan_has_rooms_detail[$s_room_id]['capacity_min']; $ii <= $this->a_plan_has_rooms_detail[$s_room_id]['capacity_max']; $ii++ ) {
						// 人数を設定
						$a_charge_work['capacity'] = $ii;
						
						//********************************
						// 提携先グループIDでループ
						//********************************
						foreach ( $this->a_plan_partner_group_id as $s_partner_group_id ) {
							
							// 提携先グループIDを設定
							$a_charge_work['partner_group_id'] = $s_partner_group_id;
							
							//********************************
							// 登録対象日でループ
							//********************************
							foreach ( $a_calendar as $a_day ) {
								// 対象期間外の日付はスキップ
								if ( $a_day['is_not_edit'] ) {
									continue;
								}
								
								// データ登録更新エラーフラグを初期化
								$b_is_update_success = false;
								
								// レコードを取得
								$a_charge_work['date_ymd'] = $a_day['ymd'];
								$a_charge_find             = $this->o_charge->find($a_charge_work);
								
								// レコードの有無で処理を分岐
								if ( is_empty($a_charge_find) ) {
									// レコード作成フラグON
									$b_is_record_create = true;
									
									// 販売終了日時を設定
									$o_models_date->set($a_day['ymd']);
									
									// 販売終了日時：日が指定されている場合
									if ( !is_empty($this->a_plan_detail['accept_e_day']) ) {
										$o_models_date->add('d', $this->a_plan_detail['accept_e_day']);
									}
									
									// 販売終了日時：時を設定
									if ( !is_empty($this->a_plan_detail['accept_e_hour']) ) {
										$o_models_date->add('H', $s_accept_e_hour);
										$o_models_date->add('i', $s_accept_e_min);
									} else {
										$o_models_date->add('H', '30');
									}
									
									//********************************
									// 新規作成に必要な項目を設定
									//********************************
									$a_charge_find['hotel_cd']         = $this->a_request_params['target_cd'];
									$a_charge_find['room_id']          = $s_room_id;
									$a_charge_find['plan_id']          = $this->a_request_params['plan_id'];
									$a_charge_find['partner_group_id'] = $s_partner_group_id;
									$a_charge_find['capacity']         = $ii;
									$a_charge_find['date_ymd']         = $a_day['ymd_num'];
									$a_charge_find['accept_s_dtm']     = strtotime(date('Y-m-d H:i'));
									$a_charge_find['accept_e_dtm']     = $o_models_date->get();
									$a_charge_find['accept_status']    = 1;
									$a_charge_find['low_price_status'] = 0;
									$a_charge_find['entry_cd']         = $this->box->info->env->action_cd;
									$a_charge_find['entry_ts']         = 'sysdate';
								} else {
									$b_is_record_create = false;
								}
								
								//********************************
								// 更新データの設定：料金
								//********************************
								// 正規料金の取得
								if ( $a_day['dow_num'] === 6 ) {
									// 土曜日は休前日と祝日より優先される
									$n_temp_usual_charge = $this->a_request_params['usual_charge_' . $this->a_day_of_week[$a_day['dow_num']] . '_' . $ii];
								} else if ( $a_day['is_bfo'] ) {
									// 休前日は祝日より優先される
									$n_temp_usual_charge = $this->a_request_params['usual_charge_' . 'bfo' . '_' . $ii];
								} else if ( $a_day['is_hol'] ) {
									// 祝日
									$n_temp_usual_charge = $this->a_request_params['usual_charge_' . 'hol' . '_' . $ii];
								} else {
									// その他の曜日
									$n_temp_usual_charge = $this->a_request_params['usual_charge_' . $this->a_day_of_week[$a_day['dow_num']] . '_' . $ii];
								}
								
								// 販売料金の取得
								$n_temp_sales_charge = $this->a_request_params['sales_charge_' . $a_day['ymd_num'] . '_' . $ii];
								
								// ここより下の処理で小数点以下が切り捨て、文字列が0にキャストされる為
								// ここでエラーチェック
								if ( !is_empty($n_temp_sales_charge) and !ctype_digit((string)$n_temp_sales_charge) ) {
									$this->box->item->error->add('大人一人販売料金はマイナス、小数点を含まない数値を入力してください。');
									$this->box->item->error->add(date('Y', $a_day['ymd_num']) . '年' . ltrim(date('m', $a_day['ymd_num']), '0') . '月' .  ltrim(date('d', $a_day['ymd_num']), '0') . '日の' . $ii  . '名利用の料金設定に失敗しました。');
									return false;
								}
								
								// 料金タイプで処理を分岐
								// ※RC・MCに関わらず、新テーブルにはMC料金で登録する
								//   その為、RC料金を料金と補正値を計算する
								if ( $this->a_plan_detail['charge_type'] === '1' ) {
									// MC料金
									$n_usual_charge        = $n_temp_usual_charge;
									$n_usual_charge_revise = 0;
									$n_sales_charge        = $n_temp_sales_charge;
									$n_sales_charge_revise = 0;
									
									// 低価格警告の判定に使用する金額を設定
									$n_low_price_calculate = $n_temp_sales_charge;
									
								} else {
									// RC料金
									$n_usual_charge        = nvl2($n_temp_usual_charge, floor($n_temp_usual_charge / $ii), null); // 正規料金
									$n_usual_charge_revise = nvl2($n_temp_usual_charge, $n_temp_usual_charge % $ii, null); // 正規料金補正値
									$n_sales_charge        = nvl2($n_temp_sales_charge, floor($n_temp_sales_charge / $ii), null); // 販売料金
									$n_sales_charge_revise = nvl2($n_temp_sales_charge, $n_temp_sales_charge % $ii, null); // 販売料金補正値
									
									// 低価格警告の判定に使用する金額を設定
									$n_low_price_calculate = $n_sales_charge;
								}
								
								// 対象日の料金入力が空の場合
								if ( is_empty($n_sales_charge) ) {
									// レコードの有無で処理を分岐
									if ( $b_is_record_create ) {
										// 新規作成時はレコードを作る必要がないので、
										// 以下の処理をスキップ
										continue;
									} else {
										// 旧テーブルとの互換性維持の為、
										// 更新時は0円に更新する
										$n_sales_charge = 0;
										$n_sales_charge_revise = 0;
										$this->a_request_params['sales_charge_' . $a_day['ymd_num'] . '_' . $ii] = 0;
									}
								}
								
								// 当日割の設定が存在するプランのとき
								if ( $this->b_exists_charge_today ) {
									// 販売料金が変更された日程を保持
									if ( (int)$a_charge_find['sales_charge'] !== $n_sales_charge ) {
										$this->a_updated_charge_ymdc[$a_day['ymd_num'] . '_' . $ii] = true;
									}
								}
								
								// 2014/7/1 金土日プランでも平日の料金が登録できてしまう不具合があった為
								// そのリカバリーを行うための処理
								// 金土日プランの平日に料金が存在している場合0円を設定して更新
								if ( $this->a_plan_detail['plan_type'] === 'fss' ) {
									if ( !in_array($a_day['dow_num'], $a_allow_day_of_week) ) {
										$n_sales_charge = 0;
										$n_sales_charge_revise = 0;
										$this->a_request_params['sales_charge_' . $a_day['ymd_num'] . '_' . $ii] = 0;
									}
								}
								
								// 料金を設定
								$a_charge_find['usual_charge']        = $n_usual_charge;
								$a_charge_find['usual_charge_revise'] = $n_usual_charge_revise;
								$a_charge_find['sales_charge']        = $n_sales_charge;
								$a_charge_find['sales_charge_revise'] = $n_sales_charge_revise;
								
								// 更新者情報を設定
								$a_charge_find['modify_cd'] = $this->box->info->env->action_cd;
								$a_charge_find['modify_ts'] = 'sysdate';
								
								// 登録内容をアクティブレコードに設定
								$this->o_charge->attributes($a_charge_find);
								
								// バリデート実行
								$this->o_validations->valid('Charge');
								
								// バリデート結果を判定
								if ( !$this->o_validations->is_valid('Charge') ) {
									
									// エラーメッセージ追加
									$this->box->item->error->add(date('Y', $a_day['ymd_num']) . '年' . ltrim(date('m', $a_day['ymd_num']), '0') . '月' .  ltrim(date('d', $a_day['ymd_num']), '0') . '日の' . $ii  . '名利用の料金設定に失敗しました。');
									
									return false;
								}
								
								// レコードを作成するかどうかで処理を分岐
								if ( $b_is_record_create ) {
									// 新規作成
									$b_is_update_success = $this->o_charge->save();
								} else {
									// 更新
									$b_is_update_success = $this->o_charge->update();
								}
								
								// エラー時
								if ( !$b_is_update_success ) {
									// エラーメッセージ追加
									$this->box->item->error->add(date('Y', $a_day['ymd_num']) . '年' . ltrim(date('m', $a_day['ymd_num']), '0') . '月' .  ltrim(date('d', $a_day['ymd_num']), '0') . '日の' . $ii  . '名利用の料金設定に失敗しました。');
									
									return false;
								}
								
								// 設定料金が1人あたりN円未満の場合保持する（最大5件、日付-人数）
								// シティホテル、旅館は5000円以下の料金登録でアラート、ビジネスホテル、カプセルホテルは1000円
								if ( 0 < $n_low_price_calculate and $n_low_price_calculate < $n_alert_charge) {
									// 5件まではメッセージ表示用データを保持
									if ( count($this->a_low_price_setting['message']) < 5 ) {
										$this->a_low_price_setting['message'][$a_day['ymd_num'] . '_' . $ii] = date('Y', $a_day['ymd_num']) . '年' . ltrim(date('m', $a_day['ymd_num']), '0') . '月' .  ltrim(date('d', $a_day['ymd_num']), '0') . '日(' . $ii  . '名利用)';
									}
									
									$this->a_low_price_setting['count'][$a_day['ymd_num'] . '_' . $ii] = true;
									$this->a_low_price_setting['ymdc'][$a_day['ymd_num'] . '_' . $ii]  = true;
									$this->a_low_price_setting['alert_charge']  = $n_alert_charge;
								}
								
							} // 日付
						} // 提携先グループID
					} // 人数
				} // 部屋
				
				// エラーメッセージが存在した場合エラーとする
				if ( !is_empty($this->box->item->error->gets()) ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 当日割が設定されている日付に料金変更が発生した場合に削除を行う
		//======================================================================
		private function _update_charge_today()
		{
			try {
				// 初期化
				$n_now_dtm  = strtotime(date('Y-m-d H:i'));
				$n_am00     = strtotime(date('Y-m-d 00:00'));
				$n_am06     = strtotime(date('Y-m-d 06:00'));
				$n_from_ymd = strtotime(date('Y-m-d'));
				
				// 現在日時が午前0時～午前6時のとき（30時対応）
				if ( $n_am00 <= $n_now_dtm and $n_now_dtm <= $n_am06 ) {
					$n_from_ymd = strtotime('-1day', $n_from_ymd);
				}
				
				$a_conditions = array(
					'hotel_cd' => $this->a_request_params['target_cd'],
					'plan_id'  => $this->a_request_params['plan_id'],
					'from_ymd' => date('Y-m-d', $n_from_ymd)
				);
				
				$a_charge_today_find = array(
					'hotel_cd' => $this->a_request_params['target_cd'],
					'plan_id'  => $this->a_request_params['plan_id']
				);
				
				$s_sql =
<<< SQL
					select	room_id,
							partner_group_id,
							capacity,
							to_char(date_ymd, 'YYYY-MM-DD') as date_ymd,
							to_char(timetable, 'YYYY-MM-DD HH24:MI:SS') as timetable
					from	charge_today
					where	hotel_cd = :hotel_cd
						and	plan_id  = :plan_id
						and	date_ymd >= to_date(:from_ymd, 'YYYY-MM-DD')
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				$a_rows = nvl($a_rows, array());
				
				// 当日割のレコード数だけループ
				foreach ( $a_rows as $a_row ) {
					// 対象日の料金が変更されているとき以外はスキップ
					if ( !$this->a_updated_charge_ymdc[strtotime($a_row['date_ymd']) . '_' . $a_row['capacity']] ) {
						continue;
					}
					
					// 変更されているものに関しては削除する
					$a_charge_today_find['room_id']          = $a_row['room_id'];
					$a_charge_today_find['capacity']         = $a_row['capacity'];
					$a_charge_today_find['partner_group_id'] = $a_row['partner_group_id'];
					$a_charge_today_find['timetable']        = strtotime($a_row['timetable']);
					$a_charge_today_find['date_ymd']         = strtotime($a_row['date_ymd']);
					
					$this->o_charge_today->destroy($a_charge_today_find);
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 入力された料金パラメータを変換するフィルター
		// ・カンマ、空白スペースの除去、半角数値への補正を行う
		//======================================================================
		private function _filter_charge()
		{
			try {
				// 変換対象文字列を定義
				$a_target_str = array(' ', '　', ',', '，', '、');
				$temp_value   = null;
				
				// リクエストパラメータをループ
				foreach ( $this->a_request_params as $key => $value ) {
					// 料金入力フィールドだけに処理を行う
					if ( preg_match('/^sales_charge_.*/', $key) or preg_match('/^usual_charge_.*/', $key) or preg_match('/^child[1-5]_charge$/', $key) ) {
						
						// 全角文字を半角文字に変換
						$temp_value = mb_convert_kana($value, 'a'); // 半角へ変換
						
						// 整形対象の文字列を変換
						$temp_value = str_replace($a_target_str, '', $temp_value);
						
						// 数値と思われるものでなければスキップ
						if ( !is_numeric($temp_value) ) {
							continue;
						}
						
						// 変換したパラメータを再設定
//						$this->a_request_params[$key] = (int)$temp_value;
						$this->a_request_params[$key] = $temp_value;
					}
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		public function _make_plan_detail($targetCd, $planId)
		{
			try {
				// プランの詳細情報を取得
				$plan_detail = $this->get_plan_detail($targetCd, $planId);

				//Controllerへ返却
				return $plan_detail;

			} catch (Exception $e) {
				throw $e;
			}
		}

		private function get_plan_detail($targetCd, $planId,$ab_is_orderby=true)
		{
			try {
				//初期化
				$s_select   = '';
				$s_order_by = '';
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($targetCd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_null($planId) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//-------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array();
				$s_sql        = '';
				$a_special_partners = array(
					'br'   => self::PTN_CD_BR,
					'jrc'  => self::PTN_CD_JRC,
					'relo' => self::PTN_CD_RELO
				);
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				// バインド変数設定
				$a_conditions['hotel_cd'] = $targetCd;
				$a_conditions['plan_id']  = $planId;

				//--------------------------------------------------------------
				// 特殊な提携先の取得方法を設定
				// [0:非対象, 1:対象]
				//--------------------------------------------------------------
				foreach ( $a_special_partners as $s_ptn_nm => $s_ptn_cd ) {
					//decode関数からcase分を使うように変更。
					$s_select .= ",max(case when partner_group_join.partner_cd = '" . $s_ptn_cd . "' then 1 else 0 end) as is_" .$s_ptn_nm;
				}
				//--------------------------------------------------------------
				// 引数によって式を指定
				//-------------------------------------------------------------- 
				// ORDER BY句の有無
				if ( $ab_is_orderby ) {
					$s_order_by =				
					"order by	plan_point_add.plan_order_no asc,
								plan_point_add.modify_ts desc";
				}		
		$s_sql = <<< SQL
				select	
					plan_point_add.hotel_cd,
					plan_point_add.plan_id,
					plan_point_add.plan_type,
					plan_point_add.plan_nm,
					plan_point_add.charge_type,
					plan_point_add.payment_way,
					plan_point_add.stay_limit,
					plan_point_add.stay_cap,
					plan_point_add.pms_cd,
					plan_point_add.accept_s_ymd as accept_s_ymd,
					plan_point_add.accept_e_ymd as accept_e_ymd,
					plan_point_add.accept_e_day,
					plan_point_add.accept_e_hour,
					plan_point_add.check_in,
					plan_point_add.check_in_end,
					plan_point_add.check_out,
					plan_point_add.accept_status,
					plan_point_add.is_br,
					plan_point_add.is_jrc,
					plan_point_add.is_relo,
					plan_point_add.meal,
					plan_point.issue_point_rate,
					plan_point.point_status
				from plan_point  
					inner join(
						select	
						plans_attribute_add.hotel_cd,
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
							from	
								plan_spec 
								inner join(
									select	
									partner_cd_add.hotel_cd,
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
									from partner_group_join 
										inner join (
											select	
											plans.hotel_cd,
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
											from plan_partner_group
												inner join (
													select
														plan.hotel_cd,
														plan.plan_id,
														plan.plan_type,
														plan.plan_nm,
														plan.charge_type,
														plan.payment_way,
														plan.stay_limit,
														plan.stay_cap,
														case	
															when plan.label_cd is null then plan.plan_id else plan.label_cd 
														end	as pms_cd,
														plan.accept_s_ymd,
														plan.accept_e_ymd,
														plan.accept_e_day,
														plan.accept_e_hour,
														plan.check_in,
														plan.check_in_end,
														plan.check_out,
														plan.accept_status,
														plan.order_no as plan_order_no,
														plan.modify_ts
													from plan
													where	plan.hotel_cd = :hotel_cd
														and	plan.plan_id  = :plan_id
												) plans
											on plan_partner_group.hotel_cd = plans.hotel_cd
												and plan_partner_group.plan_id = plans.plan_id
										) partner_cd_add
									on partner_group_join.partner_group_id = partner_cd_add.partner_group_id
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
							on plan_spec.hotel_cd = plans_attribute_add.hotel_cd
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
								)plan_point_add
					on plan_point.hotel_cd = plan_point_add.hotel_cd
					and	plan_point.plan_id  = plan_point_add.plan_id
					{$s_order_by}
				SQL;

				$a_rows = DB::select($s_sql, $a_conditions);

				// $a_rows = json_decode(json_encode($a_rows),true);
				
				// キャンペーン対象かどうかを設定
				$a_rows[0]->is_camp = $this->is_campaign($targetCd,$planId);
				
				// プランの販売先チャンネルIDを設定
				$a_rows[0]->partner_groups = $this->get_plan_partner_groups($targetCd,$planId);
				
				// プランの基礎情報を設定				
				return $a_rows[0];
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		//======================================================================
		// 対象プランがキャンペーン対象であるかチェック
		//
		// @return bool キャンペーンプラン成否（true:対象, false:非対象）
		//======================================================================
		public function is_campaign($targetCd,$planId)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($targetCd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_null($planId) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array();
				$s_sql        = '';
				
				// バインド変数設定
				$a_conditions['hotel_cd'] = $targetCd;
				$a_conditions['plan_id']  = $planId;
				
				$s_sql =
<<< SQL
					select	count(*) as camp_count
					from	hotel_camp hc
					inner join
							(
								select	hcp2.hotel_cd,
										hcp2.camp_cd
								from	hotel_camp_plan2 hcp2
								where	hcp2.hotel_cd = :hotel_cd
									and	hcp2.plan_id  = :plan_id
							) q1
					on	hc.hotel_cd = q1.hotel_cd
						and	hc.camp_cd  = q1.camp_cd
						and	hc.display_status = 1
						and	(
								hc.accept_s_ymd is null
									||
								round('%Y-%m-%d', '%d') between hc.accept_s_ymd and coalesce (hc.accept_e_ymd, round(hc.target_e_ymd, round('%Y-%m-%d', '%d')))
							)
						and	(
								hc.target_s_ymd is null
									||
								round('%Y-%m-%d', '%d') <= coalesce(hc.target_e_ymd, round('%Y-%m-%d', '%d'))
							)
SQL;
				$campData = DB::select($s_sql, $a_conditions);
				// $a_row = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				// 有効なキャンペーンが存在しないとき
				// if ( (int)$a_row[0]['camp_count'] < 1 ) {
					if((int)$campData[0] ='camp_count' < 1){
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 販売先の提携先グループIDを取得
		//
		// @return Array 対象プランの販売される提携先グループID一覧
		//======================================================================
		public function get_plan_partner_groups($targetCd,$planId)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($targetCd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_null($planId) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_result = array();
				
				$s_sql = '';
				
				$a_conditions = array(
					'hotel_cd' => $targetCd,
					'plan_id'  => $planId
				);
				
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
				// $a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				$a_rows = DB::select($s_sql, $a_conditions);
				
				// 整形
				foreach ( $a_rows ?? array() as $n_key => $a_row ) {
					return $a_rows;
					// $a_result[$n_key] = $a_row['partner_group_id'];
				}
				
				return $a_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		//======================================================================
		// 対象プランがリロプランかどうかを判定
		//======================================================================
		public function is_relo($targetCd,$planId)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($targetCd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_null($planId) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				return $this->_is_corporate(self::PTN_CD_RELO,$targetCd,$planId);
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		//======================================================================
		// 指定の提携先に販売されるプランかどうかを判定
		//
		// @param string 提携先コード
		//
		// @return bool true:販売先である, false:販売先でない
		//======================================================================
		private function _is_corporate($as_partner_cd,$targetCd,$planId)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($targetCd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_null($planId) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$s_sql = '';
				
				$a_conditions = array(
					'hotel_cd'   => $targetCd,
					'plan_id'    => $planId,
					'partner_cd' => $as_partner_cd
				);
				
				//--------------------------------------------------------------
				// データを取得
				//--------------------------------------------------------------
				$s_sql =
<<< SQL
					select	count(*) as row_count
					from	plan_partner_group ppg
							inner join(
								select	pgj.partner_group_id
								from	partner_group_join pgj
								where	pgj.partner_cd = :partner_cd
							) q1
					on	ppg.hotel_cd = :hotel_cd
						and	ppg.plan_id  = :plan_id
						and	ppg.partner_group_id = q1.partner_group_id
SQL;
				$a_rows = DB::select($s_sql, $a_conditions);
				
				if ( $a_rows{0}->{'row_count'} === 0 ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		private function set_hotel_cd($as_hotel_cd)
		{
			$this->s_hotel_cd = $as_hotel_cd;
		}

		private function set_plan_id($as_plan_id)
		{
			$this->s_plan_id = $as_plan_id;
		}
		
	}
?>