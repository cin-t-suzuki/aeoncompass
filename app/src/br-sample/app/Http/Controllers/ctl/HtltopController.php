<?php
	require_once '../models/Controllers/Action.php';
	require_once '../models/System.php';
	require_once '../models/Hotel.php';
	require_once '../models/Customer.php';


	class HtlTopController extends models_Controllers_Action
	{

		// 担当者確認ポップアップ表示間隔 =>三か月毎
		private $confirm_span = '-3 month';


		// アクションを呼び出す際、毎回ログインチェックを行う
		public function preDispatch()
		{
			try{
				// アクションを呼び出す際、毎回処理を行う。
				parent::htlDispatch();

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// インデックス
		public function indexAction()
		{

			$target_cd  = $this->_request->getParam('target_cd');

			try{

				// Hotel モデルを生成
				$hotel = new models_Hotel();

				$hotel->set_hotel_cd($target_cd);
				// Date モデルを生成
				$date = new Br_Models_Date();

				// 順位を取得
				$a_today_rank = $hotel->get_count_reserve_today();

				// お知らせを取得
				$a_twitters = $hotel->get_twitters();

				// パワープラン
				$b_power = $hotel->has_power();

				// 先週の宿泊数
				$date->add('d', -7);
				$s_after  = $date->week_day('1'); // 日曜
				$s_before = $date->week_day('7'); // 土曜

				$a_conditions = array(
									'date_ymd' => array(
													'after' => date('Y-m-d', $s_after),
													'before'=> date('Y-m-d', $s_before)),
									'pref_id'  => $this->box->user->hotel['pref_id']
				);

				$a_week_rank = $hotel->get_count_reserve($a_conditions);

				// 先々週の宿泊数
				$date->add('d', -7);

				$a_conditions = array(
									'date_ymd' => array(
													'after' => date('Y-m-d', $s_after),
													'before'=> date('Y-m-d', $s_before)),
									'pref_id'  => $this->box->user->hotel['pref_id']

				);

				$a_pre_week_rank = $hotel->get_count_reserve($a_conditions);

				// 差を計算し、セット
				if ($a_week_rank['values'][0]['reserve_ranking'] < $a_pre_week_rank['values'][0]['reserve_ranking']) {
					$change_week_reserve_rank = 'up';
				} else if ($a_week_rank['values'][0]['reserve_ranking'] > $a_pre_week_rank['values'][0]['reserve_ranking']) {
					$change_week_reserve_rank = 'down';
				} else {
					$change_week_reserve_rank = 'equal';
				}

				$a_week_rank['values'][0]['change_reserve'] = $change_week_reserve_rank;

				if ($a_week_rank['values'][0]['stay_ranking'] < $a_pre_week_rank['values'][0]['stay_ranking']) {
					$change_week_stay_rank = 'up';
				} else if ($a_week_rank['values'][0]['stay_ranking'] > $a_pre_week_rank['values'][0]['stay_ranking']) {
					$change_week_stay_rank = 'down';
				} else {
					$change_week_stay_rank = 'equal';
				}

				$a_week_rank['values'][0]['change_stay'] = $change_week_stay_rank;

				// 一旦クリア
				$date->set();

				// 先月の宿泊数
				$date->add('m', -1);
				$a_conditions = array(
									'date_ymd' => array(
													'after' => ($date->to_format('Y-m') . '-01'),
													'before'=> $date->to_format('Y-m-t')),
									'pref_id'  => $this->box->user->hotel['pref_id']

				);

				$a_month_rank = $hotel->get_count_reserve($a_conditions);

				// 先々月の宿泊数
				$date->add('m', -1);
				$a_conditions = array(
									'date_ymd' => array(
													'after' => ($date->to_format('Y-m') . '-01'),
													'before'=> $date->to_format('Y-m-t')),
									'pref_id'  => $this->box->user->hotel['pref_id']

				);

				$a_pre_month_rank = $hotel->get_count_reserve($a_conditions);

				// 差を計算し、セット
				if ($a_month_rank['values'][0]['reserve_ranking'] < $a_pre_month_rank['values'][0]['reserve_ranking']) {
					$change_month_reserve_rank = 'up';
				} else if ($a_month_rank['values'][0]['reserve_ranking'] > $a_pre_month_rank['values'][0]['reserve_ranking']) {
					$change_month_reserve_rank = 'down';
				} else {
					$change_month_reserve_rank = 'equal';
				}

				$a_month_rank['values'][0]['change_reserve'] = $change_month_reserve_rank;

				if ($a_month_rank['values'][0]['stay_ranking'] < $a_pre_month_rank['values'][0]['stay_ranking']) {
					$change_month_stay_rank = 'up';
				} else if ($a_month_rank['values'][0]['stay_ranking'] > $a_pre_month_rank['values'][0]['stay_ranking']) {
					$change_month_stay_rank = 'down';
				} else {
					$change_month_stay_rank = 'equal';
				}

				$a_month_rank['values'][0]['change_stay'] = $change_month_stay_rank;


				// hotelの全体件数を取得

				$a_conditions = array(
									'pref_id'  => $this->box->user->hotel['pref_id']
				);

				$i_hotel_count = $hotel->get_hotel_cnt($a_conditions);

				// おしらせ説明部分表示
				$models_System = new models_System();
				$a_broadcast_messages = $models_System->get_broadcast_messages($target_cd);

				$hotel_control = Hotel_Control::getInstance();
				$a_hotel_control = $hotel_control->find(array('hotel_cd' => $target_cd));

				// 「新部屋プランメンテナンス」メニューの表示・非表示判定
				$o_core = new Core();
				$a_system_versions = $o_core->to_shift($this->box->user->hotel_system_version['version'], true);
				$this->box->item->assign->is_disp_room_plan_list = $a_system_versions;

				// 「マイグレーションツール」メニューの表示・非表示判定
				$o_migration      = Migration::getInstance();
				$a_find_migration = $o_migration->find(array('hotel_cd' => $target_cd));
				$this->box->item->assign->is_migration_complete = $a_find_migration['complete_status'];

				// リンカーン利用施設かどうか
				$o_hotel_notify = Hotel_Notify::getInstance();

				$a_hotel_notify = $o_hotel_notify->find(
					array('hotel_cd' => $target_cd)
				);
				$a_notify_devices = $o_core->to_shift($a_hotel_notify['notify_device'], true);
				// 通知媒体にリンカーンが含まれている
				if ( in_array(8, nvl($a_notify_devices, array()) ) ) {
					$this->box->item->assign->is_cooperate_cd  = true;
				}

				//-------------------------------
				// JRセット参画施設かチェック
				//-------------------------------
				$b_is_jr_set = false;
				$o_hotel_status_jr      = Hotel_Status_Jr::getInstance();
				$a_find_hotel_status_jr = $o_hotel_status_jr->find(array('hotel_cd' => $target_cd));
				if ( !is_empty($a_find_hotel_status_jr) ) {
						$b_is_jr_set = true;
				}

				// アサインの登録
				$this->box->item->assign->target_cd   = $target_cd;
				$this->box->item->assign->has_power   = $b_power;                 // パワープランを持つ施設か？
				$this->box->item->assign->twitters    = $a_twitters;              // お知らせ
				$this->box->item->assign->hotel_count = $i_hotel_count;           // ホテル件数
				$this->box->item->assign->today_rank  = $a_today_rank['values'];  // 当日宿泊数
				$this->box->item->assign->week_rank   = $a_week_rank['values'];   // 先週宿泊数
				$this->box->item->assign->month_rank  = $a_month_rank['values'];  // 先月宿泊数
				$this->box->item->assign->broadcast_messages = $a_broadcast_messages;    // おしらせ説明文
				$this->box->item->assign->stock_type  = $a_hotel_control['stock_type'];

				$this->box->user->is_open_adjournment_ctl = $hotel->is_open_adjournment_ctl($this->_request->getParam('target_cd'));

				$this->box->item->assign->is_jrset = $b_is_jr_set;


				// 予約通知　リンカーンの場合のお知らせ文
				// リンカーン利用施設かどうか
				$o_hotel_notify = Hotel_Notify::getInstance();
				$o_core = new Core();
				$a_hotel_notify = $o_hotel_notify->find(array('hotel_cd' => $this->params('target_cd')));
				$a_notify_devices = $o_core->to_shift($a_hotel_notify['notify_device'], true);
				// 通知媒体にリンカーンが含まれている
				if ( in_array(8, nvl($a_notify_devices, array()) ) ) {
					$this->box->item->assign->is_notify_lincoln  = true;
				}else{
					$this->box->item->assign->is_notify_lincoln = false;
				}

				//-------------------------------
				// 担当者情報の更新確認
				//-------------------------------

				// リクエストパラメータの取得
				$a_hotel_person   = $this->_request->getParam('Hotel_Person');
				$a_customer = $this->params('customer');

				// インスタンスの取得
				$o_hotel_person  = Hotel_Person::getInstance();
				$o_customer      = Customer::getInstance();

				$m_customer  = new models_Customer();
				$models_hotel = new models_Hotel;
				$models_hotel->set_hotel_cd($target_cd);

				// 施設担当者情報取得
				if (is_empty($a_hotel_person)){
					$a_hotel_person  = $o_hotel_person->find(array('hotel_cd' => $target_cd));
				}
				// 精算先担当者情報取得
				if (is_empty($a_customer)) {
					$a_customer_hotel = $m_customer->get_customer($target_cd);
					$a_customer = $o_customer->find(array('customer_id' => $a_customer_hotel["values"][0]['customer_id']));
				}
				$this->box->item->assign->hotel_person     = $a_hotel_person;       // 施設担当者情報
				$this->box->item->assign->customer         = $a_customer;           // 精算先担当者情報

				//  担当者確認ポップアップ表示制御
				if($this->_is_support_time()){
					//  営業時間 ポップアップ表示判定をする
					$a_confirm_hotel_person = $this->_is_confirm_hotel_person($target_cd,$a_hotel_control['stock_type']);
					$b_confirm_hotel_person_force = $this->_is_confirm_hotel_person_force($a_hotel_person,$a_customer,$a_hotel_control['stock_type']);

					$this->box->item->assign->a_confirm_hotel_person      = $a_confirm_hotel_person;       // 確認用フラグ
					$this->box->item->assign->confirm_hotel_person_force  = $b_confirm_hotel_person_force;  // 変更強制フラグ
				}else{
					//  営業時間外はポップアップを抑止する
					$this->box->item->assign->a_confirm_hotel_person = array(
						'confirm_dtm_check'        => 0,
						'hotel_person_email_check' => 0,
						'customer_email_check'     => 0,
					);
					$this->box->item->assign->confirm_hotel_person_force     = false;       // 非強制
				}

				//  GoToキャンペーンポップアップ表示制御 と画面へのボタン表示 
                                //  goto_hotel_registにレコードがあるか確認する。
                                $a_hotel_goto_regist = $hotel->get_hotel_goto_regist();
                                if(is_empty($a_hotel_goto_regist['values'])){
                                        // レコードが無い場合
					if ($this->_is_goto_hotel($a_hotel_person,$a_customer,$a_hotel_control['stock_type'])){
                                        	// GoTo対象となりうる施設の場合 ポップアップを表示する。
						$this->box->item->assign->confirm_hotel_goto_regist = true;   //ポップアップ表示ON
						$this->box->item->assign->hotel_goto_registed       = -1;     //まだ回答していない

                                                // 連絡先のポップアップ表示と重なった場合はGoToのポップアップを表示を優先するため
	                                        // 連絡先のポップアップのフラグをOFFにする。
						$this->box->item->assign->a_confirm_hotel_person = array(
							'confirm_dtm_check'        => 0,
							'hotel_person_email_check' => 0,
							'customer_email_check'     => 0,
						);
	                                        $this->box->item->assign->confirm_hotel_person_force  = false;
                                        }else{
						$this->box->item->assign->confirm_hotel_goto_regist = false;  //ポップアップ表示OFF
                                        }
				}else{
	                                // レコードがある＝１度回答していればポップアップを表示しない。
					$this->box->item->assign->confirm_hotel_goto_regist = false;  //ポップアップ表示OFF
					$this->box->item->assign->hotel_goto_registed       = $a_hotel_goto_regist['values'][0]['regist_status'];   //回答済の場合regist_status(1,2,3)を設定
                                        
					// hotel_camp_gotoにレコードがあるか確認して回答状況を変数にセットする。
					$a_camps_data = $hotel->get_hotel_camp_gotos();
        	                        if( count($a_camps_data['values']) > 0){
        	                                $this->box->item->assign->hotel_camp_goto = true;
                                                $this->box->item->assign->goto_camp_cd = $a_camps_data['values'][0]['camp_cd'];
        	               	        } else {
        	                                $this->box->item->assign->hotel_camp_goto = false;
					}
				}
                                
                                
				//-------------------------------
				// アークスリー(JET STAR)連携施設かチェック
				//-------------------------------
				$b_is_jetstar_set = $this->_is_jetstar_hotel($target_cd);
				$this->box->item->assign->is_jetstar = $b_is_jetstar_set;

                                //-------------------------------
				// アークスリー連携施設への通知
				//-------------------------------
				// アークスリー連携施設数 増加対応(北海道・沖縄)
				//require_once '../models/Htltoparc01.php';
				$this->box->item->assign->is_disp_Jetstar_phase1 = in_array($target_cd, $this->read_from_txt_to_array('../config/Htltop_arc01.txt'));

				// アークスリー連携施設数 増加対応(関東圏、近畿圏、福岡)
				//require_once '../models/Htltoparc02.php';
				$this->box->item->assign->is_disp_Jetstar_phase2 = in_array($target_cd,  $this->read_from_txt_to_array('../config/Htltop_arc02.txt'));

				// アークスリー連携施設数 増加対応(カード決済プラン販売施設 各府県914軒)
				$this->box->item->assign->is_disp_Jetstar_phase3 = in_array($target_cd, array(
						// 施設番号',	 	施設名	都道府県
						'2005060031'		//買取で解約済の施設  メッセージ機能で表示に切り替えたためダミーの値のみにしておく。
				));
				// アークスリー連携施設数 増加対応(カード決済プラン販売施設 各府県914軒) ここまで

				$this->box->item->assign->is_disp_rate_info = false;
				//★ページ下部のお知らせ詳細部分、特定の施設のみプラン編集時に施設負担ポイントが設定できるようになったことを表示する制御をしていたので少し複雑になっている★
				//ここを修正した場合は_broadcast_messages.tplも修正する事
				$this->box->item->assign->disable_disp_broadcast_id = array(91,95);
				$this->box->item->assign->hotel_system_rate = 0;
				$this->box->item->assign->pdf_suffix = '';
				$n_check_rate_8_hotel = 0;
				$n_check_rate_6_hotel = 0;
				$n_check_rate_5_hotel = 0;

                                if(!$this->_is_exclude_hotel($target_cd)){
					$n_check_rate_8_hotel = $this->_check_rate_8_hotel($target_cd);
					if($n_check_rate_8_hotel != 0){
						$this->box->item->assign->hotel_system_rate = 8;
					} else {
						$n_check_rate_6_hotel = $this->_check_rate_6_hotel($target_cd);
						if($n_check_rate_6_hotel != 0){
							$this->box->item->assign->hotel_system_rate = 6;
						} else {
							$n_check_rate_5_hotel = $this->_check_rate_5_hotel($target_cd);
							$this->box->item->assign->hotel_system_rate = 5;
						}
					}
					if($n_check_rate_8_hotel == 1 ||
					$n_check_rate_6_hotel == 1 ||
					$n_check_rate_5_hotel == 1){
						$this->box->item->assign->is_disp_rate_info = true;
						$this->box->item->assign->disable_disp_broadcast_id = array(95);
					} elseif($n_check_rate_8_hotel == 3 ||
							$n_check_rate_6_hotel == 3 ||
							$n_check_rate_5_hotel == 3){
						$this->box->item->assign->is_disp_rate_info = true;
						$this->box->item->assign->pdf_suffix        = 3;
						$this->box->item->assign->disable_disp_broadcast_id = array(91);
					} elseif($n_check_rate_8_hotel == 4 ||
							$n_check_rate_6_hotel == 4 ||
							$n_check_rate_5_hotel == 4){
						$this->box->item->assign->is_disp_rate_info = true;
						$this->box->item->assign->pdf_suffix        = 'n3';
						$this->box->item->assign->disable_disp_broadcast_id = array(91);
					} elseif($n_check_rate_8_hotel == 5 ||
							$n_check_rate_6_hotel == 5 ||
							$n_check_rate_5_hotel == 5){
						$this->box->item->assign->is_disp_rate_info = true;
						$this->box->item->assign->pdf_suffix        = '33';
						$this->box->item->assign->disable_disp_broadcast_id = array(91);
					} elseif($n_check_rate_8_hotel == 6 ||
							$n_check_rate_6_hotel == 6 ||
							$n_check_rate_5_hotel == 6){
						$this->box->item->assign->is_disp_rate_info = true;
						$this->box->item->assign->pdf_suffix        = 'apafc';
						$this->box->item->assign->disable_disp_broadcast_id = array(91);
					}
				}

				// 2018/12 料率変更5-->8%の告知
				$n_check_rate_2018_hotel = 0;
				if(!$this->_is_exclude_hotel($target_cd)){
					$n_check_rate_2018_hotel = $this->_check_rate_2018_hotel($target_cd);
					if($n_check_rate_2018_hotel == 1){
						$this->box->item->assign->hotel_system_rate_2018_1 = 8;
					} elseif($n_check_rate_2018_hotel == 2){
						$this->box->item->assign->hotel_system_rate_2018_2 = 8;
					}
				}
                                
				$this->set_assign();

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
				throw $e;
			}
		}

		// サポート営業時間を判定する
		// true  :営業時間
		// false :営業時間外
		private function _is_support_time(){

			$o_models_date = new Br_Models_Date();

			if($o_models_date->to_week('e') == 'Sat' ||
				$o_models_date->to_week('e') == 'Sun' ||
				$o_models_date->is_holiday()){
				return false;
			}

			if(	strtotime('9:30:00') < strtotime($o_models_date->to_format('H:i:s')) &&
				strtotime('18:30:00') > strtotime($o_models_date->to_format('H:i:s'))){
				// 営業時間
				return true;
			}else{
				//営業時間外
				return false;
			}
			return false;
		}


		// 施設担当者情報の更新チェック判定 空欄あり入力強制する場合
		// true  :ポップアップを表示する
		// false :ポップアップを表示する
		private function _is_confirm_hotel_person_force($a_hotel_person,$a_customer,$stock_type){

			// 施設スタッフ以外（社内スタッフ NTAスタッフ）の場合は対象外
			if($this->box->user->operator->is_staff() || $this->box->user->operator->is_nta()){
				return false;
			}
			// 0:受託販売以外は対象外
			if($stock_type  != 0){
				return false;
			}
			// 名前、電話番号、メールアドレスいずれかに空欄がある場合は、変更画面を強制する。
			if(	is_empty($a_hotel_person['person_nm']) || is_empty($a_hotel_person['person_tel']) || is_empty($a_hotel_person['person_email']) ||
				is_empty($a_customer['person_nm']) || is_empty($a_customer['tel']) ||is_empty($a_customer['email']) ){
				return true;
			}
			return false;
		}

		// 施設担当者情報の更新チェック判定
		private function _is_confirm_hotel_person($target_cd,$stock_type){

			$result_confirm = array(
					'confirm_dtm_check'        => 0,
					'hotel_person_email_check' => 0,
					'customer_email_check'     => 0,
			);

			// 施設スタッフ以外（社内スタッフ NTAスタッフ）の場合は対象外
			if($this->box->user->operator->is_staff() || $this->box->user->operator->is_nta()){
				return $result_confirm;
			}
			// 0:受託販売以外は対象外
			if($stock_type  != 0){
				return $result_confirm;
			}

			$o_confirm_hotel_person  = Confirm_Hotel_Person::getInstance();
			$a_confirm_hotel_person = $o_confirm_hotel_person->find(array('hotel_cd' => $target_cd));

			// 初回の為、対象外
			if(!$a_confirm_hotel_person){
				try {
					// バリデーションクラスをロード
					$validations = Validations::getInstance($this->box);
					// トランザクション開始
					$this->oracle->beginTransaction();
					// 保存対象となるテーブルオブジェクトを設定します。
					$validations->set_table(Confirm_Hotel_Person::getInstance());

					// バリデート対象となるテーブルとカラムをエラーメッセージ表示順で設定します。
					$validations->set_validate(array('Confirm_Hotel_Person'      => 'hotel_cd'));
					$validations->set_validate(array('Confirm_Hotel_Person'      => 'confirm_dtm'));

					$o_confirm_hotel_person->attributes(array(
									'hotel_cd'     => $target_cd,
									'confirm_dtm'  => 'sysdate',
									'hotel_person_email_check'  => 0,
									'customer_email_check'      => 0,
									'entry_cd'     => $this->box->info->env->action_cd,
									'entry_ts'     => 'sysdate',
									'modify_cd'    => $this->box->info->env->action_cd,
									'modify_ts'    => 'sysdate',
					));
					$o_confirm_hotel_person->save();
					// コミット
					$this->oracle->commit();

					// ポップアップ表示用のフラグを立てる
					$result_confirm["confirm_dtm_check"] = 1;
					return $result_confirm;

				} catch (\Exception $e) {
					// ロールバック
					$this->oracle->rollback();
					// エラーメッセージ
					$this->box->item->error->add("更新確認情報を更新できませんでした。 ");
					$this->set_assign();
				}
			//確認対象
			//  指定期間(3か月)を過ぎたため
			//  施設担当者のメール送信エラー
			//  精算担当者のメール送信エラー
			}else if($a_confirm_hotel_person['confirm_dtm'] < strtotime($this->confirm_span)||
					$a_confirm_hotel_person['hotel_person_email_check'] ||
					$a_confirm_hotel_person['customer_email_check']
					){

					// ポップアップ表示用のフラグを立てる
					if($a_confirm_hotel_person['confirm_dtm'] < strtotime($this->confirm_span)){
						$result_confirm["confirm_dtm_check"] = 1;
					}
					if($a_confirm_hotel_person['hotel_person_email_check']){
						$result_confirm["hotel_person_email_check"] = 1;
					}
					if($a_confirm_hotel_person['customer_email_check']){
						$result_confirm["customer_email_check"] = 1;
					}

				try {
					// バリデーションクラスをロード
					$validations = Validations::getInstance($this->box);
					// トランザクション開始
					$this->oracle->beginTransaction();
					// 保存対象となるテーブルオブジェクトを設定します。
					$validations->set_table(Confirm_Hotel_Person::getInstance());

					// バリデート対象となるテーブルとカラムをエラーメッセージ表示順で設定します。
					$validations->set_validate(array('Confirm_Hotel_Person'      => 'hotel_cd'));
					$validations->set_validate(array('Confirm_Hotel_Person'      => 'confirm_dtm'));

					$o_confirm_hotel_person->attributes(array(
									'confirm_dtm'  => 'sysdate',
									//'hotel_person_email_check'  => 0,
									//'customer_email_check'      => 0,
									'modify_cd'    => $this->box->info->env->action_cd,
									'modify_ts'    => 'sysdate',
					));
					$o_confirm_hotel_person->update();

					// コミット
					$this->oracle->commit();

					return $result_confirm;

				} catch (\Exception $e) {
					// ロールバック
					$this->oracle->rollback();
					// エラーメッセージ
					$this->box->item->error->add("更新確認情報を更新できませんでした。 ");
					$this->set_assign();
				}
			}

			return $result_confirm;
		}

		// GoToポップアップ表示判定
		// 戻り値 
		//   true  :ポップアップを表示する
		//   false :ポップアップを表示する
		private function _is_goto_hotel($a_hotel_person,$a_customer,$stock_type){

			// 施設スタッフ以外（社内スタッフ NTAスタッフ）の場合は対象外
			if($this->box->user->operator->is_staff() || $this->box->user->operator->is_nta()){
				return false;
			}
			// 0:受託販売以外（＝東横イン）は対象外
			if($stock_type  != 0){
				return false;
			}
			// 東京の施設は表示しない。 
			//if ($this->box->user->hotel['pref_id'] == 13){
			//	return false;
			//}
			// HOTEL_CDの先頭が202008の施設（リリース直前に登録された施設）は表示しない。 
			//if (substr($this->box->user->hotel['hotel_cd'],0,6) == '202008'){
			//	return false;
			//}

			  
			// 以下を出さないようにする。 48施設
			// ■現状維持してほしい施設（後日対応します）
			// ⑦リブマックス①【マッチング済み】東京＆8月以降新規
			// ⑧リブマックス②【未マッチング】東京＆8月以降新規
			if     ($this->box->user->hotel['hotel_cd'] == '2020090003'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020090002'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020090001'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019080008'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019080006'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019080003'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019070006'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019070005'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019030011'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019030009'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019030008'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2019010002'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018110013'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018100010'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018090004'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018090003'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018070015'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018050014'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018050013'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018050006'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018030022'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018030018'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2018010009'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2017090004'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2017020027'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2017010017'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2016110003'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2016060004'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2016040009'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2016010006'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2015110002'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2015080005'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2015010017'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2013080010'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2012100005'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2012060013'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2012020022'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2012020021'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2011070013'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2010050011'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2010010018'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2009120015'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020080024'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020080023'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020090004'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020080025'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020080022'){ return false; }
			elseif ($this->box->user->hotel['hotel_cd'] == '2020080018'){ return false; }

                        return true;
		}

		private function _is_exclude_hotel($as_target_cd) {
			return in_array($as_target_cd, array(

			));
		}
		private function _check_rate_8_hotel($as_target_cd) {
                        //---------------------------------------------------------
                        // 2017-01 料率8%-->6%  
                        //---------------------------------------------------------
                        $_rate_8_hotel = array(
                                'disp_1701'      => $this->read_from_txt_to_array('../config/Htltop_rate_8to6_201701.txt'),
                                'disp_1703'      => $this->read_from_txt_to_array('../config/Htltop_rate_8to6_201703.txt'),
                                'disp_1703nta'   => $this->read_from_txt_to_array('../config/Htltop_rate_8to6_201703nta.txt'),
                                'disp_17033crct' => $this->read_from_txt_to_array('../config/Htltop_rate_8to6_2017033crct.txt'),
                                'disp_1711apafc' => $this->read_from_txt_to_array('../config/Htltop_rate_8to6_201711apafc.txt'),
                        );
			if(in_array($as_target_cd, $_rate_8_hotel['disp_1701'])){
				return 1;
			} elseif (in_array($as_target_cd, $_rate_8_hotel['disp_1703'])){
				return 3;
			} elseif (in_array($as_target_cd, $_rate_8_hotel['disp_1703nta'])){
				return 4;
			} elseif (in_array($as_target_cd, $_rate_8_hotel['disp_17033crct'])){
				return 5;
			} elseif (in_array($as_target_cd, $_rate_8_hotel['disp_1711apafc'])){
				return 6;
			}
			return 0;
		}

		private function _check_rate_6_hotel($as_target_cd) {
                        //---------------------------------------------------------
                        // 2017-01 料率6%-->5%
                        //---------------------------------------------------------
                        $_rate_6_hotel = array(
                                'disp_1701'      => $this->read_from_txt_to_array('../config/Htltop_rate_6to5_201701.txt'),
                                'disp_1703'      => $this->read_from_txt_to_array('../config/Htltop_rate_6to5_201703.txt'),
                                'disp_1703nta'   => $this->read_from_txt_to_array('../config/Htltop_rate_6to5_201703nta.txt'),
                                'disp_17033crct' => $this->read_from_txt_to_array('../config/Htltop_rate_6to5_2017033crct.txt'),
                        );

			if(in_array($as_target_cd, $_rate_6_hotel['disp_1701'])){
				return 1;
			} elseif (in_array($as_target_cd, $_rate_6_hotel['disp_1703'])){
				return 3;
			} elseif (in_array($as_target_cd, $_rate_6_hotel['disp_1703nta'])){
				return 4;
			} elseif (in_array($as_target_cd, $_rate_6_hotel['disp_17033crct'])){
				return 5;
			}
			return 0;
		}

		private function _check_rate_5_hotel($as_target_cd) {
                        //---------------------------------------------------------
                        // 2017-01 料率5%-->5%  
                        //---------------------------------------------------------
                        $_rate_5_hotel = array(
                                'disp_1701'      => $this->read_from_txt_to_array('../config/Htltop_rate_to5_201701.txt'),
                                'disp_1703'      => $this->read_from_txt_to_array('../config/Htltop_rate_to5_201703.txt'),
                                'disp_1703nta'   => $this->read_from_txt_to_array('../config/Htltop_rate_to5_201703nta.txt'),
                                'disp_17033crct' => $this->read_from_txt_to_array('../config/Htltop_rate_to5_2017033crct.txt'),
                                'disp_1711apafc' => $this->read_from_txt_to_array('../config/Htltop_rate_to5_201711apafc.txt'),
                        );
			if(in_array($as_target_cd, $_rate_5_hotel['disp_1701'])){
				return 1;
			} elseif (in_array($as_target_cd, $_rate_5_hotel['disp_1703'])){
				return 3;
			} elseif (in_array($as_target_cd, $_rate_5_hotel['disp_1703nta'])){
				return 4;
			} elseif (in_array($as_target_cd, $_rate_5_hotel['disp_17033crct'])){
				return 5;
			} elseif (in_array($as_target_cd, $_rate_5_hotel['disp_1711apafc'])){
				return 6;
			}
			return 0;
		}

		private function _check_rate_2018_hotel($as_target_cd) {
                        //---------------------------------------------------------
                        // 2019-02 料率-->5%  
                        //---------------------------------------------------------
                        $_rate_2018_hotel = array(
                                'disp_5294'     => $this->read_from_txt_to_array('../config/Htltop_rate_to8_20190201.txt'),
                                'disp_859'      => $this->read_from_txt_to_array('../config/Htltop_rate_to8_20190301.txt'),
                        );

			if(in_array($as_target_cd, $_rate_2018_hotel['disp_5294'])){
				return 1;
			}
			if(in_array($as_target_cd, $_rate_2018_hotel['disp_859'])){
				return 2;
			}
			return 0;
		}

		// テキストファイルから施設コードを配列に読み込む
		// テキストファイルは1行1施設コードで//以降はコメント扱いする
		private function read_from_txt_to_array($filename)
		{
			$result = array();
			// fopenでファイルを開く（'r'は読み込みモードで開く）
			$fp = fopen($filename, 'r');
                        if ( $fp == false) {
                            return $result;
                        }
			while (!feof($fp)) {
                            $line = fgets($fp);
                            //   //以降はコメント扱いとして無視
                            $line = trim(substr($line, 0, strcspn($line,'//')));
                            if( $line <> "") {
			  	array_push($result, $line);
                            }
			}
			fclose($fp);
			return $result;
		}

                // アークスリー連携中施設か確認する。
		private function _is_jetstar_hotel($as_target_cd)
		{
			try{
				$_oracle = _Oracle::getInstance();

				$s_sql =
<<<SQL
					select count(*) cnt from deny_list where PARTNER_CD = '3016007888' and hotel_cd = :hotel_cd
SQL;

				// データの取得
				$a_row = $_oracle->find_by_sql($s_sql, array('hotel_cd' => $as_target_cd));
                                // レコードがあれば連携対象外
				if( $a_row[0]['cnt'] > 0 ) {
					return false;
				}else{
					return true;                            
				}

			// 各メソッドで Exception が投げられた場合
			} catch (\Exception $e) {
					throw $e;
			}

		}

	}
?>