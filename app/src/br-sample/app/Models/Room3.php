<?php
namespace App\Models;
	use App\Models\common\CommonDBModel;
	use App\Models\common\ValidationColumn;
	use Illuminate\Support\Facades\DB;
	use App\Common\Traits;

	class Room3 extends CommonDBModel
	{
		use Traits;

		// 部屋スペックの定義
		const ROOM_SPEC_BATH   = 1; // 風呂
		const ROOM_SPEC_TOILET = 2; // トイレ
		const ROOM_SPEC_SMOKE  = 3; // 禁煙/喫煙
		
		
		// メンバ変数の定義
		protected $o_box;
		private $s_hotel_cd;
		protected $s_plan_id;
		protected $s_room_id;
		protected $o_oracle;
		
		//======================================================================
		// コンストラクタ
		//======================================================================
		public function __construct()
		{
			// try {
			// 	// boxの生成
			// 	$o_controller = Zend_Controller_Front::getInstance();
			// 	$this->o_box = & $o_controller->getPlugin('Box')->box;
				
			// 	// インスタンス生成
			// 	$this->o_oracle = _Oracle::getInstance();
				
			// } catch (Exception $e) {
			// 	throw $e;
			// }
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
		// 部屋の詳細情報を取得
		//======================================================================
		public function get_detail()
		{
			try {
				//--------------------------------------------------------------
				// エラーチェック
				//--------------------------------------------------------------
				// 施設コード
				//is_empty->is_null
				if ( is_null($this->s_hotel_cd) ) {
					throw new Exception('施設コードを設定してください。');
				}
				//is_empty->is_null
				// プランID
				if ( is_null($this->s_room_id) ) {
					throw new Exception('部屋IDを設定してください。');
				}
				
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'room_id'  => $this->s_room_id
				);
				
				$a_result = array();
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				// 部屋の抽出条件を指定
				$s_where =
<<< SQL_WHERE
					where	room2.hotel_cd = :hotel_cd
						and	room2.room_id  = :room_id
SQL_WHERE;
				$s_sql = $this->get_sql_room_base($s_where);
				
				$a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);
				
				return $a_rows[0];
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 施設の保持する管理画面上有効な部屋の詳細情報を取得
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
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd
				);
				
				$a_results = array();
				
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
				$s_sql = $this->get_sql_room_base($s_where);
				
				// $a_rows = $this->o_oracle->find_by_sql($s_sql, $a_conditions);

				$a_rows = DB::select($s_sql, $a_conditions);

				//--------------------------------------------------------------
				// 配列のキーが部屋IDになるように整形
				//--------------------------------------------------------------
				foreach ( $a_rows ?? array() as $a_row ) {
					$a_results[ $a_row->{'room_id'}] = $a_row;
				}
				// $a_results = json_decode(json_encode($a_result),true);
				return $a_results;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 部屋情報（管理画面上でオレンジ枠で表現されるもの）を取得するSQL文を取得。
		//
		// @params string 部屋の抽出条件（WHERE句）
		// @params bool   ORDER BY句を付与するか否か（true:付与する, false:付与しない）
		//
		// @return string 部屋情報を取得する為のSQL文
		//======================================================================
		public function get_sql_room_base($as_where, $ab_is_orderby=true)
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
				if ( is_null($as_where) ) {
					throw new Exception('「部屋抽出の為のWHERE句」が指定されていません。');
				}
				
				//--------------------------------------------------------------
				// 引数によって式を指定
				//--------------------------------------------------------------
				// ORDER BY句の有無
				if ( $ab_is_orderby ) {
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
							substr(room_akafu_relation.roomtype_cd, -6) as akafu_cd,
							case
								when room_akafu_relation.roomtype_cd is not null then 1
								else 0
                            end as is_akafu
					from	room_akafu_relation 
					right outer join(
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
								from	room_network2
										inner join(
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
											from	room_spec2
													inner join(
														select	room2.hotel_cd,
																room2.room_id,
																case 
																	when room2.label_cd is null then room2.room_id end 
																as pms_cd,
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
																end as def_capacity_max,
																room2.room_type,
																room2.accept_status
														from	room2
														{$as_where}
													) rooms
											on	room_spec2.hotel_cd = rooms.hotel_cd
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
								on	room_network2.hotel_cd = room_specs_add.hotel_cd
									and	room_network2.room_id  = room_specs_add.room_id
							) network_add
					on	room_akafu_relation.hotel_cd = network_add.hotel_cd
						and	room_akafu_relation.room_id  = network_add.room_id
					{$s_order_by}
SQL;
				return $s_sql;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
	} //-->
?>