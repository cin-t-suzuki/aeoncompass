<?php
    namespace App\Models;

	use App\Common\Traits;
	use App\Models\common\CommonDBModel;
	use App\Models\common\ValidationColumn;
	use Illuminate\Support\Facades\DB;

	class Plan3 extends CommonDBModel
	{
		use Traits;

		// 特殊な扱いになる提携先コードの定義
		const PTN_CD_BR   = '0000000000'; // ベストリザーブ
		const PTN_CD_JRC  = '3015008801'; // JRコレクション
		const PTN_CD_RELO = '3015008796'; // リロクラブ
		
		// プランスペックの定義
		const PLAN_SPEC_MEAL = 4; // 食事
		
		// メンバ変数の定義
		protected $o_box;
		protected $s_hotel_cd;
		protected $s_plan_id;
		protected $s_room_id;
		protected $o_oracle;
		protected $a_detail;  // プラン情報単体（plan_idが指定されたものを格納）
		protected $a_details; // プラン情報複数（施設の保有するプラン全てを格納）
		protected $a_special_partners; // 特殊な提携先コードのリスト
		protected $a_accept_ymd_selecter;
		
		//======================================================================
		// コンストラクタ
		//======================================================================
		function __construct()
		{
			try {
				// boxの生成
				// $o_controller = Zend_Controller_Front::getInstance();
				// $this->_o_box = & $o_controller->getPlugin('Box')->box;
				
				// インスタンス生成
				// $this->o_oracle = _Oracle::getInstance();
				
				// メンバ変数の初期化
				$this->a_detail           = array();
				$this->a_details          = array();
				$this->a_special_partners = array(
					'br'   => self::PTN_CD_BR,
					'jrc'  => self::PTN_CD_JRC,
					'relo' => self::PTN_CD_RELO
				);
				
				$this->_make_accept_ymd_selecter();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// Setter：施設コード
		//
		// @param String 施設コード
		//======================================================================
		public function set_hotel_cd($as_hotel_cd)
		{
			$this->s_hotel_cd = $as_hotel_cd;
		}
		
		//======================================================================
		// Setter：プランID
		//
		// @param String プランID
		//======================================================================
		public function set_plan_id($as_plan_id)
		{
			$this->s_plan_id = $as_plan_id;
		}
		
		//======================================================================
		// Setter：部屋ID
		//
		// @param String 部屋ID
		//======================================================================
		public function set_room_id($as_room_id)
		{
			$this->s_room_id = $as_room_id;
		}
		
		//======================================================================
		// 指定したプランの詳細情報を取得
		//   ・プランメンテナンス画面のプラン表示と同フォーマット
		//   ・必要な情報のみを取得
		//
		// @return array プランの詳細情報
		//======================================================================
		public function get_detail()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_null($this->s_plan_id) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array();
				$s_sql        = '';
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				// バインド変数設定
				$a_conditions['hotel_cd'] = $this->s_hotel_cd;
				$a_conditions['plan_id']  = $this->s_plan_id;
				
				// プランの抽出条件を指定
				$s_where =
<<< SQL_WHERE
					where	plan.hotel_cd = :hotel_cd
						and	plan.plan_id  = :plan_id
SQL_WHERE;
				// $s_sql = $this->get_sql_plan_base($s_where);
				
				// $a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);

				$s_sql =<<< SQL
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
						plan_point_add.to_char(accept_s_ymd, 'YYYY-MM-DD') || ' 00:00' as accept_s_ymd,
						plan_point_add.to_char(accept_e_ymd, 'YYYY-MM-DD') || ' 23:59' as accept_e_ymd,
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
						plan_point_add.issue_point_rate,
						plan_point_add.point_status
					from	
						plan_point 
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
							max(decode(plan_spec.element_id, 4, element_value_id, -1)) as meal -- 食事
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
										from	
											partner_group_join 
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
												from	
													plan_partner_group
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
															nvl(plan.label_cd, plan.plan_id) as pms_cd,
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
														from	
															plan
														where 
														plan_partner_group.hotel_cd = plans.hotel_cd
														and	plan_partner_group.plan_id  = plans.plan_id
													) plans
												where plan_partner_group.hotel_cd = plans.hotel_cd
												and plan_partner_group.plan_id = plans.plan_id
											) partner_cd_add
										where partner_group_join.partner_group_id = partner_cd_add.partner_group_id
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
								where plan_spec.hotel_cd = plans_attribute_add.hotel_cd
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
							where plan_point.hotel_cd = plan_point_add.hotel_cd
							and	plan_point.plan_id  = plan_point_add.plan_id
							{$s_order_by}
SQL;

				$data = DB::select($s_sql, $a_conditions);
				
				// キャンペーン対象かどうかを設定
				$a_rows[0]['is_camp'] = $this->is_campaign();
				
				// プランの販売先チャンネルIDを設定
				$a_rows[0]['partner_groups'] = $this->get_plan_partner_groups();
				
				// プランの基礎情報を設定
				$this->a_detail = $a_rows[0];
				
				return $this->a_detail;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 指定した施設の管理画面に表示されるプラン詳細情報を取得
		//   ・プランメンテナンス画面のプラン表示と同フォーマット
		//   ・必要な情報のみを取得
		//
		// @return array 管理画面に表示されているプランの詳細情報
		//======================================================================
		public function get_details()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_null($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions   = array();
				$s_sql          = '';
				$s_temp_plan_id = $this->s_plan_id; // メンバに設定されているプランIDの一時退避先
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				// バインド変数設定
				$a_conditions['hotel_cd'] = $this->s_hotel_cd;
				
				// プランの抽出条件を指定
				$s_where =
<<< SQL_WHERE
					where	plan.hotel_cd = :hotel_cd
						and	plan.display_status = 1
						and	plan.active_status  = 1
SQL_WHERE;
				$s_sql = $this->get_sql_plan_base($s_where);
				
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				foreach ( nvl($a_rows, array()) as $key => $a_row ) {
					// キャンペーン対象かどうかを設定
					$this->s_plan_id                                 = $a_row['plan_id'];
					$this->a_details[ $a_row['plan_id'] ]            = $a_row;
					$this->a_details[ $a_row['plan_id'] ]['is_camp'] = $this->is_campaign();
					
					// プランの販売先チャンネルIDを設定
					$this->a_details[ $a_row['plan_id'] ]['partner_groups'] = $this->get_plan_partner_groups();
				}
				
				// 退避していたプランIDを設定しなおす
				$this->s_plan_id = $s_temp_plan_id;
				
				return $this->a_details;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 販売先の提携先グループIDを取得
		//
		// @return Array 対象プランの販売される提携先グループID一覧
		//======================================================================
		public function get_plan_partner_groups()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_empty($this->s_plan_id) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_result = array();
				
				$s_sql = '';
				
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'plan_id'  => $this->s_plan_id
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
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				// 整形
				foreach ( nvl($a_rows, array()) as $n_key => $a_row ) {
					$a_result[$n_key] = $a_row['partner_group_id'];
				}
				
				return $a_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 対象のプランに当日割料金が設定されているかを取得
		//
		// @return bool true:設定あり, false:設定なし
		//======================================================================
		public function exists_charge_today()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_empty($this->s_plan_id) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$s_sql      = '';
				
				$n_now_dtm  = strtotime(date('Y-m-d H:i'));
				$n_am00     = strtotime(date('Y-m-d 00:00'));
				$n_am06     = strtotime(date('Y-m-d 06:00'));
				$n_from_ymd = strtotime(date('Y-m-d'));
				
				// 現在日時が午前0時～午前6時のとき（30時対応）
				if ( $n_am00 <= $n_now_dtm and $n_now_dtm <= $n_am06 ) {
					$n_from_ymd = strtotime('-1day', $n_from_ymd);
				}
				
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'plan_id'  => $this->s_plan_id,
					'from_ymd' => date('Y-m-d', $n_from_ymd)
				);
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				$s_sql =
<<< SQL
					select	count(*) as record_count
					from	charge_today
					where	hotel_cd = :hotel_cd
						and	plan_id  = :plan_id
						and	date_ymd  >= to_date(:from_ymd, 'YYYY-MM-DD')
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				if ( $a_rows[0]['record_count'] > 0 ) {
					return true;
				}
				
				return false;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 対象プランがキャンペーン対象であるかチェック
		//
		// @return bool キャンペーンプラン成否（true:対象, false:非対象）
		//======================================================================
		public function is_campaign()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_empty($this->s_plan_id) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array();
				$s_sql        = '';
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				// バインド変数設定
				$a_conditions['hotel_cd'] = $this->s_hotel_cd;
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
								trunc(sysdate, 'DD') between hc.accept_s_ymd and nvl(hc.accept_e_ymd, nvl(hc.target_e_ymd, trunc(sysdate, 'DD')))
							)
						and	(
								hc.target_s_ymd is null
									or
								trunc(sysdate, 'DD') <= nvl(hc.target_e_ymd, trunc(sysdate, 'DD'))
							)
