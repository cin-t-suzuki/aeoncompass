<?php
	namespace App\Models;
	use Illuminate\Support\Facades\DB;

//	require_once '_common/models/Core/License.php';
//	require_once '../models/System.php';
	
	class ModelsLicense
	{
		private $box         = null;   // ボックス
		// コンストラクタ
		//
		function __construct(){

			

		}
		
		/*
		private function _token_collation($aa_conditions) {
			try {
				$_oracle = _Oracle::getInstance();
				$o_cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
				
				$aa_conditions['license_token'] = $o_cipher->encrypt($aa_conditions['license_token']);
				
				$s_sql =
<<<SQL
					select	secure_license.license_id
					from	secure_license
					where	secure_license.license_status = 0
						and	secure_license.applicant_staff_id = :applicant_staff_id
						and	secure_license.approver_staff_id = :approver_staff_id
						and	secure_license.license_limit_dtm > sysdate
						and	secure_license.applicant_staff_id <> secure_license.approver_staff_id
						and	secure_license.license_token = :license_token
SQL;
				$a_buf = $_oracle->find_by_sql($s_sql, $aa_conditions);
				
				if (!$a_buf) {
					return false;
				} else {
					return $a_buf[0]['license_id'];
				}
				
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
		
		private function _token_nullification($as_license_cd) {
			try {
				// SecureLicense モデルのインスタンスを取得
				$secure_license = Secure_License::getInstance();

				// バリデーションクラスをロード
				$validations = Validations::getInstance($this->box);

				$secure_license->find(array('license_id' => $as_license_cd));
				
				// 保存対象となるテーブルオブジェクトを設定します。
				$validations->set_table(Secure_License::getInstance());

				$secure_license->attributes(array('license_status'    => 1));
				$secure_license->attributes(array('modify_cd'         => $this->box->info->env->action_cd));
				$secure_license->attributes(array('modify_ts'         => 'sysdate'));
				
				// 登録
				$secure_license->update();
				
				// 登録されなかった場合
				if($secure_license->row_count() == 0){
					// エラーメッセージ
					$this->box->item->error->add('更新エラー<br>トップページよりやり直してください。');
					return false;
				}
					
				return true;
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
		
		// 10 ~ 16文字のランダムな文字列を取得
		public function get_random_string(){
			try {
				$s_list = "abcdvefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
	
				// 文字数取得
				$an_len = mt_rand(10, 16);

				$s_string = "";
				for($n_cnt = 0; $n_cnt < $an_len; $n_cnt++) {
					$s_string .= $s_list{mt_rand(0, strlen($s_list) - 1)};
				}
	
				return $s_string;
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}

		// メールアドレスが登録されているかチェック
		public function is_staff($as_email){
			try {
				$_oracle = _Oracle::getInstance();
				// Cipher モデルを生成
				$cipher = new Br_Models_Cipher((string)$this->box->config->environment->cipher->public->key);
				
				$as_email = $cipher->encrypt($as_email);

				$s_sql =
<<<SQL
						select	staff.staff_cd,
								staff.staff_id
						from	staff
						where	staff.staff_status = 1
							and staff.email = :email
SQL;

				$a_buf = $_oracle->find_by_sql($s_sql, array('email' => $as_email));

				
				if (count($a_buf) > 0) {
					$b_rs = $a_buf[0];
				} else {
					$b_rs = false;
				}
				
				return $b_rs;
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
		
		// 有効なライセンスが無いかチェック
		public function is_duplication($as_staff_id){
			try {
				$_oracle = _Oracle::getInstance();
				
				$s_sql =
<<<SQL
						select	secure_license.applicant_staff_id
						from	secure_license
						where	secure_license.applicant_staff_id = :applicant_staff_id
							and	secure_license.license_status = 0
							and	secure_license.license_limit_dtm > sysdate
SQL;

				$a_buf = $_oracle->find_by_sql($s_sql, array('applicant_staff_id' => $as_staff_id));

				if (count($a_buf) > 0) {
					return false;
				}
				
				return true;
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
		
		// ライセンストークン認証
		public function inspect($aa_conditions){
			try {
				$a_conditions = array (
										'applicant_staff_id' => $aa_conditions['staff_account_id'],
										'approver_staff_id'  => $aa_conditions['approver_staff_id'],
//										'license_token'      => hash('sha256', $aa_conditions['license_token'])
										'license_token'      => $aa_conditions['license_token']
				);

				// tokenの存在チェック
				$s_license_cd = $this->_token_collation($a_conditions);
				if (!$s_license_cd) {
					return false;
				}

				// tokenの無効化
				if (!$this->_token_nullification($s_license_cd)) {
					return false;
				}
				
				return $s_license_cd;
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
		// ライセンストークン認証
		public function attestation($aa_conditions){
			try {
				$a_conditions = array (
										'applicant_staff_id' => $aa_conditions['staff_account_id'],
										'approver_staff_id'  => $aa_conditions['approver_staff_id'],
//										'license_token'      => hash('sha256', $aa_conditions['license_token'])
										'license_token'      => $aa_conditions['license_token']
				);
				
				// tokenの存在チェック
				$s_license_cd = $this->_token_collation($a_conditions);
				if (!$s_license_cd) {
					return false;
				}
				
				return true;
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
		*/
		//======================================================================
		// 申請者に許可されているライセンス一覧の取得
		//======================================================================
		public function get_applicant_license($n_applicant_staff_id)
		{

			// 初期化
				$a_conditions = array();
				$applicant_staff_id = $n_applicant_staff_id;
				$a_result = array();
//				$o_secure_license = Secure_License::getInstance();
				
				$s_sql =
					<<< SQL
					select	license_id
					from 	secure_license
					where	license_status = 0
						and	applicant_staff_id = {$applicant_staff_id}
						and	secure_license.license_limit_dtm > sysdate()
					SQL;

				$a_rows = DB::select($s_sql);

				if(!empty($a_rows) && count($a_rows) > 0){
					return $a_rows;
				}
//				foreach ( nvl($a_rows, array()) as $value ) {
//					$a_find_secure_license = $o_secure_license->find(array('license_id' => $value['license_id']));				
//					$a_result[] = $a_find_secure_license['license_token'];
//				}
				
				return $a_result;
				

			}

	}

?>