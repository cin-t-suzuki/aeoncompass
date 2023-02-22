<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;


class BrSecurityService
{
    	// セキュリティログ一覧取得
		/**
		* @param array
		*       aa_conditions
		*			account_class アカウントクラス
		*			account_key   アカウント認証キー
		*			request_dtm   リクエスト日時
		* @return array
		* 		log_securities		結果内容
		*			security_cd		セキュリティログコード
		*			session_id		セッションID
		*			request_dtm		リクエスト日時
		*			account_class	アカウントクラス
		*			account_key		アカウント認証キー
		*			ip_address		IPアドレス
		*			uri				リクエストURI
		*/
		public function get_log_securities($aa_conditions = array()){
			try {

				//SQL内で使用のパラメータの初期化
                $s_account_key ='';
                $s_account_class='';
				$as_result=array();
				$a_conditions = array();
             

		 		// アカウントクラスを設定
				if (!empty($aa_conditions['account_class'])){
					$a_conditions['account_class'] = $aa_conditions['account_class'];
					$s_account_class = '	and	account_class = :account_class';
				}

				// アカウント認証キーを設定
				if (!empty($aa_conditions['account_key'])){
					$a_conditions['account_key'] = $aa_conditions['account_key'];
					$s_account_key = '	and	account_key = :account_key';
				}

		 		// リクエスト日時を設定
				if (!empty($aa_conditions['request_dtm']['after'])){
					$s_after_request_dtm = "	and	request_dtm >= :after_request_dtm";
					$a_conditions['after_request_dtm'] = $aa_conditions['request_dtm']['after'];
				}

				if (!empty($aa_conditions['request_dtm']['before'])){
					$s_before_request_dtm = "	and	request_dtm <= :before_request_dtm";
					$a_conditions['before_request_dtm'] = $aa_conditions['request_dtm']['before'];
				}

				$o_after = date('m', strtotime($aa_conditions['request_dtm']['after']));
				$o_before = date('m', strtotime($aa_conditions['request_dtm']['before']));
				

				//テーブルlog_security_01~12　のレコードを繰り返し取得
				while ($o_after <= $o_before){
                    

					// 最低料金を取得
					$s_sql = 
<<< SQL
                    select
                                security_cd,
                                session_id,
                                request_dtm,
                                account_class,
                                account_key,
                                ip_address,
                                uri
                        from  log_security_{$o_after}

                        where   null is null
                        {$s_account_class}
                        {$s_account_key}  
                        {$s_after_request_dtm}
						{$s_before_request_dtm}
                        order by request_dtm
SQL;
					//クエリの発行
                    $a_row = DB::select($s_sql, $a_conditions);


					//結果内容を配列に詰める
					for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++){

							// 機能として使っていないロジックではあるものの、一応コメントアウトにて保管
								// $staff            = Staff::getInstance();
								// $hotel            = Hotel::getInstance();
								// $partner          = Partner::getInstance();
								// $hotel_supervisor = Hotel_Supervisor::getInstance();
								// $member_free      = Member_Free::getInstance();
								// $models_member    = new models_Member();
			
								// for ($n_cnt = 0; $n_cnt < count($a_row); $n_cnt++){
								// 	switch ($a_row[$n_cnt]['account_class']) {
								// 	case 'staff':
								// 		$a_row[$n_cnt]['staff'] = $staff->find(array('staff_id' => $a_row[$n_cnt]['account_key']));
								// 		break;
								// 	case 'hotel':
								// 		$a_row[$n_cnt]['hotel'] = $hotel->find(array('hotel_cd' => $a_row[$n_cnt]['account_key']));
								// 		break;
								// 	case 'partner':
								// 		$a_row[$n_cnt]['partner'] = $partner->find(array('partner_cd' => $a_row[$n_cnt]['account_key']));
								// 		break;
								// 	case 'supervisor':
								// 		$a_row[$n_cnt]['hotel_supervisor'] = $hotel_supervisor->find(array('supervisor_cd' => $a_row[$n_cnt]['account_key']));
								// 		break;
								// 	case 'member':
								// 		$a_row[$n_cnt]['member'] = $models_member->get_member($a_row[$n_cnt]['account_key']);
								// 		break;
								// 	case 'member_free':
								// 		$a_row[$n_cnt]['member_free'] = $member_free->find(array('member_cd' => $a_row[$n_cnt]['account_key']));
								// 		break;
								// 	}
					
									// 上記機能が必要になった際のlaravel用、下記の記述で置き換え可能
										// switch ($a_row[$n_cnt]->account_class) {
										// 	case 'staff':
										// 		$a_row[$n_cnt]->staff = Staff::where('staff_id',$a_row[$n_cnt]->account_key)->get();
										// 		break;
										// 	}


						//繰り返しごとの結果内容を総合結果に詰める
						$as_result[] = $a_row[$n_cnt];
					}

					//01~12の月の繰り上げ、うるう年や31日あるなし対策で標準関数不使用
					if($o_after != '12'){
						$o_after=sprintf('%02d', $o_after+1);
					}else{
						 $o_after='01';
					}
				}

                    if (count($as_result) > 0) {
						return array(
							'values'     => $as_result,
							// 'reference' => $this->set_reference('セキュリティログ一覧取得', __METHOD__)
						);
             
                    } else {
                        return  null;                  
                    }
                
				
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}


		// セキュリティログ一覧詳細情報
		/**
		* @param array
		*       a_conditions
		*			security_cd アカウントキー
		* @return array
		* 		log_securities		結果内容
		*			security_cd		セキュリティログコード
		*			session_id		セッションID
		*			request_dtm		リクエスト日時
		*			account_class	アカウントクラス
		*			account_key		アカウント認証キー
		*			ip_address		IPアドレス
		*			uri				リクエストURI
		*			parameter		パラメータ
		*/
		public function get_log_securities_show($aa_conditions = array()){
			try {

				//SQL内で使用のパラメータの初期化
				$sql_param_month=$aa_conditions['sql_param_month'];;
             
					$security_cd ='	and	account_key = :security_cd';
					$a_conditions['security_cd'] = $aa_conditions['security_cd'];

			
					$s_sql = 
<<< SQL
                    select
                                security_cd,
                                session_id,
                                request_dtm,
                                account_class,
                                account_key,
                                ip_address,
                                uri,
								parameter

                        from  log_security_{$sql_param_month}

                        where   null is null
                        {$security_cd}
                        order by request_dtm
SQL;
					//クエリの発行
                    $a_row = DB::select($s_sql, $a_conditions);

                    if (count($a_row) > 0) {
						return $a_row[0];
                    } else {
                        return  null;  
                    }
				
			// 各メソッドで Exception が投げられた場合
			} catch (Exception $e) {
					throw $e;
			}
		}
    }