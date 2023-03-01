<?php
namespace App\Models;
	use App\Common\Traits;
	use App\Models\common\CommonDBModel;
	use App\Models\common\ValidationColumn;
    use App\Models\RecordObject;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\DB;
	use Exception;
	
	class Charge3 extends Model
	{
		use Traits;
		// メンバ変数の定義
		protected $o_box;
		protected $o_oracle;
		protected $s_hotel_cd;
		protected $s_plan_id;
		protected $s_room_id;
		protected $s_from_ymd;
		protected $s_to_ymd;
		protected $a_charges;
		protected $_a_attributes;
		protected $_a_find_charge;
		protected $_a_exists_charge_farthest_ymd;
		protected $_o_models_record_object;
		protected $_o_models_date;
		
		//======================================================================
		// コンストラクタ
		//======================================================================
		// function __construct()
		// {
		// 	try {
		// 		// インスタンス生成
		// 		$this->o_oracle = _Oracle::getInstance();
		// 		$this->_o_models_record_object = new Models_Record_Object(Charge::getInstance());
		// 		$this->_o_models_date = new Br_Models_Date();
				
		// 		$this->o_box = Zend_Controller_Front::getInstance()->getPlugin('Box')->box;
		// 	} catch (Exception $e) {
		// 		throw $e;
		// 	}
		// }
		
		//======================================================================
		// メンバ変数をクリアー
		//======================================================================
		public function clear()
		{
			try {
				// 料金データを初期化
				$this->a_charges = array();
				
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
		// Getter：料金
		//
		// @param Array 料金データ（人数、日付、販売料金）
		//======================================================================
		public function get_charges()
		{
			return $this->a_charges;
		}
		
		//======================================================================
		// Setter：対象期間の開始日
		//======================================================================
		public function set_from_ymd($as_from_ymd)
		{
			try {
				// エラーチェック
				if ( !is_numeric($as_from_ymd) and !is_string($as_from_ymd) ) {
					// エラーとする
					// ※例外を投げてもメンテ画面が表示されるだけで
					//   詳細がわからないので処理を停止するようにしています。
					throw new Exception('開始日付に' . gettype($as_from_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_from_ymd) ) {
					$as_from_ymd = strtotime($as_from_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_from_ymd), date('d', $as_from_ymd), date('Y', $as_from_ymd)) ) {
					echo "開始日付が日付として正しくありません。";
					exit;
				}
				
				// 指定された日付を設定
				$this->s_from_ymd = date('Y-m-d', $as_from_ymd);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// Setter：対象期間の終了日
		//======================================================================
		public function set_to_ymd($as_to_ymd)
		{
			try {
				// エラーチェック
				if ( !is_numeric($as_to_ymd) and !is_string($as_to_ymd) ) {
					// エラーとする
					// ※例外を投げてもメンテ画面が表示されるだけで
					//   詳細がわからないので処理を停止するようにしています。
					throw new Exception('開始日付に' . gettype($as_to_ymd) . 'が指定されています。数値または文字列(Y-m-d形式)で指定してください。');
				}
				
				// 文字列の場合
				if ( is_string($as_to_ymd) ) {
					$as_to_ymd = strtotime($as_to_ymd);
				}
				
				// 入力された日付が日付として正しくない場合はエラー
				if ( !checkdate(date('m', $as_to_ymd), date('d', $as_to_ymd), date('Y', $as_to_ymd)) ) {
					echo "開始日付が日付として正しくありません。";
					exit;
				}
				
				// 指定された日付を設定
				$this->s_to_ymd = date('Y-m-d', $as_to_ymd);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 指定した期間の施設・プラン・部屋の料金を取得
		//======================================================================
		public function make_from_to_ymd_charges()
		{
			try {
				//--------------------------------------------------------------
				// 初期化
				//--------------------------------------------------------------
				$this->a_charges = array();
				// シティホテル、旅館は5000円以下の料金登録でアラート、ビジネスホテル、カプセルホテルは1000円
				if ($this->o_box->user->hotel['hotel_category'] == 'c' or $this->o_box->user->hotel['hotel_category'] == 'j') {
					$n_alert_charge = 5000;
				} else {
					$n_alert_charge = 1000;
				}
				
				//--------------------------------------------------------------
				// データ取得
				//--------------------------------------------------------------
				$a_conditions = array(
					'from_ymd'     => $this->s_from_ymd,
					'to_ymd'       => $this->s_to_ymd,
					'hotel_cd'     => $this->s_hotel_cd,
					'plan_id'      => $this->s_plan_id,
					'room_id'      => $this->s_room_id,
					'alert_charge' => $n_alert_charge
				);
				
				$s_sql =
<<< SQL
					-- 整形
					select	to_char(q3.date_ymd, 'YYYY-MM-DD') as date_ymd,
							q3.capacity,
							q3.sales_charge,
							q3.is_low_price
					from	(
								-- 日程に料金を紐づける
								select	c.date_ymd,
										c.capacity,
										case
											when 0 < c.sales_charge and c.sales_charge < :alert_charge then
												1
											else
												0
										end as is_low_price,
										case
											when q2.charge_type = 0 then
												-- 料金タイプがRCの時はMC料金から変換する
												c.sales_charge * c.capacity + c.sales_charge_revise
											else
												-- 料金タイプがMCの時はそのまま
												c.sales_charge
										end as sales_charge
								from	charge c,
										(
											-- プランの料金タイプを取得
											select	p.hotel_cd,
													p.plan_id,
													p.charge_type,
													q1.partner_group_id
											from	plan p,
													(
														-- プランの販売先グループIDを１つに絞る
														select	ppg.hotel_cd,
																ppg.plan_id,
																min(ppg.partner_group_id) as partner_group_id
														from	plan_partner_group ppg
														where	ppg.hotel_cd = :hotel_cd
															and	ppg.plan_id  = :plan_id
														group by	ppg.hotel_cd,
																	ppg.plan_id
													) q1
											where	p.hotel_cd = q1.hotel_cd
												and	p.plan_id  = q1.plan_id
										) q2
								where	c.hotel_cd = q2.hotel_cd
									and	c.plan_id  = q2.plan_id
									and	c.room_id  = :room_id
									and	c.partner_group_id = q2.partner_group_id
									and	c.date_ymd between to_date(:from_ymd, 'YYYY-MM-DD') and to_date(:to_ymd, 'YYYY-MM-DD')
							) q3
					-- ソート順は宿泊日、人数
					order by	q3.date_ymd,
								q3.capacity
SQL;
				$this->a_charges = nvl($this->o_oracle->find_by_sql($s_sql, $a_conditions), array());
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// レコードの取得
		//
		//======================================================================
		public function find()
		{
			try {
				// プライマリキーの設定
				$this->_o_models_record_object->set_attributes_key_value('hotel_cd',         $this->_a_attributes['hotel_cd']);
				$this->_o_models_record_object->set_attributes_key_value('plan_id',          $this->_a_attributes['plan_id']);
				$this->_o_models_record_object->set_attributes_key_value('room_id',          $this->_a_attributes['room_id']);
				$this->_o_models_record_object->set_attributes_key_value('partner_group_id', $this->_a_attributes['partner_group_id']);
				$this->_o_models_record_object->set_attributes_key_value('capacity',         $this->_a_attributes['capacity']);
				$this->_o_models_record_object->set_attributes_key_value('date_ymd',         $this->_a_attributes['date_ymd']);
				
				// レコードを検索
				$this->_a_find_child_charge = $this->_o_models_record_object->find();
				
				return $this->_a_find_child_charge;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// データの設定
		//
		// @params String フィールド名
		//         mixed  値
		//
		//======================================================================
		public function set_attributes_key_value($as_key_nm, $am_value)
		{
			try {
				$this->_a_attributes[ $as_key_nm ] = $am_value;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// ======================================================================
		// レコードの登録
		
		// ======================================================================
		public function save(array $options = [])
		{
			try {
				// 登録するデータを設定
				foreach ( nvl($this->_a_attributes, array()) as $key => $value ) {
					$this->_o_models_record_object->set_attributes_key_value($key, $value);
				}
				
				// 登録
				if ( !$this->_o_models_record_object->save() ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// レコードの更新
		//
		//======================================================================
		public function update(array $attributes = [], array $options = [])
		{
			try {
				// 登録するデータを設定
				foreach ( nvl($this->_a_attributes, array()) as $key => $value ) {
					$this->_o_models_record_object->set_attributes_key_value($key, $value);
				}
				
				// 登録
				if ( !$this->_o_models_record_object->update() ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// レコードの削除
		//
		//======================================================================
		public static function destroy()
		{
			try {
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// レコードがなければ登録、あれば更新
		//
		// @return boolean 処理結果（true：成功, false：失敗）
		//
		//======================================================================
		public function save_on_duplicate_update()
		{
			try {
				// 登録するデータを設定
				foreach ( nvl($this->_a_attributes, array()) as $key => $value ) {
					$this->_o_models_record_object->set_attributes_key_value($key, $value);
				}
				
				return $this->_o_models_record_object->save_on_duplicate_update();
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
		//======================================================================
		// 対象のプランの料金登録のある最遠の年月日を取得
		//
		// @return Array 年月日情報
		//
		//======================================================================
		public function get_exists_charge_farthest_ymd()
		{
			try {
				// 初期化
				$a_conditions = array(
					'hotel_cd' => $this->s_hotel_cd,
					'plan_id'  => $this->s_plan_id
				);
				
				$a_define_day_of_week = array('日', '月', '火', '水', '木', '金', '土');
				
				$s_sql =
<<< SQL
					select	plan_id,
							room_id,
							to_char(max(date_ymd), 'YYYY-MM-DD') as date_ymd
					from	charge
					where	hotel_cd = :hotel_cd
						and	plan_id  = :plan_id
						and	date_ymd >= trunc(sysdate, 'DD')
					group by	plan_id,
								room_id
					order by	plan_id,
								room_id
SQL;
				$a_rows = nvl($this->o_oracle->find_by_sql($s_sql, $a_conditions), array());
				
				// 整形
				foreach ( $a_rows as $a_row ) {
					$this->_o_models_date->set($a_row['date_ymd']);
					$this->_a_exists_charge_farthest_ymd[ $a_row['room_id'] ]['ymd']     = $a_row['date_ymd'];
					$this->_a_exists_charge_farthest_ymd[ $a_row['room_id'] ]['ymd_num'] = $this->_o_models_date->get();
					$this->_a_exists_charge_farthest_ymd[ $a_row['room_id'] ]['ymd_str'] = date('Y', $this->_o_models_date->get()) . '年' . ltrim(date('m', $this->_o_models_date->get()), '0') . '月' . ltrim(date('d', $this->_o_models_date->get()), '0') . '日';
					$this->_a_exists_charge_farthest_ymd[ $a_row['room_id'] ]['dow_num'] = (int)date('w', $this->_o_models_date->get());
					$this->_a_exists_charge_farthest_ymd[ $a_row['room_id'] ]['dow_str'] = $a_define_day_of_week[$this->_a_exists_charge_farthest_ymd[ $a_row['room_id'] ]['dow_num']];
				}
				
				return $this->_a_exists_charge_farthest_ymd;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		
	} //-->
?>