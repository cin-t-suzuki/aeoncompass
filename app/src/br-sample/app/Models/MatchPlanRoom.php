<?php
namespace App\Models;

	use App\Common\Traits;
	use App\Models\common\CommonDBModel;
	use App\Models\common\ValidationColumn;
	use Illuminate\Support\Facades\DB;

	class MatchPlanRoom extends CommonDBModel
	{
		// メンバ変数定義
		protected $s_hotel_cd;
		protected $s_plan_id;
		protected $s_room_id;
		protected $o_oracle;
		
		//======================================================================
		// コンストラクタ
		//======================================================================
		// public function __construct()
		// {
		// 	try {
		// 		// 初期化
		// 		$this->s_hotel_cd = null;
		// 		$this->s_plan_id = null;
		// 		$this->s_room_id = null;
		// 		$this->o_oracle = _Oracle::getInstance();
				
		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }
		
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
		// 対象のプランに紐づく部屋IDをすべて取得
		//======================================================================
		public function get_match_plan_rooms()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'plan_id'  => $this->s_plan_id
				);
				
				$s_where =
<<< WHERE
					where	room_plan_match.hotel_cd = :hotel_cd
						and	room_plan_match.plan_id  = :plan_id
WHERE;
				
				$s_sql = $this->get_sql_match_plan_room_base($s_where);
				
				$match_result = [];

				//--------------------------------------------------------------
				// 結果取得
				//--------------------------------------------------------------
				// return nvl($this->o_oracle->find_by_sql($s_sql, $a_conditions), array());
				//取得結果がnullだった場合は空の配列を返す。
				$match_result = DB::select($s_sql, $a_conditions);
				if($match_result == null){
					$match_result = array();
				}
				$match_results = json_decode(json_encode($match_result),true);
				return $match_results;
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 対象の部屋が紐づくプランIDをすべて取得
		//======================================================================
		public function get_match_room_plans()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'room_id'  => $this->s_room_id
				);
				
				$s_where =
<<< WHERE
					where	room_plan_match.hotel_cd = :hotel_cd
						and	room_plan_match.room_id  = :room_id
WHERE;

				$s_sql = $this->get_sql_match_plan_room_base($s_where);
				
				//--------------------------------------------------------------
				// 結果取得
				//--------------------------------------------------------------
				return nvl($this->o_oracle->find_by_sql($s_sql, $a_conditions), array());
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 施設のすべての部屋が紐づくプランIDをすべて取得
		//======================================================================
		public function get_match_room_plans_all()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd
				);
				
				$a_result = array();
				
				$s_where =
<<< WHERE
					where	room_plan_match.hotel_cd = :hotel_cd
WHERE;

				$s_sql = $this->get_sql_match_plan_room_base($s_where);
				
				//--------------------------------------------------------------
				// 結果取得
				//--------------------------------------------------------------
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				// 整形
				foreach ( nvl($a_rows, array()) as $a_row ) {
					$a_result[ $a_row['room_id'] ][] = $a_row['plan_id'];
				}
				
				return $a_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 施設のすべてのプランに紐づく部屋IDをすべて取得
		//======================================================================
		public function get_match_plan_rooms_all()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd
				);
				
				$a_result = array();
				
				$s_where =
<<< WHERE
					where	room_plan_match.hotel_cd = :hotel_cd
WHERE;

				$s_sql = $this->get_sql_match_plan_room_base($s_where);
				
				//--------------------------------------------------------------
				// 結果取得
				//--------------------------------------------------------------
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				// 整形
				foreach ( nvl($a_rows, array()) as $a_row ) {
					$a_result[ $a_row['plan_id'] ][] = $a_row['room_id'];
				}
				
				return $a_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プランと部屋の組合せ一覧を取得するベースとなるSQL文を取得
		//
		// @params string 抽出条件（WHERE句）
		//
		// @return string SQL文
		//======================================================================
		public function get_sql_match_plan_room_base($as_where)
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
				if ( is_null($as_where) ) {
					throw new Exception('「抽出条件のWHERE句」が指定されていません。');
				}
				
				$s_sql =
