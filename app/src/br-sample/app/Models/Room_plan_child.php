<?php

    namespace App\Models;

    use App\Models\common\CommonDBModel;
    use App\Models\common\ValidationColumn;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\DB;

	class Room_Plan_Child extends CommonDBModel{

		// テーブル名称
		protected $table_name = 'plan_child_room';
        
        //カラム
        public string $COL_HOTEL_CD = 'hotel_cd';                                           
        public string $COL_ROOM_ID = 'room_id';                                    
        public string $COL_PLAN_ID = 'plan_id';                                             
        public string $COL_CHILD1_ACCEPT = 'child1_accept';                                
        public string $COL_CHILD2_ACCEPT = 'child2_accept';                             
        public string $COL_CHILD3_ACCEPT = 'child3_accept';                              
        public string $COL_CHILD4_ACCEPT = 'child4_accept';                                 
        public string $COL_CHILD5_ACCEPT = 'child5_accept';                                
        public string $COL_CHILD1_PERSON = 'child1_person';                                 
        public string $COL_CHILD2_PERSON ='child2_person';                         
        public string $COL_CHILD3_PERSON ='child3_person';                                 
        public string $COL_CHILD4_PERSON ='child4_person';                                  
        public string $COL_CHILD5_PERSON ='child5_person';                                  
        public string $COL_CHILD1_CHARGE_INCLUDE = 'child1_charge_include'; 
        public string $COL_CHILD2_CHARGE_INCLUDE = 'child2_charge_include'; 
        public string $COL_CHILD3_CHARGE_INCLUDE = 'child3_charge_include'; 
        public string $COL_CHILD4_CHARGE_INCLUDE = 'child4_charge_include'; 
        public string $COL_CHILD5_CHARGE_INCLUDE = 'child5_charge_include'; 
        public string $COL_CHILD1_CHARGE_UNIT = 'child1_charge_unit';   
        public string $COL_CHILD2_CHARGE_UNIT = 'child2_charge_unit';   
        public string $COL_CHILD3_CHARGE_UNIT = 'child3_charge_unit';   
        public string $COL_CHILD4_CHARGE_UNIT = 'child4_charge_unit';   
        public string $COL_CHILD5_CHARGE_UNIT = 'child5_charge_unit';   
        public string $COL_CHILD1_CHARGE = 'child1_charge';        
        public string $COL_CHILD2_CHARGE = 'child2_charge';        
        public string $COL_CHILD3_CHARGE = 'child3_charge';        
        public string $COL_CHILD4_CHARGE = 'child4_charge';         
        public string $COL_CHILD5_CHARGE = 'child5_charge';         
        public string $COL_CHILD1_RATE = 'child1_rate';         
        public string $COL_CHILD2_RATE = 'child2_rate';          
        public string $COL_CHILD3_RATE = 'child3_rate';           
        public string $COL_CHILD4_RATE = 'child4_rate';           
        public string $COL_CHILD5_RATE = 'child5_rate';

		// フィールド名称
		// テーブルの内容が変更された時修正、それ以外の変更は禁止します。
		// 画面へ表示するフィールド名称が変わった時は、コントローラ側で修正すること。
		protected $field_names = array(
									
								);

		// 暗号キー
		protected $cipher   = array(

								);
								

		function __construct(){

			parent::__construct();


			// 施設コード
			$this->validate_presence_of(array('hotel_cd'));                                            // 必須入力チェック
			$this->validate_kana_of(array('hotel_cd'));                                                // 半角カナチェック
			$this->validate_length_of('hotel_cd', array(0, 10));                                       // 長さチェック

			// 部屋ID
			$this->validate_presence_of(array('room_id'));                                             // 必須入力チェック
			$this->validate_kana_of(array('room_id'));                                                 // 半角カナチェック
			$this->validate_length_of('room_id', array(0, 10));                                        // 長さチェック

			// プランID
			$this->validate_presence_of(array('plan_id'));                                             // 必須入力チェック
			$this->validate_kana_of(array('plan_id'));                                                 // 半角カナチェック
			$this->validate_length_of('plan_id', array(0, 10));                                        // 長さチェック

			// 子供1部屋受入
			$this->validate_length_of('child1_accept', array(0, 1));                                   // 長さチェック
			$this->validate_numericality_of('child1_accept', array('only_integer' => true));           // 数字：数値チェック

			// 子供2部屋受入
			$this->validate_length_of('child2_accept', array(0, 1));                                   // 長さチェック
			$this->validate_numericality_of('child2_accept', array('only_integer' => true));           // 数字：数値チェック

			// 子供3部屋受入
			$this->validate_length_of('child3_accept', array(0, 1));                                   // 長さチェック
			$this->validate_numericality_of('child3_accept', array('only_integer' => true));           // 数字：数値チェック

			// 子供4部屋受入
			$this->validate_length_of('child4_accept', array(0, 1));                                   // 長さチェック
			$this->validate_numericality_of('child4_accept', array('only_integer' => true));           // 数字：数値チェック

			// 子供5部屋受入
			$this->validate_length_of('child5_accept', array(0, 1));                                   // 長さチェック
			$this->validate_numericality_of('child5_accept', array('only_integer' => true));           // 数字：数値チェック

			// 子供1部屋人数係数
			$this->validate_length_of('child1_person', array(0, 2));                                   // 長さチェック
			$this->validate_numericality_of('child1_person', array('only_integer' => true));           // 数字：数値チェック

			// 子供2部屋人数係数
			$this->validate_length_of('child2_person', array(0, 2));                                   // 長さチェック
			$this->validate_numericality_of('child2_person', array('only_integer' => true));           // 数字：数値チェック

			// 子供3部屋人数係数
			$this->validate_length_of('child3_person', array(0, 2));                                   // 長さチェック
			$this->validate_numericality_of('child3_person', array('only_integer' => true));           // 数字：数値チェック

			// 子供4部屋人数係数
			$this->validate_length_of('child4_person', array(0, 2));                                   // 長さチェック
			$this->validate_numericality_of('child4_person', array('only_integer' => true));           // 数字：数値チェック

			// 子供5部屋人数係数
			$this->validate_length_of('child5_person', array(0, 2));                                   // 長さチェック
			$this->validate_numericality_of('child5_person', array('only_integer' => true));           // 数字：数値チェック

			// 子供1料金計算時の定員に含める
			$this->validate_length_of('child1_charge_include', array(0, 1));                           // 長さチェック
			$this->validate_numericality_of('child1_charge_include', array('only_integer' => true));   // 数字：数値チェック

			// 子供2料金計算時の定員に含める
			$this->validate_length_of('child2_charge_include', array(0, 1));                           // 長さチェック
			$this->validate_numericality_of('child2_charge_include', array('only_integer' => true));   // 数字：数値チェック

			// 子供3料金計算時の定員に含める
			$this->validate_length_of('child3_charge_include', array(0, 1));                           // 長さチェック
			$this->validate_numericality_of('child3_charge_include', array('only_integer' => true));   // 数字：数値チェック

			// 子供4料金計算時の定員に含める
			$this->validate_length_of('child4_charge_include', array(0, 1));                           // 長さチェック
			$this->validate_numericality_of('child4_charge_include', array('only_integer' => true));   // 数字：数値チェック

			// 子供5料金計算時の定員に含める
			$this->validate_length_of('child5_charge_include', array(0, 1));                           // 長さチェック
			$this->validate_numericality_of('child5_charge_include', array('only_integer' => true));   // 数字：数値チェック

			// 子供1料金単位
			$this->validate_length_of('child1_charge_unit', array(0, 1));                              // 長さチェック
			$this->validate_numericality_of('child1_charge_unit', array('only_integer' => true));      // 数字：数値チェック

			// 子供2料金単位
			$this->validate_length_of('child2_charge_unit', array(0, 1));                              // 長さチェック
			$this->validate_numericality_of('child2_charge_unit', array('only_integer' => true));      // 数字：数値チェック

			// 子供3料金単位
			$this->validate_length_of('child3_charge_unit', array(0, 1));                              // 長さチェック
			$this->validate_numericality_of('child3_charge_unit', array('only_integer' => true));      // 数字：数値チェック

			// 子供4料金単位
			$this->validate_length_of('child4_charge_unit', array(0, 1));                              // 長さチェック
			$this->validate_numericality_of('child4_charge_unit', array('only_integer' => true));      // 数字：数値チェック

			// 子供5料金単位
			$this->validate_length_of('child5_charge_unit', array(0, 1));                              // 長さチェック
			$this->validate_numericality_of('child5_charge_unit', array('only_integer' => true));      // 数字：数値チェック

			// 子供1料金
			$this->validate_length_of('child1_charge', array(0, 7));                                   // 長さチェック
			$this->validate_numericality_of('child1_charge', array('only_integer' => true));           // 数字：数値チェック
			$this->validate_method_of('child1_charge', array('child1_charge_validate'));               // 独自チェック

			// 子供2料金
			$this->validate_length_of('child2_charge', array(0, 7));                                   // 長さチェック
			$this->validate_numericality_of('child2_charge', array('only_integer' => true));           // 数字：数値チェック
			$this->validate_method_of('child2_charge', array('child2_charge_validate'));               // 独自チェック

			// 子供3料金
			$this->validate_length_of('child3_charge', array(0, 7));                                   // 長さチェック
			$this->validate_numericality_of('child3_charge', array('only_integer' => true));           // 数字：数値チェック
			$this->validate_method_of('child3_charge', array('child3_charge_validate'));               // 独自チェック

			// 子供4料金
			$this->validate_length_of('child4_charge', array(0, 7));                                   // 長さチェック
			$this->validate_numericality_of('child4_charge', array('only_integer' => true));           // 数字：数値チェック
			$this->validate_method_of('child4_charge', array('child4_charge_validate'));               // 独自チェック

			// 子供5料金
			$this->validate_length_of('child5_charge', array(0, 7));                                   // 長さチェック
			$this->validate_numericality_of('child5_charge', array('only_integer' => true));           // 数字：数値チェック
			$this->validate_method_of('child5_charge', array('child5_charge_validate'));               // 独自チェック

			// 子供1率
			$this->validate_length_of('child1_rate', array(0, 3));                                     // 長さチェック
			$this->validate_numericality_of('child1_rate', array('only_integer' => true));             // 数字：数値チェック
			$this->validate_method_of('child1_rate', array('child1_rate_validate'));                   // 独自チェック

			// 子供2率
			$this->validate_length_of('child2_rate', array(0, 3));                                     // 長さチェック
			$this->validate_numericality_of('child2_rate', array('only_integer' => true));             // 数字：数値チェック
			$this->validate_method_of('child2_rate', array('child2_rate_validate'));                   // 独自チェック

			// 子供3率
			$this->validate_length_of('child3_rate', array(0, 3));                                     // 長さチェック
			$this->validate_numericality_of('child3_rate', array('only_integer' => true));             // 数字：数値チェック
			$this->validate_method_of('child3_rate', array('child3_rate_validate'));                   // 独自チェック

			// 子供4率
			$this->validate_length_of('child4_rate', array(0, 3));                                     // 長さチェック
			$this->validate_numericality_of('child4_rate', array('only_integer' => true));             // 数字：数値チェック
			$this->validate_method_of('child4_rate', array('child4_rate_validate'));                   // 独自チェック

			// 子供5率
			$this->validate_length_of('child5_rate', array(0, 3));                                     // 長さチェック
			$this->validate_numericality_of('child5_rate', array('only_integer' => true));             // 数字：数値チェック
			$this->validate_method_of('child5_rate', array('child5_rate_validate'));                   // 独自チェック


		}

		//=====================================================================
		// 子供料金「値段・差額」の独自バリデート
		//=====================================================================
		private function _child_charge_validate($as_child_type, $ao_column)
		{
			try {
				// 初期化
				$o_validator = Validations::getInstance($this->box);
				$o_plan      = new Plan();
				$s_error     = '';
				$a_find_plan = array();
				
				// 子供受け入れ状態が「受け入れる」 & 値段が空のとき
				if ( $this->_a_attributes[$as_child_type . '_accept'] == 1 and is_empty($this->_a_attributes[$as_child_type . '_charge']) ) {
					// プラン情報を取得
					$a_find_plan['hotel_cd'] = $this->_a_attributes['hotel_cd'];
					$a_find_plan['plan_id']  = $this->_a_attributes['plan_id'];
					$a_find_plan = $o_plan->find($a_find_plan);
					
					// マンチャージプランのときのみ
					if ( $a_find_plan['charge_type'] == 1 ) {
						// 料金単位が「1:金額」 or 「2:差額」のとき
						if ( $this->_a_attributes[$as_child_type . '_charge_unit'] == 1 or $this->_a_attributes[$as_child_type . '_charge_unit'] == 2 ) {
							$s_error = $ao_column->get_logical_nm() . 'の「値段・率」を入力して下さい。';
						}
					}
				}
				
				return $s_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供1
		public function child1_charge_validate()
		{
			try {
				
				$o_column = $this->get_column_object('child1_charge');
				
				$s_ret_error = $this->_child_charge_validate('child1', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供2
		public function child2_charge_validate()
		{
			try {
				
				$o_column = $this->get_column_object('child2_charge');
				
				$s_ret_error = $this->_child_charge_validate('child2', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供3
		public function child3_charge_validate()
		{
			try {
				// 画面上では子供4と項目名を入れ替えている為3ではなく4
				$o_column = $this->get_column_object('child4_charge');
				
				$s_ret_error = $this->_child_charge_validate('child3', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供4
		public function child4_charge_validate()
		{
			try {
				// 画面上では子供3と項目名を入れ替えている為4ではなく3
				$o_column = $this->get_column_object('child3_charge');
				
				$s_ret_error = $this->_child_charge_validate('child4', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供5
		public function child5_charge_validate()
		{
			try {
				
				$o_column = $this->get_column_object('child5_charge');
				
				$s_ret_error = $this->_child_charge_validate('child5', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//=====================================================================
		// 子供料金「率」の独自バリデート
		//=====================================================================
		private function _child_rate_validate($as_child_type, $ao_column)
		{
			try {
				// 初期化
				$o_validator = Validations::getInstance($this->box);
				$o_plan      = new Plan();
				$s_error     = '';
				$a_find_plan = array();
				
				// 子供受け入れ状態が「受け入れる」 & 率が空のとき
				if ( $this->_a_attributes[$as_child_type . '_accept'] == 1 and is_empty($this->_a_attributes[$as_child_type . '_rate']) ) {
					// プラン情報を取得
					$a_find_plan['hotel_cd'] = $this->_a_attributes['hotel_cd'];
					$a_find_plan['plan_id']  = $this->_a_attributes['plan_id'];
					$a_find_plan = $o_plan->find($a_find_plan);
					
					// マンチャージプランのときのみ
					if ( $a_find_plan['charge_type'] == 1 ) {
						// 料金単位が「1:金額」 or 「2:差額」以外のとき（「0:率」）
						if ( !($this->_a_attributes[$as_child_type . '_charge_unit'] == 1 or $this->_a_attributes[$as_child_type . '_charge_unit'] == 2) ) {
							$s_error = $ao_column->get_logical_nm() . 'の「値段・率」を入力して下さい。';
						}
					}
				}
				
				return $s_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供1
		public function child1_rate_validate()
		{
			try {
				
				$o_column = $this->get_column_object('child1_rate');
				
				$s_ret_error = $this->_child_rate_validate('child1', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供2
		public function child2_rate_validate()
		{
			try {
				$o_column = $this->get_column_object('child2_rate');
				
				$s_ret_error = $this->_child_rate_validate('child2', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供3
		public function child3_rate_validate()
		{
			try {
				// 画面上では子供4と項目名を入れ替えている為3ではなく4
				$o_column = $this->get_column_object('child4_rate');
				
				$s_ret_error = $this->_child_rate_validate('child3', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供4
		public function child4_rate_validate()
		{
			try {
				// 画面上では子供4と項目名を入れ替えている為4ではなく3
				$o_column = $this->get_column_object('child3_rate');
				
				$s_ret_error = $this->_child_rate_validate('child4', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		// 子供5
		public function child5_rate_validate()
		{
			try {
				
				
				$o_column = $this->get_column_object('child5_rate');
				
				$s_ret_error = $this->_child_rate_validate('child5', $o_column);
				
				return $s_ret_error;
				
			} catch (Exception $e) {
				throw $e;
			}
		}

		public function save() {

			$a_attributes = $this->_a_attributes;

			if (is_empty($a_attributes['child1_person'])){ $a_attributes['child1_person'] = 1;}
			if (is_empty($a_attributes['child2_person'])){ $a_attributes['child2_person'] = 1;}
			if (is_empty($a_attributes['child3_person'])){ $a_attributes['child3_person'] = 1;}
			if (is_empty($a_attributes['child4_person'])){ $a_attributes['child4_person'] = 0;}
			if (is_empty($a_attributes['child5_person'])){ $a_attributes['child5_person'] = 0;}

			$this->attributes($a_attributes);

			if (!parent::save()){
				return false;
			}

			return true;

		}


		public function update() {

			$a_attributes = $this->_a_attributes;

			if (is_empty($a_attributes['child1_person'])){ $a_attributes['child1_person'] = 1;}
			if (is_empty($a_attributes['child2_person'])){ $a_attributes['child2_person'] = 1;}
			if (is_empty($a_attributes['child3_person'])){ $a_attributes['child3_person'] = 1;}
			if (is_empty($a_attributes['child4_person'])){ $a_attributes['child4_person'] = 0;}
			if (is_empty($a_attributes['child5_person'])){ $a_attributes['child5_person'] = 0;}

			$this->attributes($a_attributes);

			if (!parent::update()){
				return false;
			}

			return true;

		}


		// シングルトンインスタンスを実装
		private static $_o_instance = null;
		public static function getInstance()
		{
			if (null === self::$_o_instance) {self::$_o_instance = new self();}
			return self::$_o_instance;
		}

        //インスタンスを取得する。
        public function get_room_plan_child(){
            $a_conditions = array();

            $a_result = array();

            $s_sql 
            <<< SQL
            select *
            from room_plan_child
            where hotel_cd ={$hotel_cd}
            and room_id = ($plan_id)
            and plan_id = {$room_id}
            SQL;

            $a_rows = DB:select($s_sql);
            if(!empty($a_rows) && count($a_rows) > 0){
                return $a_rows;
            }

            return $a_result;

        }

	}

?>