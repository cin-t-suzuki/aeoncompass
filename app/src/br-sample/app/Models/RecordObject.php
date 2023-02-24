<?php
	namespace App\Models;
    
	class RecordObject
	{
		// メンバ変数の定義
		protected $_o_box;
		protected $_o_table;
		protected $_o_oracle;
		protected $_o_validations;
		protected $_a_validation_include_keys;
		protected $_a_validation_exclude_keys;
		private   $_a_field_names;
		private   $_a_primary_keys;
		private   $_a_attributes;
		private   $_a_find_attributes;
		private   $_s_table_name;
		
		//======================================================================
		// コンストラクタ
		//
		// @params Object テーブルのインスタンス（例、テーブル名::getInstance）
		//
		//======================================================================
		function __construct($ao_table)
		{
			try {
				// Boxオブジェクトの取得
				$this->_o_box = & Zend_Controller_Front::getInstance()->getPlugin('Box')->box;
				
				// インスタンス生成
				$this->_o_oracle      = _Oracle::getInstance();
				$this->_o_validations = Validations::getInstance($this->_o_box);
				$this->_o_table       = $ao_table;
				
				// メンバ変数の初期化
				$this->_a_validation_include_keys = null;
				$this->_a_validation_exclude_keys = null;
				
				// 対象テーブルのフィールド数だけループ
				foreach ( nvl($this->_o_table->get_column_objects(), array()) as $s_item_name => $a_item_properties ) {
					// 対象のテーブルに存在するフィールド名を取得
					$this->_a_field_names[] = $s_item_name;
					
					// プライマリキーだった場合、プライマリキー情報として取得
					if ( $a_item_properties->is_primary() ) {
						$this->_a_primary_keys[] = $s_item_name;
					}
				}
				
				// テーブル名を取得
				$this->_s_table_name = get_class($this->_o_table);
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// バリデーションを行うフィールドのみを指定する
		// ※本来すべてのフィールドにおいてバリデーションを行うのが当然であり
		//   この機能は一部既存の新旧テーブル制でダブルスタンダードなチェックが
		//   存在してしまっていることへの互換性を維持する為のものだという認識を
		//   忘れずに！
		//
		// @params バリデーションを実行させたいフィールド名の配列
		//
		//======================================================================
		public function set_validation_include_keys($aa_include_keys)
		{
			try {
				// 引数が指定されているが配列でなかった場合は整形しておく
				if ( !is_empty($aa_include_keys) and !is_array($aa_include_keys) ) {
					$this->_a_validation_include_keys[] = $aa_include_keys;
				} else {
					$this->_a_validation_include_keys   = $aa_include_keys;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// バリデーションを行いたくないフィールドのみを指定する
		// ※本来すべてのフィールドにおいてバリデーションを行うのが当然であり
		//   この機能は一部既存の新旧テーブル制でダブルスタンダードなチェックが
		//   存在してしまっていることへの互換性を維持する為のものだという認識を
		//   忘れずに！
		//
		// @params バリデーションを実行させたくないフィールド名の配列
		//
		//======================================================================
		public function set_validation_exclude_keys($aa_exclude_keys)
		{
			try {
				// 引数が指定されているが配列でなかった場合は整形しておく
				if ( !is_empty($aa_exclude_keys) and !is_array($aa_exclude_keys) ) {
					$this->_a_validation_exclude_keys[] = $aa_exclude_keys;
				} else {
					$this->_a_validation_exclude_keys   = $aa_exclude_keys;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// バリデーション設定
		//
		//======================================================================
		private function _set_validation()
		{
			try {
				// 限定型・除外型のフィールド指定の配列が両方指定されている場合はエラーとする
				if ( !is_empty($this->_a_validation_include_keys) and !is_empty($this->_a_validation_exclude_keys) ) {
					throw new Exception('限定型と除外型のフィールド指定配列はどちらかのみ設定してください。');
				}
				
				// バリデーションを実行するテーブルを指定
				$this->_o_validations->set_table($this->_o_table);
				
				// 対象テーブルのフィールド数分ループ
				foreach ( nvl($this->_o_table->get_column_objects(), array()) as $s_item_name => $a_item_properties ) {
					// 各フィールドへのバリデート設定
					if ( !is_empty($this->_a_validation_include_keys) ) {
						//-----------------------------------------------------
						// 限定リストが設定されている場合
						//-----------------------------------------------------
						// 指定されたフィールドのみバリデート
						if ( in_array($s_item_name, $this->_a_validation_include_keys) ) {
							$this->_o_validations->set_validate(array($this->_s_table_name => $s_item_name));
						}
					} else if ( !is_empty($this->_a_validation_exclude_keys) ) {
						//-----------------------------------------------------
						// 除外リストが設定されている場合
						//-----------------------------------------------------
						// 指定されていないフィールドのみバリデート
						if ( !in_array($s_item_name, $this->_a_validation_exclude_keys) ) {
							$this->_o_validations->set_validate(array($this->_s_table_name => $s_item_name));
						}
					} else {
						//-----------------------------------------------------
						// 未指定時は全フィールドをバリデート
						//-----------------------------------------------------
						$this->_o_validations->set_validate(array($this->_s_table_name => $s_item_name));
					}
				}
				
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
				// 指定されたフィールド名が対象テーブルに存在するものだけを設定
				if ( in_array($as_key_nm, $this->_a_field_names) ) {
					$this->_a_attributes[ $as_key_nm ] = $am_value;
				}
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 検索
		//
		// @return Array 検索結果のレコード
		//
		//======================================================================
		public function find()
		{
			try {
				// 対象レコードのプライマリキーを取得して設定する
				$a_temp_primary_keys = array();
				
				foreach ( nvl($this->_a_attributes, array()) as $s_item_name => $value ) {
					// 対象のキーがプライマリキーの場合のみ
					if ( in_array($s_item_name, $this->_a_primary_keys) ) {
						$a_temp_primary_keys[ $s_item_name ] = $value;
					}
				}
				
				// 対象のレコードを取得する
				$this->_a_find_attributes = $this->_o_table->find($a_temp_primary_keys);
				
				return nvl($this->_a_find_attributes, array());
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 登録
		//
		// @return boolean 処理結果（true：成功, false：失敗）
		//
		//======================================================================
		public function save()
		{
			try {
				// 登録するデータを設定
				$this->_a_attributes['entry_cd']  = $this->_o_box->info->env->action_cd;
				$this->_a_attributes['entry_ts']  = 'sysdate';
				$this->_a_attributes['modify_cd'] = $this->_o_box->info->env->action_cd;
				$this->_a_attributes['modify_ts'] = 'sysdate';
				
				$this->_o_table->attributes($this->_a_attributes);
				
				// バリデーション設定
				$this->_set_validation();
				
				// バリデーションを実行
				$this->_o_validations->valid($this->_s_table_name);
				
				// バリデーション結果を判定
				if ( !$this->_o_validations->is_valid($this->_s_table_name) ) {
					return false;
				}
				
				// 登録実行
				if ( !$this->_o_table->save() ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 更新
		//
		// @return boolean 処理結果（true：成功, false：失敗）
		//
		//======================================================================
		public function update()
		{
			try {
				// 更新を行うために対象レコードを取得する
				$this->find();
				
				// 更新するデータを設定
				$this->_a_attributes['modify_cd'] = $this->_o_box->info->env->action_cd;
				$this->_a_attributes['modify_ts'] = 'sysdate';
				
				$this->_o_table->attributes($this->_a_attributes);
				
				// バリデーション設定
				$this->_set_validation();
				
				// バリデーションを実行
				$this->_o_validations->valid($this->_s_table_name);
				
				// バリデーション結果を判定
				if ( !$this->_o_validations->is_valid($this->_s_table_name) ) {
					return false;
				}
				
				// 登録実行
				if ( !$this->_o_table->update() ) {
					return false;
				}
				
				return true;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
		
		//======================================================================
		// 削除
		//
		// @return 
		//
		//======================================================================
		public function destroy()
		{
			try {
				// 対象レコードのプライマリキーを取得して設定する
				$a_temp_primary_keys = array();
				
				foreach ( nvl($this->_a_attributes, array()) as $s_item_name => $value ) {
					// 対象のキーがプライマリキーの場合のみ
					if ( in_array($s_item_name, $this->_a_primary_keys) ) {
						$a_temp_primary_keys[ $s_item_name ] = $value;
					}
				}
				
				// 削除
				$this->_o_table->destroy($a_temp_primary_keys);
				
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
				// 初期化
				$b_result = true;
				
				// レコードが存在するかを判定
				// ※整形したデータを利用している為、自クラスのfindメソッドを利用しない
				if ( is_empty($this->find()) ) {
					// 登録
					$b_result = $this->save();
				} else {
					// 更新
					$b_result = $this->update();
				}
				
				return $b_result;
				
			} catch (Exception $e) {
				throw $e;
			}
		}
	}
	
?>