SQL;
				$a_row = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				// 有効なキャンペーンが存在しないとき
				if ( (int)$a_row[0]['camp_count'] < 1 ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 対象プランがリロプランかどうかを判定
		//======================================================================
		public function is_relo()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_empty($this->s_plan_id) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				return $this->_is_corporate(self::PTN_CD_RELO);
				
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
		private function _is_corporate($as_partner_cd)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// プランID
				if ( is_empty($this->s_plan_id) ) {
					throw new Exception('プランIDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$s_sql = '';
				
				$a_conditions = array(
					'hotel_cd'   => $this->s_hotel_cd,
					'plan_id'    => $this->s_plan_id,
					'partner_cd' => $as_partner_cd
				);
				
				//--------------------------------------------------------------
				// データを取得
				//--------------------------------------------------------------
				$s_sql =
<<< SQL
					select	count(*) as row_count
					from	plan_partner_group ppg,
							(
								select	pgj.partner_group_id
								from	partner_group_join pgj
								where	pgj.partner_cd = :partner_cd
							) q1
					where	ppg.hotel_cd = :hotel_cd
						and	ppg.plan_id  = :plan_id
						and	ppg.partner_group_id = q1.partner_group_id
SQL;
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				if ( $a_rows[0]['row_count'] > 0 ) {
					return true;
				}
				
				return false;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プラン情報（管理画面上で緑枠で表現されるもの）を取得するSQL文を取得。
		//
		// @params string プランの抽出条件（WHERE句）
		// @params bool   ORDER BY句を付与するか否か（true:付与する, false:付与しない）
		//
		// @return string プラン情報を取得する為のSQL文
		//======================================================================
		public function get_sql_plan_base($as_where, $ab_is_orderby=true)
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
				//isempty->isnullへ
				if ( is_null($as_where) ) {
					throw new Exception('「プラン抽出の為のWHERE句」が指定されていません。');
				}
				
				//--------------------------------------------------------------
				// 特殊な提携先の取得方法を設定
				// [0:非対象, 1:対象]
				//--------------------------------------------------------------
				foreach ( $this->a_special_partners as $s_ptn_nm => $s_ptn_cd ) {
					$s_select .= ", max(decode(partner_group_join.partner_cd, '" . $s_ptn_cd . "', 1, 0)) as is_" . $s_ptn_nm;
				}
				
				//--------------------------------------------------------------
				// 引数によって式を指定
				//--------------------------------------------------------------
				// ORDER BY句の有無
				if ( $ab_is_orderby ) {
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
							to_char(plan_point_add.accept_s_ymd, 'YYYY-MM-DD') || ' 00:00' as accept_s_ymd,
							to_char(plan_point_add.accept_e_ymd, 'YYYY-MM-DD') || ' 23:59' as accept_e_ymd,
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
										max(decode(plan_spec.element_id, 4, element_value_id, -1)) as meal -- 食事
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
																			nvl(plan.label_cd, plan.plan_id) as pms_cd,
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
				echo $s_sql;
				return $s_sql;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 販売期間の選択項目情報を取得
		//======================================================================
		public function get_accept_ymd_selecter()
		{
			try {
				return $this->a_accept_ymd_selecter;
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
		//======================================================================
		// 販売期間の選択項目情報を生成
		//======================================================================
		private function _make_accept_ymd_selecter()
		{
			try {
				$n_start_year = 2000;                 // 過去データとの互換性維持の為2000年開始とする
				$n_now_year   = ((int)date('Y')) + 2; // 2年先まで選択可能
				
				for ( $ii = $n_start_year; $ii <= $n_now_year; $ii++ ) {
					$this->a_accept_ymd_selecter['year'][$ii] = $ii;
				}
				
				for ( $ii = 1; $ii <= 12; $ii++ ) {
					$this->a_accept_ymd_selecter['month'][$ii] = $ii;
				}
				
				for ( $ii = 1; $ii <= 31; $ii++ ) {
					$this->a_accept_ymd_selecter['day'][$ii] = $ii;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	} //-->
?>