<<< SQL
					select	room2.hotel_cd,
							room2.room_id,
							exists_plan.plan_id,
							exists_plan.order_no_plan,
							exists_plan.modify_ts
					from	room2
							inner join(
								select	plan.hotel_cd,
										plan.plan_id,
										plan.order_no as order_no_plan,
										plan.modify_ts,
										extract_match.room_id
								from	plan
										inner join(
											select	room_plan_match.hotel_cd,
													room_plan_match.plan_id,
													room_plan_match.room_id
											from	room_plan_match
											{$as_where}
										) extract_match
								on	plan.hotel_cd = extract_match.hotel_cd
									and	plan.plan_id  = extract_match.plan_id
									and	plan.display_status = 1
									and	plan.active_status  = 1
							) exists_plan
					on	room2.hotel_cd = exists_plan.hotel_cd
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
		
		//======================================================================
		// 部屋・プラン別の指定期間の予約数を取得（プラン基準）
		//
		// @params string 開始日
		//         string 終了日
		//
		// @return array 指定期間の各部屋プラン別の予約数
		//======================================================================
		public function get_from_to_reserve_count_plan_room($as_from_ymd, $as_to_ymd)
		{
			try {
				// 初期化
				$a_base_reserve_count = array();
				$a_result             = array();
				
				// 指定期間内の販売状況を判断するための情報を取得s
				$a_base_reserve_count = $this->_get_from_to_reserve_count($as_from_ymd, $as_to_ymd);
				
				// 日付基準の形に整形
				foreach ( nvl($a_base_reserve_count, array()) as $a_reserve_count ) {
					// キー情報を設定
					$s_room_id = $a_reserve_count['room_id'];
					$s_plan_id = $a_reserve_count['plan_id'];
					$n_ymd     = strtotime($a_reserve_count['date_ymd']);
					
					// 日別の予約数合計
					$a_result[$n_ymd]['reserve_count_sum'] = nvl($a_result[$n_ymd]['reserve_count_sum'], 0) + $a_reserve_count['reserve_count'];
					
					// 日別・部屋・プランの予約数
					$a_result[$n_ymd]['plan'][$s_plan_id]['reserve_count'] = $a_reserve_count['reserve_count'];
				}
				
				return $a_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 部屋・プラン別の指定期間の予約数を取得（部屋基準）
		//
		// @params string 開始日
		//         string 終了日
		//
		// @return array 指定期間の各部屋プラン別の予約数
		//======================================================================
		public function get_from_to_reserve_count_room_plan($as_from_ymd, $as_to_ymd)
		{
			try {
				// 初期化
				$a_base_reserve_count = array();
				$a_result             = array();
				
				// 指定期間内の販売状況を判断するための情報を取得s
				$a_base_reserve_count = $this->_get_from_to_reserve_count($as_from_ymd, $as_to_ymd);
				
				// 日付基準の形に整形
				foreach ( nvl($a_base_reserve_count, array()) as $a_reserve_count ) {
					// キー情報を設定
					$s_room_id = $a_reserve_count['room_id'];
					$s_plan_id = $a_reserve_count['plan_id'];
					$n_ymd     = strtotime($a_reserve_count['date_ymd']);
					
					// 日別の予約数合計
					$a_result[$n_ymd]['reserve_count_sum'] = nvl($a_result[$n_ymd]['reserve_count_sum'], 0) + $a_reserve_count['reserve_count'];
					
					// 日別・部屋・プランの予約数
					$a_result[$n_ymd]['room_plan']['room'][$s_room_id]['plan'][$s_plan_id]['reserve_count'] = $a_reserve_count['reserve_count'];
				}
				
				return $a_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プランと部屋の指定期間の予約数を取得
		//
		// @params string 開始日
		//         string 終了日
		//
		// @return array 指定期間の各部屋プラン別の予約数
		//======================================================================
		private function _get_from_to_reserve_count($as_from_ymd, $as_to_ymd)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// 開始日が設定されているか
				if ( !is_numeric($as_from_ymd) and !is_string($as_from_ymd) ) {
					// エラーとする
					throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_from_ymd) ) {
					$as_from_ymd = strtotime($as_from_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd)) ) {
					throw new Exception('開始日付が日付として正しくありません。');
				}
				
				// 指定された日付を設定
				$as_from_ymd = date('Y-m-d', $as_from_ymd);
				
				// 終了日が設定されているか
				if ( !is_numeric($as_to_ymd) and !is_string($as_to_ymd) ) {
					// エラーとする
					throw new Exception('終了日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_to_ymd) ) {
					$as_to_ymd = strtotime($as_to_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd)) ) {
					throw new Exception('終了日付が日付として正しくありません。');
				}
				
				// 指定された日付を設定
				$as_to_ymd = date('Y-m-d', $as_to_ymd);
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'from_ymd' => $as_from_ymd,
					'to_ymd'   => $as_to_ymd
				);
				
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
														where	mc.date_ymd	between to_date(:from_ymd, 'YYYY-MM-DD')	and	to_date(:to_ymd, 'YYYY-MM-DD')
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
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				return $a_rows;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// プランと部屋の指定期間の販売状況を判断するための情報を取得(プラン基準)
		// ・「在庫数」、「料金」、「手仕舞」の状態
		//
		// @params string 開始日
		//         string 終了日
		//
		// @return array 指定期間のプランと部屋の販売状況
		//======================================================================
		public function get_from_to_sale_state_plan_room($as_from_ymd, $as_to_ymd)
		{
			try {
				// 初期化
				$a_base_sale_state = array();
				$a_temp            = array();
				$a_result          = array();
				
				// 指定期間内の販売状況を判断するための情報を取得
				$a_base_sale_state = $this->_get_from_to_sale_state($as_from_ymd, $as_to_ymd);
				
				// プラン基準の形に整形
				foreach ( nvl($a_base_sale_state, array()) as $a_sale_state ) {
					// キー情報を設定
					$s_room_id = $a_sale_state['room_id'];
					$s_plan_id = $a_sale_state['plan_id'];
					$n_ymd     = strtotime($a_sale_state['date_ymd']);
					
					// 販売停止フラグ
					$b_is_stop = false;
					
					if ( is_empty($s_room_id) or is_empty($s_plan_id) ) {
						continue;
					}
					
					// プランの手仕舞状態(対象プランの料金が1つでも販売されていれば手仕舞ではないとする)
					if ( is_empty($a_sale_state['accept_status_charge']) ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_without'] = true;
						$b_is_stop = true;
					} else if ( $a_sale_state['accept_status_charge'] == 1 ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_sale'] = true;
					} else {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_charge']['is_stop'] = true;
						$b_is_stop = true;
					}
					
					// プランの休止状態
					$a_temp[$n_ymd]['plan'][$s_plan_id]['accept_status_plan'] = $a_sale_state['accept_status_plan'];
					
					// 部屋情報のうち必要なものを取得
					$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['rooms']                    = $a_sale_state['rooms'];
					$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['remaining_rooms']          = $a_sale_state['remaining_rooms'];
					$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['accept_status_room']       = $a_sale_state['accept_status_room'];
					$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['accept_status_room_count'] = $a_sale_state['accept_status_room_count'];
					$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sales_charge']             = $a_sale_state['sales_charge'];
					
					//----------------------------------------------------------
					// 部屋が休止
					//----------------------------------------------------------
					if ( $a_sale_state['accept_status_room'] != 1 ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// プランが休止
					//----------------------------------------------------------
					if ( $a_sale_state['accept_status_plan'] != 1 ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_plan'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// プランの期間内で販売日時が経過
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_e_dtm']) and strtotime($a_sale_state['accept_e_dtm']) < strtotime('now') ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_expiration'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 部屋が手仕舞
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_status_room_count']) and $a_sale_state['accept_status_room_count'] != 1 ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room_count'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 料金が手仕舞
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_status_charge']) and ($a_sale_state['accept_status_charge'] != 1) ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_charge'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 満室
					//----------------------------------------------------------
					if ( ($a_sale_state['rooms'] > 0) and ($a_sale_state['remaining_rooms'] < 1) ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_full'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 販売がまだ開始されていない
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_s_dtm']) and strtotime($a_sale_state['accept_s_dtm']) > strtotime('now') ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_sale_still'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 再販なしの在庫0
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['rooms']) and $a_sale_state['rooms'] < 1 ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stock_zero'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 料金が0
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['sales_charge']) and ($a_sale_state['sales_charge'] < 1) ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_zero'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 料金の登録がない
					//----------------------------------------------------------
					if ( is_empty($a_sale_state['sales_charge']) ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_without'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 在庫の登録がない
					//----------------------------------------------------------
					if ( is_empty($a_sale_state['rooms']) ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stock_without'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 再販の可能性
					//----------------------------------------------------------
					if (     $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_full']
						 and $a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['accept_status_room'] == 1
						 and $a_sale_state['accept_status_charge'] == 1
						 and !$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_charge_zero']
						 and !$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_charge']
						 and !$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop_room_count']
					) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_resale'] = true;
					}
					
					//----------------------------------------------------------
					// 上記条件にあてはまらない場合は販売されているとする
					//----------------------------------------------------------
					if ( $b_is_stop ) {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_stop'] = true;
					} else {
						$a_temp[$n_ymd]['plan'][$s_plan_id]['room'][$s_room_id]['sale_status']['is_sale'] = true;
					}
				}
				
				// 日付でループ
				foreach ( nvl($a_temp, array()) as $n_ymd => $a_day_sale_state ) {
					// 日別のプランでループ
					foreach ( nvl($a_day_sale_state['plan'], array()) as $s_plan_id => $a_plan ) {
						$a_result[$n_ymd]['plan'][$s_plan_id] = $a_plan;
						
						// 日別・プランの部屋でループ
						foreach ( nvl($a_plan['room'], array()) as $s_room_id => $a_room) {
							// 料金または在庫が登録されていない
							if ( $a_room['sale_status']['is_charge_without'] or $a_room['sale_status']['is_stock_without'] ) {
								$a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_without'] = true;
							} else {
								// 販売開始前
								if ( $a_room['sale_status']['is_sale_still'] ) {
									$a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_sale_still'] = true;
								}
								
								// 販売終了
								if ( $a_room['sale_status']['is_expiration'] ) {
									$a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_expiration'] = true;
								}
								
								// 止（再販有）
								if ( $a_room['sale_status']['is_resale'] ) {
									$a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_resale'] = true;
								}
								
								// 売
								if ( $a_room['sale_status']['is_sale'] ) {
									$a_result[$n_ymd]['plan'][$s_plan_id]['sale_status']['is_sale'] = true;
								}
								
								
								
								// 上記条件郡に一致しなかった場合「止」
								// 止（再販無）
								if (    $a_plan['accept_status_plan'] != 1
									 or $a_room['accept_status_room'] != 1
									 or $a_room['sale_status']['is_stop_plan']
									 or $a_room['sale_status']['is_stop_room']
								     or $a_room['sale_status']['is_expiration']
								     or $a_room['sale_status']['is_stop_room_count']
								     or $a_room['sale_status']['is_stop_charge']
								     or $a_room['sale_status']['is_sale_still']
								     or $a_room['sale_status']['is_stock_zero']
								     or $a_room['sale_status']['is_charge_zero']
								     or $a_room['sale_status']['is_full']
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
		
		//======================================================================
		// プランと部屋の指定期間の販売状況を判断するための情報を取得(部屋基準)
		// ・「在庫数」、「料金」、「手仕舞」の状態
		//
		// @params string 開始日
		//         string 終了日
		//
		// @return array 指定期間のプランと部屋の販売状況
		//======================================================================
		public function get_from_to_sale_state_room_plan($as_from_ymd, $as_to_ymd)
		{
			try {
				// 初期化
				$a_base_sale_state = array();
				$a_temp            = array();
				$a_result          = array();
				
				// 指定期間内の販売状況を判断するための情報を取得
				$a_base_sale_state = $this->_get_from_to_sale_state($as_from_ymd, $as_to_ymd);
				
				// 部屋基準の形に整形
				foreach ( nvl($a_base_sale_state, array()) as $a_sale_state ) {
					// キー情報を設定
					$s_room_id = $a_sale_state['room_id'];
					$s_plan_id = $a_sale_state['plan_id'];
					$n_ymd     = strtotime($a_sale_state['date_ymd']);
					
					// 販売停止フラグ
					$b_is_stop = false;
					
					$a_temp[$n_ymd]['room'][$s_room_id]['rooms']                    = $a_sale_state['rooms'];
					$a_temp[$n_ymd]['room'][$s_room_id]['reserve_rooms']            = $a_sale_state['reserve_rooms'];
					$a_temp[$n_ymd]['room'][$s_room_id]['remaining_rooms']          = $a_sale_state['remaining_rooms'];
					$a_temp[$n_ymd]['room'][$s_room_id]['accept_status_room']       = $a_sale_state['accept_status_room'];
					$a_temp[$n_ymd]['room'][$s_room_id]['accept_status_room_count'] = $a_sale_state['accept_status_room_count'];
					
					if ( is_empty($s_plan_id)) {
						continue;
					}
					
					$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_status_plan']   = $a_sale_state['accept_status_plan'];
					$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_status_charge'] = $a_sale_state['accept_status_charge'];
					$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_s_dtm']         = strtotime($a_sale_state['accept_s_dtm']);
					$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_e_dtm']         = strtotime($a_sale_state['accept_e_dtm']);
					$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sales_charge']         = $a_sale_state['sales_charge'];
					
					//----------------------------------------------------------
					// 部屋が休止
					//----------------------------------------------------------
					if ( $a_sale_state['accept_status_room'] != 1 ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// プランが休止
					//----------------------------------------------------------
					if ( $a_sale_state['accept_status_plan'] != 1 ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_plan'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 販売日時が経過
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_e_dtm']) and strtotime($a_sale_state['accept_e_dtm']) < strtotime('now') ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_expiration'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 部屋が手仕舞
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_status_room_count']) and $a_sale_state['accept_status_room_count'] != 1 ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room_count'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 料金が手仕舞
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_status_charge']) and ($a_sale_state['accept_status_charge'] != 1) ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_charge'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 満室かどうか
					//----------------------------------------------------------
					if ( ($a_sale_state['rooms'] > 0) and ($a_sale_state['remaining_rooms'] < 1) ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_full'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 販売がまだ開始されていない
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['accept_s_dtm']) and strtotime($a_sale_state['accept_s_dtm']) > strtotime('now') ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_sale_still'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 料金が0
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['sales_charge']) and ($a_sale_state['sales_charge'] < 1) ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_zero'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 提供室数が0（再販なしの在庫0）
					//----------------------------------------------------------
					if ( !is_empty($a_sale_state['rooms']) and $a_sale_state['rooms'] < 1 ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stock_zero'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 料金の登録がない
					//----------------------------------------------------------
					if ( is_empty($a_sale_state['sales_charge']) ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_without'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 在庫の登録がない
					//----------------------------------------------------------
					if ( is_empty($a_sale_state['rooms']) ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stock_without'] = true;
						$b_is_stop = true;
					}
					
					//----------------------------------------------------------
					// 再販の可能性
					//----------------------------------------------------------
					if ( $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_full']
						 and $a_temp[$n_ymd]['room'][$s_room_id]['accept_status_room'] == 1
						 and $a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['accept_status_plan'] == 1
						 and !$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_charge_zero']
						 and !$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_charge']
						 and !$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop_room_count']
					) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_resale'] = true;
					}
					
					//----------------------------------------------------------
					// 上記条件にあてはまらない場合は販売されているとする
					//----------------------------------------------------------
					if ( $b_is_stop ) {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_stop'] = true;
					} else {
						$a_temp[$n_ymd]['room'][$s_room_id]['plan'][$s_plan_id]['sale_status']['is_sale'] = true;
					}
				}
				
				// 日付でループ
				foreach ( nvl($a_temp, array()) as $n_ymd => $a_day_sale_state ) {
					// 日別の部屋でループ
					foreach ( nvl($a_day_sale_state['room'], array()) as $s_room_id => $a_room ) {
						
						// 日別の提供室数合計を取得
						$a_result[$n_ymd]['rooms_sum'] = nvl($a_result[$n_ymd]['rooms_sum'], 0) + $a_room['rooms'];
						
						// 日別の残室数合計を取得
						$a_result[$n_ymd]['remaining_rooms_sum'] = nvl($a_result[$n_ymd]['remaining_rooms_sum'], 0) + $a_room['remaining_rooms'];
						
						$a_result[$n_ymd]['room'][$s_room_id] = $a_room;
						
						// 日別・部屋・プランでループ
						foreach ( nvl($a_room['plan'], array()) as $s_plan_id => $a_plan ) {
							// 料金または在庫が登録されていない
							if ( $a_plan['sale_status']['is_charge_without'] or $a_plan['sale_status']['is_stock_without'] ) {
								$a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_without'] = true;
								$a_result[$n_ymd]['sale_status']['is_without'] = true;
							} else {
								// 販売開始前
								if ( $a_plan['sale_status']['is_sale_still'] ) {
									$a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_sale_still'] = true;
									$a_result[$n_ymd]['sale_status']['is_sale_still'] = true;
								}
								
								// 販売終了
								if ( $a_plan['sale_status']['is_expiration'] ) {
									$a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_expiration'] = true;
									$a_result[$n_ymd]['sale_status']['is_expiration'] = true;
								}
								
								// 止（再販有）
								if ( $a_plan['sale_status']['is_resale'] ) {
									$a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_resale'] = true;
									$a_result[$n_ymd]['sale_status']['is_resale'] = true;
								}
								
								// 売
								if ( $a_plan['sale_status']['is_sale'] ) {
									$a_result[$n_ymd]['room'][$s_room_id]['sale_status']['is_sale'] = true;
									$a_result[$n_ymd]['sale_status']['is_sale'] = true;
								}
								
								// 止
								if (    $a_room['accept_status_room'] != 1
								     or $a_plan['accept_status_plan'] != 1
								     or $a_plan['sale_status']['is_expiration']
								     or $a_plan['sale_status']['is_stop_room_count']
								     or $a_plan['sale_status']['is_stop_charge']
								     or $a_plan['sale_status']['is_sale_still']
								     or $a_plan['sale_status']['is_stock_zero']
								     or $a_plan['sale_status']['is_charge_zero']
								     or $a_plan['sale_status']['is_full']
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
		
		//======================================================================
		// プランと部屋の指定期間の販売状況を判断するための情報を取得
		// ・「在庫数」、「料金」、「手仕舞」の状態
		//======================================================================
		private function _get_from_to_sale_state($as_from_ymd, $as_to_ymd)
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				if ( is_empty($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				
				// 開始日が設定されているか
				if ( !is_numeric($as_from_ymd) and !is_string($as_from_ymd) ) {
					// エラーとする
					throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_from_ymd) ) {
					$as_from_ymd = strtotime($as_from_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd)) ) {
					throw new Exception('開始日付が日付として正しくありません。');
				}
				
				// 指定された日付を設定
				$as_from_ymd = date('Y-m-d', $as_from_ymd);
				
				// 終了日が設定されているか
				if ( !is_numeric($as_to_ymd) and !is_string($as_to_ymd) ) {
					// エラーとする
					throw new Exception('終了日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_to_ymd) ) {
					$as_to_ymd = strtotime($as_to_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd)) ) {
					throw new Exception('終了日付が日付として正しくありません。');
				}
				
				// 指定された日付を設定
				$as_to_ymd = date('Y-m-d', $as_to_ymd);
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'from_ymd' => $as_from_ymd,
					'to_ymd'   => $as_to_ymd
				);
				
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
							to_char(c.accept_s_dtm, 'YYYY-MM-DD HH24:MI:SS') as accept_s_dtm,
							to_char(c.accept_e_dtm, 'YYYY-MM-DD HH24:MI:SS') as accept_e_dtm
					from	charge c,
							(
								select	q4.hotel_cd,
										q4.room_id,
										q4.accept_status_room,
										q4.plan_id,
										q4.accept_status_plan,
										q4.date_ymd,
										rc2.rooms,
										rc2.reserve_rooms,
										rc2.rooms - rc2.reserve_rooms as remaining_rooms,
										rc2.accept_status as accept_status_room_count
								from	room_count2 rc2,
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
														from	plan p,
																(
																	select	q1.hotel_cd,
																			q1.room_id,
																			q1.accept_status_room,
																			rpm.plan_id
																	from	room_plan_match rpm,
																			(
																				select	r2.hotel_cd,
																						r2.room_id,
																						r2.accept_status as accept_status_room
																				from	room2 r2
																				where	r2.hotel_cd = :hotel_cd
																					and	r2.display_status = 1
																					and	r2.active_status  = 1
																			) q1
																	where	rpm.hotel_cd(+) = q1.hotel_cd
																		and	rpm.room_id(+)  = q1.room_id
																) q2
														where	p.hotel_cd(+) = q2.hotel_cd
															and	p.plan_id(+)  = q2.plan_id
															and	p.display_status(+) = 1
															and	p.active_status(+)  = 1
													) q3
											where	mc.date_ymd	between to_date(:from_ymd, 'YYYY-MM-DD') and to_date(:to_ymd, 'YYYY-MM-DD')
										) q4
								where	rc2.hotel_cd(+) = q4.hotel_cd
									and	rc2.room_id(+)  = q4.room_id
									and	rc2.date_ymd(+) = q4.date_ymd
							) q5
					where	c.hotel_cd(+) = q5.hotel_cd
						and	c.plan_id(+)  = q5.plan_id
						and	c.room_id(+)  = q5.room_id
						and	c.date_ymd(+) = q5.date_ymd
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
				
				return nvl($this->o_oracle->find_by_sql($s_sql, $a_conditions), array());
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	} //-->
?>